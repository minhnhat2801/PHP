<?php 
include "class/category_class.php";

$category = new category();

if (!isset($_GET['category_id']) || empty($_GET['category_id'])) {
    echo "<script>alert('ID danh mục không hợp lệ!'); window.location = 'categorylist.php';</script>";
    exit();
}

$category_id = $_GET['category_id'];

// Thực hiện xóa danh mục
$delete_category = $category->delete_category($category_id);

if ($delete_category) {
    echo "<script>alert('Xóa danh mục thành công!'); window.location = 'categorylist.php';</script>";
} else {
    echo "<script>alert('Xóa danh mục thất bại!'); window.location = 'categorylist.php';</script>";
}
?>
