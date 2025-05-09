<?php 
include "database.php";
?>
<?php 
class brand {
    private $db;
    public function __construct(){
       $this -> db = new Database();
    }
    public function insert_brand($category_id, $brand_name) {
        $query = "INSERT INTO brand (category_id, brand_name) VALUES ('$category_id', '$brand_name')";
        $result = $this -> db -> insert($query);
        header('location: branlist.php');
        return $result;
    }

    public function show_category(){
        $query = "SELECT * FROM category ORDER BY category_id DESC";
        $result = $this -> db -> select($query);
        return $result;
    }

    public function show_brand(){
        // $query = "SELECT * FROM brand ORDER BY brand_id DESC";
        $query = "SELECT  brand.* , category.category_name 
        FROM brand INNER JOIN category ON brand.category_id = category.category_id
        ORDER BY brand.brand_id DESC";
        $result = $this -> db -> select($query);
        return $result;
    }

    public function get_brand($brand_id){
        $query = "SELECT * FROM brand WHERE brand_id = '$brand_id'";
        $result = $this -> db -> select($query);
        return $result;
    }

    public function update_brand($category_id,$brand_id, $brand_name){
        $query = "UPDATE brand SET category_id = '$category_id', brand_name = '$brand_name' WHERE brand_id = '$brand_id'";
        $result = $this -> db -> update($query);
        header('location: branlist.php');
        return $result;
    }

    public function delete_brand($brand_id){
        $query = "DELETE FROM brand WHERE brand_id = '$brand_id' ";
        $result = $this -> db -> delete($query);
        header('location: branlist.php');
        return $result;
    }
}
?>