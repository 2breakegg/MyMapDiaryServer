<?php
  require "conn.php";
  
  $userid=$_POST["userid"];
  $start_date=$_POST["start_date"];
  $end_date=$_POST["end_date"];

  
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
  $message['status']=1;
  $message['data']=$results;

  echo json_encode($message);
?>