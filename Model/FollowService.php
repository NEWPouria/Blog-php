<?php
require_once "DataBase.php";
require_once "Users.php";

class FollowService
{
    private $id;
    private $UserID;
    private $FollowerUserID;
    private $FollowDate;

    public function Follow($UserID, $FollowerUserID)
    {
        if ($UserID == $FollowerUserID) {
            return false;
        }

        $this->UserID = $UserID;
        $this->FollowerUserID = $FollowerUserID;

        $data = new DataBase();
        $FollowInsertSql = "INSERT IGNORE INTO Follows (User_id, Follower_User_id) VALUES (?,?)";
        $WillPrepareFollow = $data->myprepare($FollowInsertSql);
        $WillPrepareFollow->bind_param("ii", $this->UserID, $this->FollowerUserID);
        $WillPrepareFollow->execute();
        $WillPrepareFollow->close();

        $data->myclose();
        // echo json_encode(["FollowService.php L29"]);
        // return true;
        error_log("FollowService.php: Follow operation executed for UserID=$UserID");
        return true;
    }
    public function UnFollow($UserID, $FollowerUserID)
    {
        if ($UserID == $FollowerUserID) {
            return false;
        }
        $this->UserID = $UserID;
        $this->FollowerUserID = $FollowerUserID;
        $data = new DataBase();
        $UnFollowSql = "DELETE FROM Follows WHERE User_id=? AND Follower_User_id=?";
        $WillPrepareUnFollow = $data->myprepare($UnFollowSql);
        $WillPrepareUnFollow->bind_param("ii", $this->UserID, $this->FollowerUserID);
        $WillPrepareUnFollow->execute();
        $data->myclose();
        return true;

    }
    public static function FollowingCount($UserID)
    {
        
        $data = new DataBase();
        $FollowingCountSql = "SELECT COUNT(*) as FollowingCount FROM Follows WHERE Follower_User_id=?";
        $WillPrepareFollowingCount = $data->myprepare($FollowingCountSql);
        $WillPrepareFollowingCount->bind_param("i", $UserID);
        $WillPrepareFollowingCount->execute();
        $result = $WillPrepareFollowingCount->get_result();
        $row = $result->fetch_assoc();
        return $row['FollowingCount'];
    }
    public static function FollowerCount($UserID)
    {
        
        $data = new DataBase();
        $FollowerCountSql = "SELECT COUNT(*) as FollowerCount FROM Follows WHERE User_id=?";
        $WillPrepareFollowerCount = $data->myprepare($FollowerCountSql);
        $WillPrepareFollowerCount->bind_param("i", $UserID);
        $WillPrepareFollowerCount->execute();
        $result = $WillPrepareFollowerCount->get_result();
        $row = $result->fetch_assoc();
        return $row['FollowerCount'];
    }
    public function MutualFollow($LoggedInUserID, $PageOwnerUserID)
    {
        $data = new DataBase();
        $MutualFollowSQL = "SELECT COUNT(*) FROM Follows WHERE (User_id=? AND Follower_User_id=?) OR (User_id=? AND Follower_User_id=?)";
        $WillPrepareMutualFollow = $data->myprepare($MutualFollowSQL);
        $WillPrepareMutualFollow->bind_param("iiii", $LoggedInUserID, $PageOwnerUserID, $PageOwnerUserID, $LoggedInUserID);
        $WillPrepareMutualFollow->execute();
        $result = $WillPrepareMutualFollow->get_result();
        $row = $result->fetch_assoc();
        if ($row['COUNT(*)'] == 1) {
            return true;
        } else {
            return false;
        }
    }
    public function isFollowing( $PageOwnerUserID,$LoggedInUserID)
    {
        $data = new DataBase();
        $isFollowingSQL = "SELECT COUNT(*) FROM Follows WHERE User_id=? AND Follower_User_id=?";
        $WillPrepareisFollowing = $data->myprepare($isFollowingSQL);
        $WillPrepareisFollowing->bind_param("ii", $PageOwnerUserID, $LoggedInUserID);
        $WillPrepareisFollowing->execute();
        $result = $WillPrepareisFollowing->get_result();
        $row = $result->fetch_assoc();
        if ($row['COUNT(*)'] == 1) {
            return true;
        } else {
            return false;
        }
    }
}
?>