<?php
session_start();
require_once 'admin/database.php';

$db = new Database();
$conn = $db->conn;

// Kiểm tra nếu chưa đăng nhập thì chuyển hướng về login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = 'profile.php';
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin người dùng
$sql = "SELECT username, email, phone, address FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Xử lý cập nhật thông tin
if (isset($_POST['update'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $sql_update = "UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssi", $username, $email, $phone, $address, $user_id);
    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thông tin thành công!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật thông tin!');</script>";
    }
    $stmt->close();
}

// Tính số sản phẩm khác nhau trong giỏ hàng (để hiển thị trong header)
$sql_cart = "SELECT COUNT(DISTINCT product_id) as total FROM cart WHERE user_id = ?";
$stmt_cart = $conn->prepare($sql_cart);
$stmt_cart->bind_param("i", $user_id);
$stmt_cart->execute();
$cart_count = $stmt_cart->get_result()->fetch_assoc()['total'] ?? 0;
$stmt_cart->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân - Kính Mắt Hà Nội</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/fontawesome-free-6.5.1-web/css/all.min.css">
</head>
<body>
<header>
        <div class="logo">
            <img src="image/logo.png">

        </div>
        
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

// Truy vấn danh mục
$sql_category = "SELECT * FROM category";
$result_category = $conn->query($sql_category);

// Truy vấn thương hiệu
$sql_brand = "SELECT * FROM brand";
$result_brand = $conn->query($sql_brand);

// Mảng để lưu thương hiệu theo từng danh mục
$brands_by_category = [];

while ($row_brand = $result_brand->fetch_assoc()) {
    $brands_by_category[$row_brand['category_id']][] = $row_brand;
}

?>

<div class="menu">
    <?php while ($row_category = $result_category->fetch_assoc()): ?>
        <li>
            <a href="category.php"><?php echo $row_category['category_name']; ?></a>
            <?php if (isset($brands_by_category[$row_category['category_id']])): ?>
                <ul class="sub-menu">
                    <?php foreach ($brands_by_category[$row_category['category_id']] as $row_brand): ?>
                        <li>
                            <a href=""><?php echo $row_brand['brand_name']; ?></a>
                            
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endwhile; ?>
</div>

<?php
// Đóng kết nối
$conn->close();
?>
    </div>
    <div class="others">
    <!-- Form tìm kiếm -->
    <li>
        <form action="search.php" method="get">
            <input placeholder="Tìm Kiếm" type="text" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" ><button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </li>
    <!-- Liên kết tới trang profile hoặc login -->
    <li>
        <a class="fa fa-user" href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"></a>
    </li>
    <!-- Liên kết giỏ hàng -->
    <li>
        <a class="fa fa-shopping-bag" href="cart.php"></a>
    </li>
</div>
    </header>

    <section class="profile">
        <div class="container">
            <h1>Thông tin cá nhân</h1>
            <div class="profile-content">
                <form method="POST">
                    <div class="form-group">
                        <label>Tên người dùng:</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại:</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ:</label>
                        <textarea name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                    <button type="submit" name="update">Cập nhật thông tin</button>
                    <a href="category.php" class="back-link">Quay lại</a>
                </form>
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
    
    </footer>

    <?php $conn->close(); ?>
</body>
</html>