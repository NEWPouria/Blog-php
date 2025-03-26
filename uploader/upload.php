<?php
// مسیر ذخیره فایل
// "uploads/تصویر زمینه.png";
$target_dir = "uploads/";
// مسیر کامل فایل
// $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

// مسیر کامل فایل با اسم اختصاصی
$target_file = $target_dir . basename(date("y-m-d-h-i-s"));

// اگر یک باشد خطایی نیست
$uploadOk = true;
// پیدا کردن فرمت فایل (از طریق نام) 
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// بررسی متغیر پست
if (isset($_POST["submit"])) {
    // برای بدست اوردن نوع فایل
    // "image/png"
    $type = explode("/", $_FILES["fileToUpload"]["type"]);
    // بررسی اینکه فایل تصویر است یا خیر
    if ($type[0] == "image") {
        $uploadOk = true;
    } else {
        echo "File is not an image.";
        $uploadOk = false;
    }
    // بررسی اینکه فایل تکراری نباشد
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = false;
    }
    // بررسی اینکه خطایی در آپلود نباشد
    if ($uploadOk == false) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // انتقال فایل از مسیر موقت به مسیر نهایی
        // if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

        // انتقال فایل از مسیر موقت به مسیر نهایی با اسم اختصاصی
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file . "." . $type[1])) {

            echo $_FILES["fileToUpload"]["tmp_name"] . "<br>";
            echo $target_file . "<br>";
            echo $fileType . "<br>";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>