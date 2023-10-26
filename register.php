<?php
include_once("assets/php/funkcije.php");

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = UserInput::sanitize($_POST["username"]);
    $email = UserInput::sanitize($_POST["email"]);
    $password = UserInput::sanitize($_POST["password"]);
    $firstName = UserInput::sanitize($_POST["first_name"]);
    $lastName = UserInput::sanitize($_POST["last_name"]);
    $birthday = DateTimeImmutable::createFromFormat('Y-m-d', UserInput::sanitize($_POST["birthday"]));
    $address = UserInput::sanitize($_POST["address"]);
    $phone = UserInput::sanitize($_POST["phone"]);

    // Validate inputs (you can add more validation logic here)
    if (empty($username) || empty($email) || empty($password) || empty($firstName) || empty($lastName) || empty($birthday) || empty($address) || empty($phone)) {
        $error_message = "All fields are required.";
    } else {
        // Check if username and email are already taken
        if (User::isUsernameTaken($username)) {
            $error_message = "Username is already taken.";
        } elseif (User::isEmailTaken($email)) {
            $error_message = "Email is already taken.";
        } else {
            //validate fields
            $data = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'name' => $firstName,
                'last name' => $lastName,
                'birthday' => $birthday->format('Y-m-d'),
                'address' => $address,
                'phone' => $phone
            ];

            $rules = [
                'username' => 'required|min:5|max:20',
                'email' => 'required|min:5|max:90|email',
                'password' => 'required|min:8|max:50',
                'name' => 'required|min:3|max:50',
                'last name' => 'required|min:3|max:50',
                'birthday' => 'required|date',
                'address' => 'required|min:3|max:50',
                'phone' => 'required',
            ];

            $validator = new Validator($data, $rules);

            if ($validator->validate()) {
                // Hash the password before storing it in the database
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Create a new user in the database
                $new_user = User::createUser($username, $email, $hashed_password, $firstName, $lastName, $birthday, $address, $phone);

                if ($new_user) {
                    // Redirect to login page after successful registration
                    header("Location: login.php");
                    exit();
                } else {
                    $error_message = "Registration failed. Please try again later.";
                }
            } else {
                $errors = $validator->getErrors();

                foreach ($errors as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $msg .= Message::danger("Error in $field: $error");
                    }
                }
            }


        }
    }
}
?>

<!doctype html>
<html lang="sr">
<head>
    <!-- meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="Index, Follow">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="copyright" content="">
    <meta name="Audience" content="all">
    <meta name="distribution" content="global">
    <meta name="theme-color" content="#EBEBEB" >
    <meta name="language" content="sr">

    <link rel="preload" as="image" href="important.png">

    <link href="" rel="canonical">

    <link href="" rel="icon">
    <link href="" rel="apple-touch-icon">

    <title>Hello, world!</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="loginPage">
<div class="container-fluid">

    <div class="row">
        <?php include_once("assets/components/navigacija.php"); ?>
    </div>

    <div class="row">
        <div class="col-12">
            <?php echo $msg; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 offset-md-3">
            <form class="loginForm" method="POST" action="register">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="mb-3">
                    <label for="birthday" class="form-label">Birthday</label>
                    <input type="date" class="form-control" id="birthday" name="birthday" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <button type="submit" class="btn btn-primary" name="register">Register</button>
            </form>
        </div>
    </div>

    <div class="row">
        <?php include_once("assets/components/footer.php"); ?>
    </div>

</div>


<script src="assets/js/script.js" defer></script>
</body>
</html>