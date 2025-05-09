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
require_once __DIR__ . "/admin/database.php";

$db = new Database();
$conn = $db->conn;

if (!$conn) {
    die("Lỗi kết nối CSDL: " . mysqli_connect_error());
}

// Xử lý thêm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = "product.php?id=" . $_POST['product_id'];
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $sql_check = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql_update = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        $sql_insert = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
    }
    $stmt->close();
    header("Location: product.php?id=" . $product_id);
    exit();
}

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



<!-- Phần còn lại của product.php giữ nguyên -->

<header>
    <div class="logo">
        <img src="image/logo.png">
    </div>
    <div class="menu">
        <li><a href="#">Kính Thời Trang</a>
            <ul class="sub-menu">
                <li><a href="">Hàng mới về</a></li>
                <li><a href="">Hàng cao cấp</a>
                    <ul>
                        <li><a href="">Gucci</a></li>
                        <li><a href="">Luis Vuiton</a></li>
                        <li><a href="">Lexus</a></li>
                    </ul>
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
        <li><input placeholder="Tìm Kiếm" type="text" name=""><i class="fas fa-search"></i></li>
        <li><a class="fa fa-user" href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"></a></li>
        <li style="position: relative;">
            <a class="fa fa-shopping-bag" href="cart.php">
                <span id="cart-count"><?php echo $cart_count; ?></span>
            </a>
        </li>
    </div>
</header>

<?php
// Lấy ID sản phẩm từ URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];
} else {
    die("<p style='color:red;'>Sản phẩm không tồn tại!</p>");
}

// Truy vấn sản phẩm
$sql = "SELECT * FROM product WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    die("<p style='color:red;'>Sản phẩm không tồn tại!</p>");
}
$stmt->close();
?>

<section class="product">
    <div class="container">
        <div class="product-content row">
            <div class="product-content-left row">
                <div class="product-content-left-big-img">
                    <img src="admin/<?php echo htmlspecialchars($product['product_img']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                </div>
                <div class="product-content-left-small-img">
                    <?php 
                    require_once __DIR__ . "/admin/class/product_class.php";
                    $product_obj = new product();
                    $get_product_images = $product_obj->get_product_images($product_id);

                    if ($get_product_images && $get_product_images->num_rows > 0) {
                        while ($row = $get_product_images->fetch_assoc()) {
                            echo '<img src="admin/' . htmlspecialchars($row['product_img_desc']) . '" alt="Ảnh sản phẩm">';
                        }
                    } else {
                        echo '<p>Không có ảnh mô tả.</p>';
                    }
                    ?>
                </div>
            </div>
            <div class="product-content-right">
                <div class="product-content-right-product-name">
                    <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
                    <p>Mã sản phẩm: <?php echo htmlspecialchars($product['product_id']); ?></p>
                </div>
                <div class="product-content-right-product-price">
                    <p class="price"><?php echo number_format($product['product_price'], 0, ',', '.'); ?> VND</p>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <div class="quantity">
                        <p style="font-weight: bold;">Số Lượng</p>
                        <input type="number" name="quantity" min="1" value="1">
                    </div>
                    <div class="product-content-right-product-button">
                        <button type="submit" name="add_to_cart">
                            <i class="fas fa-shopping-cart"></i> <p>Thêm Vào Giỏ Hàng</p>
                        </button>
                    </div>
                </form>
                <div class="product-content-right-bottom">
                    <div class="product-content-right-bottom-top">∨</div>
                    <div class="product-content-right-bottom-content-big">
                        <div class="product-content-right-bottom-content-title row">
                            <div class="product-content-right-bottom-content-title-item chitiet">Chi Tiết</div>
                            <div class="product-content-right-bottom-content-title-item vanchuyen">Vận Chuyển Và Đổi Trả</div>
                        </div>
                        <div class="product-content-right-bottom-content">
                            <div class="product-content-right-bottom-content-chitiet">
                                <strong>Tên Sản Phẩm:</strong> <?php echo htmlspecialchars($product['product_name']); ?> <br>
                                <strong>Mã sản phẩm:</strong> <?php echo $product['product_id']; ?> <br>
                                <strong>Mô tả:</strong> <?php echo nl2br(htmlspecialchars($product['product_desc'])); ?>
                            </div>
                            <div class="product-content-right-bottom-content-vanchuyen">
                                Thời gian giao hàng dao động từ 2-4 ngày đối với đơn gọng kính, 3-5 ngày đối với đơn cắt cận. <br>
                                1. Bảo hành 1 đổi 1 trong 180 ngày sau khi mua hàng nếu lớp váng dầu của tròng kính gặp vấn đề về kỹ thuật như xô váng, mất váng mà không phải do nhiệt hay tác động vật lý như trầy xước, nứt, vỡ. <br>
                                2. Kinh Mat Ha Noi bảo hành cho cả lỗi người dùng nếu không may làm gẫy hoặc mất kính. Trợ giá 50% giá niêm yết khi khách hàng sử dụng lại sản phẩm cũ. Trong trường hợp sản phẩm cũ hết hàng có thể thay thế sang sản phẩm có giá trị bằng hoặc thấp hơn. Áp dụng 1 lần duy nhất trên tổng hóa đơn trong 60 ngày kể từ khi mua hàng <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Truy vấn sản phẩm liên quan
$query = "SELECT category_id FROM product WHERE product_id = $product_id";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
    $category_id = $product['category_id'];
    $query_related = "SELECT * FROM product WHERE category_id = $category_id AND product_id != $product_id LIMIT 5";
    $result_related = mysqli_query($conn, $query_related);
}
?>

<section class="product-relate">
    <div class="product-relate-title">
        <p>Sản Phẩm Liên Quan</p>
    </div>
    <div class="product-relate-content row">
        <?php
        if (isset($result_related) && mysqli_num_rows($result_related) > 0) {
            while ($related = mysqli_fetch_assoc($result_related)) {
                ?>
                <div class="product-relate-item">
                    <a href="product.php?id=<?php echo $related['product_id']; ?>">
                        <img src="/PHP/admin/<?php echo htmlspecialchars($related['product_img']); ?>" alt="<?php echo htmlspecialchars($related['product_name']); ?>">
                    </a>
                    <h1><?php echo htmlspecialchars($related['product_name']); ?></h1>
                    <p><?php echo number_format($related['product_price'], 0, ',', '.'); ?> <span>VND</span></p>
                </div>
                <?php
            }
        } else {
            echo "<p>Không có sản phẩm liên quan.</p>";
        }
        ?>
    </div>
</section>

<script src="main.js"></script>

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