<?php
  require "conn.php";
  
  
  $userid=$_POST["userid"];
  $lat=$_POST["lat"];
  $lon=$_POST["lon"];

  if($userid==-1) 
    die();
  
  
  $mysql_qry="INSERT INTO positionlog VALUES($lat,$lon,NOW(),$userid)";
  // var_dump
  $result = mysqli_query($conn,$mysql_qry);

  // $str=("lat:".(31+0.02)."lon:".(121.76+0.02)."time:12:00");
  if($result)
    echo "success";
  
  // echo $str;
?>