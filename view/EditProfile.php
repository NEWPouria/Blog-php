<?php
session_start();
if (!isset($_SESSION["USER"])) {
    header("Location: loginForm.php");
    exit();
}


include(__DIR__ . "/../../Blog/Model/Users.php");
$LoggedUser = new Users();
$UserID = isset($_GET['UserID']) ? (int) $_GET['UserID'] : 0;
$LoggedUserID = $LoggedUser->FindUserID($_SESSION["USER"]);
if ($UserID != $LoggedUserID) {
    header("Location: EditProfile.php?UserID=$LoggedUserID");
    exit();
}

$Loggedinfo = Users::FetchUserInfoBYID($LoggedUserID);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pico.blue.min.css">
    <link rel="icon" href="/Blog/favicon.ico" type="image/x-icon">

    <title>Edit Profile</title>
</head>

<?php


if (isset($_SESSION["user"])) {
    header("Location: LoginForm.php");
}

if (!empty($_POST)) {


    // کنترل نام ورودی توسط کابر
    $name = $_POST['name'];
    $sanitized_name = filter_var($name, FILTER_SANITIZE_STRING);


    // کنترل ایمیل ورودی توسط کابر
    function Check_Email($Entered_Email)
    {
        $santize_Entered_Email = filter_var($Entered_Email, FILTER_VALIDATE_EMAIL);
        if (!$santize_Entered_Email === false) {
            return true;
        } else {
            echo "ERROR: Structure of " . "<u>$Entered_Email</u>" . " is not valid . Please check Email and try again..." . "<br>";
            return false;
        }
    }


    // کنترل شماره تلفن ورودی توسط کابر
    function Check_Phone($Entered_Phone)
    {
        $santize_Entered_Phone = filter_var($Entered_Phone, FILTER_SANITIZE_NUMBER_INT);
        //$santize_Entered_Phone=str_replace("-","",$santize_Entered_Phone);
        $PhoneNumber_pattern = "/^(\+?[0-9]{10,15})$/";
        if (preg_match($PhoneNumber_pattern, $santize_Entered_Phone) && strlen($santize_Entered_Phone) >= 10 && strlen($santize_Entered_Phone) <= 13) {
            return true;
        } else {
            echo "ERROR: Structure of " . "<u>$Entered_Phone</u>" . " is not valid . Please check Phone and try again..." . "<br>";
            return false;
        }

    }


    //کنترل پسورد ورودی توسط کاربر 
    // function Check_Password($Entered_Password)
    // {
    //     $Password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
    //     if (preg_match($Password_pattern, $Entered_Password)) {
    //         return true;
    //     } else {
    //         echo "Invalid Password" . "<br>";
    //         echo "password minimum length should be 8.<br>
    //          at least one uppercase letter,<br>
    //          at least one lowercase letter,<br>
    //          and one digit." . "<br>";
    //         return false;
    //     }
    // }

    //بررسی درست بودن ورودی ها و ساخت کاربر 
    if (Check_Email($_POST['email']) && Check_Phone($_POST['phone'])) {

        $UpdateUser = new Users();
        $UpdateUser->UpdateUserInfo($LoggedUserID, $_POST['name'], $_POST['email'], $_POST['phone']);
        header("Location: LoginForm.php");
        die("<center><h1>User Created</h1></center>");

    } else {
        echo "<center><h1> We Have some ERRORs so try again.</h1></center>";
    }

    //echo var_dump($_POST['phone']);
    //echo var_dump(Check_Phone($_Post['phone']));
}




?>


<body class="container">
    <main>
        <article>
            <header>
                <h2 dir="">Edit Profile</h2>
            </header>
            <form method="post">
                <fieldset>
                    <label>
                        Name
                        <input type="text" name="name" placeholder="Name" aria-label="Name" autocomplete="name"
                            value="<?php echo $Loggedinfo["UserName"]; ?>">
                    </label>
                    <label>
                        Email
                        <input type="email" name="email" placeholder="Email" aria-label="Email" autocomplete="email"
                            value="<?php echo $Loggedinfo["Email"]; ?>" disabled>
                    </label>
                    <label>
                        Phone
                        <input type="text" name="phone" placeholder="Phone" aria-label="Phone" autocomplete="phone"
                            value="<?php echo $Loggedinfo["Phone"]; ?>">
                    </label>
                    <label>
                        <div style="display: flex;justify-content: space-evenly; "><a href="ChangePassword.php" >Change Password</a></div>
                    </label>
                    <!-- <input name="terms" type="checkbox" role="switch" />Remember Me</label> -->
                </fieldset>
                <button type="submit">Done</button>
            </form>
        </article>
    </main>
</body>

</html>