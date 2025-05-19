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
    // header("Location: EditProfile.php?UserID=$LoggedUserID");
    // exit();
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

    <title>Change Password</title>
</head>




<body class="container">
    <main>
        <article>
            <header>
                <h2 dir="">Change Password</h2>
            </header>
            <form method="post">
                <fieldset>
                    <label>
                        Last Password
                        <input type="password" name="LastPassword" placeholder="Last Password">
                    </label>
                    <label>
                        New Password
                        <input type="password" name="NewPassword" placeholder="New Password">
                    </label>
                    <label>
                        Repeat New Password
                        <input type="password" name="RepeatNewPassword" placeholder="Repeat New Password">
                    </label>
                    
                </fieldset>
                <button type="submit">Change Password</button>
            </form>
        </article>
    </main>
</body>

</html>