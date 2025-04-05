<?php
require_once __DIR__.'/../../Blog/Model/Articles.php';
require_once __DIR__ .'/../../Blog/Model/Media.php';
require_once __DIR__ .'/../../Blog/Model/Users.php';

// دریافت ID مقاله از پارامتر URL
/*
 اگر isset($_GET['articleId']) مقدارش true بود
 (int)$_GET['articleId'] را داخل $articleId بریز
 در غیر اینصورت عدد صفر را داخل $articleId بریز
 */

$articleId = isset($_GET['articleID']) ? (int)$_GET['articleID'] : 0; // Use Ternary Operator
echo "line 14 <br>";
echo $articleId;
echo "line 14 <br>";

// دریافت اطلاعات مقاله
$article = Articles::GetArticleInfoById($articleId);
$UserInfo = Users::FetchUserInfoBYID($article['UserID'] ?? 0);  // Use Null coalescing Operator
$MediaIDs = Media::GetArticleMediaID($articleId);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مقاله کامل</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .article-content {
            margin-top: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 15px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-link:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <?php if ($article): ?>
        <h1><?= htmlspecialchars($article['ArticleTitle'] ?? 'بدون عنوان') ?></h1>
        <h3>نویسنده: <?= htmlspecialchars($UserInfo['UserName'] ?? 'ناشناس') ?></h3>
        
        <div class="article-content">
            <p><?= htmlspecialchars($article['ArticleText']) ?></p>
            
            <?php foreach($MediaIDs as $MediaID): ?>
                <?php $MediaInfo = Media::MediaInfo($MediaID); ?>
                <img src="/../Blog/Controller/<?= $MediaInfo['MediaPath']?>" width="400" style="margin: 10px 0;">
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>مقاله مورد نظر یافت نشد!</p>
    <?php endif; ?>
    
    <a href="javascript:history.back()" class="back-link">بازگشت</a>
</body>
</html>