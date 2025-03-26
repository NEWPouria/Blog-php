<?php
require_once 'Users.php';
session_start();
$useremail=$_SESSION["USER"];
$user=new Users;
$userid=$user->FindUserID($useremail);
// var_dump($userid);

require_once "Media.php";
$kkkk=new Media;
$kkkk->AddMedia($userid,"banner")
?>