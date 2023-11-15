<?php

require './../config/db.php';

if(isset($_POST['submit'])) {
    global $db_connect;

    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $tempImage = $_FILES['image']['tmp_name'];

    $randomFilename = time().'-'.md5(rand()).'-'.$image;

    $uploadPath = $_SERVER['DOCUMENT_ROOT'].'/PENWEB6/uploads/'.$randomFilename;

    $uploads = move_uploaded_file($tempImage,$uploadPath);

    if($uploads) {
        mysqli_query($db_connect,"INSERT INTO products (name,price,image)
                    VALUES ('$name','$price','/PEMWEB6/uploads/$randomFilename')");
        echo "berhasil upload";
    } else {
        echo "gagal upload";
    }

}