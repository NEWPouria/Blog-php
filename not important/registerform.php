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



if (isset($_SESSION["user"])) {
    header("Location: /");
}

if (!empty($_POST)) {

    $q="Create database davod";
    $query = "INSERT INTO Users (name,email,phone,password,reg_date,role) VALUES (?,?,?,?,?,?)";
    $connection = new mysqli("localhost", "root", "", "blog");
    
    if ($connection->connect_error) {
        die("Error " . $connection->connect_error);
    }

    $stmt = $connection->prepare($query);
    $stmt->bind_param("sssssi", $name, $email, $phone, $password, $reg_date, $role);
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $reg_date = date('Y-m-d');
    $role = 0;

    $stmt->execute();

    $stmt->close();
    $connection->close();

    die('User Created');


}

?>

<body class="container">
    <main>
        <article>
            <header>
                <!-- <h2 dir="rtl">ثبتنام</h2> -->
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