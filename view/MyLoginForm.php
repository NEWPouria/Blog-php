<?php
session_start();
if(isset($_SESSION["USER"])){
    header("Location: ProfilePage.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pico.blue.min.css">
    <title>ورود</title>
</head>

<?php
include(__DIR__."/../Users.php");

if (!empty($_POST)) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // کنترل ایمیل ورودی توسط کابر
    $email=filter_var($email,FILTER_SANITIZE_EMAIL);
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)===false){
        //در تایع لاگین یوزرنیم کاربر در سشن ذخیره میشود
        $Loging_in_User=new Users();
        $Loging_in_User->Login($email, $password);
    }else{
        echo "ERROR: Structure of "."<u>$email</u>"." is not valid . Please check Email and try again...";
    }

    //بدون کنترل ایمیل ورودی توسط کاربر
    // $Loging_in_User=new Users();
    // $Loging_in_User->Login($email,$password);
}

?>




<body class="container">
    <main>
        <article>
            <header>
                <h2 dir="rtl">ورود</h2>
            </header>
            <form method="post">
                <fieldset>
                    <label>
                        Email <?php //echo $temp; ?>
                        <input type="email" name="email" placeholder="Email" aria-label="Email" autocomplete="email">
                    </label>
                    <label>
                        Password
                        <input type="password" name="password" placeholder="Password" aria-label="Password">
                    </label>
                    <input name="terms" type="checkbox" role="switch" />Remember Me</label>
                </fieldset>
                <button type="submit">Login</button>
            </form>
        </article>
    </main>
</body>

</html>