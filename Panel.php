<?php
session_start();
if (!isset($_SESSION["USER"])) {
    header("Location: loginForm.php");
    exit();
}
?>
<?php   
require_once 'Users.php';
$UserName=$_SESSION["USER"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1>خوش آمدید، <?php echo $_SESSION["USER"]; 
    var_dump($_SESSION);
    ?></h1>
    <form method="post" action="logout.php">
        <button type="submit">خروج</button>
    </form>
</body>
</html>
