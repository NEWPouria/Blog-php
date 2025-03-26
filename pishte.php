<?php
session_start();
$userID = $_SESSION["UserID"]; // فرض کنید UserID کاربر در session ذخیره شده است

// اتصال به دیتابیس
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// آپلود فایل
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['file'];
    $fileType = explode("/", $file['type'])[0]; // نوع فایل (image, video, etc.)
    $fileFormat = explode("/", $file['type'])[1]; // فرمت فایل (jpg, png, etc.)
    $fileName = uniqid() . "." . $fileFormat; // نام منحصر به فرد برای فایل
    $filePath = "uploads/" . $fileName; // مسیر ذخیره‌سازی فایل

    // ذخیره فایل در سرور
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // درج اطلاعات فایل در جدول Media
        $mediaCategory = $_POST['mediaCategory']; // نوع مدیا (profile, banner, article, comment)
        $mediaName = $_POST['mediaName']; // نام مدیا
        $mediaAlt = $_POST['mediaAlt']; // متن جایگزین مدیا

        $sql = "INSERT INTO Media (MediaUserID, MediaPath, MediaCategory, MediaName, MediaAlt)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $userID, $filePath, $mediaCategory, $mediaName, $mediaAlt);

        if ($stmt->execute()) {
            $mediaID = $stmt->insert_id; // دریافت MediaID ایجاد شده

            // ذخیره MediaID در جدول مرتبط
            if ($mediaCategory === 'profile') {
                $sql = "UPDATE UserProfiles SET ProfilePicID = ? WHERE UserID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $mediaID, $userID);
                $stmt->execute();
            } elseif ($mediaCategory === 'banner') {
                $sql = "UPDATE UserProfiles SET BannerPicID = ? WHERE UserID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $mediaID, $userID);
                $stmt->execute();
            } elseif ($mediaCategory === 'article') {
                $articleID = $_POST['articleID']; // ID مقاله
                $sql = "INSERT INTO ArticleMedia (ArticleID, MediaID) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $articleID, $mediaID);
                $stmt->execute();
            } elseif ($mediaCategory === 'comment') {
                $commentID = $_POST['commentID']; // ID کامنت
                $sql = "INSERT INTO CommentMedia (CommentID, MediaID) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $commentID, $mediaID);
                $stmt->execute();
            }

            echo "فایل با موفقیت آپلود و ذخیره شد!";
        } else {
            echo "خطا در ذخیره اطلاعات فایل در دیتابیس!";
        }
    } else {
        echo "خطا در آپلود فایل!";
    }
}

$conn->close();
?>