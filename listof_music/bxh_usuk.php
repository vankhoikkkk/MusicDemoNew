<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
            include("./listof_music/connect.php");
            mysqli_set_charset($connect,'utf8');
            $query = mysqli_query($connect, "SELECT * FROM `baihat`,`casi` WHERE baihat.id_casi = casi.id_casi and baihat.id_baihat BETWEEN 91 and 120 ORDER by baihat.luotnghe DESC;");
            $count = 0;
            while ($row = mysqli_fetch_assoc($query)){
                $count++;
                ?>
                <li>
                    <div class="song-number"><?php echo $count; ?></div>
                        <div class="song-info">
                            <div>
                                <strong><a href="<?php echo $row['linknhac'] ?>"><?php echo $row['tenbaihat']; ?></a></strong>
                            </div>
                            <div>
                                <?php echo $row['tenCaSi']; ?>
                            </div>
                    </div>
                </li>
                <?php
                    if ($count == 10){
                        break;
                    }
            }
    ?>
</body>
</html>