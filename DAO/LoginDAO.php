<?php 
include_once 'Util/database.php';
?>


<?php 
class LoginDao {
    private $db;

    public function __construct()
    {
        $this -> db = new Database();
    }
   
    function checkLogin($username, $password) {
        $query = "SELECT * FROM `nguoidung` WHERE tennguoidung = '$username' AND matkhau = '$password'";
        $result = $this -> db -> select($query);
        return $result;
    }
}



?>