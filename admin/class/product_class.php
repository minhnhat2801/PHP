<?php 
include_once(__DIR__ . "/../database.php");
?>
<?php 
class product {
    private $db;
    public function __construct(){
       $this -> db = new Database();
    }
    public function insert_product() {
        $product_name = $_POST['product_name'];
        $category_id = $_POST['category_id'];
        $brand_id = $_POST['brand_id'];
        $product_price = $_POST['product_price'];
        $product_price_new = $_POST['product_price_new'];
        $product_desc = $_POST['product_desc'];
    
        // Xử lý ảnh chính
        if (isset($_FILES['product_img']) && $_FILES['product_img']['error'] == 0) {
            $product_img = $_FILES['product_img']['name'];
            move_uploaded_file($_FILES['product_img']['tmp_name'], 'upload/' . $product_img);
            $product_img_path = 'upload/' . $product_img;
        } else {
            $product_img_path = ''; // Nếu không có ảnh thì để trống
        }
    
        // Thêm sản phẩm vào bảng product
        $query = "INSERT INTO product 
            (product_name, category_id, brand_id, product_price, product_price_new, product_desc, product_img) 
            VALUES ('$product_name', '$category_id', '$brand_id', '$product_price', '$product_price_new', '$product_desc', '$product_img_path')";
        
        $result = $this->db->insert($query);
    
        if ($result) {
            // Lấy ID sản phẩm vừa thêm
            $query = "SELECT * FROM product ORDER BY product_id DESC LIMIT 1";
            $result = $this->db->select($query)->fetch_assoc();
            $product_id = $result['product_id'];
    
            // Kiểm tra xem có ảnh mô tả không
            if (!empty($_FILES['product_img_desc']['name'][0])) {
                $filenames = $_FILES['product_img_desc']['name'];
                $filetmps = $_FILES['product_img_desc']['tmp_name'];
    
                foreach ($filenames as $key => $filename) {
                    $file_path = 'upload/' . $filename;
                    move_uploaded_file($filetmps[$key], $file_path);
                    
                    // Thêm ảnh mô tả vào bảng product_img_desc
                    $query = "INSERT INTO product_img_desc (product_id, product_img_desc) VALUES ('$product_id', '$file_path')";
                    $this->db->insert($query);
                }
            }
            header('Location: product_list.php');
        }
    }
    


    public function show_product($search_query = '') {
        // Kiểm tra kết nối CSDL
        if (!isset($this->db) || !isset($this->db->conn)) {
            return false;
        }
    
        // Bắt đầu câu truy vấn
        $query = "SELECT product.*, category.category_name, brand.brand_name 
                  FROM product 
                  INNER JOIN category ON product.category_id = category.category_id 
                  INNER JOIN brand ON product.brand_id = brand.brand_id";
    
        // Nếu có từ khóa tìm kiếm, thêm điều kiện vào truy vấn
        if (!empty($search_query)) {
            $search_query = $this->db->conn->real_escape_string($search_query);
            $query .= " WHERE product.product_name LIKE '%$search_query%'";
        }
    
        $query .= " ORDER BY product.product_id DESC";
    
        // Thực hiện truy vấn
        return $this->db->select($query);
    }
    
    

    public function show_brand(){
        // $query = "SELECT * FROM brand ORDER BY brand_id DESC";
        $query = "SELECT  brand.* , category.category_name 
        FROM brand INNER JOIN category ON brand.category_id = category.category_id
        ORDER BY brand.brand_id DESC";
        $result = $this -> db -> select($query);
        return $result;
    }

    public function get_product($product_id){
        $query = "SELECT * FROM product WHERE product_id = '$product_id'";
        $result = $this -> db -> select($query);
        return $result;
    }
    public function update_product($product_id, $category_id, $brand_id, $product_name, $product_price, $product_price_new, $product_desc, $product_img, $product_img_desc) {
        // Kiểm tra nếu có ảnh mới
        if (!empty($product_img)) {
            move_uploaded_file($_FILES['product_img']['tmp_name'], 'upload/' . $product_img);
            $query = "UPDATE product SET 
                category_id = '$category_id', 
                brand_id = '$brand_id', 
                product_name = '$product_name', 
                product_price = '$product_price', 
                product_price_new = '$product_price_new', 
                product_img = '$product_img', 
                product_desc = '$product_desc'
            WHERE product_id = '$product_id'";
        } else {
            // Nếu không có ảnh mới, không cập nhật ảnh
            $query = "UPDATE product SET 
                category_id = '$category_id', 
                brand_id = '$brand_id', 
                product_name = '$product_name', 
                product_price = '$product_price', 
                product_price_new = '$product_price_new', 
                product_desc = '$product_desc'
            WHERE product_id = '$product_id'";
        }
    
        $result = $this->db->update($query);
    
        // Cập nhật ảnh mô tả
        if (!empty($_FILES['product_img_desc']['name'][0])) {
            // Xóa ảnh mô tả cũ
            $query_delete = "DELETE FROM product_img_desc WHERE product_id = '$product_id'";
            $this->db->delete($query_delete);
    
            // Thêm ảnh mới
            $filenames = $_FILES['product_img_desc']['name'];
            $filetmp = $_FILES['product_img_desc']['tmp_name'];
    
            foreach ($filenames as $key => $value) {
                $target_path = 'upload/' . $value;
                move_uploaded_file($filetmp[$key], $target_path);
                $query_insert = "INSERT INTO product_img_desc (product_id, product_img_desc) VALUES ('$product_id', '$target_path')";
                $this->db->insert($query_insert);
            }
        }
    
        return $result;
    }
    
    
    public function get_product_images($product_id) {
        $query = "SELECT product_img_desc FROM product_img_desc WHERE product_id = '$product_id'";
        return $this->db->select($query);
    }

    public function delete_product($product_id){
        $query = "DELETE FROM product WHERE product_id = '$product_id' ";
        $result = $this -> db -> delete($query);
        header('location: product_list.php');
        return $result;
    }
    public function show_category(){
        $query = "SELECT * FROM category ORDER BY category_id DESC";
        $result = $this -> db -> select($query);
        return $result;
    }

   
}
?>
