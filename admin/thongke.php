<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_bankinh";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Truy vấn tổng doanh thu
$sql_revenue = "SELECT SUM(total_price) AS total_revenue, COUNT(order_id) AS total_orders FROM orders WHERE status = 'completed'";
$result = $conn->query($sql_revenue);
$data = $result->fetch_assoc();
$total_revenue = $data['total_revenue'] ?? 0;
$total_orders = $data['total_orders'] ?? 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê doanh thu</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { max-width: 500px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        h2 { color: #333; }
        .stat { font-size: 20px; margin: 10px 0; }
        .stat span { font-weight: bold; color: #27ae60; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thống kê doanh thu</h2>
        <p class="stat">Tổng doanh thu: <span><?php echo number_format($total_revenue, 0, ',', '.'); ?> VND</span></p>
        <p class="stat">Tổng số đơn hàng: <span><?php echo $total_orders; ?></span></p>
    </div>
</body>
</html>
