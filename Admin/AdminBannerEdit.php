<?php
include_once '../Util/database.php';
$db = new Database();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM banner WHERE id_banner = $id";
    $result = $db->select($query);
    $row = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { //kiểm tra người dùng có nhấn gửi form kh
    $tenBanner = $_POST['tenBanner']; // lấy giá trị của input có name="tenBanner"
    $trangThai = $_POST['trangThai'];

    $uploadSuccess = true;
    $uploadDir = "../album_banner/";
    // Kiểm tra người dùng có upload ảnh mới không
    if (!empty($_FILES['linkImage']['name'])) {
        $newImage = $_FILES['linkImage']['name'];
        $newImagePath = $uploadDir . basename($newImage);

        // Xóa ảnh cũ trước
        $oldImagePath = "../" . $row['linkImage'];
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }

        // Upload ảnh mới
        if (!move_uploaded_file($_FILES['linkImage']['tmp_name'], $newImagePath)) {
            echo "Lỗi khi tải ảnh mới lên.";
            $uploadSuccess = false;
        }

        $linkImageDB = 'album_banner/' . basename($newImage);
    } else {
        // Nếu không upload mới thì giữ ảnh cũ
        $linkImageDB = $row['linkImage'];
    }


    // Cập nhật DB nếu upload thành công
    if ($uploadSuccess) {
        $query = "UPDATE banner SET 
        tenBanner = '$tenBanner',
        trangThai = '$trangThai',
        linkImage = '$linkImageDB'
        WHERE id_banner = $id";

        $result = $db->update($query);

        if ($result) {
            header("Location: Adminbanner.php");
            exit();
        } else {
            echo "Cập nhật không thành công.";
        }
    }
}



?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa Banner</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="CssAdmin/adminleft.css">
    <link rel="stylesheet" href="CssAdmin/AdminBanner.css">
</head>

<body>

    <?php include 'adminleft.php' ?>
    <div class="container-right">
        <div class="form-wrapper">
            <form method="POST" class="form-box" enctype="multipart/form-data">
                <h2>Sửa Banner</h2>

                <label>Tên Banner:</label>
                <input type="text" name="tenBanner" value="<?= $row['tenBanner'] ?? '' ?>" required>

                <label>Trạng Thái:</label>
                <input type="text" name="trangThai" value="<?= $row['trangThai'] ?? '' ?>" required>

                <label>Ảnh Hiện Tại:</label><br>
                <img src="../<?= $row['linkImage'] ?>" width="150"><br><br>

                <label>Chọn ảnh mới (nếu muốn thay):</label>
                <input type="file" name="linkImage" accept="image/*"><br><br>

                <button type="submit">Cập nhật</button>
            </form>
        </div>
    </div>
    </div>

</body>

</html>