<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh muc san pham</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>CHÀO MỪNG TỚI GIAO DIỆN ADMIN</h1>
        <?php session_start(); ?>
<nav>
    <a href="PHP/index.php">Trang chủ</a>
    <a href="PHP/category.php">Sản phẩm</a>
    <a href="PHP/cart.php">Giỏ hàng</a>
    
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin_dashboard.php">Quản lý sản phẩm</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php">Đăng xuất</a>
    <?php else: ?>
        <a href="PHP/login.php">Đăng nhập</a>
    <?php endif; ?>
</nav>

    </header>
