<?php
    $connect = mysqli_connect('localhost', 'root', '', 'publicart');
    if(!$connect){
      die("Connection Failed: " . mysqli_connect_error());
    }
?>