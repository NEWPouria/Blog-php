
<?php
session_start();
$user=$_SESSION["USER"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<h1>HHHHHHHHHHHHHHHHHHHHHHHHHHH</h1>
<?php

$Magsad="uploader/uploads/$user/";
$Masire_File_Magsad=$Magsad . basename(date("y-m-d-h-i-s"));

$ok=true;
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $type=explode("/",$_FILES['batman']['type'])[0];
    $format_file=explode("/",$_FILES['batman']['type'])[1];
    if($type=="image" or $type=="video"){
        $ok=true;
    }else{
        echo "<script>alert('لطفا عکس یا ویدیو ارسال کنید');</script>";
        $ok=false;
    }

    if (!is_dir($Magsad)) {
        if (!mkdir($Magsad, 0777, true)) {
            die("خطا: امکان ایجاد پوشه‌ی مقصد وجود ندارد!");
        }
    }

    if(file_exists($Masire_File_Magsad)){
        echo"فایل تکراری است";
        $ok=false;
    }
    if($ok==false){
        echo"فایل شما آپلود نشد";

    }else{
        if(move_uploaded_file($_FILES['batman']['tmp_name'],$Masire_File_Magsad.".".$format_file)){
            echo"فایل با موفقیت آپلود و ذخیره شد". "1<br>";
            echo $_FILES["batman"]["tmp_name"] . "2<br>";
            echo $Masire_File_Magsad . "3<br>";
            echo $_FILES['batman']['error']."4<br>";

            print_r(error_get_last())."5<br>";
        }else{
            echo"در فرآیند آپلود فایل شما مشکلی وجود داشت";
            echo $_FILES['batman']['error']."6<br>";

            print_r(error_get_last())."7<br>";
        }

    }



}
?>
</body>
</html>