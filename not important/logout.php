<?php
session_start();
session_destroy();
header("Location: MyLoginForm.php");
exit("خارح شدیم");
?>
