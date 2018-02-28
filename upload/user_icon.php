<?php
  // $user_id=$_POST['user_id'];
  echo "===========111==========<br/>\n";
  
  $request_body = file_get_contents('php://input');
  //$data = json_decode($request_body);
  echo $request_body;
  echo "=========222============\n";
  foreach (getallheaders() as $name => $value) {
    echo "$name: $value\n";
  }
  $name = $_FILES['icon']['name'];
  $tmp = $_FILES['icon']['tmp_name'];
  $filepath = $_SERVER['DOCUMENT_ROOT'].'/usericon/';
  if(move_uploaded_file($tmp,$filepath.$name.".png")){
    echo "上传成功";
  }else{
    echo "上传失败";
  }
?>