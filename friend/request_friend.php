<?php
  require "../conn.php";

  //$_POST["type"] request:好友申请 response:回复申请

  //type=request 下有$_POST["friendname"];
  //type=response 下有$_POST["friendid"]; $_POST["response"];
  //$_POST["response"] 1:接受好友  2:拒绝好友
  $type=$_POST["type"];
  $userid=$_POST["userid"];
  $message=array();

  if($userid==-1){
    $message['status']=0;
    $message['text']='请先登录';
    echo json_encode($message);
    die();
  }

  if($type=="request" && $userid){

    //先判断是否存在用户
    $friendname=$_POST["friendname"];    

    $mysql_qry="SELECT * FROM user WHERE username='$friendname'";
    // var_dump($mysql_qry);
    $result = mysqli_query($conn,$mysql_qry);
    // var_dump($result);
    $rows=mysqli_num_rows($result);
  
    if($rows==0){
      $message['status']=0;
      $message['text']='用户不存在';
      echo json_encode($message);
      die();
    }

    while ($row = mysqli_fetch_assoc($result)) {
       $friendid = $row["Id"];
    }

    //如果是申请好友,先判断下是否已申请过
    
    $mysql_qry="SELECT * FROM friend WHERE userid=$userid AND friendid=$friendid AND status!=2"
      ." UNION ALL SELECT * FROM friend WHERE userid=$friendid AND friendid=$userid AND status!=2";
    $result = mysqli_query($conn,$mysql_qry);
    $rows=mysqli_num_rows($result);

    if($rows>0){
      //已申请
      $message['status']=0;
      $message['text']='已经申请或已成为好友';
      echo json_encode($message);
      die();
    }else{
      //判断是否是拒绝好友 拒绝的话update记录的status=0 ,否则insert一条记录
      $mysql_qry="SELECT * FROM friend WHERE userid=$userid AND friendid=$friendid AND status=2"
        ." UNION ALL SELECT * FROM friend WHERE userid=$friendid AND friendid=$userid AND status=2";
      $result = mysqli_query($conn,$mysql_qry);
      $rows=mysqli_num_rows($result);
      if($rows>0){
        $mysql_qry="UPDATE friend SET status=0 WHERE (userid=$userid AND friendid=$friendid) OR (userid=$friendid AND friendid=$userid)";
        $result = mysqli_query($conn,$mysql_qry);
      }else{
        $mysql_qry="INSERT INTO friend VALUE($userid,$friendid,0)";
        $result = mysqli_query($conn,$mysql_qry);
      }
      //判断记录插入or更新 是否成功
      if($result){
        $message['status']=1;
        $message['text']='申请成功';
        echo json_encode($message);
        die();
      }else{
        $message['status']=0;
        $message['text']='数据库错误';
        echo json_encode($message);
        die();
      }
    }

    

  }else if($type=="response" && $userid){
    //判断是否有status!=2记录 有则status=1 否则不变动
    //$response=1接受好友   $response=2拒绝好友
    $response=$_POST["response"];
    $friendid=$_POST["friendid"];

    $mysql_qry="SELECT * FROM friend WHERE userid=$userid AND friendid=$friendid AND status!=2"
      ." UNION ALL SELECT * FROM friend WHERE userid=$friendid AND friendid=$userid AND status!=2";
    $result = mysqli_query($conn,$mysql_qry);
    $rows=mysqli_num_rows($result);

    if($rows>0){
      $mysql_qry="UPDATE friend SET status=$response WHERE (userid=$userid AND friendid=$friendid) OR (userid=$friendid AND friendid=$userid)";
      $result = mysqli_query($conn,$mysql_qry);
      if($result){
        $message['status']=1;
        if($response==1){
          $message['text']='已成为好友';
        }else{
          $message['text']='已拒绝好友';          
        }
        echo json_encode($message);
        die();
      }else{
        $message['status']=0;
        $message['text']='数据库错误';
        echo json_encode($message);
        die();
      }
    }else{
      $message['status']=0;
      $message['text']='没有申请记录,或被拒绝';
      echo json_encode($message);
    }
  }else{
    $message['status']=0;
    $message['text']='数据非法';
    echo json_encode($message);
    die();
  }
?>
