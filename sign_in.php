<?php
  require "conn.php";
  
  function a(){
    //获取用户名密码,判断是否为空
    $username=$_POST["username"];
    $password=$_POST["password"];
    $table="user";
    $message=array();

    if($username==false || $password==false || $username=='' || $password==''){
      $message['status']=0;
      $message['text']='用户名或密码不能为空';
      echo json_encode($message);
      die;
    }
    
    $password=md5($password);
    $mysql_qry="select * from $table where username = '$username' and password = '$password';";
    //执行语句账号密码匹配
    $result = mysqli_query($GLOBALS['conn'],$mysql_qry);
    $b=mysqli_num_rows($result);

    if($b>1){
      //多个账号密码匹配
      $message['status']=0;
      $message['text']='数据库出错,请告诉我(开发者)';
    }else if($b==1){
      //登录成功
      $row = mysqli_fetch_assoc($result);
      $message['status']=1;
      $message['text']='登录成功';
      $message['user_id']=$row['Id'];
      $message['user_name']=$row['username'];
    }else{
      //账户或密码错误
      $message['status']=0;
      $message['text']='账号或密码错误';
    }
    echo json_encode($message);
  }
  a();
?>