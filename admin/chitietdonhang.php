<?php

include 'database.php';
$db = new Database();
$conn = $db->conn; // Kết nối CSDL
include("header.php");
include("slider.php");

// Xử lý cập nhật trạng thái đơn hàng
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $update_query = "UPDATE orders SET status = '$new_status' WHERE order_id = $order_id";
    mysqli_query($conn, $update_query);
    echo "<script>alert('Cập nhật trạng thái thành công!'); window.location.href='chitietdonhang.php';</script>";
    exit();
}

$query = "SELECT orders.order_id, users.username, orders.order_date, orders.status, orders.total_price 
          FROM orders 
          JOIN users ON orders.user_id = users.user_id 
          ORDER BY orders.order_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; padding: 0; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: center; border: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .view-btn { padding: 5px 10px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        .view-btn:hover { background: #218838; }
        .status-select { padding: 5px; border-radius: 5px; }
        .update-btn { padding: 5px 10px; background: #ffc107; color: white; border: none; cursor: pointer; border-radius: 5px; }
        .update-btn:hover { background: #e0a800; }
    </style>
</head>
<body>
    <h2>Danh sách đơn hàng</h2>
    <table>
        <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Ngày đặt</th>
            <th>Trạng thái</th>
            <th>Tổng tiền</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['order_date']; ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                        <select name="status" class="status-select">
                            <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Chờ xử lý</option>
                            <option value="processing" <?php if ($row['status'] == 'processing') echo 'selected'; ?>>Đang giao</option>
                            <option value="completed" <?php if ($row['status'] == 'completed') echo 'selected'; ?>>Hoàn thành</option>
                            <option value="canceled" <?php if ($row['status'] == 'canceled') echo 'selected'; ?>>Hủy</option>
                        </select>
                        <button type="submit" name="update_status" class="update-btn">Cập nhật</button>
                    </form>
                </td>
                <td><?php echo number_format($row['total_price'], 0, ',', '.'); ?> đ</td>
                <td><a class="view-btn" href="order_detail.php?order_id=<?php echo $row['order_id']; ?>">Xem</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>