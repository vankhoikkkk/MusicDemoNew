<?php
include_once '../Util/database.php';
$db=new Database();
//kiểm tra có tham số id được truyền không 
if (isset($_GET['id']) ) {
    $id = $_GET['id'];
    $query = "SELECT linkImage FROM banner WHERE id_banner = $id";
    $result = $db->select($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = "../" . $row['linkImage']; // Đường dẫn tuyệt đối tới file ảnh

        // Xóa file ảnh nếu tồn tại
        if (file_exists($imagePath)) {
            unlink($imagePath);  // Hàm PHP dùng để xóa file
        }

        // Xóa bản ghi khỏi database
        $deleteQuery = "DELETE FROM banner WHERE id_banner = $id";
        $deleteResult = $db->delete($deleteQuery);

        if ($deleteResult) {
            header("Location: AdminBannerShow.php"); 
            exit();
        } else {
            echo "Xóa banner thất bại.";
        }
    } else {
        echo "Không tìm thấy banner.";
    }
} 
?>
