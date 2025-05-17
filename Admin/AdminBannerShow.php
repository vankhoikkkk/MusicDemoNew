<?php
include_once '../Util/database.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
     <link rel="stylesheet" href="CssAdmin/adminleft.css">
     <link rel="stylesheet" href="CssAdmin/AdminBanner.css">
     <style>
          h2 {
               text-align: center;
               color: darkslateblue;
          }

          .add {
               display: inline-block;
               padding: 10px 20px;
               background-color: rgb(72, 115, 181);
               color: white;
               font-weight: bold;
               text-decoration: none;
               border-radius: 8px;
               text-align: center;
          }

          .them {
               display: flex;
               justify-content: center;
               /* căn giữa ngang */
               align-items: center;
               /* căn giữa dọc nếu có chiều cao */
               padding: 5px;
          }
     </style>
</head>

<body>

     <?php include 'adminleft.php' ?>

     <div class="container-right">
          <h2>Danh sách banner</h2>
          <div class="them"><a href="add.php" class="add">
                    Thêm banner</a></div>
          <div class="container">
               <?php
               $db = new Database();
               $query = "SELECT * FROM banner";
               $result = $db->select($query);
               if ($result->num_rows > 0) {
                    echo "<table border='1' style='width:100%; border-collapse:collapse;'>";
                    echo "<tr>
      <th>id_banner</th>
      <th>Tên banner</th>
      <th>Trạng thái</th>
      <th>linkImage</th>
      <th>Tùy chỉnh</th></tr>";

                    while ($row = $result->fetch_assoc()) {
                         echo "<tr>
        <td>" . $row["id_banner"] . "</td>
        <td>" . $row["tenBanner"] . "</td>
        <td>" . $row["trangThai"] . "</td>
        <td>" . $row["linkImage"] . "</td>
        <td>
         <a href='AdminBannerEdit.php?id=" . $row["id_banner"] . "'>Sửa</a> | 
            <a href='AdminBannerDelete.php?id=" . $row["id_banner"] . "' onclick=\"return confirm('Bạn có chắc chắn muốn xóa ca sĩ này không?');\">Xóa</a>
            
        </td>
        </tr>";
                    }
                    echo "</table>";
               } else {
                    echo "0 results";
               }

               ?>
          </div>
     </div>
     </div>

</body>

</html>