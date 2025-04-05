<?php
session_start();

// Ellenőrzés, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['username'])) {
    $_SESSION['error_message'] = "Please login to access this page.";
    header("Location: ../Web/log.php");
    exit;
    }

    $username = $_SESSION['username'];

// Siker- és hibavisszajelzések megjelenítése
if (isset($_SESSION['success_message'])) {
    echo $_SESSION['success_message'];
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    echo $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

include '../API/db.php';

// Adatbázis kapcsolat inicializálása
try {
    $db = new Database();
    $pdo = $db->getConnection();
} catch (Exception $e) {
    die("<p class='error'>Could not connect to the database.</p>");
}

// Játékok lekérése
$sql = "SELECT id, title, description, price FROM games";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CapyGames</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content welcome-container">
        <h1>Welcome to CapyGames!</h1>
        <p>Where you can buy and discover the best games!</p>
    </div>
    
    <!-- NAVIGÁCIÓS SÁV -->
    <nav class="navbar" style="margin:0%;padding: 0% 1%;">
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

    <!-- FŐ TARTALOM -->
    <div id="main-content">
        <?php if (count($result) > 0): ?>
            <div class=" store-container">
            <?php foreach ($result as $row): ?>
                <div class="game-card">
                    <h3>
                        <a href="game_details.php?game_id=<?php echo htmlspecialchars($row['id']); ?>">
                            <?php echo htmlspecialchars($row['title']); ?>
                        </a>
                    </h3>
                    <p>Price: <?php echo number_format($row['price'], 2); ?>€</p>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <div class="button-container">
                        <a href="add_to_wishlist.php?game_id=<?php echo htmlspecialchars($row['id']); ?>" class="favorite-button">Favorite</a>
                        <a href="../API/add_to_cart.php?game_id=<?php echo htmlspecialchars($row['id']); ?>" class="add-to-cart">Add to Cart</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="info">No games available.</p>
        <?php endif; ?>
    </div>

    <!-- Keresési eredmények -->
    <div id="search-results">
        <ul id="results" style="list-style: none;"></ul>
    </div>

    <script>
    function searchGames(event) {
        if (event) event.preventDefault();

        let query = document.getElementById('search-input').value;

        if (query.trim() === '') {
            alert('Please enter a search term.');
            return false;
        }

        fetch(`../API/search.php?query=${encodeURIComponent(query)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                let resultsContainer = document.getElementById('results');
                let mainContent = document.getElementById('main-content');
                let searchResults = document.getElementById('search-results');

                // Alapértelmezett tartalom elrejtése
                mainContent.style.display = 'none';
                searchResults.style.display = 'block';
                resultsContainer.innerHTML = ''; 

                if (data.error) {
                    resultsContainer.innerHTML = `<p>${data.error}</p>`;
                    return;
                }

                // Keresési eredmények megjelenítése
                data.forEach(game => {
                    let li = document.createElement('li');
                    li.innerHTML = `
                            <div class="game-card">
                                <h3><a href="game_details.php?game_id=${game.id}">${game.title}</a></h3>
                    <p>Price: ${game.price}€</p>
                    <p>${game.description}</p>
                    <div class="button-container">
                        <a href="add_to_wishlist.php?game_id=${game.id}" class="favorite-button">Favorite</a>
                        <a href="../API/add_to_cart.php?game_id=${game.id}" class="add-to-cart">Add to Cart</a>
                    </div>
        </div>`;
                    resultsContainer.appendChild(li);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while fetching search results.');
            });

        return false;
    }
    </script>
</body>
</html>
