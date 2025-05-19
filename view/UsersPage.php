<?php
// Session cannot be started after headers have already been sent in C:\laragon\www\InClass\Blog\ProfilePage.php on line 14
session_start();
if (!isset($_SESSION["USER"])) {
    header("Location: loginForm.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fa">
<!-- کد های php برای نمایش profile -->
<?php

// require_once 'Users.php';
// require_once 'C:/laragon/www/InClass/Blog/Users.php';
require_once __DIR__ . '/../../Blog/Model/Users.php';
$LoggedUserEmail = $_SESSION["USER"];

// فراخوانی اطلاعات کاربر
$LoggedUser = new Users;
$Loggedinfo = $LoggedUser->FetchUserInfo($_SESSION["USER"]);
// $LoggedUserName = strtoupper($info['UserName']);
$LoggedCreate_date = $Loggedinfo['Create_date'];

// گرفتن ID کاربر چون در فرم پست گذاری برای ثبت متن به آن نیاز داریم
$LoggedUserID = $LoggedUser->FindUserID($_SESSION["USER"]);
// var_dump($UserID["UserID"]);
?>

<!-- دریافت اطلاعات صاحب صفحه -->
<?php
$PageOwner_UserID = isset($_GET['UserID']) ? (int) $_GET['UserID'] : 0;

$PageOwner_Info = Users::FetchUserInfoBYID($PageOwner_UserID);
// echo "<pre>";
// print_r($PageOwner_Info);echo "</pre>";
// echo $PageOwner_Info['UserName'];
?>
<?php
/**چک کردن اینکه آیا کاربر لاگین کرده یوزر این صفحه رو فالو دارد یا خیر */
require_once '../Model/FollowService.php';
$checkFollow = new FollowService();
$checkFollow->isFollowing($LoggedUserID, $PageOwner_UserID);
// echo "<pre> CheckFollow->isFollowing(PageOwner $PageOwner_UserID,LoggedUser $LoggedUserID);</pre>"; 
// echo $checkFollow->isFollowing($PageOwner_UserID ,$LoggedUserID) ? "Follow this page" : "does not follow this page";
?>
<?php
/**کد های مربوط به شمارش فالو */
require_once '../Model/FollowService.php';
$FollowingCount = FollowService::FollowingCount($PageOwner_UserID);
$FollowerCount = FollowService::FollowerCount($PageOwner_UserID);
// var_dump($FollowingCount);
// var_dump($FollowerCount);
?>
<?php
if ($LoggedUserID == $PageOwner_UserID) {
    header("Location: ProfilePage.php");
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $PageOwner_Info['UserName'] ?></title>
    <link rel="stylesheet" href="/Blog/css/ProfilePage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="/Blog/favicon.ico" type="image/x-icon">

</head>


<body class="dark-mode">
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
                    <button onclick="toggleBlur()" class="round_button"><i id="BlurIcon"
                            class="fa-regular fa-eye-slash"></i></button>
                    <button onclick="gocenter()" class="round_button"><i id="ExpandIcon"
                            class="fa-solid fa-expand fa-lg"></i></button>
                </div>
                <form action="/../Blog/Controller/PostProcess.php" method="post" enctype="multipart/form-data">
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
                        <a style="font-size:larger; font-weight:bold ;"
                            href="#"><?php echo ucfirst($PageOwner_Info['UserName']); ?></a>
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
                        <!-- چک فالو بودن و اعمال در دکمه -->
                        <?php if (!$checkFollow->isFollowing($PageOwner_UserID, $LoggedUserID)): ?>
                            <button class="oval_button" id="Follow-btn" style="background-color:#4D6BFE ;"
                                data-page-owner-user-id="<?php echo $PageOwner_UserID; ?>" data-following="false"> Follow
                            </button>
                        <?php else: ?>
                            <button class="oval_button" id="Follow-btn" style="background-color:#2e2e2e ;"
                                data-page-owner-user-id="<?php echo $PageOwner_UserID; ?>" data-following="true"> UnFollow
                            </button>
                        <?php endif; ?>

                        <button class="round_button"> <i class="fa-solid fa-magnifying-glass"></i></button>
                        <button class="round_button"> <i class="fa-solid fa-ellipsis"></i></button>
                    </div>
                </div>

            </div>
            <div class="User_info" style=" padding-top: 5px;">
                <h3><?php echo ucfirst($PageOwner_Info['UserName']); ?></h3>
                <H4><?php echo ucfirst($PageOwner_Info['Email']); ?></H4>
                <p>Joined Date <?= ucfirst($PageOwner_Info['Create_date']); ?></p>
                <br>
                <div style="display: flex; justify-items: center; gap: 10px; color: blue;">
                    <p><?= $FollowingCount ?> Following</p>
                    <p><?= $FollowerCount ?> Follower</p>
                </div>
                <br>
            </div>

            <hr>
            <hr>

            <!-- نمایش پست ها -->
            <div class="post">
                <?php
                // require_once 'Articles.php';
                require_once __DIR__ . '/../../Blog/Model/Articles.php';
                // require_once 'Media.php';
                require_once __DIR__ . '/../../Blog/Model/Media.php';
                $articles = Articles::ShowAllUserArticles($PageOwner_UserID);
                $UserInfo = Users::FetchUserInfoBYID($PageOwner_UserID);

                ?>
                <div id="articles-container">
                    <?php if (!empty($articles)): ?>
                        <?php foreach ($articles as $article): ?>

                            <a href="singlepost.php?articleID=<?= $article['ArticleID'] ?>"
                                style="text-decoration: none; color: inherit;">

                                <?php
                                $Autherinfo = Users::FetchUserInfoBYID($article['AutherID']);
                                ?>
                                <div class="profile_pic"
                                    style="display: flex; justify-content: flex-start; align-items: center; gap: 10px;">
                                    <img style="width: 50px; height: 50px;" src="/Blog/css/profile.png" alt="">
                                    <h3> <?= htmlspecialchars(ucfirst($Autherinfo['UserName'])) ?></h3>
                                </div>
                                <p><?= htmlspecialchars($article['ArticleText']) ?></p>
                                <!-- <p>اینجا تا بخش 2 انجام شده ArticleID ....   <?= htmlspecialchars($article['ArticleID']) ?></p> -->
                                <?php
                                $ArticleID = $article['ArticleID'];
                                $Articles_MediaIDs = Media::GetArticleMediaID($ArticleID);
                                // echo"profilepage L160 <br>";
                                // print_r($MediaIDs);                                
                                ?>

                                <?php if (is_array($Articles_MediaIDs) && !empty($Articles_MediaIDs)): ?>
                                    <?php foreach ($Articles_MediaIDs as $MediaID): ?>
                                        <?php
                                        $MediaInfo = Media::MediaInfo($MediaID);
                                        if (isset($MediaInfo['MediaPath'])) {
                                            ?>
                                            <img src="/../Blog/Controller/<?= $MediaInfo['MediaPath'] ?>" width="200">
                                        <?php } ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p></p> <!-- یا هر پیام جایگزین دیگر -->
                                <?php endif; ?>
                            </a>
                            <hr>

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


        /**کد های کربوط به دکمه فالو Follow-btn */
        /*
        document.getElementById("Follow-btn").addEventListener("click", function () {
            const PageOwner_UserID=this.dataset.pageOwnerUserId;
            // alert(PageOwner_UserID);
            const xhr=new XMLHttpRequest();
            xhr.open('POST','../Controller/FollowProcess.php',true);
            xhr.setRequestHeader("Content-Type", "application/json");

            xhr.onload=function(){
                if(xhr.status===200){
                    const data=JSON.parse(xhr.responseText);
                    if(data.success){
                        const btn=document.getElementById("Follow-btn");
                        btn.textContent=data.following ? "Unfollow" : "Follow";
                        btn.style.backgroundColor=data.following ? "green" : "gray" ;
                    }else{
                        alert("Error" + data.message);
                    }
                }
            };
            xhr.send(JSON.stringify({user_id: PageOwner_UserID}))
        });
        */
        document.getElementById("Follow-btn").addEventListener("click", function () {
            const btn = this;
            const PageOwner_UserID = btn.dataset.pageOwnerUserId;
            const isCurrentlyFollowing = btn.dataset.following === "true"; // وضعیت فعلی

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../Controller/FollowProcess.php', true);
            xhr.setRequestHeader("Content-Type", "application/json");

            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            // وضعیت جدید را بر اساس پاسخ سرور آپدیت کنید
                            btn.dataset.following = data.following;
                            btn.textContent = data.following ? "Unfollow" : "Follow";
                            btn.style.backgroundColor = data.following ? "#2e2e2e" : "#4D6BFE";
                        } else {
                            alert("Error: " + data.message);
                        }
                    } catch (e) {
                        console.error("Invalid JSON:", e);
                        alert("Server error!");
                    }
                } else {
                    alert("Request failed: " + xhr.status);
                }
            };

            xhr.onerror = function () {
                alert("Network error!");
            };

            // ارسال درخواست با وضعیت فعلی به سرور
            xhr.send(JSON.stringify({
                user_id: PageOwner_UserID,
                action: isCurrentlyFollowing ? "unfollow" : "follow" // به سرور بگویید چه عملی انجام شود
            }));
        });



    </script>

</body>

</html>