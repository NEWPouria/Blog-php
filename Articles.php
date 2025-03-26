<?php
// include(__DIR__."/DataBase.php");
require_once 'Database.php';
class Articles{
    private $ID;
    private $AutherID;
    private $Title;
    private $MetaTitel;
    private $ArticleText;
    private $ArticleDate;
    //private $ArticleImage; //هنوز برنامه نویسی نشده
    public function Create($AutherID,$Title,$MetaTitel,$ArticleText){
        //نکته مهم//take $AutherID from $_SESSION["USER"] by searching database by user email and find UserID
        $this->AutherID=$AutherID;
        $this->Title= $Title;
        $this->MetaTitel=$MetaTitel;
        $this->ArticleText=$ArticleText;
        //انتقال اطلاعات بصورت کنترل شده به دیتابیس
        $Data=new DataBase();
        $ArticleCreateSqlCode="INSERT INTO Article(AutherID,Title,MetaTitel,ArticleText) VALUES (?,?,?,?) ";
        $WillPrepareArticleCreateSqlCode=$Data->myprepare($ArticleCreateSqlCode);
        $WillPrepareArticleCreateSqlCode->bind_param("isss",$this->AutherID,$this->Title,$this->MetaTitel,$this->ArticleText) ;
        $WillPrepareArticleCreateSqlCode->execute() ;
        
        $CreatedArticleID=$WillPrepareArticleCreateSqlCode->insert_id;
        $WillPrepareArticleCreateSqlCode->close() ;
        $Data->myclose();
        return $CreatedArticleID;

    }
    public static function ShowAllUserArticles($UserID){
        
        $Data=new DataBase();   
        $ShowAllUserArticlesSqlCode="SELECT * FROM Article WHERE AutherID=? ORDER BY ArticleDate DESC;";
        $WillPrepareUserArticles=$Data->myprepare($ShowAllUserArticlesSqlCode);
        $WillPrepareUserArticles->bind_param("i",$UserID);
        $WillPrepareUserArticles->execute();
        $sqlResult=$WillPrepareUserArticles->get_result();
        

        $articles=[];
        while($row=$sqlResult->fetch_assoc()){
            $articles[]=$row;
        }
        return $articles;
        $Data->myclose();
    }
}




?>