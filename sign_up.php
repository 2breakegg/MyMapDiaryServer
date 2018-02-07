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

    //查看用户名是否被注册   
    $mysql_qry="select * from $table where username = '$username'";
    $result = mysqli_query($GLOBALS['conn'],$mysql_qry);
    $b=mysqli_num_rows($result);
    if($b>0){
      $message['status']=0;
      $message['text']='该用户名已被注册';
      echo json_encode($message);
      die;
    }

    $password=md5($password);
    $mysql_qry="insert into $table (username,password) values('$username','$password')";
    $result = mysqli_query($GLOBALS['conn'],$mysql_qry);
    
    
    // var_dump($result);
    if($result){
      //注册成功 返回 id username...
      $mysql_qry="select * from $table where username = '$username';";
      $result = mysqli_query($GLOBALS['conn'],$mysql_qry);
      $b=mysqli_num_rows($result);
      $row = mysqli_fetch_assoc($result);
      
      $message['status']=1;
      $message['text']='注册成功';
      $message['user_id']=$row['Id'];
      $message['user_name']=$row['username'];
    }else{
      $message['status']=0;
      $message['text']='注册失败,数据库错误';
    }
    echo json_encode($message);    
  }
  a();

  
  
?>