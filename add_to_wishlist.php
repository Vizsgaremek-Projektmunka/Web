<?php
session_start();
require_once '../API/db.php'; // Adatbázis kapcsolat

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
if (!isset($_SESSION['username'])) {
    $_SESSION['error_message'] = "You must be logged in to add to wishlist.";
    header("Location: ../Web/game_details.php");
    exit;
}

// Ellenőrizzük, hogy van-e game_id a kérésben
if (!isset($_GET['game_id']) || empty($_GET['game_id'])) {
    $_SESSION['error_message'] = "Game ID is missing.";
    header("Location: ../Web/game_details.php");
    exit;
}

$username = $_SESSION['username'];
$game_id = intval($_GET['game_id']); // GET metódus használata

// Ellenőrizzük, hogy a játék már szerepel-e a kívánságlistában
$query = "SELECT * FROM wishlists WHERE username = :username AND game_id = :game_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['username' => $username, 'game_id' => $game_id]);
$result = $stmt->fetch();

if ($result) {
    $_SESSION['error_message'] = "Game is already in wishlist.";
    header("Location: ../Web/game_details.php?game_id=" . htmlspecialchars($game_id));
    exit;
} else {
    // Hozzáadás a kívánságlistához
    $insert = "INSERT INTO wishlists (username, game_id) VALUES (:username, :game_id)";
    $stmt = $pdo->prepare($insert);
    $success = $stmt->execute(['username' => $username, 'game_id' => $game_id]);

    if ($success) {
        $_SESSION['success_message'] = "Game added to wishlist.";
    } else {
        $_SESSION['error_message'] = "Error adding to wishlist.";
    }

    header("Location: ../Web/game_details.php?game_id=" . htmlspecialchars($game_id));
    exit;
}
?>
