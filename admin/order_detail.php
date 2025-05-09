<?php
include 'database.php'; // Kết nối database
$db = new Database();
$conn = $db->conn;

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    
    // Lấy thông tin đơn hàng
    $order_query = "SELECT o.order_id, o.order_date, o.status, o.total_price, u.username, u.email, u.phone, u.address 
                    FROM orders o
                    JOIN users u ON o.user_id = u.user_id 
                    WHERE o.order_id = ?";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_result = $stmt->get_result();
    $order = $order_result->fetch_assoc();
    
    // Lấy danh sách sản phẩm trong đơn hàng
    $details_query = "SELECT p.product_name, od.quantity, od.price 
                      FROM order_details od
                      JOIN product p ON od.product_id = p.product_id 
                      WHERE od.order_id = ?";
    $stmt = $conn->prepare($details_query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $details_result = $stmt->get_result();
} else {
    echo "Không có đơn hàng nào được chọn!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Chi Tiết Đơn Hàng #<?php echo $order['order_id']; ?></h2>
    <p><strong>Ngày đặt hàng:</strong> <?php echo $order['order_date']; ?></p>
    <p><strong>Trạng thái:</strong> <?php echo $order['status']; ?></p>
    <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total_price'], 0, ',', '.'); ?> VND</p>
    
    <h3>Thông Tin Khách Hàng</h3>
    <p><strong>Tên khách hàng:</strong> <?php echo $order['username']; ?></p>
    <p><strong>Email:</strong> <?php echo $order['email']; ?></p>
    <p><strong>Số điện thoại:</strong> <?php echo $order['phone']; ?></p>
    <p><strong>Địa chỉ:</strong> <?php echo $order['address']; ?></p>
    
    <h3>Sản Phẩm Trong Đơn Hàng</h3>
    <table border="1">
        <tr>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Thành tiền</th>
        </tr>
        <?php while ($row = $details_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo number_format($row['price'], 0, ',', '.'); ?> VND</td>
                <td><?php echo number_format($row['quantity'] * $row['price'], 0, ',', '.'); ?> VND</td>
            </tr>
        <?php } ?>
    </table>
    
    <a href="chitietdonhang.php">Quay lại danh sách đơn hàng</a>
</body>
</html>