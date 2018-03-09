<?php
    // data{userid,lat,lon,date}
    require "../conn.php";

    //$userid=$_POST["userid"];
    $userid=$_GET["userid"];
    $userid=44;
    $message=array();

    // $mysql_qry="SELECT *,MAX(date) FROM positionlog WHERE userid in( "
    //     ."SELECT userid FROM friend WHERE friendid=1 UNION ALL "
    //     ."SELECT friendid FROM friend WHERE userid=1 "
    //     .")group by userid";
    $mysql_qry=<<<mysql_qry
    SELECT *,MAX(date) FROM positionlog WHERE userid in( 
    SELECT userid FROM friend WHERE friendid=$userid UNION ALL 
    SELECT friendid FROM friend WHERE userid=$userid 
    )group by userid
mysql_qry;

    $result = mysqli_query($conn,$mysql_qry);
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }