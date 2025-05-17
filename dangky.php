<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dangky.css"/>
    <title>Register</title>
</head>
<body>
    <form action="config.php" method="post" class="form">
        <p id="heading">Đăng Ký</p>
        
        <div class="field">
            <input name="username" autocomplete="off" placeholder="Tên Tài khoản" class="input-field" type="text" required>
        </div>

        <div class="field">
            <input name="email" autocomplete="off" placeholder="Email" class="input-field" type="email" required>
        </div>

        <div class="field">
            <input name="password" placeholder="Mật khẩu" class="input-field" type="password" required>
        </div>

        <div class="field">
            <input name="confirm_password" placeholder="Nhập lại mật khẩu" class="input-field" type="password" required>
        </div>

        <div class="btn">
            <a class="button1" href="dangnhap.php">Đăng nhập</a>
            <button class="button2" type="submit">Đăng ký</button>
        </div>
    </form>

    <script src="dangky.js"></script>
</body>
</html>
