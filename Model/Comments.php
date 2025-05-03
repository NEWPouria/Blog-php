<?php
require_once 'DataBase.php';
Class Comments{
    private $ID;
    private $Rate;
    private $ArticleID;
    private $Text;
    private $UserID;

    public function Create( $UserID, $ArticleID, $Text,$Rate=0){
        $this->Rate = $Rate;
        $this->ArticleID = $ArticleID;
        $this->Text = $Text;
        $this->UserID = $UserID;

        $Data=new DataBase();
        $CommentCreateSqlCode="INSERT INTO Comment(Rate,CommentArticleID,CommentText,CommentUserID) VALUES (?,?,?,?)";
        $WillPrepareCommentCreateSqlCode=$Data->myprepare($CommentCreateSqlCode);
        $WillPrepareCommentCreateSqlCode->bind_param("iisi",$this->Rate,$this->ArticleID,$this->Text,$this->UserID);
        $WillPrepareCommentCreateSqlCode->execute();
        $Data->myclose();
    }

    public function Read($ArticleID){
        $Data=new DataBase();
        $CommentReadSqlCode="SELECT * FROM Comment WHERE CommentArticleID=? ORDER BY CommentDate DESC;";
        $WillPrepareCommentReadSqlCode=$Data->myprepare($CommentReadSqlCode);
        $WillPrepareCommentReadSqlCode->bind_param("i",$ArticleID);
        $WillPrepareCommentReadSqlCode->execute();
        $sqlResult=$WillPrepareCommentReadSqlCode->get_result();
        $Comments=[];
        while($row=$sqlResult->fetch_assoc()){
            $Comments[]=$row;
        }
        return $Comments;
        $Data->myclose();
    }

}

?>