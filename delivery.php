<?php
session_start();
include 'admin/database.php'; // Kết nối database
$db = new Database();
$conn = $db->conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $address = $_POST['address'];
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
    // Lấy tổng giá trị giỏ hàng
    $query = "SELECT SUM(c.quantity * p.product_price_new) AS total_price FROM cart c INNER JOIN product p ON c.product_id = p.product_id WHERE c.user_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $total_price = $row['total_price'];
    
    // Lưu vào bảng orders
    $insert_order = "INSERT INTO orders (user_id, order_date, status, total_price) VALUES ('$user_id', NOW(), 'pending', '$total_price')";
    mysqli_query($conn, $insert_order);
    $order_id = mysqli_insert_id($conn);
    
    // Lưu chi tiết đơn hàng
    $cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
    while ($item = mysqli_fetch_assoc($cart_items)) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        
        $product_query = mysqli_query($conn, "SELECT product_price_new FROM product WHERE product_id = '$product_id'");
        $product = mysqli_fetch_assoc($product_query);
        $price = $product['product_price_new'];
        
        mysqli_query($conn, "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '$price')");
    }
    
    // Xóa giỏ hàng sau khi đặt hàng thành công
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");
    
    echo "<script>alert('Đặt hàng thành công!'); window.location.href='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kính Mắt Hà Nội</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/fontawesome-free-6.5.1-web/css/all.min.css">
</head>
<body>

    <header>
        <div class="logo">
            <img src="image/logo.png">

        </div>
        <div class="menu">
                <li><a href="">Kính Thời Trang</a>
                <ul class = "sub-menu">
                    <li><a href="">Hàng mới về</a></li>
                    <li><a href="">Hàng cao cấp</a>
                    <ul>
                        <li><a href="">Gucci</a></li>
                        <li><a href="">Luis Vuiton</a</li>
                        <li><a href="">Lexus</a</li>
                    </ul>
                    </li>
                    
                    <ul></ul>
                    </li>
                </ul>
                
                </li>

                <li><a href="">Gọng Kính Cận</a></li>
                <li><a href="">Tròng Kính</a></li>
                <li><a href="">Kính Râm</a></li>
                <li><a href="">Luxury</a></li>
                <li><a href="">Học sinh sinh viên</a></li>    
        </div>
        <div class="others">
            <li><input placeholder="Tìm Kiếm" type="text" name="" ><i class = "fas fa-search" ></i></li>
            <li><a class = "fa fa-user" href=""></a></li>
            <li><a class = "fa fa-shopping-bag" href=""></a></li>
        </div>
    </header>

    <section class="delivery">
        <div class="container">
            <div class="delivery-top-wrap">
                <div class="delivery-top">
                  <div class="delivery-top-cart delivery-top-item"><i class="fas fa-shopping-cart"></i></div>
                  <div class="delivery-top-address delivery-top-item"><i class="fas fa-map-marker-alt"></i></div>
                  <div class="delivery-top-payment delivery-top-item"><i class="fas fa-credit-card"></i></div>
                </div>
              </div>
        </div>

        <form action="" method="POST">
        <div class="delivery-content row">
            <div class="delivery-content-left">
                <p>Vui lòng chọn địa chỉ giao hàng</p>
                <div class="delivery-content-left-input-top row">
                    <div class="delivery-content-left-input-top-item">
                        <label for="name">Họ Tên</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="delivery-content-left-input-top-item">
                        <label for="phone">Số Điện Thoại</label>
                        <input type="text" name="phone" required>
                    </div>
                    <div class="delivery-content-left-input-top-item">
                        <label for="city">Tỉnh/Thành Phố</label>
                        <input type="text" name="city" required>
                    </div>
                    <div class="delivery-content-left-input-top-item">
                        <label for="district">Quận/Huyện</label>
                        <input type="text" name="district" required>
                    </div>
                </div>
                <div class="delivery-content-left-input-bottom">
                    <label for="address">Địa Chỉ</label>
                    <input type="text" name="address" required>
                </div>
                <div class="delivery-content-left-button row">
                    <a href="cart.php"><span>&#171;</span>Quay lại giỏ hàng</a>
                    <button type="submit"><p style="font-weight: bold;">THANH TOÁN VÀ GIAO HÀNG</p></button>
                </div>
            </div>
        </div>
    </form>

    <?php


$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id == 0) {
    echo "<p>Vui lòng đăng nhập để tiếp tục.</p>";
    exit;
}

// Truy vấn giỏ hàng của người dùng
$sql = "SELECT c.quantity, p.product_name, p.product_price, p.product_price_new
        FROM cart c
        JOIN product p ON c.product_id = p.product_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
$vat = 0.1; // Thuế VAT 10%
?>

<div class="delivery-content-right">
    <table>
        <tr>
            <th>Tên Sản Phẩm</th>
            <th>Giảm Giá</th>
            <th>Số Lượng</th>
            <th>Thành Tiền</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
            $discount = round(100 - ($row['product_price_new'] / $row['product_price']) * 100);
            $subtotal = $row['product_price_new'] * $row['quantity'];
            $total_price += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= $discount ?>%</td>
                <td><?= $row['quantity'] ?></td>
                <td><p><?= number_format($subtotal, 0, ',', '.') ?> VND</p></td>
            </tr>
        <?php endwhile; ?>
        <tr>
            <td style="font-weight: bold;" colspan="3">Tổng</td>
            <td style="font-weight: bold;"> <?= number_format($total_price, 0, ',', '.') ?> VND </td>
        </tr>
        <tr>
            <td style="font-weight: bold;" colspan="3">Thuế VAT (10%)</td>
            <td style="font-weight: bold;"> <?= number_format($total_price * $vat, 0, ',', '.') ?> VND </td>
        </tr>
        <tr>
            <td style="font-weight: bold;" colspan="3">Tổng Tiền Hàng</td>
            <td style="font-weight: bold;"> <?= number_format($total_price * (1 + $vat), 0, ',', '.') ?> VND </td>
        </tr>
    </table>
</div>
    </section>

    <footer>
        <div class="footer">
            <div class="about">
                <h3>GIỚI THIỆU</h3>
                <p>Thông tin công ty CÔNG TY TNHH SẢN XUẤT VÀ THƯƠNG MẠI MINH NHẤT</p>
            </div>
            <div class="contact">
                <h3>LIÊN HỆ</h3>
                <p>Điện thoại: 0972.359.6669</p>
            </div>
            <div class="policy">
                <h3>CHÍNH SÁCH</h3>
                <p>Chính sách thẻ Vip</p>
            </div>
        </div>
        <div class="footer copyright">
            © 2025 Bản quyền Kính Mắt Hà Nội
        </div>
    
    </footer>
</body>
</html>