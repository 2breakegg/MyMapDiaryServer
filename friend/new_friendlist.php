<?php

// 返回的 status 1已申请 2被拒绝  -1被申请  -2拒绝  0没记录

    require "../conn.php";

    $userid=$_POST["userid"];
    $message=array();

    $mysql_qry="SELECT id AS userid,username,status FROM user,friend WHERE friend.userid = user.Id AND friendid=$userid AND (status=0 OR status=2)";
    $mysql_qry2=" SELECT id AS userid,username,status FROM user,friend WHERE friend.friendid = user.Id AND userid=$userid AND (status=0 OR status=2)";
    $result = mysqli_query($conn,$mysql_qry);
    $rows=mysqli_num_rows($result);

    while ($row = mysqli_fetch_assoc($result)) {
        if($row["status"]==0){
            $row["status"]="-1";
        }else if($row["status"]==2){
            $row["status"]="-2";
        }

        $results[] = $row;
    }

    $result = mysqli_query($conn,$mysql_qry2);
    $rows=mysqli_num_rows($result);

    while ($row = mysqli_fetch_assoc($result)) {
        if($row["status"]==0){
            $row["status"]="1";
        }
        $results[] = $row;
    }

    if(!isset($results)){
        $results[]=["userid"=>"-1","username"=>"没有记录","status"=>"0"];
    }

    $message['status']=1;
    $message['data']=$results;

    echo json_encode($message);
?>