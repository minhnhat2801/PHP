<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kính Mắt Hà Nội</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styless.css">
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
            <a href=""><?php echo $row_category['category_name']; ?></a>
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
            <input placeholder="Tìm Kiếm" type="text" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
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

// Xử lý tìm kiếm và vệ sinh dữ liệu
$search_query = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

// Truy vấn sản phẩm nếu có tìm kiếm
if ($search_query) {
    $sql_search = "SELECT * FROM product WHERE product_name LIKE '%$search_query%'";
    $result_search = $conn->query($sql_search);
}
?>

<!-- Hiển thị sản phẩm tìm thấy -->
<?php if (isset($result_search) && $result_search->num_rows > 0): ?>
    <div class="product_list_search">
    <?php while ($row_product = $result_search->fetch_assoc()): ?>
        <a href="product.php?id=<?php echo $row_product['product_id']; ?>" class="product_item_search">
            <img src="admin/<?php echo $row_product['product_img']; ?>" alt="<?php echo $row_product['product_name']; ?>">
            <h3><?php echo $row_product['product_name']; ?></h3>
            <p><?php echo $row_product['product_desc']; ?></p>
            <p>Giá sản phẩm: <?php echo number_format($row_product['product_price']); ?> VND</p>
        </a>
    <?php endwhile; ?>
</div>
<?php elseif (isset($result_search)): ?>
    <p>Không tìm thấy sản phẩm nào phù hợp với từ khóa tìm kiếm.</p>
<?php endif; ?>

<?php
// Đóng kết nối
$conn->close();
?>
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