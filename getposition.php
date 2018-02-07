<?php
  require "conn.php";
  
  $userid=$_POST["userid"];
  $mysql_qry="SELECT * FROM positionlog WHERE userid=$userid";
  // var_dump
  $result = mysqli_query($conn,$mysql_qry);

  // $str=("lat:".(31+0.02)."lon:".(121.76+0.02)."time:12:00");
  if($result) $results = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $results[] = $row;
  }
    
    // 将数组转成json格式
  echo json_encode($results);


  // echo $str;
?>