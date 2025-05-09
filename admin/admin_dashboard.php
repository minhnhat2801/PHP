<?php
session_start();
include 'slider.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // Chuyển về trang chính nếu không phải admin
    exit();
}
?>
