<?php 

include("header.php");
include("slider.php");
include "class/brand_class.php";
?>
<?php 
$brand = new brand();
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $category_id = $_POST['category_id'];
    $brand_name = $_POST['brand_name'];
    $insert_brand = $brand -> insert_brand($category_id, $brand_name);
    
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
                <h1>Thêm Loại Sản Phẩm</h1>
                <form action="" method="POST">
                    <select name="category_id" >
                        <option value="#">Chọn Danh Mục</option>
                        <?php 
                        $show_category = $brand -> show_category();
                        if ($show_category){
                            while($result = $show_category -> fetch_assoc()){

                          
                        ?>
                        <option value="<?php echo $result['category_id'] ?>"><?php echo $result['category_name'] ?></option>
                        <?php
                          }
                        }
                        ?>
                        </select>
                    </select> <br>
                    <input name="brand_name" type="text" placeholder="Nhập Tên Loại Sản Phẩm">
                    <button type="submit">Thêm</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>