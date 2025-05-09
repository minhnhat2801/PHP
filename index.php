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

    <section id="slider">
        <div class="aspect-ratio-169">
            <img src="image/slide1.jpg">
            <img src="image/silde2.png">
            <img src="image/slide3.jpg">
            <img src="image/slide4.jpg">
            <img src="image/slide5.png">
        </div>

        <div class="dot-container">
            <div class="dot active"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </section>
    <script>
      // Chuyển slider
const imgPosition = document.querySelectorAll(".aspect-ratio-169 img");
const imgContainer = document.querySelector(".aspect-ratio-169");
const dotItems = document.querySelectorAll(".dot"); // Chọn tất cả dot
let index = 0;
let imgNumber = imgPosition.length;

// Đặt vị trí ảnh
imgPosition.forEach((image, idx) => {
    image.style.left = idx * 100 + "%";
});

// Gán sự kiện click cho từng dot
dotItems.forEach((dot, idx) => {
    dot.addEventListener("click", function () {
        slider(idx);
    });
});

// Hàm slider về ban đầu khi chạy hết ảnh
function imgSlider() {
    index++;
    if (index >= imgNumber) {
        index = 0;
    }
    slider(index);
}

// Hàm thay đổi slider
function slider(index) {
    imgContainer.style.transform = `translateX(-${index * 100}%)`;
    imgContainer.style.transition = "0.5s ease-in-out";
    const dotactive = document.querySelector('.active')
    dotactive.classList.remove("active");
    dotItems[index].classList.add("active");
    
}

// Hàm set time cho slider
 setInterval(imgSlider, 5000);
    </script>

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