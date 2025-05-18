<?php
// include(__DIR__."/DataBase.php");
require_once 'Database.php';
class Users{
    
    private $id;
    private $username;
    private $password;
    private $email;
    private $modify_date;
    private $phone;
    private $role;
    //private $profile_pic; // عکس پروفایل کاربر که هنوز برنامه نویسی نشده
    public function Create($username,$password,$email,$phone){
        // $this->id = $id;
        // $this->modify_date = date("Y-m-d H:i:s");
        $hashed_password=password_hash($password,PASSWORD_DEFAULT);//هش کردن پسورد وارد شده برایذخیره در دیتا بیس

        $this->username = $username;
        $this->password = $hashed_password;//ارسال پسورد هش شده برای ذخیره
        $this->email = $email;
        $this->phone = $phone;
        $this->role = 0;


        // $UserInsertSql1="INSERT INTO users (UserName, PASSWORD,Email,Phone,Role)
        //                  VALUES ('$this->username', '$this->password','$this->email','$this->phone',$this->role)";
        
        $data=new DataBase() ;

        $UserInsertSql="INSERT INTO users (UserName, PASSWORD,Email,Phone,Role) VALUES (?,?,?,?,?)";
        $WillPrepareUser=$data->myprepare($UserInsertSql);
        $WillPrepareUser->bind_param("ssssi",$this->username,$this->password,$this->email,$this->phone,$this->role) ;
        $WillPrepareUser->execute() ;
        $WillPrepareUser->close() ;

       
        // $data->myquery($UserInsertSql1);
        $data->myclose();
        
    }
    
    public function Login($Entered_Email, $Entered_Password)
    {   
        $data=new DataBase() ;
        $UserLoginSql= "SELECT PASSWORD FROM USERS WHERE Email= ?";
        $WillPrepareUserLogin=$data->myprepare($UserLoginSql);
        $WillPrepareUserLogin->bind_param("s",$Entered_Email);
        $WillPrepareUserLogin->execute();
        
        $sqlResult=$WillPrepareUserLogin->get_result();
        
        if($sqlResult->num_rows > 0){
            $PASSWORD_from_DataBase_array=$sqlResult->fetch_assoc();
            $Hashed_PASSWORD_from_DataBase=$PASSWORD_from_DataBase_array["PASSWORD"];
            //در تایع لاگین یوزرنیم کاربر در سشن ذخیره میشود
            if(password_verify($Entered_Password,$Hashed_PASSWORD_from_DataBase)){
                $_SESSION["USER"]=$Entered_Email;
                header("Location: ProfilePage.php"); 
                

            }else{
                die("Email or Password is invalide");
            }
        }
        $data->myclose();
    }

    public function FindUserID($UserEmail){
        $FindUserID_Data=new DataBase();
        $FindUserID_Sql="SELECT UserID FROM USERS WHERE Email=?";
        $WillPrepare_FindUserID_Sql=$FindUserID_Data->myprepare($FindUserID_Sql);
        $WillPrepare_FindUserID_Sql->bind_param("s",$UserEmail);
        $WillPrepare_FindUserID_Sql->execute();
        $FindUserID_SqlResult=$WillPrepare_FindUserID_Sql->get_result();
        $SELECTed_UserID=$FindUserID_SqlResult->fetch_assoc()["UserID"];
        /* نکته بسیار مهم درباره فچ اسو این است که این تابع به ما یک اسوشی اتیو ارای برمیگرداند
        و اینکه باید حتما چیزی را که داخل آرایه فراخوانی مینی قبلتر در کد اس کیو ال که به دیتابیس ارسال میکنید درخواست کرده باشی */
        
        // echo "<br>".($SELECTed_UserID["UserID"]);
        
        return $SELECTed_UserID;
    }
    public function FetchUserInfo($UserEmail){
        $FetchUserInfo_Data=new DataBase();
        $FetchUserInfo_sql="SELECT UserName , Create_date FROM USERS WHERE Email=?";
        $WillPrepare_FetchUserInfo_sql=$FetchUserInfo_Data->myprepare($FetchUserInfo_sql);
        $WillPrepare_FetchUserInfo_sql->bind_param("s",$UserEmail);
        $WillPrepare_FetchUserInfo_sql->execute();
        $FetchUserInfo_sqlResult=$WillPrepare_FetchUserInfo_sql->get_result();
        $UserInfo=$FetchUserInfo_sqlResult->fetch_assoc();
        return $UserInfo;
        //این تابع نام کاربری و تاریخ عضویت را میدهد
        //دقت کن که تو در خروجی این تابع فقط یک متغییر برگردوندی
        //پس در صفحه پروفایل که میخواهی ازش استفاده کنی هم اول بریز تو یک متغییر آرایه ای
    }
    public static function FetchUserInfoBYID($UserID){
        $FetchUserInfo_Data=new DataBase();
        $FetchUserInfo_sql="SELECT UserName ,Email ,Create_date,Phone,Role FROM USERS WHERE UserID=?";
        $WillPrepare_FetchUserInfo_sql=$FetchUserInfo_Data->myprepare($FetchUserInfo_sql);
        $WillPrepare_FetchUserInfo_sql->bind_param("i",$UserID);
        $WillPrepare_FetchUserInfo_sql->execute();
        $FetchUserInfo_sqlResult=$WillPrepare_FetchUserInfo_sql->get_result();
        $UserInfo=$FetchUserInfo_sqlResult->fetch_assoc();
        return $UserInfo;
        //این تابع نام کاربری و تاریخ عضویت را میدهد
        //دقت کن که تو در خروجی این تابع فقط یک متغییر برگردوندی
        //پس در صفحه هرجا که میخواهی ازش استفاده کنی هم اول بریز تو یک متغییر آرایه ای
    }
}

?>