<?php
class DataBase
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "MyBlog";
    private $DBconnection;
    public function __construct()
    {
        $this->DBconnection = new mysqli(
            $this->servername,
            $this->username,
            $this->password
        );

        if ($this->DBconnection->connect_error) {
            die("Data Base Connection Failed." . $this->DBconnection->connect_error);
        }
        // ایجاد دیتابیس در صورت عدم وجود
        $CreateDB_SQLcode =
            "CREATE DATABASE IF NOT EXISTS MyBlog;";
        if ($this->DBconnection->query($CreateDB_SQLcode) === TRUE) {
            // echo "Database created successfully";
        } else {
            echo "Error creating database: " . $this->DBconnection->error;
        }

        // انتخاب دیتابیس
        $this->DBconnection->select_db($this->dbname);

        // ایجاد جداول
        $CreateDB_SQLcode_USERS =
            "CREATE TABLE if NOT exists USERS (
            UserID int not null AUTO_INCREMENT ,
            UserName varchar(255) not null,
            PASSWORD varchar(255),
            Email varchar(100),
            Create_date timestamp DEFAULT CURRENT_TIMESTAMP,
            Phone varchar(11),
            Role int(1),
            Profile varchar(255),
            PRIMARY KEY (UserID));";
        $CreateDB_SQLcode_ARTICLE =
            "CREATE TABLE IF NOT EXISTS Article(
            ArticleID int not null AUTO_INCREMENT,
            AutherID int,
            Title varchar(255),
            MetaTitel varchar(255),
            ArticleText text,
            ArticleDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ArticleImage varchar(255),
            PRIMARY KEY (ArticleID),
            FOREIGN KEY (AutherID) REFERENCES USERS (UserID));";
        $CreateDB_SQLcode_Comment =
            "CREATE TABLE IF NOT EXISTS Comment (
            CommentID int not null AUTO_INCREMENT,
            Rate int(1),
            CommentArticleID int,
            CommentDate timestamp DEFAULT CURRENT_TIMESTAMP,
            CommentUserID int,
            CommentText text,
            CommentReplyID int,
            PRIMARY KEY (CommentID),
            FOREIGN KEY (CommentArticleID) REFERENCES Article(ArticleID),
            FOREIGN KEY (CommentUserID) REFERENCES users(UserID),
            FOREIGN KEY (CommentReplyID) REFERENCES Comment(CommentID));";
        $CreateDB_SQLcode_Media =
            "CREATE TABLE IF NOT EXISTS Media (
            MediaID int not null AUTO_INCREMENT,
            MediaUserID int not null,
            MediaPath VARCHAR(255) not null,
            MediaCategory ENUM('profile','banner','post','comment') not null,
            MediaName varchar(255),
            MediaAlt varchar(255),
            MediaCreatedDate timestamp DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (MediaID),
            FOREIGN KEY (MediaUserID) REFERENCES USERS(UserID));";
        $CreateDB_SQLcode_UserProfile =
            "CREATE TABLE IF NOT EXISTS UserProfiles(
            UserProfileID int not null AUTO_INCREMENT PRIMARY KEY,
            UserID int not null,
            ProfilePicID int DEFAULT NULL,
            BannerPicID int DEFAULT NULL,
            FOREIGN KEY (UserID) REFERENCES USERS(UserID) ON DELETE CASCADE,
            FOREIGN KEY (ProfilePicID) REFERENCES Media(MediaID) ON DELETE SET NULL,
            FOREIGN KEY (BannerPicID) REFERENCES Media(MediaID) ON DELETE SET NULL);";
        $CreateDB_SQLcode_ArticleMedia =
            "CREATE TABLE IF NOT EXISTS ArticleMedia (
            ArticleMediaID INT AUTO_INCREMENT PRIMARY KEY,
            ArticleID INT NOT NULL,
            MediaID INT NOT NULL,
            FOREIGN KEY (ArticleID) REFERENCES Article(ArticleID) ON DELETE CASCADE,
            FOREIGN KEY (MediaID) REFERENCES Media(MediaID) ON DELETE CASCADE);";
        $CreateDB_SQLcode_CommentMedia =
            "CREATE TABLE IF NOT EXISTS CommentMedia (
            CommentMediaID INT AUTO_INCREMENT PRIMARY KEY,
            CommentID INT NOT NULL,
            MediaID INT NOT NULL,
            FOREIGN KEY (CommentID) REFERENCES Comment(CommentID) ON DELETE CASCADE,
            FOREIGN KEY (MediaID) REFERENCES Media(MediaID) ON DELETE CASCADE);";    
        $CreateDB_SQLcode_Follows =
        "CREATE TABLE IF NOT EXISTS Follows (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        User_id int UNSIGNED NOT NULL,
        Follower_User_id int UNSIGNED NOT NULL,
        FollowDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY (User_id, Follows_User_id),
        
        FOREIGN KEY (User_id) REFERENCES USERS(UserID) ON DELETE CASCADE,
        FOREIGN KEY (Follows_User_id) REFERENCES USERS(UserID) ON DELETE CASCADE;";

        // if( $this->DBconnection->query($CreateDB_SQLcode_USERS)===TRUE&& $this->DBconnection->query($CreateDB_SQLcode_ARTICLE)===TRUE&& $this->DBconnection->query($CreateDB_SQLcode_Comment===TRUE)&& $this->DBconnection->query($CreateDB_SQLcode_Files)===TRUE){
        //     echo "Tables created successfully"; 
        // } else { 
        //     echo "Error creating tables: " . $this->DBconnection->error;
        // }
        // $this->DBconnection->close();

        $this->DBconnection->query($CreateDB_SQLcode_USERS);
        $this->DBconnection->query($CreateDB_SQLcode_ARTICLE);
        $this->DBconnection->query($CreateDB_SQLcode_Comment);
        $this->DBconnection->query($CreateDB_SQLcode_Media);

        $this->DBconnection->query($CreateDB_SQLcode_UserProfile);
        $this->DBconnection->query($CreateDB_SQLcode_ArticleMedia);
        $this->DBconnection->query($CreateDB_SQLcode_CommentMedia);
        $this->DBconnection->query($CreateDB_SQLcode_Follows);
    }
    public function myprepare($myquery)
    {
        return $this->DBconnection->prepare($myquery);
    }

    public function myquery($sqlcode)
    {
        return $this->DBconnection->query($sqlcode);
    }
    public function myclose()
    {
        $this->DBconnection->close();
    }
    // public function mygetresult(){
    //     $this->DBconnection->get_result();
    // }
}
?>