<?php
    require "../conn.php";

    $type=$_POST["type"];
    $userid=$_POST["userid"];
    $message=array();

    $mysql_qry="SELECT id,username FROM user where Id in (SELECT friendid FROM friend WHERE userid=$userid AND status=1 UNION ALL SELECT userid FROM friend WHERE friendid=$userid AND status=1)";
    $result = mysqli_query($conn,$mysql_qry);
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }

    $message=array();
    $message['status']=1;
    $message['data']=$results;

    echo json_encode($message);
?>