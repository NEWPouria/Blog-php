<?php
session_start();
if (!isset($_SESSION["USER"])) {
    echo "سشن کار نمیکنه";
} else {
    echo "سشن کار ه";
}

echo "<pre>";
print_r($_FILES["Comment_Media"]);
echo "</pre>";

require_once(__DIR__ . "/../../Blog/Model/Users.php");
require_once(__DIR__ . '/../../Blog/Model/Articles.php');
require_once(__DIR__ . '/../../Blog/Model/Comments.php');
require_once(__DIR__ . '/../../Blog/Model/Media.php');


$User = new Users;
$UserID = $User->FindUserID($_SESSION["USER"]);
$ArticleID = $_POST["ArticleID_Toward_Action"];

echo "<pre>";
print_r($_POST);
echo "</pre>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["CommentText"])) {
        $Text = $_POST["CommentText"];
        $Comment = new Comments;
        echo $Text;
        $CommentID=$Comment->Create($UserID, $ArticleID, $Text);

        echo "commentidddddddddddddddddddd====";
        echo $CommentID;

        if (isset($_FILES['Comment_Media']) && !empty($_FILES['Comment_Media']['name'][0])) {
            $CommentMedia = new Media;
            for ($i = 0; $i < count($_FILES['Comment_Media']['name']); $i++) {
                $TYPE = $_FILES['Comment_Media']['type'][$i];
                $TEMP = $_FILES['Comment_Media']['tmp_name'][$i];
                echo "<pre>";
                echo $i;
                var_dump($TYPE);
                echo "</pre>";
                // در اینجا CommentID را به عنوان txtid میفرستیم که در جدول مدیا ذخیره شود 
                $CommentMedia->AddMedia($UserID, 'comment', $TEMP,$TYPE,$CommentID);
            }
        }


        header('Location: /../../Blog/View/ArticlePage.php?ArticleID='.$ArticleID);
        exit();
    } else {
        echo "متن کامنت خالی است";
    }
}








?>