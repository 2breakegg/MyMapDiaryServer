<?php
  $apkname = $_FILES['apk']['name'];
  $tmp = $_FILES['apk']['tmp_name'];
  $filepath = $_SERVER['DOCUMENT_ROOT'].'/apks/';
  if(move_uploaded_file($tmp,$filepath.$apkname.".apk")){
      echo "上传成功";
  }else{
      echo "上传失败";
  }
?>