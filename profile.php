<?php include("../API/profile_image.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'header.php'; ?>
    <div class="content">
        <h1>Welcome to CapyGames!</h1>
    </div>

    <h1 style="text-align:center;">Edit Profile</h1>
    <div class="login" style="padding:2%;text-align:center;">
        <form method="POST" action="update.php" enctype="multipart/form-data">
            <label for="username">New Username:</label><br>
            <input style="text-align:center;" type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required><br><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="5" cols="30"></textarea><br><br>

            <label for="profile_image">Profile Image:</label><br>

            <?php if ($profileImage && file_exists("uploads/" . $profileImage)): ?>
                <img src="uploads/<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" width="150"><br>
            <?php endif; ?>

            <input type="file" id="profile_image" name="profile_image"><br><br>

            <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>
