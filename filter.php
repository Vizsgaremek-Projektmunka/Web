<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter</title>
</head>
<body>
    <h1>Filter</h1>
    <form method="GET" action="../API/filter_games.php">
        <label for="min_price">Minimum price:</label>
        <input type="number" id="min_price" name="min_price" value="<?php echo htmlspecialchars($minPrice); ?>"><br>

        <label for="max_price">Maximum price:</label>
        <input type="number" id="max_price" name="max_price" value="<?php echo htmlspecialchars($maxPrice); ?>"><br>

        <label for="developer">Developer:</label>
        <input type="text" id="developer" name="developer" value="<?php echo htmlspecialchars($developer); ?>"><br>

        <button type="submit">Filter</button>
    </form>

    <?php if (!empty($games)): ?>
        <h2>Results:</h2>
        <ul>
            <?php foreach ($games as $game): ?>
                <li>
                    <img src="<?php echo $game['banner_img']; ?>" alt="<?php echo htmlspecialchars($game['title']); ?>" style="width: 100px;">
                    <h3><?php echo htmlspecialchars($game['title']); ?></h3>
                    <p><?php echo htmlspecialchars($game['description']); ?></p>
                    <p>Price: <?php echo $game['price']; ?> USD</p>
                    <a href="game_details.php?id=<?php echo $game['id']; ?>">Details</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No results found for the given filter criteria.</p>
    <?php endif; ?>
</body>
</html>
