<?php
session_start();
require 'admin/database.php'; // Kết nối đến database
$db = new Database();
$conn = $db->conn;

// Kiểm tra nếu chưa đăng nhập thì yêu cầu đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = 'cart.php'; // Lưu URL để quay lại sau khi đăng nhập
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Kiểm tra kết nối database
if (!$conn) {
    die("Kết nối CSDL thất bại: " . mysqli_connect_error());
}

// Tính số sản phẩm khác nhau trong giỏ hàng
$sql_cart_count = "SELECT COUNT(DISTINCT product_id) as total FROM cart WHERE user_id = ?";
$stmt_cart = $conn->prepare($sql_cart_count);
$stmt_cart->bind_param("i", $user_id);
$stmt_cart->execute();
$cart_count = $stmt_cart->get_result()->fetch_assoc()['total'] ?? 0;
$stmt_cart->close();

// Lấy danh sách sản phẩm trong giỏ hàng
$sql = "SELECT c.cart_id, p.product_name, p.product_price_new, p.product_img, c.quantity 
        FROM cart c
        INNER JOIN product p ON c.product_id = p.product_id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $row['total'] = $row['product_price_new'] * $row['quantity'];
    $total_price += $row['total'];
    $cart_items[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Kính Mắt Hà Nội</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/fontawesome-free-6.5.1-web/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/logo.png">
        </div>
        <div class="menu">
            <li><a href="#">Kính Thời Trang</a></li>
            <li><a href="#">Gọng Kính Cận</a></li>
            <li><a href="#">Tròng Kính</a></li>
            <li><a href="#">Kính Râm</a></li>
            <li><a href="#">Luxury</a></li>
            <li><a href="#">Học sinh sinh viên</a></li>
        </div>
        <div class="others">
            <li><input placeholder="Tìm Kiếm" type="text"><i class="fas fa-search"></i></li>
            <li><a class="fa fa-user" href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"></a></li>
            <li style="position: relative;">
                <a class="fa fa-shopping-bag" href="cart.php">
                    <span id="cart-count"><?php echo $cart_count; ?></span>
                </a>
            </li>
        </div>
    </header>

    <section class="cart">
        <div class="container">
            <div class="cart-top-wrap">
                <div class="cart-top">
                    <div class="cart-top-cart cart-top-item"><i class="fas fa-shopping-cart"></i></div>
                    <div class="cart-top-address cart-top-item"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="cart-top-payment cart-top-item"><i class="fas fa-credit-card"></i></div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="cart-content row">
                <div class="cart-content-left">
                    <table>
                        <tr>
                            <th>Sản Phẩm</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Số Lượng</th>
                            <th>Giá</th>
                            <th>Thành Tiền</th>
                            <th>Xóa</th>
                        </tr>
                        <?php if (empty($cart_items)) { ?>
                            <tr><td colspan="6">Giỏ hàng trống!</td></tr>
                        <?php } else { ?>
                            <?php foreach ($cart_items as $item) { ?>
                                <tr>
                                    <td><img src="admin/<?php echo htmlspecialchars($item['product_img']); ?>" width="50"></td>
                                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo number_format($item['product_price_new'], 0, ',', '.'); ?> VND</td>
                                    <td><?php echo number_format($item['total'], 0, ',', '.'); ?> VND</td>
                                    <td><a href="remove_from_cart.php?cart_id=<?php echo $item['cart_id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">X</a></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </table>
                </div>
                <div class="cart-content-right">
                    <h3>Tổng tiền: <?php echo number_format($total_price, 0, ',', '.'); ?> VND</h3>
                    <div class="cart-content-right-text">
                        <p>Bạn sẽ được miễn phí ship khi đơn hàng có tổng giá trị trên 2.000.000 VND</p>
                    </div>
                    <div class="cart-content-right-button">
                        <button onclick="window.location.href='category.php'">TIẾP TỤC MUA SẢN PHẨM</button>
                        <button onclick="window.location.href='delivery.php'">THANH TOÁN</button>
                    </div>
                </div>
            </div>
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
    Penalytics
    </footer>

    <?php $conn->close(); ?>
</body>
</html>