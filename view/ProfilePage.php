<?php
// Session cannot be started after headers have already been sent in C:\laragon\www\InClass\Blog\ProfilePage.php on line 14
session_start();
if (!isset($_SESSION["USER"])) {
    header("Location: MyloginForm.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحه سه ستونی با تم دارک و روشن</title>
    <link rel="stylesheet" href="/Blog/css/ProfilePage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<!-- کد های php برای نمایش profile -->
<?php

// require_once 'Users.php';
// require_once 'C:/laragon/www/InClass/Blog/Users.php';
require_once __DIR__ . '/../../Blog/Users.php';
$UserEmail = $_SESSION["USER"];

// فراخوانی اطلاعات کاربر
$User = new Users;
$info = $User->FetchUserInfo($_SESSION["USER"]);
$UserName = strtoupper($info['UserName']);
$Create_date = $info['Create_date'];

// گرفتن ID کاربر چون در فرم پست گذاری برای ثبت متن به آن نیاز داریم
$UserID = $User->FindUserID($_SESSION["USER"]);
// var_dump($UserID["UserID"]);
?>

<!-- کد های php برای فرم پست -->
<?php
// include_once 'Articles.php';
// if(!empty($_POST)){
// $text=$_POST['Post_Text'];
// $article=new Articles();
// $article->Create($UserID["UserID"],"title","meta",$text);
// }

?>

<body>



    <div class="container">
        <!-- ستون چپ -->
        <div class="left-column">
            <h2>منوی سایت</h2>
            <ul>
                <li><a href="#">خانه</a></li>
                <li><a href="#">درباره ما</a></li>
                <li><a href="#">تماس با ما</a></li>
            </ul>

            <div id="publish" class="publish">
                <h2>Publish</h2>
                <div class="UpperPostBTNs">
                    <!-- <button onclick="goceneter()">,flgfgl,fgl,f,lfgl,</button> -->
                    <button onclick="toggleBlur()" class="round_button"><i id="BlurIcon"
                            class="fa-regular fa-eye-slash"></i></button>
                    <button onclick="gocenter()" class="round_button"><i id="ExpandIcon"
                            class="fa-solid fa-expand fa-lg"></i></button>
                </div>
                <form action="PostProcess.php" method="post" enctype="multipart/form-data">
                    <textarea id="PostText" name="Post_Text" class="PostText" placeholder="Whats Happening?" rows="7"
                        style="resize: none;width: 100%;height: 140px;background-color: inherit; color: inherit; border: 1px solid #ccc; padding: 10px;"></textarea>
                    <div class="UnderPostBTNs">
                        <div class="UnderPostBTNs_Options">
                            <input type="file" id="images" name="article[]" accept="image/*" multiple hidden>
                            <button type="button" id="ImagesWithIcon" name="image_files" class="round_button"><i
                                    class="fa-regular fa-images"></i></button>
                                    <!-- نکته ای اینجا وجود داره که دکمه ما با جاوااسکریپت باعث کلیک شدن دکمه فایل میشود -->
                                     <!-- برای اینکه دکمه فایل ما درست کار کند توع دکمه ظاهری باید button باشد -->
                        </div>
                        <div class="UnderPostBTNs_POST">
                            <button type="submit" class="oval_button"><i class="fa-regular fa-pen-to-square"></i>
                                POST </button>
                        </div>

                    </div>

                </form>
            </div>

        </div>

        <!-- ستون وسط -->
        <div class="center-column">

            <div class="banner">
                <div class="upper_banner">
                    <div style="display: flex; align-items: center; gap: 3px; ">
                        <button class="back-button">
                            <i class="fa-solid fa-arrow-left"></i> <!-- آیکون قبگرد -->
                        </button>
                        <a style="font-size:larger; font-weight:bold ;" href="#"><?php echo $UserName; ?></a>
                    </div>
                    <button class="round_button" onclick="toggleTheme()"><i id="ThemeIcon"
                            class="fa-regular fa-moon"></i></button>
                </div>
                <img src="/Blog/css/banner.jpg" alt="banner_pic">

                <div class="UnderBanner">
                    <div class="profile_pic">
                        <img src="/Blog/css/profile.png" alt="profile_pic">
                    </div>
                    <div class="UnderBannerBTNs">
                        <button class="oval_button"> <i class="fa-solid fa-user-plus"></i> Follow </button>
                        <button class="round_button"> <i class="fa-solid fa-magnifying-glass"></i></button>
                        <button class="round_button"> <i class="fa-solid fa-ellipsis"></i></button>
                    </div>
                </div>

            </div>
            <div class="User_info" style=" padding-top: 5px;">
                <h3><?php echo $UserName; ?></h3>
                <H4><?php echo $UserEmail; ?></H4>
                <p>Joined Date <?= $Create_date; ?></p>
                <div>
                    <a href="#">Following</a>
                    <a href="#">Follower</a>
                </div>
            </div>

            <hr>
            <hr>
            <h2>پست‌ها</h2>

            <!-- نمایش پست ها -->
            <div class="post">
                <?php
                // require_once 'Articles.php';
                require_once __DIR__.'/../../Blog/Articles.php';
                // require_once 'Media.php';
                require_once __DIR__ .'/../../Blog/Media.php';
                $articles = Articles::ShowAllUserArticles($UserID);
                $UserInfo = Users::FetchUserInfoBYID($UserID);

                ?>
                <h1>لیست مقالات</h1>
                <div id="articles-container">
                    <?php if (!empty($articles)): ?>
                        <?php foreach ($articles as $article): ?>
                            <div>
                                <h3>Auther: <?= htmlspecialchars($UserInfo['UserName']) ?></h3>
                                <p><?= htmlspecialchars($article['ArticleText']) ?></p>
                                <p><?= htmlspecialchars($article['ArticleID']) ?></p>
                                <?php
                                $ArticleID = $article['ArticleID'];
                                // $ArticleMediaID=Media::GetArticleMediaID($ArticleID);
                                // $ArticleMedia_src=Media::MediaInfo($ArticleMediaID); 
                                $MediaID=Media::FindMediaIDFromArticleMediaTable($ArticleID);
                                // echo"<br>hoooo<br>";
                                // var_dump($MediaID[1]);
                                $ArticleMedia_src = Media::MediaInfo(51);
                                ?>
                                <img src="/Blog/<?= htmlspecialchars($ArticleMedia_src["MediaPath"]) ?>" width="200">
                                <hr>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>هیچ مقاله‌ای یافت نشد!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ستون راست -->
        <div class="right-column">
            <h2>پیشنهادات</h2>
            <ul>
                <li>پیشنهاد ۱</li>
                <li>پیشنهاد ۲</li>
                <li>پیشنهاد ۳</li>
            </ul>

            <!-- بخش پیام‌رسان -->
            <div class="messenger">
                <h2>پیام‌رسان</h2>
                <p>این بخش برای نمایش پیام‌ها و چت‌ها استفاده می‌شود.</p>
            </div>
        </div>
    </div>
    <script>

        // اد ایونت لیسنتر رو داخل تابع نگزارید
        let NoIcon = document.getElementById('images');
        let TheIcon = document.getElementById('ImagesWithIcon');
        TheIcon.addEventListener("click", () => {
            NoIcon.click();
        });




        function gocenter() {
            let publishDIV = document.getElementById('publish')
            publishDIV.classList.toggle('to-center');
            let textarea = document.getElementById('PostText');
            var rowsCount = textarea.rows;
            console.log("now ", rowsCount);


            // isExpand=false;
            if (rowsCount == 12) {
                rowsCount = 7;
                textarea.style.height = '140px';
            } else if (rowsCount == 7) {
                rowsCount = 12;
                textarea.style.height = '210px';
            } textarea.setAttribute('rows', rowsCount)
            // تغییر آیکون
            toggleIcon("ExpandIcon", "fa-expand", "fa-compress")


        }


        const textarea = document.getElementById("PostText");
        const text = "Whats Happening?"; // متن placeholder
        let index = 0;
        let isDeleting = false;
        let timer = 100;
        var loop;
        var count;
        // loop = setInterval(typeWriter, 100);

        function typeWriter() {
            if (!isDeleting) {
                textarea.setAttribute("placeholder", text.substring(0, index + 1));
                index++;
                if (index === text.length) {
                    isDeleting = true;
                    setTimeout(typeWriter, 1000);
                    return;
                }
                // setTimeout(typeWriter, 1000); // سرعت تایپ (100 میلی‌ثانیه)
            } else {
                textarea.setAttribute("placeholder", text.substring(0, index - 1));
                index--;
                if (index === 0) {
                    isDeleting = false;
                }
                // setTimeout(typeWriter, 150);
            }
            setTimeout(typeWriter, 100);
        }
        typeWriter();

        // setInterval(typeWriter, timer);

        // const textElement1 = document.getElementById('publish');
        // // const textElement = textElement1.placeholder;
        // const cursorElement = document.querySelector('.cursor');
        // const texts = ['متن اول', 'متن دوم', 'متن سوم'];
        // let currentTextIndex = 0;
        // let currentCharIndex = 0;
        // let isDeleting = false;

        // function type() {
        //     const currentText = texts[currentTextIndex];
        //     if (isDeleting) {
        //         currentCharIndex--;
        //         textElement.textContent = currentText.substring(0, currentCharIndex);
        //         if (currentCharIndex === 0) {
        //             isDeleting = false;
        //             currentTextIndex = (currentTextIndex + 1) % texts.length;
        //         }
        //     } else {
        //         currentCharIndex++;
        //         textElement.textContent = currentText.substring(0, currentCharIndex);
        //         if (currentCharIndex === currentText.length) {
        //             isDeleting = true;
        //         }
        //     }
        //     setTimeout(type, isDeleting ? 50 : 100);
        // }

        // document.addEventListener('DOMContentLoaded', () => {
        //     setTimeout(type, 1000);
        // });




        // تابع تغییر تم
        function toggleTheme() {
            const body = document.body;
            body.classList.toggle('dark-mode');
            // تغییر آیکون
            toggleIcon("ThemeIcon", "fa-moon", "fa-sun");
        }
        // تابع بلر کردن المان
        function toggleBlur() {
            // انتخاب div اصلی
            const PostText = document.getElementById('PostText');

            // تغییر کلاس blur-effect
            PostText.classList.toggle('Blur');
            // تغییر آیکون
            toggleIcon("BlurIcon", "fa-eye-slash", "fa-eye");
        }
        function toggleIcon(IconId, DefaultIcon, NewIcon) {
            var icon = document.getElementById(IconId);
            if (icon.classList.contains(DefaultIcon)) {
                icon.classList.remove(DefaultIcon);
                icon.classList.add(NewIcon);
            } else {
                icon.classList.remove(NewIcon);
                icon.classList.add(DefaultIcon);
            }
        }

    </script>

</body>

</html>