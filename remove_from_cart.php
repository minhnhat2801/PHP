<?php
session_start();
require_once 'admin/database.php';

$db = new Database();
$conn = $db->conn;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];
    $sql = "DELETE FROM cart WHERE cart_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: cart.php");
exit();
?>