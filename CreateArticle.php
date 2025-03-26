<!-- برای استفاده از نشست باید اولین خط کد باشد -->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/pico.blue.min.css">
    <link rel="stylesheet" href="./css/main.css">
    <title>صفحه اصلی</title>
</head>
<!-- بررسی اینکه کاربر لاگین کرده یا خیر -->
<?php
// include(__DIR__."./Users.php");
// include(__DIR__."./Articles.php");
// require_once'Users.php';
// require_once 'Articles.php';
require_once(__DIR__."./Articles.php");
require_once(__DIR__."./Users.php");
if (isset($_SESSION["USER"])) {
    $UserID=new Users();
    $Session_UserID=$UserID->FindUserID($_SESSION['USER']);
    echo $Session_UserID;
    if (!empty($_POST)){
    // دریافت اطلاعات مقاله از فرانت
    $Enter_Title=$_POST['title'];
    $Enter_Summary=$_POST['summary'];
    $Enter_Body=$_POST['body'];
    // echo $Enter_Title . $Enter_Summary .$Enter_Body ;
    $newPost=new Articles();

    $sanitized_Title=filter_var($Enter_Title,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
    if(strlen($sanitized_Title)>255){
        die("Your Title Text Is TOO LONG . Write less...");
    }
    $sanitized_Summary=filter_var($Enter_Summary,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
    if(strlen($sanitized_Summary)>255){
        die("Your Summary Text Is TOO LONG . Write less...");
    }
    $sanitized_Body=filter_var($Enter_Body,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
    if(strlen($sanitized_Body)>1000){
        die("Your Body Text Is TOO LONG . Write less...");
    }

    $newPost->Create($Session_UserID,$sanitized_Body,$sanitized_Summary,$sanitized_Body);
    }
}
?>

<body class="container-fluid">
    <main>
        <div class="content">
            <div class="sidebar">
                <article>
                    <header>لینک ها</header>
                    <ul>
                        <li><a href="/articles/create.php">مقاله ها</a></li>
                        <li><a href="#">کاربران</a></li>
                    </ul>
                </article>
            </div>
            <div class="main-content">
                <form method="post">
                    <article>
                        <header>مقاله جدید</header>
                        <fieldset>
                            <label>
                                عنوان
                                <input type="text" name="title" placeholder="title" aria-label="title"
                                    autocomplete="title">
                            </label>
                            <label>
                                خلاصه
                                <textarea type="text" name="summary" placeholder="summary"
                                    aria-label="summary"></textarea>
                            </label>
                            <label>
                                متن کامل
                                <textarea type="text" name="body" placeholder="body" aria-label="body"></textarea>
                            </label>
                            <label>
                                تصویر اصلی
                                <input type="file" name="cover" placeholder="cover" aria-label="cover">
                            </label>
                        </fieldset>
                        <footer dir="rtl"> <button type="submit">ثبت</button>
                        </footer>
                    </article>
                </form>
            </div>
        </div>
    </main>
</body>

</html>