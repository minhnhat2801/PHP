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
<?php
session_start();
include 'admin/database.php';

$db = new Database();
$conn = $db->conn;

// Tính số sản phẩm khác nhau trong giỏ hàng
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql_cart = "SELECT COUNT(DISTINCT product_id) as total FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql_cart);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_count = $result->fetch_assoc()['total'] ?? 0;
    $stmt->close();
}
?>

<header>
    <div class="logo">
        <img src="image/logo.png">
    </div>
    <div class="menu">
        <!-- Menu giữ nguyên -->
    </div>
    <div class="others">
        <li><input placeholder="Tìm Kiếm" type="text" name=""><i class="fas fa-search"></i></li>
        <li><a class="fa fa-user" href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"></a></li>
        <li style="position: relative;">
            <a class="fa fa-shopping-bag" href="cart.php">
                <span id="cart-count"><?php echo $cart_count; ?></span>
            </a>
        </li>
    </div>
</header>

<!-- Phần còn lại của category.php giữ nguyên -->

<?php
// Số sản phẩm trên mỗi trang
$limit = 8;

// Lấy trang hiện tại từ URL, mặc định là 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page = max(1, $page); // Đảm bảo page không nhỏ hơn 1

// Tính offset
$offset = ($page - 1) * $limit;

// Đếm tổng số sản phẩm
$total_query = "SELECT COUNT(*) FROM product";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_array()[0];
$total_page = ceil($total_row / $limit); // Tính tổng số trang

// Lấy danh sách sản phẩm theo trang
$sql = "SELECT * FROM product ORDER BY product_id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<section class="category">
    <div class="container">
        <div class="row">
            <div class="category-left">
                <ul>
                    <li class="category-left-li"><a href="#">Kính Thời Trang</a>
                        <ul>
                            <li><a href="">Hàng Mới Về</a></li>
                            <li><a href="">Hàng Cao Cấp</a></li>
                        </ul>
                    </li>
                    <li class="category-left-li"><a href="">Gọng Kính Cận</a></li>
                    <li class="category-left-li"><a href="">Tròng Kính</a></li>
                    <li class="category-left-li"><a href="">Kính Râm</a></li>
                    <li class="category-left-li"><a href="">Luxury</a></li>
                    <li class="category-left-li"><a href="">Học Sinh Sinh Viên</a></li>
                </ul>
            </div>
            <div class="category-right row">
                <div class="category-right-top-item">
                    <p>Kính Thời Trang</p>
                </div>
                <div class="category-right-content row">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <div class="category-right-content-item">
                            <a href="product.php?id=<?php echo $row['product_id']; ?>">
                                <img src="admin/<?php echo htmlspecialchars($row['product_img']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                            </a>
                            <h1><?php echo htmlspecialchars($row['product_name']); ?></h1>
                            <p><?php echo number_format($row['product_price'], 0, ',', '.'); ?> <span>VND</span></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $conn->close(); ?>

<div class="pagination">
    <?php if ($page > 1) { ?>
        <a href="?page=<?php echo $page - 1; ?>">«</a>
    <?php } ?>

    <?php for ($i = 1; $i <= $total_page; $i++) { ?>
        <a class="<?php echo ($i == $page) ? 'active' : ''; ?>" href="?page=<?php echo $i; ?>">
            <?php echo $i; ?>
        </a>
    <?php } ?>

    <?php if ($page < $total_page) { ?>
        <a href="?page=<?php echo $page + 1; ?>">»</a>
    <?php } ?>
</div>

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