<?php
session_start();
require_once 'admin/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Vui lòng đăng nhập để tiếp tục.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id']; 
    $product_id = $_POST['product_id']; 
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($quantity < 1) {
        $quantity = 1;
    }

    // Kiểm tra số lượng tồn kho
    $stock_query = "SELECT product_quantity, product_price_new FROM product WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $stock_query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $stock_result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($stock_result);

    if ($product) {
        $stock_quantity = $product['product_quantity'];
        $price = $product['product_price_new'];

        if ($quantity > $stock_quantity) {
            $quantity = $stock_quantity;  // Giới hạn số lượng mua theo tồn kho
        }

        $total_price = $price * $quantity;

        // Cập nhật số lượng trong order_details
        $update_query = "UPDATE order_details SET quantity = ?, price = ? WHERE order_id = ? AND product_id = ?";
        $stmt_update = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt_update, "idii", $quantity, $total_price, $order_id, $product_id);
        mysqli_stmt_execute($stmt_update);

        // Cập nhật tổng tiền trong bảng orders
        $update_total_query = "UPDATE orders SET total_price = (SELECT SUM(price) FROM order_details WHERE order_id = ?) WHERE order_id = ?";
        $stmt_total = mysqli_prepare($conn, $update_total_query);
        mysqli_stmt_bind_param($stmt_total, "ii", $order_id, $order_id);
        mysqli_stmt_execute($stmt_total);

        // Cập nhật số lượng tồn kho sau khi đặt hàng
        $new_stock = $stock_quantity - $quantity;
        $update_stock_query = "UPDATE product SET product_quantity = ? WHERE product_id = ?";
        $stmt_stock = mysqli_prepare($conn, $update_stock_query);
        mysqli_stmt_bind_param($stmt_stock, "ii", $new_stock, $product_id);
        mysqli_stmt_execute($stmt_stock);
    }

    // Quay lại giỏ hàng
    header("Location: giohang.php");
    exit;
}

mysqli_close($conn);
?>
