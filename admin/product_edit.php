<?php 
include("header.php");
include("slider.php");
include "class/product_class.php";

$product = new product();
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

if (!$product_id) {
    die("Lỗi: Không tìm thấy sản phẩm!");
}

$get_product = $product->get_product($product_id);
if ($get_product) {
    $resultA = $get_product->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_price_new = $_POST['product_price_new'];
    $product_desc = $_POST['product_desc'];

    // Kiểm tra xem có file ảnh mới không
    if (!empty($_FILES['product_img']['name'])) {
        $product_img = "upload/" . basename($_FILES['product_img']['name']); // Thêm 'upload/' vào đường dẫn
        $target_path = $product_img;
    
        if (move_uploaded_file($_FILES['product_img']['tmp_name'], $target_path)) {
            // Nếu tải ảnh lên thành công, giữ đường dẫn mới
        }
    } else {
        $product_img = $_POST['old_product_img'];
    }

    $update_product = $product->update_product($product_id, $category_id, $brand_id, $product_name, $product_price, $product_price_new, $product_desc, $product_img, $product_img_desc);

    if ($update_product) {
        echo "<script>alert('Cập nhật sản phẩm thành công!'); window.location.href='product_list.php';</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi cập nhật!');</script>";
    }
}
?>
<style>
    select {
        height: 30px;
        width: 200px;
    }
</style>

<div class="admin-content-right">
    <div class="admin-content-right-category-add">
        <h1>Sửa Sản Phẩm</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="category_id">Chọn Danh Mục</label>
            <select name="category_id" required>
                <option value="#">Chọn Danh Mục</option>
                <?php 
                $show_category = $product->show_category();
                if ($show_category) {
                    while ($result = $show_category->fetch_assoc()) {
                ?>
                <option <?php if ($resultA['category_id'] == $result['category_id']) { echo "SELECTED"; } ?> 
                    value="<?php echo $result['category_id'] ?>">
                    <?php echo $result['category_name'] ?>
                </option>
                <?php
                    }
                }
                ?>
            </select> <br>

            <label for="brand_id">Chọn Loại Sản Phẩm</label>
            <select name="brand_id" required>
                <option value="#">Chọn Loại Sản Phẩm</option>
                <?php 
                $show_brand = $product->show_brand();
                if ($show_brand) {
                    while ($result = $show_brand->fetch_assoc()) {
                ?>
                <option <?php if ($resultA['brand_id'] == $result['brand_id']) { echo "SELECTED"; } ?> 
                    value="<?php echo $result['brand_id'] ?>">
                    <?php echo $result['brand_name'] ?>
                </option>
                <?php
                    }
                }
                ?>
            </select> <br>

            <label for="product_name">Tên Sản Phẩm</label>
            <input name="product_name" type="text" placeholder="Nhập Tên Sản Phẩm" value="<?php echo $resultA['product_name'] ?>" required> <br>

            <label for="product_price">Giá Gốc</label>
            <input name="product_price" type="number" placeholder="Nhập Giá Gốc" value="<?php echo $resultA['product_price'] ?>" required> <br>

            <label for="product_price_new">Giá Khuyến Mãi</label>
            <input name="product_price_new" type="number" placeholder="Nhập Giá Khuyến Mãi" value="<?php echo $resultA['product_price_new'] ?>"> <br>

            <label for="product_desc">Mô Tả</label>
            <textarea name="product_desc" rows="5" required><?php echo $resultA['product_desc'] ?></textarea> <br>

            <label for="product_img">Ảnh Sản Phẩm</label>
            <input type="file" name="product_img"> <br>
            <img src="<?php echo $resultA['product_img'] ?>" width="100">
            <input type="hidden" name="old_product_img" value="<?php echo $resultA['product_img'] ?>"> <br>
            <label for="">Ảnh mô tả sản phẩm <span style="color: red;">*</span></label>
            <input multiple type="file" required name="product_img_desc[]">
            <button type="submit">Sửa</button>
        </form>
    </div>
</div>
</section>
</body>
</html>
