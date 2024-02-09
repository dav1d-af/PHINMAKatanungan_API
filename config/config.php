<?php

    $host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'phinmakatanungan';

    $conn = mysqli_connect($host,$db_user,$db_password,$db_name);

    if(!$conn){
        die("Connection Failed: " . mysqli_connect_error());
    }
?>