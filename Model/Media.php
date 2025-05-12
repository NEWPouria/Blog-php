<?php
class Media
{
    private $ID;
    private $UserID;
    private $Path;
    private $Category; /* 'profile','banner','article','comment' */
    private $Name;
    private $Alt;
    private $CrearedDate;
    // TXTID= آیدی پست یا کامنتی که مدی باری آن بارگذاری میشود
    public function AddMedia($Entered_UserID, $Entered_Category, $InputFileTemp, $InputFileType, $TXTID = 0, $Entered_Alt = "Not Provided")
    {
        $this->UserID = $Entered_UserID;
        $this->Category = $Entered_Category;
        $this->Alt = $Entered_Alt;
        $this->Name = $this->UserID . "'s " . $this->Category;
        $Referer = "";
        // TXTID is refered to ArticleID and CommentID That We will Take it From the Form 

        // در فرانت هرجا که میخواهیم فایل آپلود کنیم نام فایل رو برابر با یکی از کتگوری ها قرار دهیم
        $TargestPath = "uploads/$this->UserID/$this->Category/";
        // $this->Path = $TargestPath . basename(date("y-m-d-h-i-s"));
        $isUploadFINE = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $Referer = $_SERVER['HTTP_REFERER'];

            $FileType = explode("/", $InputFileType)[0];
            $FileFormat = explode("/", $InputFileType)[1];
            // $this->Path = $TargestPath . basename(date("y-m-d-h-i-s")) . "." . $FileFormat;
            $this->Path = $TargestPath . basename(date("y-m-d-h-i-s"))."_". uniqid() . "." . $FileFormat;
            if ($FileType == "image" or $FileType == "video") {
                $isUploadFINE = true;
            } else {
                echo "<script>alert('Please Just Insert Image OR Video');</script>";
                $isUploadFINE = false;
            }
            if (!is_dir($TargestPath)) {
                if (!mkdir($TargestPath, 0777, true)) {
                    echo "<script>alert('COUTION: Target Folder Couldnt Find or Make !!!');</script>";
                    $isUploadFINE = false;
                }
            }
            if (file_exists($this->Path)) {
                echo "<script>alert('This File is Existed in The Directory');</script>";
                $isUploadFINE = false;
            }
            if ($isUploadFINE == false) {
                echo "<script>alert('File is not Uploaded')</script>";
            } else {
                if (move_uploaded_file($InputFileTemp, $this->Path)) { //ایرادی داشتیم که وقتی عکس به فولدر منتقل میشد فرمت دوباره نوشته میشد که آن را حل کردیم

                    // ارسال اطلاعات فایل به جدول Media
                    require_once "DataBase.php";
                    $MediaDB = new DataBase();
                    echo "pspsps";
                    var_dump($this->Category);
                    // echo "<script>alert('ooooooooooooooooooo       " . $bb . "')</script>";
                    // var_dump($this->Category);
                    $MediaInstertSql = "INSERT INTO Media(MediaUserID,MediaPath,MediaCategory,MediaName,MediaAlt) VALUES (?,?,?,?,?)";
                    $WillPrepareMedia = $MediaDB->myprepare($MediaInstertSql);
                    $WillPrepareMedia->bind_param("issss", $this->UserID, $this->Path, $this->Category, $this->Name, $this->Alt);
                    if ($WillPrepareMedia->execute()) {
                        echo "<script>alert('File Uploaded Successfully')</script>";

                        $insertedMediaID = $WillPrepareMedia->insert_id;


                        if ($this->Category === 'profile') {    /* ایجاد اتصال بین جدول پروفایل و جدول مدیا */
                            $sql = "INSERT INTO UserProfiles(UserID,ProfilePicID) VALUES (?,?);";
                            $stmt = $MediaDB->myprepare($sql);
                            $stmt->bind_param("ii", $this->UserID, $insertedMediaID);

                            if ($stmt->execute()) {
                                echo "<script>
                            alert('Your Profile Have Saved In The DataBase.');
                            window.location.href='$Referer';  //ریدایرکت با جاوااسکریپت
                            </script>";
                                // ریفرر بالا تر در خط 22 مقداردهی شده
                                // header('Location:'. $FormAdress);
                            } else {
                                echo "<script>alert('Your Profile Have Not Saved In The DataBase." . $stmt->error . "')</script>";
                            }

                        } elseif ($this->Category === 'banner') { /* ایجاد اتصال بین جدول بنر و جدول مدیا */
                            $sql = "INSERT INTO UserProfiles(UserID,BannerPicID) VALUES (?,?);";
                            $stmt = $MediaDB->myprepare($sql);
                            $stmt->bind_param("ii", $this->UserID, $insertedMediaID);

                            if ($stmt->execute()) {
                                echo "<script>
                                alert('Your Banner Have Saved In The DataBase.');
                                // window.location.href='$Referer';  //ریدایرکت با جاوااسکریپت
                                </script>";
                            } else {
                                echo "<script>alert('Your Banner Have Not Saved In The DataBase." . $stmt->error . "')</script>";
                            }

                        } elseif ($this->Category === 'post') { /* ایجاد اتصال بین جدول مقالات و جدول مدیامقالات */
                            $sql = "INSERT INTO ArticleMedia(ArticleID,MediaID) VALUES (?,?);";
                            $stmt = $MediaDB->myprepare($sql);
                            $stmt->bind_param("ii", $TXTID, $insertedMediaID);

                            if ($stmt->execute()) {
                                echo "<script>
                                alert('Your Article Media Have Saved In The DataBase.');
                                window.location.href='$Referer';  //ریدایرکت با جاوااسکریپت
                                </script>";
                            } else {
                                echo "<script>alert('Your Article Media Have Not Saved In The DataBase." . $stmt->error . "')</script>";
                            }

                        } elseif ($this->Category === 'comment') {/* ایجاد اتصال بین جدول کامنت و جدول مدیاکامنت ها */
                            $sql = "INSERT INTO CommentMedia(CommentID,MediaID) VALUES (?,?);";
                            $stmt = $MediaDB->myprepare($sql);
                            $stmt->bind_param("ii", $TXTID, $insertedMediaID);

                            if ($stmt->execute()) {
                                echo "<script>
                                alert('Your Comment Media Have Saved In The DataBase.');
                                window.location.href='$Referer';  //ریدایرکت با جاوااسکریپت
                                </script>";
                            } else {
                                echo "<script>alert('Your Comment Media Have Not Saved In The DataBase." . $stmt->error . "')</script>";
                            }
                        }
                        /* دقت شود که این پایان ارسال اطلاعات به جدول مادر یعنی مدیا است  */
                    } else {
                        echo "<script>alert('Upload Failed: " . $WillPrepareMedia->error . "')</script>";
                    }
                    $WillPrepareMedia->close();


                } else {
                    echo "<script>alert('The Uploading Procces had issue')</script>";
                }
            }
        }
    }
    /**
     * Summary of GetArticleMediaID
     * دقت شود که این تابع آرایه برمیگرداند و بهتر است درجایی که از آن استفاده میشود 
     * foreach استفاده کنید
     * @param mixed $ArticleID
     * @return array
     */
    public static function GetArticleMediaID($ArticleID)
    {
        $GetArticleMediaID_Data = new DataBase();
        $GetArticleMediaID_sql = "SELECT MediaID From ArticleMedia WHERE ArticleID = ?";
        $WillPrepare_GetArticleMediaID_sql = $GetArticleMediaID_Data->myprepare($GetArticleMediaID_sql);
        $WillPrepare_GetArticleMediaID_sql->bind_param("i", $ArticleID);
        $WillPrepare_GetArticleMediaID_sql->execute();
        $GetArticleMediaID_sqlResult = $WillPrepare_GetArticleMediaID_sql->get_result();
        if ($GetArticleMediaID_sqlResult->num_rows > 0) {
            $MediaIDs=array();
            // $ArticleMediaID = $GetArticleMediaID_sqlResult->fetch_assoc();// returns single result
            // return $ArticleMediaID;
            while($row=$GetArticleMediaID_sqlResult->fetch_assoc()){
                $MediaIDs[] =$row['MediaID'];
            }
            return $MediaIDs;
        } else {
            echo "no media .... (Media.php L151 )<br>";
        }

        $GetArticleMediaID_Data->myclose();
    }

    public static function GetCommentMediaID($CommentID){
        $GetCommentMediaID_Data=new DataBase();
        $GetCommentMediaID_sql="SELECT * FROM commentmedia WHERE CommentID=?;";
        $WillPrepare_GetCommentMediaID_sql=$GetCommentMediaID_Data->myprepare($GetCommentMediaID_sql);
        $WillPrepare_GetCommentMediaID_sql->bind_param("i",$CommentID);
        $WillPrepare_GetCommentMediaID_sql->execute();
        $GetCommentMediaID_sqlResult=$WillPrepare_GetCommentMediaID_sql->get_result();
        if($GetCommentMediaID_sqlResult->num_rows>0){
            $MediaID=array();
            while($row=$GetCommentMediaID_sqlResult->fetch_assoc()){
                $MediaID[]=$row['MediaID'];
            }
            return $MediaID;
        }else{
            echo "no media .... (Media.php L184 )<br>";
        }
        $GetCommentMediaID_Data->myclose();
    }

    /**
     * تمام اطلاعات مربوط به آیدی داده شده را از جدول مدیا برمیگرداند
     * برای درک بهتر دیتابیس را چک کنید
     * @param mixed $MediaID
     * @return array|bool|null
     */
    public static function MediaInfo($MediaID)
    {
        $MediaInfo_Data = new DataBase();
        $MediaInfo_sql = "SELECT * From Media WHERE MediaID=?";
        $WillPrepare_MediaID_sql = $MediaInfo_Data->myprepare($MediaInfo_sql);
        $WillPrepare_MediaID_sql->bind_param("i", $MediaID);
        $WillPrepare_MediaID_sql->execute();
        $MediaID_sqlResult = $WillPrepare_MediaID_sql->get_result();
        $MediaInfo = $MediaID_sqlResult->fetch_assoc();

        // echo ('Media.php  L161');
        // print_r($MediaInfo);
        // echo ('Media.php  L163');
        return $MediaInfo;
        $MediaInfo_Data->myclose();
    }

}

?>