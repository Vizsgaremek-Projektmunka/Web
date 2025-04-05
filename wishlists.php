<?php
    session_start(); // Start session

    if (isset($_SESSION['success_message'])) {
        echo $_SESSION['success_message'];
        unset($_SESSION['success_message']);
    } elseif (isset($_SESSION['error_message'])) {
        echo $_SESSION['error_message'];
        unset($_SESSION['error_message']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php 

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
    <h2>My wishlist</h2>
    <div id="wishlist"><p>You have no wishlisted games.</p></div>
    <script>
        // Fetch wishlist data from the API
        fetch('../API/my_wishlist.php')
            .then(response => response.json())
            .then(data => {
                let wishlistDiv = document.getElementById('wishlist');
                if (data.length === 0) {
                    wishlistDiv.innerHTML = "<p>You have no wishlisted games.</p>";
                } else {
                    wishlistDiv.innerHTML = "";
                    data.forEach(game => {
                    let div = document.createElement('div');
                    div.innerHTML = `
                            <div class="game-card">
                                <h3><a href="game_details.php?game_id=${game.id}">${game.title}</a></h3>
                    <p>Price: ${game.price}€</p>
                    <p>${game.description}</p>
                    <div class="button-container">
                        <a href="../API/add_to_cart.php?game_id=${game.id}" class="add-to-cart">Add to Cart</a>
                        <a href="delete_from_wishlist.php?game_id=${game.id}" class="remove">Remove</a>
                    </div>
        </div>`;
                        wishlistDiv.appendChild(div);
                    });
                }
            })
            .catch(error => {
                console.error('An error occurred:', error);
            });
    </script>
</body>
</html>
