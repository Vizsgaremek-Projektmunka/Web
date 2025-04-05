<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php 
    session_start(); // Start session

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: log.php"); // Redirect to the login page
        exit();
    }

    $username = $_SESSION['username']; // Get logged-in username from session
    
    include 'header.php'; 
    include '../API/db.php';
    
    // Create a database connection with the Database class
    try {
        $db = new Database();
        $pdo = $db->getConnection();
    } catch (Exception $e) {
        die("<p class='error'>Could not connect to the database.</p>");
    }
    ?>
    <nav>
        <?php if ($username): ?>
            <p>Welcome, User #<?php echo htmlspecialchars($username); ?>!</p> <!-- Display logged-in user's name -->
        <?php else: ?>
            <a href="log.php">Login</a>
        <?php endif; ?>
    </nav>
    <h2>My library</h2>
    <div id="library"></div>
    <script>
        // Fetch library data from the API
        fetch('../API/my_library.php')
            .then(response => response.json())
            .then(data => {
                const libraryDiv = document.getElementById('library');
                if (data.length === 0) {
                    libraryDiv.innerHTML = "<p>You have no purchased games.</p>";
                } else {
                    data.forEach(game => {
                        const gameDiv = document.createElement('div');
                        gameDiv.innerHTML = `
                                    <div class="game-card">
                                        <h3><a href="game_details.php?game_id=${game.id}">${game.title}</a></h3>
                                        <p>Price: ${game.price}€</p>
                                    <a href="../API/add_to_cart.php?game_id=${game.id}" class="buy-button add-to-cart">Add to Cart</a>
                                    </div>`;
                        libraryDiv.appendChild(gameDiv);
                    });
                }
            })
            .catch(error => {
                console.error('An error occurred:', error);
            });
    </script>
</body>
</html>
