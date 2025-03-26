<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/pico.blue.min.css">
    <title>ثبت نام</title>
</head>

<?php
include(__DIR__."/Users.php");

if (isset($_SESSION["user"])) {
    header("Location: /");
}

if (!empty($_POST)) {
    $flag = false;
    $StoredEmail="";
    $StoredPhone="";
    $StoredPassword="";

    // کنترل نام ورودی توسط کابر
    $name = $_POST['name'];
   // $sanitized_name=filter_var($name,FILTER_SANITIZE_STRING);


    // کنترل ایمیل ورودی توسط کابر
    $email = $_POST['email'];
    $sanitized_email=filter_var($email,FILTER_VALIDATE_EMAIL);
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)===false){
        $StoredEmail=$sanitized_email;
        $flag = true;
    }else{
        $flag = false;
        echo "ERROR: Structure of "."<u>$email</u>"." is not valid . Please check Email and try again..."."<br>";
    }


    // کنترل شماره تلفن ورودی توسط کابر
    $phone = $_POST['phone'];
    $sanitized_phone=filter_var($phone,FILTER_SANITIZE_NUMBER_INT);
    $sanitized_phone=str_replace("-","",$sanitized_phone);
    $phone_pattern="/^(\+?[0-9]{10,15})$/";
    if(preg_match($phone_pattern,$sanitized_phone)&& strlen($sanitized_phone)>=10 && strlen($sanitized_phone)<=13){
        $StoredPhone=$sanitized_phone;
        $flag=true;
    }else{
        echo "ERROR: Structure of "."<u>$phone</u>"." is not valid . Please check Phone and try again..."."<br>";
        $flag=false;
    }


    //کنترل پسورد ورودی توسط کاربر 
    $password = $_POST['password'];
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
    if (preg_match($pattern, $password)) {
        $StoredPassword=$password;
        echo "Valid Password";
        $flag=true;
    } else {
        echo "Invalid Password"."<br>";
        echo"password minimum length should be 8.
             at least one uppercase letter,
             at least one lowercase letter,
             and one digit."."<br>";
             $flag=false;
             //Pouriakhosravi10
    }
   
    if($flag) {

        $CreateMyNewUser=new Users();
        $CreateMyNewUser->Create($name,$StoredPassword,$StoredEmail,$StoredPhone);
        die('User Created');
    } else {
        echo "<center><h1> We Have some ERRORs so try again.</h1></center>";     
    }
    
    }




?>


<body class="container">
    <main>
        <article>
            <header>
                <h2 dir="">Register</h2>
            </header>
            <form method="post">
                <fieldset>
                    <label>
                        Name
                        <input type="text" name="name" placeholder="Name" aria-label="Name" autocomplete="name">
                    </label>
                    <label>
                        Email
                        <input type="email" name="email" placeholder="Email" aria-label="Email" autocomplete="email">
                    </label>
                    <label>
                        Phone
                        <input type="text" name="phone" placeholder="Phone" aria-label="Phone" autocomplete="phone">
                    </label>
                    <label>
                        Password
                        <input type="password" name="password" placeholder="Password" aria-label="Password">
                    </label>
                    <input name="terms" type="checkbox" role="switch" />Remember Me</label>
                </fieldset>
                <button type="submit">Register</button>
            </form>
        </article>
    </main>
</body>

</html>