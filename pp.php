
<?php

if(!empty($_FILES)){
    echo "<pre>";
print_r($_FILES);
echo "<pre>";

echo $_FILES["batman"]["type"]."<br>";


$ttype = explode("/", $_FILES["batman"]["type"])[0];
echo $ttype ."<br>";
echo "----------------<br>";
$type = explode("/", $_FILES["batman"]["type"]);
echo $type[0] ."<br>";
echo $type[1] ."<br>";
echo "----------------<br>";
$F=strtolower(pathinfo($_FILES["batman"]["tmp_name"],PATHINFO_EXTENSION));
echo $F;
}else{
    echo"فایل انتخاب نشده";
}

?>
<?php
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     echo "<pre>";
//     print_r($_FILES);
//     var_dump($_FILES);
//     echo "</pre>";
// }
?>

