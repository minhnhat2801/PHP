<?php 
include("header.php");
include("slider.php");
include "class/category_class.php";
?>
<?php 
$category = new category();
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $category_name = $_POST['category_name'];
    $insert_category = $category -> insert_category($category_name);
    
}
?>

<div class="admin-content-right">
            <div class="admin-content-right-category-add">
                <h1>Thêm Danh Mục</h1>
                <form action="" method="POST">
                    <input name="category_name" type="text" placeholder="Nhập Tên Danh Mục">
                    <button type="submit">Thêm</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>