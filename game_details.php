<?php
session_start();
if (isset($_SESSION['success_message'])) {
    echo $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Remove message so it doesn't show again
} elseif (isset($_SESSION['error_message'])) {
    echo $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Remove message so it doesn't show again
}

require_once '../API/db.php';

$database = new Database();
$pdo = $database->getConnection();

$game_id = isset($_GET['game_id']) ? (int)$_GET['game_id'] : 0;
$gameData = null; // Initially set to null

if ($game_id > 0) {
    try {
        $stmt = $pdo->prepare(
            "SELECT 
                g.title, 
                c_dev.name AS developer, 
                c_pub.name AS publisher, 
                g.price, 
                s_min.system AS min_system, 
                s_min.cpu AS min_cpu, 
                s_min.gpu AS min_gpu, 
                s_min.ram AS min_ram, 
                s_min.storage AS min_storage, 
                s_rec.system AS rec_system, 
                s_rec.cpu AS rec_cpu, 
                s_rec.gpu AS rec_gpu, 
                s_rec.ram AS rec_ram, 
                s_rec.storage AS rec_storage
            FROM 
                games g
            LEFT JOIN 
                companies c_dev ON g.developer = c_dev.id
            LEFT JOIN 
                companies c_pub ON g.publisher = c_pub.id
            LEFT JOIN 
                specifications s_min ON g.min_spec = s_min.id
            LEFT JOIN 
                specifications s_rec ON g.rec_spec = s_rec.id
            WHERE 
                g.id = ?;"
        );
        $stmt->execute([$game_id]);
        $gameData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$gameData) {
            $gameData = null;
        }
    } catch (PDOException $e) {
        echo "An error occurred: " . $e->getMessage();
        exit;
    }
} else {
    echo "Invalid or missing game_id.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($gameData['title'] ?? 'Game not found'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #111; color: #fff; text-align: center; }
        .container { width: 60%; margin: auto; background: #222; padding: 20px; border-radius: 10px; }
        .price { font-size: 24px; font-weight: bold; color: #0f0; }
        .buy-button { background: green; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 5px; }
        .specs { display: flex; justify-content: space-between; background: #333; padding: 10px; border-radius: 5px; margin-top: 1%;}
        .specs div { width: 48%; }
    </style>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include "header.php"; ?>
    <div class="container">
        <?php if ($gameData): ?>
            <h1><?php echo htmlspecialchars($gameData['title']); ?></h1>
            <p><strong>Developer:</strong> <?php echo htmlspecialchars($gameData['developer']); ?></p>
            <p><strong>Publisher:</strong> <?php echo htmlspecialchars($gameData['publisher']); ?></p>
            <p class="price"><?php echo htmlspecialchars($gameData['price']); ?>€</p>
            <a href="../API/add_to_cart.php?game_id=<?php echo htmlspecialchars($game_id); ?>" class="buy-button">Buy</a>

            <div class="specs">
                <div>
                    <h3>Minimum Requirements</h3>
                    <p>System: <?php echo htmlspecialchars($gameData['min_system'] ?? 'Not specified'); ?></p>
                    <p>CPU: <?php echo htmlspecialchars($gameData['min_cpu'] ?? 'Not specified'); ?></p>
                    <p>GPU: <?php echo htmlspecialchars($gameData['min_gpu'] ?? 'Not specified'); ?></p>
                    <p>RAM: <?php echo htmlspecialchars($gameData['min_ram'] ?? 'Not specified'); ?></p>
                    <p>Storage: <?php echo htmlspecialchars($gameData['min_storage'] ?? 'Not specified'); ?></p>
                </div>
                <div>
                    <h3>Recommended Requirements</h3>
                    <p>System: <?php echo htmlspecialchars($gameData['rec_system'] ?? 'Not specified'); ?></p>
                    <p>CPU: <?php echo htmlspecialchars($gameData['rec_cpu'] ?? 'Not specified'); ?></p>
                    <p>GPU: <?php echo htmlspecialchars($gameData['rec_gpu'] ?? 'Not specified'); ?></p>
                    <p>RAM: <?php echo htmlspecialchars($gameData['rec_ram'] ?? 'Not specified'); ?></p>
                    <p>Storage: <?php echo htmlspecialchars($gameData['rec_storage'] ?? 'Not specified'); ?></p>
                </div>
            </div>
        <?php else: ?>
            <h1>Game not found</h1>
            <p>No game found with the provided ID.</p>
        <?php endif; ?>
    </div>
</body>
</html>
