<?php 

include("header.php");
include("slider.php");
include "class/product_class.php";
?>
<?php 
$product = new product();
$search_query = isset($_GET['q']) ? $_GET['q'] : '';
$show_product = $product->show_product($search_query);
?>

<div class="admin-content-right">
    <div class="admin-content-right-category-list">
        <h1>Danh sách sản phẩm</h1>
        
        <!-- Form tìm kiếm -->
        <form method="GET" action="">
            <input type="text" name="q" placeholder="Nhập tên sản phẩm..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Tìm kiếm</button>
        </form>

        <table>
            <tr>
                <th>STT</th>
                <th>ID</th>
                <th>Tên Sản Phẩm</th>
                <th>Danh Mục</th>
                <th>Loại Sản Phẩm</th>
                <th>Giá</th>
                <th>Giá Khuyến Mại</th>
                <th>Mô Tả</th>
                <th>Ảnh sản phẩm</th>
            </tr>
            <?php 
            if ($show_product){ $i = 0;
                while($result = $show_product->fetch_assoc()){$i++;
            ?>
            <tr>
                <td><?php echo $i ?></td>
                <td><?php echo $result['product_id'] ?></td>
                <td><?php echo $result['product_name'] ?></td>
                <td><?php echo $result['category_name'] ?></td>
                <td><?php echo $result['brand_name'] ?></td>
                <td><?php echo $result['product_price'] ?></td>
                <td><?php echo $result['product_price_new'] ?></td>
                <td><?php echo $result['product_desc'] ?></td>
                <td><img src="<?php echo $result['product_img']; ?>" width="100"></td>
                <td><a href="product_edit.php?product_id=<?php echo $result['product_id'] ?>" style="color: red;">Sửa</a> | <a href="product_delete.php?product_id=<?php echo $result['product_id'] ?>" style="color: red;">Xóa</a></td>
            </tr>
            <?php 
                }
            }
            ?>
        </table>
    </div>
</div>
