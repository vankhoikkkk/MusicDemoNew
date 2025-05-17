<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/css.css">
    <title>nhactre</title>
</head>
<body>
        <?php
        include("connect.php");
        mysqli_set_charset($connect, "utf8");
        $query = mysqli_query($connect, "SELECT * FROM baihat, casi WHERE theloai = 'Nhạc Âu' and baihat.id_casi = casi.id_casi ORDER by luotnghe DESC");
        $count = 0;
        while($row = mysqli_fetch_assoc($query)){
            $count++;
            ?>
            <li>
                <div class="song-card" data-audio="<?php echo $row['linknhac']; ?>" data-id="<?php echo $row['id_baihat']; ?>">
                        <div class="play-overlay">
                        <i class='bx bx-play-circle'></i>
                        </div>
                        <img src="<?php echo $row['album']; ?>" alt="Song">
                        <div class="song-info">
                            <p class="baihat"><?php echo $row['tenbaihat']; ?></p>
                            <p class="casi">(<?php echo $row['tenCaSi']; ?>)</p>
                        </div>
                </div>
            </li>
            <?php
            if ($count == 4){
                break;
            }
        }
        ?>
</body>
</html>