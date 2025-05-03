<?php
// Session cannot be started after headers have already been sent in C:\laragon\www\InClass\Blog\ProfilePage.php on line 14
session_start();
if (!isset($_SESSION["USER"])) {
    header("Location: MyloginForm.php");
    exit();
}
?>
<?php
// Include the necessary files
require_once __DIR__ . '/../../Blog/vendor/autoload.php';
use Carbon\Carbon;
require_once __DIR__ . '/../../Blog/Model/Articles.php';
require_once __DIR__ . '/../../Blog/Model/Media.php';
require_once __DIR__ . '/../../Blog/Model/Users.php';
require_once __DIR__ . '/../../Blog/Model/Comments.php';

// دریافت ID مقاله از پارامتر URL
/*
اگر isset($_GET['articleID']) مقدارش true بود
(int)$_GET['articleID'] را داخل $articleID بریز
در غیر اینصورت عدد صفر را داخل $articleID بریز
*/

$articleId = isset($_GET['articleID']) ? (int) $_GET['articleID'] : 0; // Use Ternary Operator
/*
//to see what does $articleID returns to me
echo "(SinglePost.php L24) <pre>";
echo "ArticleID id :" . $articleId . "</pre>";
*/

// دریافت اطلاعات مقاله
$article = Articles::GetArticleInfoById($articleId);
$UserInfo = Users::FetchUserInfoBYID($article['AutherID'] ?? 0);  // Use Null coalescing Operator
$MediaIDs = Media::GetArticleMediaID($articleId);

/*
//to see what does $article , $UserInfo , $MediaIDs returns to me
echo "(singlepost L35)<pre> article ";
print_r($article);
echo "</pre>";
echo "(singlepost L38)<pre> UserInfo ";
print_r($UserInfo);
echo "</pre>";
echo "(singlepost L41)<pre> MediaIDs ";
print_r($MediaIDs);
echo "</pre>";
*/

// دریافت کامنت ها
$Comments = new Comments;
$CommentsList = $Comments->Read($articleId);
echo "<pre>";
print_r($CommentsList);
echo "</pre>";

?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحه سه ستونی با تم دارک و روشن</title>
    <link rel="stylesheet" href="/Blog/css/ProfilePage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/Blog/css/comments.css">

</head>
<!-- کد های php برای نمایش profile -->

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
                        <!-- آیکون قبگرد -->
                        <button class="back-button"><a href="javascript:history.back()" class="back-link"><i
                                    class="fa-solid fa-arrow-left"></i></a></button>
                        <a style="font-size:larger; font-weight:bold ; text-transform:uppercase"
                            href="#"><?= $UserInfo['UserName'] ?></a>
                    </div>
                    <button class="round_button" onclick="toggleTheme()"><i id="ThemeIcon"
                            class="fa-regular fa-moon"></i></button>
                </div>
            </div>
            <hr>
            <hr>
            <h2></h2>
            <!-- نمایش پست ها -->
            <div class="post">
                <!-- بخشی از php مربوط به اینجا اول فایل قرار گرفته -->

                <div id="articles-container">
                    <!-- محتوای پست -->
                    <h3>Auther: <?= htmlspecialchars($UserInfo['UserName']) ?></h3>
                    <p><?= htmlspecialchars($article['ArticleText']) ?></p>

                    <!-- نمایش عکس های پست -->
                    <?php if (!empty($article)): ?>
                        <?php foreach ($MediaIDs as $MediaID): ?>
                            <?php $MediaInfo = Media::MediaInfo($MediaID); ?>
                            <img src="/../Blog/Controller/<?= $MediaInfo['MediaPath'] ?>" width="200">
                        <?php endforeach; ?>
                        </a>
                        <hr>

                    <?php else: ?>
                        <p>هیچ مقاله‌ای یافت نشد!</p>
                    <?php endif; ?>
                    <!-- comment section -->
                    <div class="comments-section">
                        <link rel="stylesheet"
                            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
                        <div class="card mb-3">
                            <div class="card-body">
                                <form action="/../Blog/Controller/CommentProcess.php" method="post"
                                    enctype="multipart/form-data">
                                    <!-- دقت شود 
                                     کا اینجا داریم articleId رو با post ارسال میکنیم
                                     برای همین حتما باید مقدار رو در value اکو کنیم -->
                                    <input type="hidden" name="ArticleID_Toward_Action" id=""
                                        value="<?php echo $articleId; ?>">
                                    <h5 class="card-title">Comments</h5>
                                    <div class="mb-3">
                                        <textarea class="form-control" name="CommentText"
                                            placeholder="Post Your Reply ..." rows="3"></textarea>
                                    </div>
                                    <div
                                        style="display: flex; flex-direction: row;align-items: center; justify-content: space-between;">
                                        <input style="display: none" type="file" name="Comment_Media[]"
                                            id="Comment_Images" accept="image/*" multiple hidden>
                                        <button type="button" id="Comment_ImagesWithIcon" name=""
                                            class="round_button"><i class="fa-regular fa-images"></i></button>
                                        <button class="btn"> <i class="fas fa-reply"> </i> Reply</button>

                                    </div>
                                </form>
                                <!-- لیست کامنت‌ها -->
                                <div class="mt-3">
                                    <?php if (!empty(($CommentsList))): ?>

                                        <?php foreach ($CommentsList as $Comment): ?>
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <?php
                                                    $CommenterInfo = Users::FetchUserInfoBYID($Comment['CommentUserID'] ?? 0);
                                                    // echo "<pre>";
                                                    // print_r($CommenterInfo); echo "</pre>";
                                                    ?>
                                                    <a
                                                        style=" font-weight: 500 ; text-transform:uppercase"><?= htmlspecialchars($CommenterInfo['UserName']) ?><br></a>
                                                    <p><?= htmlspecialchars($Comment['CommentText']) ?><br></p>
                                                    <?php
                                                    $Comment_RelativeTime = Carbon::createFromFormat('Y-m-d H:i:s', $Comment['CommentDate'])->diffForHumans();
                                                    ?>
                                                    <small
                                                        class="text-muted"><?= htmlspecialchars($Comment_RelativeTime); ?></small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <strong>کاربر نمونه <br></strong>
                                    <p>این یک نظر آزمایشی است.</p>
                                    <small class="text-muted">2 روز پیش</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- comment section -->
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

        let CommentNoIcon = document.getElementById('Comment_Images');
        let CommentTheIcon = document.getElementById('Comment_ImagesWithIcon');
        CommentTheIcon.addEventListener("click", () => {
            CommentNoIcon.click();
        });

        function gocenter() {
            let publishDIV = document.getElementById('publish')
            publishDIV.classList.toggle('to-center');
            let textarea = document.getElementById('PostText');
            var rowsCount = textarea.rows;
            console.log("now ", rowsCount);

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



    </script>
</body>

</html>