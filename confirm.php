<?php
include_once("assets/php/funkcije.php");

// Check if the user is already logged in, redirect to dashboard if logged in
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Check if the token provided in the URL
if (isset($_GET['token'])) {
    $token = UserInput::sanitize($_GET['token']);

    // Validate the token
    if (TokenManager::validateToken($token)) {
        // Token is valid, activate the user's account
        $userId = TokenManager::getUserIdByToken($token);
        User::activateAccount($userId);

        // Delete the confirmation token from the database
        TokenManager::deleteToken($userId);

        // Display a success message to the user
        echo "Account activated successfully. You can now <a href='login'>log in</a>.";
    } else {
        // Invalid token, display an error message
        echo "Invalid or expired token.";
    }
} else {
    // Token or user ID not provided, display an error message
    echo "Invalid request.";
}
?>
