<?php
//-1 没好友
//-2 申请好友
//-3 申请记录
    require "../conn.php";

    $userid=$_POST["userid"];
    $message=array();

    $mysql_qry="SELECT id AS userid,username FROM user where Id in (SELECT friendid FROM friend WHERE userid=$userid AND status=1 UNION ALL SELECT userid FROM friend WHERE friendid=$userid AND status=1)";
    $result = mysqli_query($conn,$mysql_qry);
    $rows=mysqli_num_rows($result);

    if($rows==0){
        $message['status']=1;
        $message['data']=[["userid"=>"-1","username"=>"没有朋友"]];
        echo json_encode($message);
        die();
    }

    while ($row = mysqli_fetch_assoc($result)) {
        // echo var_dump($row);
        // echo var_dump($results);
        $results[] = $row;
    }

    $message['status']=1;
    $message['data']=$results;

    echo json_encode($message);
?>