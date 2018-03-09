<?php
  require "conn.php";
  // $_POST["type"]  
    //==some_one_position 获取某人摸时间段的位置   
    //==friend_last_position 获取朋友的当前位置(就是最后上传的位置)
  $type=$_POST["type"];
  $userid=$_POST["userid"];


  if($type=="some_one_position"){
    $start_date=$_POST["start_date"];
    $end_date=$_POST["end_date"];
    someOnePosition($conn,$userid,$start_date,$end_date);
  }else if($type=="friend_last_position"){
    friendLastPosition($conn,$userid);
  }

  function someOnePosition($conn,$userid,$start_date,$end_date){    
    // $mysql_qry="SELECT * FROM positionlog WHERE userid=$userid";
    $mysql_qry="SELECT UNIX_TIMESTAMP(date)as date,lat,lon,userid FROM positionlog WHERE userid=$userid AND UNIX_TIMESTAMP(date) BETWEEN $start_date AND $end_date";
    $result = mysqli_query($conn,$mysql_qry);

    // $str=("lat:".(31+0.02)."lon:".(121.76+0.02)."date: 12:00");
    if($result) 
      $results = array();
    while ($row = mysqli_fetch_assoc($result)) {
      $results[] = $row;
    }

    $message=array();
    if(isset($results)){
      $message['status']=1;
      $message['data']=$results;
    }else{
      $message['status']=1;
      $message['data']=[];
    }

    echo json_encode($message);
  }

  function friendLastPosition($conn,$userid){
    $mysql_qry=<<<mysql_qry
      SELECT lat,lon,userid,MAX(UNIX_TIMESTAMP(date)) AS date FROM positionlog WHERE userid in( 
      SELECT userid FROM friend WHERE friendid=$userid UNION ALL 
      SELECT friendid FROM friend WHERE userid=$userid 
      )group by userid
mysql_qry;

    $result = mysqli_query($conn,$mysql_qry);
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }
    $message=array();
    if(isset($results)){
      $message['status']=1;
      $message['data']=$results;
    }else{
      $message['status']=1;
      $message['data']=[];
    }

    echo json_encode($message);
  }
?>