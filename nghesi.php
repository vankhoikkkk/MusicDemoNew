<?php
include_once 'Util/database1.php';
include 'header.php';
try {
    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception("Không thể kết nối đến database");
    }

    $limit = 20;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $limit;

    // Truy vấn tổng số nghệ sĩ
    $countArtist = "SELECT COUNT(DISTINCT id_casi) AS total FROM albumcasi";
    $resultcount = $db->select($countArtist);
    $row = $resultcount->fetch_assoc();
    $totalArtists = $row['total'];
    $totalPages = ceil($totalArtists / $limit);

    // Truy vấn nghệ sĩ
    $query = "SELECT albumcasi.id_casi, tenCaSi, MIN(linkAnh) AS linkAnh 
              FROM albumcasi 
              JOIN casi ON casi.id_casi = albumcasi.id_casi 
              GROUP BY albumcasi.id_casi, tenCaSi  
              LIMIT ?, ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Lỗi chuẩn bị truy vấn: " . $conn->error);
    }

    $stmt->bind_param("ii", $offset, $limit);
    if (!$stmt->execute()) {
        throw new Exception("Lỗi thực thi truy vấn: " . $stmt->error);
    }
    $result = $stmt->get_result();
} catch (Exception $e) {
    die("Lỗi: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <title>Danh sách nghệ sĩ</title>
    <style>
        .artist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 30px;
            padding: 20px;
            padding-top: 100px;
        }

        .artist-item {
            text-align: center;
        }

        .artist-item img {
            width: 170px;
            height: 170px;
            object-fit: cover;
            border-radius: 5px;
            transition: transform 0.3s;
        }

        .artist-item img:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <?php include 'header.php' ?>
    <div>
        <h1 style="color: blue">Danh sách nghệ sĩ</h1>
    </div>
    <div class="artist-grid">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
                <div class="artist-item">
                    <a href="chitiet.php?id=<?php echo $row['id_casi']; ?>">
                        <img src="<?php echo htmlspecialchars($row['linkAnh']); ?>" alt="<?php echo htmlspecialchars($row['tenCaSi']); ?>">
                    </a>
                    <div class="ten">
                        <p class="casi">(<?php echo htmlspecialchars($row['tenCaSi']); ?>)</p>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>Không có nghệ sĩ nào.</p>";
        }
        ?>
    </div>

    <!-- Phân trang -->
    <div>
        <?php
        if ($totalPages > 1) {
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == $page) {
                    echo "<strong>$i</strong> ";
                } else {
                    echo '<a href="?page=' . $i . '">' . $i . '</a> ';
                }
            }
        }
        ?>
    </div>
    <?php include 'footer.php' ?>
</body>

</html>