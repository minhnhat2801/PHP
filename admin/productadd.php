<?php 
include("header.php");
include("slider.php");
include "class/product_class.php";
?>
<?php 
$product = new product();
if ($_SERVER['REQUEST_METHOD'] === "POST"){
    $insert_product = $product -> insert_product();
}
?>

<div class="admin-content-right">
    <div class="admin-content-right-category-add">
                <h1>Thêm Sản Phẩm</h1>
                <form action="" method="POST" enctype="multipart/form-data">
                    <label for="">Nhập tên sản phẩm <span style="color: red;">*</span></label>
                    <input name="product_name" type="text" required>
                    <label for="">Chọn danh mục <span style="color: red;">*</span></label>
                    <select name="category_id" id="">
                    <?php
                           $show_category = $product -> show_category();
                           if ($show_category){
                            while ($result = $show_category -> fetch_assoc()){

                            
                        ?>
                        <option value="<?php echo $result['category_id'] ?>"><?php echo $result['category_name'] ?></option>
                        <?php
                           }
                        }
                            
                        ?>
                    </select>
                    <label for="">Chọn loại sản phẩm<span style="color: red;">*</span></label>
                    <select name="brand_id" id="">
                    <?php
                           $show_category = $product -> show_brand();
                           if ($show_category){
                            while ($result = $show_category -> fetch_assoc()){

                            
                        ?>
                        <option value="<?php echo $result['brand_id'] ?>"><?php echo $result['brand_name'] ?></option>
                        <?php
                           }
                        }
                            
                        ?>
                    </select>
                    <label for="">Nhập Giá sản phẩm <span style="color: red;">*</span></label>
                    <input name="product_price" type="text" required>
                    <label for="">Nhập giá khuyến mãi <span style="color: red;">*</span></label>
                    <input type="text" required name="product_price_new">
                    <label for="">Mô tả sản phẩm <span style="color: red;">*</span></label>
                    <textarea cols="30" rows="10" placeholder="Mô tả sản phẩm" name="product_desc"></textarea>
                    <label for="">Ảnh sản phẩm <span style="color: red;">*</span></label>
                    <input type="file" required name="product_img">
                    <label for="">Ảnh mô tả sản phẩm <span style="color: red;">*</span></label>
                    <input multiple type="file" required name="product_img_desc[]">
                    <button type="submit">Thêm</button>
                </form>
            </div>
    </div>
    </section>
</body>
</html>