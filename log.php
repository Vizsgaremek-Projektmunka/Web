<?php
session_start();
if (isset($_SESSION['success_message'])) {
    echo  $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Remove message so it doesn't show again
}
elseif(isset($_SESSION['error_message'])) {
    echo  $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Remove message so it doesn't show again
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body style="text-align: center;">
<?php include 'header.php'; ?>
    <div class="content">
        <h1>Welcome to CapyGames!</h1>
        <p style="padding: 0%; margin-top: 0%;">Where you can buy and discover the best games!</p>
    </div>
<div class="login">
        <h1>Log in</h1>
    <form method="POST" action="../API/login.php">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="passwd">Password:</label><br>
        <input type="password" id="passwd" name="passwd" required><br><br>

        <button type="submit">Log in</button>
    </form>
</div>
</body>
</html>
