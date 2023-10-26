<?php

class User {
    /**
     * @var int|null
     */
    private int $id;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $birthday;

    /**
     * @var string
     */
    private string $address;

    /**
     * @var string
     */
    private string $phone;

    /**
     * @var string
     */
    private string $role;

    /**
     * @var string
     */
    private string $status;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $updatedAt;

    /**
     * User constructor.
     *
     * @param int|null $id The unique identifier for the user.
     * @param string $username The username of the user.
     * @param string $email The email address of the user.
     * @param string $password The hashed password of the user.
     * @param string $firstName The first name of the user.
     * @param string $lastName The last name of the user.
     * @param DateTimeImmutable $birthday The date of birth of the user.
     * @param string $address The address of the user.
     * @param string $phone The phone number of the user.
     * @param string $role The role of the user (e.g., 'registered', 'customer', 'admin').
     * @param string $status The status of the user (e.g., 'not confirmed', 'confirmed', 'blocked').
     * @param DateTimeImmutable $createdAt The timestamp indicating when the user account was created.
     * @param DateTimeImmutable $updatedAt The timestamp indicating the last update to the user account.
     */
    public function __construct(
        ?int $id, string $username, string $email, string $password, string $firstName, string $lastName, DateTimeImmutable $birthday,
        string $address, string $phone, string $role, string $status, DateTimeImmutable $createdAt, DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthday = $birthday;
        $this->address = $address;
        $this->phone = $phone;
        $this->role = $role;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Creates a new user in the database and returns the user object.
     *
     * @param string $username The username for the new user.
     * @param string $email The email address for the new user.
     * @param string $password The password for the new user.
     * @param string $firstName The first name of the new user.
     * @param string $lastName The last name of the new user.
     * @param DateTimeImmutable $birthday The birthday of the new user.
     * @param string $address The address of the new user.
     * @param string $phone The phone number of the new user.
     *
     * @return User The newly created user object.
     *
     * @throws Exception If there is an error creating the user.
     */
    public static function createUser(
        string $username, string $email, string $password, string $firstName, string $lastName, DateTimeImmutable $birthday, string $address, string $phone
    ): User {
        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // SQL query with placeholders for prepared statement
        $sql = "
            INSERT INTO users (username, email, password, first_name, last_name, birthday, address, phone, role, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'registered', 'not confirmed', NOW(), NOW())
        ";

        // Bind parameters for the prepared statement
        $params = [$username, $email, $hashedPassword, $firstName, $lastName, $birthday->format('Y-m-d'), $address, $phone];

        // Execute the prepared statement using setP function
        $success = Connection::setP($sql, $params);

        // Check if the user was successfully created and get the user data from the database
        if ($success) {
            $getUserSql = "SELECT * FROM users WHERE username = ?";
            $getUserParams = [$username];

            $userResult = Connection::getP($getUserSql, $getUserParams);

            if ($user = $userResult->fetch_assoc()) {
                // Create a User object and return it
                $u = new User(
                    $user['id'], $user['username'], $user['email'], $user['password'], $user['first_name'], $user['last_name'], new DateTimeImmutable($user['birthday']), $user['address'],
                    $user['phone'], $user['status'], $user['status'], new DateTimeImmutable($user['created_at']), new DateTimeImmutable($user['updated_at'])
                );

                // Create an email confirmation token
                $token = TokenManager::generateToken();

                // Get the current date and time
                $currentDateTime = new DateTimeImmutable();

                // Add one day to the current date and time
                $oneDayLater = $currentDateTime->modify('+1 day');

                // Now $oneDayLater contains the date and time one day from now
                ///echo $oneDayLater->format('Y-m-d H:i:s');

                TokenManager::storeToken($u->getId(),$token,$oneDayLater);
                TokenManager::sendConfirmationEmail($u->getEmail(),$token);

                return $u;
            }
        }

        // Placeholder return value, replace this with actual logic
        throw new Exception("Failed to create user.");
    }

    /**
     * Activates the user's account.
     *
     * @param int $userId The user ID to activate the account for.
     * @return bool True if the account is successfully activated, false otherwise.
     */
    public static function activateAccount(int $userId): bool {
        // Implement logic to activate the user's account (e.g., update a 'verified' flag in the users table)
        // Example SQL query: UPDATE users SET verified = 1 WHERE id = ?

        // Execute the query and handle the result
        $sql = "UPDATE users SET status = 'confirmed' WHERE id = ?";
        $params = [$userId];
        $success = Connection::setP($sql, $params);

        if ($success) {
            return true; // Account activated successfully
        } else {
            return false; // Account activation failed
        }
    }

    /**
     * Updates the user's profile information in the database.
     *
     * @return bool True if the update is successful, false otherwise.
     */
    public function updateProfile(): bool {
        // Prepare the SQL query with placeholders for user ID and updated profile information
        $sql = "UPDATE users SET first_name = ?, last_name = ?, birthday = ?, address = ?, phone = ?, updated_at = NOW() WHERE id = ?";
        $params = [$this->firstName, $this->lastName, $this->birthday->format('Y-m-d'), $this->address, $this->phone, $this->id];

        // Execute the update query
        $success = Connection::setP($sql, $params);

        // Return true if the update is successful, false otherwise
        return $success;
    }

    /**
     * Authenticates a user based on the provided credentials.
     *
     * @param string $username The username of the user to authenticate.
     * @param string $password The password of the user to authenticate.
     *
     * @return bool Returns true if authentication is successful; otherwise, returns false.
     */
    public static function authenticateUser(string $username, string $password): bool {
        // Get user data from the database based on the provided username
        $sql = "SELECT * FROM users WHERE username = ?";
        $params = [$username];
        $result = Connection::getP($sql, $params);

        // Check if the user with the given username exists
        if ($result && $result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            // Verify the password hash
            if (password_verify($password, $userData['password'])) {
                // Authentication successful, create session and return true
                self::handleLogin(new User(
                    $userData['id'],
                    $userData['username'],
                    $userData['email'],
                    $userData['password'],
                    $userData['first_name'],
                    $userData['last_name'],
                    new DateTimeImmutable($userData['birthday']),
                    $userData['address'],
                    $userData['phone'],
                    $userData['role'],
                    $userData['status'],
                    new DateTimeImmutable($userData['created_at']),
                    new DateTimeImmutable($userData['updated_at'])
                ));
                return true;
            }
        }

        // Authentication failed, return false
        return false;
    }

    /**
     * Checks if a username is already taken in the database.
     *
     * @param string $username The username to check.
     * @return bool True if the username is taken, false otherwise.
     */
    public static function isUsernameTaken(string $username): bool {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = ?";
        $params = [$username];
        $result = Connection::getP($sql, $params);

        if ($result && $row = $result->fetch_assoc()) {
            return (intval($row['count']) > 0);
        }

        return false;
    }

    /**
     * Checks if an email address is already taken in the database.
     *
     * @param string $email The email address to check.
     * @return bool True if the email address is taken, false otherwise.
     */
    public static function isEmailTaken(string $email): bool {
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $params = [$email];
        $result = Connection::getP($sql, $params);

        if ($result && $row = $result->fetch_assoc()) {
            return (intval($row['count']) > 0);
        }

        return false;
    }

    /**
     * Creates a session for the authenticated user and performs redirect based on user role.
     *
     * @param User $user The authenticated user object.
     *
     * @return void
     */
    public static function handleLogin(User $user): void {
        // Store user information in the session
        $_SESSION['user'] = $user;

        // Perform redirect based on user role
        if ($user->isSuperAdmin()) {
            header("Location: superadmin/"); // Redirect to admin dashboard
        }else if ($user->isAdmin()) {
            header("Location: admin/"); // Redirect to admin dashboard
        } else {
            header("Location: home.php"); // Redirect to regular user homepage
        }

        // End script execution after the redirect
        exit();
    }

    /**
     * Checks if the user is an super admin.
     *
     * @return bool Returns true if the user is an admin; otherwise, returns false.
     */
    public function isSuperAdmin(): bool {
        // Check the user's role (You may have a 'role' column in your 'users' table in the database)
        // For example, assuming 1 represents the admin role in your database
        return $this->getRole() === "super_admin";
    }

    /**
     * Checks if the user is an admin.
     *
     * @return bool Returns true if the user is an admin; otherwise, returns false.
     */
    public function isAdmin(): bool {
        // Check the user's role (You may have a 'role' column in your 'users' table in the database)
        // For example, assuming 1 represents the admin role in your database
        return $this->getRole() === "admin";
    }

    /**
     * Gets the full name of the user.
     *
     * @return string
     */
    public function getFullName(): string {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * Deletes the user from the database based on their ID.
     *
     * @return bool True if deletion is successful, false otherwise.
     */
    public function deleteUser(): bool {
        $conn = Connection::connection();

        // Prepare the SQL query with a placeholder for user ID
        $sql = "DELETE FROM users WHERE id = ?";
        $params = [$this->id];

        // Use setP method to execute the delete query
        $success = Connection::setP($sql, $params);

        // Close the database connection
        $conn->close();

        // Return true if deletion is successful, false otherwise
        return $success;
    }

    /**
     * Retrieves all users from the database.
     *
     * @return array
     */
    public static function getAllUsers(): array {
        $userData = Connection::get("SELECT * FROM users");

        $users = [];
        foreach ($userData as $userDataItem) {
            // Instantiate User objects and add them to the $users array
            $user = new User(
                $userDataItem['id'], $userDataItem['username'],
                $userDataItem['email'],
                $userDataItem['password'],
                $userDataItem['first_name'],
                $userDataItem['last_name'],
                new DateTimeImmutable($userDataItem['birthday']),
                $userDataItem['address'],
                $userDataItem['phone'],
                $userDataItem['status'],
                $userDataItem['status'],
                new DateTimeImmutable($userDataItem['created_at']),
                new DateTimeImmutable($userDataItem['updated_at'])
            );
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Retrieves a user by their ID from the database.
     *
     * @param int $userId
     *
     * @return User|null Returns the User object if the user is found; otherwise, returns null.
     */
    public static function getUserById(int $userId): ?User {
        $sql = "SELECT * FROM users WHERE id = ?";
        $params = [$userId];
        $result = Connection::getP($sql, $params);

        if ($result && $result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            // Extract user data fields (adjust field names accordingly)
            $username = $userData['username'];
            $email = $userData['email'];
            $password = $userData['password'];
            $firstName = $userData['first_name'];
            $lastName = $userData['last_name'];
            try {
                $birthday = new DateTimeImmutable($userData['birthday']);
                $createdAt = new DateTimeImmutable($userData['created_at']); // Assuming created_at is stored as a string in the database
                $updatedAt = new DateTimeImmutable($userData['updated_at']); // Assuming updated_at is stored as a string in the database
            } catch (\Exception $e) {
                return null;
            } // Assuming birthday is stored as a string in the database
            $address = $userData['address'];
            $phone = $userData['phone'];
            $role = $userData['role'];
            $status = $userData['status'];

            // Create and return the User object
            return new User($userId, $username, $email, $password, $firstName, $lastName, $birthday, $address, $phone, $role, $status, $createdAt, $updatedAt);
        }

        // Return null if the user is not found in the database
        return null;
    }

    /**
     * Converts the user object to a string representation.
     *
     * @return string
     */
    public function __toString(): string {
        return "User ID: {$this->id}\n" .
            "Username: {$this->username}\n" .
            "Email: {$this->email}\n" .
            "Full Name: {$this->getFullName()}\n" .
            "Role: {$this->role}\n" .
            "Status: {$this->status}\n" .
            "Created At: {$this->createdAt->format('Y-m-d H:i:s')}\n" .
            "Updated At: {$this->updatedAt->format('Y-m-d H:i:s')}\n";
    }

    /**
     * @return int|null
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getBirthday(): DateTimeImmutable
    {
        return $this->birthday;
    }

    /**
     * @param DateTimeImmutable $birthday
     */
    public function setBirthday(DateTimeImmutable $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeImmutable $updatedAt
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}