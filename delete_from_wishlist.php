<?php
session_start();
require_once '../API/db.php';  // Adatbázis kapcsolat

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['username'])) {
    sendResponse(401, ["message" => "User not logged in"]);
}

$username = $_SESSION['username'];  // Felhasználónév beállítása

// JSON válaszküldés funkció
function sendResponse($status, $response) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Ellenőrizzük, hogy a `game_id` GET paraméterben meg van adva
if (!isset($_GET['game_id'])) {
    $_SESSION['error_message'] = "Game ID is missing.";
        header("Location: ../Web/wishlists.php");
        exit;
}

$game_id = $_GET['game_id']; // Játék ID lekérése GET paraméterből

// SQL törlés előkészítése
$query = "DELETE FROM wishlists WHERE username = :username AND game_id = :game_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(":username", $username);
$stmt->bindParam(":game_id", $game_id);

try {
    if ($stmt->execute()) {
        $_SESSION['succes_message'] = "Game removed from wishlist";
        header("Location: ../Web/wishlists.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Unable to remove game from wishlist";
        header("Location: ../Web/wishlists.php");
        exit;
    }
} catch (PDOException $e) {
    sendResponse(500, ["message" => "Database error", "error" => $e->getMessage()]);
}
