<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome to your dashboard!</h1>
    <p>Congratulations! You have successfully logged in.</p>
</body>
</html> -->

<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
//var_dump ($_SESSION).".<br>";
print_r($_SESSION);
include(__DIR__."/../Users.php");
$uuser=new Users();
// $uuser->FindUserID($_SESSION['USER']);
$ss=$uuser->FindUserID($_SESSION['USER']);
echo "<br>". $ss["UserName"] ."<br>". $ss["UserID"];
// var_dump($uuser->FindUserID("Parasiiiiiiiiiiiiiiiiiiiiii@gmail.com09059295086"));

?>

</body>
</html> 

