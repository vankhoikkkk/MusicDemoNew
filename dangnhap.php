<?php 
include_once 'DAO/LoginDao.php';   
  session_start();
if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $login = new LoginDao();
    $result = $login -> checkLogin($username, $password);
    if($result -> num_rows > 0) {
        $row = $result -> fetch_assoc();
        $_SESSION['username'] = $row['tennguoidung'];
        $_SESSION['id_nguoidung'] = $row['id_nguoidung'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['admin'] = $row['laAdmin'];

        if($row['laAdmin'] == 1) {

            header('Location: admin.php');

        } else if($row['laAdmin'] == 0) {

            header('Location: TrangChu.php');
        } 

    } else {
        echo "<script>alert('Tài khoản hoặc mật khẩu không đúng')</script>";
    }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dangnhap.css"/>
    <title>Login</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post" class="form">
        <p id="heading">Đăng Nhập</p>
        <div class="field">
            <input name="username" autocomplete="off" placeholder="Tên tài khoản" class="input-field" type="text">
        </div>

        <div class="field">
            <input name="password" placeholder="Mật khẩu" class="input-field" type="password">
        </div>

        <div class="btn">
            <button type="submit" class="button1">Đăng nhập</button>
            <button class="button2"><a href="dangky.php">Đăng ký</a></button>
        </div>
        <button class="button3"><a href="#">Quên mật khẩu</a></button>
    </form>
    <?php
    
    ?>
    <script src="login.js"></script>
</body>
</html>