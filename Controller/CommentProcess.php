<?php
session_start();
if (!isset($_SESSION["USER"])) {
    echo "سشن کار نمیکنه";
} else {
    echo "سشن کار ه";
}


require_once (__DIR__ . "/../../Blog/Model/Users.php");
require_once (__DIR__ . '/../../Blog/Model/Articles.php');
require_once (__DIR__ . '/../../Blog/Model/Comments.php');
require_once (__DIR__ . '/../../Blog/Model/Media.php');


$User=new Users;
$UserID=$User->FindUserID($_SESSION["USER"]);   
$ArticleID=$_POST["ArticleID_Toward_Action"];

echo "<pre>";
print_r($_POST); echo "</pre>";
echo "<pre>";
print_r($_SERVER); echo "</pre>";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(!empty($_POST["CommentText"])){
        $Text=$_POST["CommentText"];
        $Comment=new Comments;
        echo $Text;
        $Comment->Create($UserID,$ArticleID,$Text);
        // header('Location: /../../Blog/View/ArticlePage.php?ArticleID='.$ArticleID);
        // exit();
    }else{
        echo "متن کامنت خالی است";
    }
}








?>