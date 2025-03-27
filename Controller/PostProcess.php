<?php
session_start();
if (!isset($_SESSION["USER"])) {
    echo "سشن کار نمیکنه";
} else {
    echo "سشن کار ه";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    holalaaa
    <?php
    require_once (__DIR__ . "/../../Blog/Model/Users.php");
    require_once (__DIR__ . '/../../Blog/Model/Articles.php');
    include_once (__DIR__ . "/../../Blog/Model/Media.php");
    $PostMedia = new Media;
    $User = new Users;
    $UserID = $User->FindUserID($_SESSION["USER"]);
    var_dump($UserID);
    echo "<br>";
    if (!empty($_POST)) {
        $text = $_POST['Post_Text'];
        $article = new Articles();
        $ArticleID = $article->Create($UserID, "title", "meta", $text);

        if (isset($_FILES['article']) && !empty($_FILES['article']['name'][0])) {
            for ($i = 0; $i < count($_FILES['article']['name']); $i++) {
                $TYPE=$_FILES['article']['type'][$i];
                $TEMP=$_FILES['article']['tmp_name'][$i];
                
                // echo "<pre>"; print_r($_FILES); echo "</pre>";
                echo "<pre>"; echo $i; var_dump($TYPE); echo "</pre>";
                $PostMedia->AddMedia($UserID, 'post',$TEMP,$TYPE, $ArticleID);
                
            }
            echo "داخل ایف";
        } else {
            echo "عکس نیست";
        }
        // var_dump($ArticleID);
        // echo "<pre>";
        // print_r($_FILES);
        // echo "</pre>";

        header('Location: /../../Blog/View/ProfilePage.php');
        exit();
    }
    ?>
</body>

</html>