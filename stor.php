<?php 
    include "../API/store.php";
    $username = $_SESSION['username'] ?? null; 
    $games = $games ?? []; 
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
    <link rel="stylesheet" href="styles.css">
    <title>CapyGames</title>
</head>
<body>
    <?php include "header.php";?>
    <nav>
        <?php if ($username): ?>
            <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <?php endif; ?>
    </nav>

    <nav class="navbar">
        <a href="stor.php" class="nav-item">Store</a>
        <a href="library.php" class="nav-item">Library</a>
        <a href="wishlists.php" class="nav-item">Wishlist</a>
        <a href="view_cart.php" class="nav-item">Cart</a>
        <div class="search-container">
            <form onsubmit="return searchGames(event)" method="GET">
                <input type="text" placeholder="Search games..." id="search-input" name="query" class="search-input">
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>
    </nav>
    <section>
    <?php if (!$username): ?>
            <div class="not-logged-in">
                You are not logged in. To add items to the cart, please <a href="log.php">log in</a>.
            </div>
        <?php endif; ?>
        
        <h2>Available Games</h2>
        <div class="store-container">
            <?php foreach ($games as $game): ?>
                <div class="game-card">
                    <h3>
                        <a href="game_details.php?game_id=<?php echo htmlspecialchars($game['id']); ?>">
                            <?php echo htmlspecialchars($game['title']); ?>
                        </a>
                    </h3>
                    <p>Price: <?php echo number_format($game['price'], 2); ?>€</p>
                    <p><?php echo htmlspecialchars($game['description']); ?></p>
                    <div class="button-container">
                        <a href="add_to_wishlist.php?game_id=<?php echo htmlspecialchars($game['id']); ?>" class="favorite-button">Favorite</a>
                        <a href="../API/add_to_cart.php?game_id=<?php echo htmlspecialchars($game['id']); ?>" class="add-to-cart">Add to Cart</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
