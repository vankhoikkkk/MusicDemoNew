<?php
    $connect = mysqli_connect("localhost", "root", "", "musicdemo") or die ("Not Found!!!");
    mysqli_select_db($connect, "musicdemo") or die ("Can't connect to db!!");
?>