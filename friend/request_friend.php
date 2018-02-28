<?php
  require "../conn.php";

  $type=$_POST["type"];
  $userid=$_POST["userid"];
  $friendid=$_POST["friendid"];
  $message=array();


  $mysql_qry="SELECT * FROM user WHERE Id=$friendid";
  $result = mysqli_query($conn,$mysql_qry);
  $rows=mysqli_num_rows($result);

  if($rows==0){
    $message['status']=0;
    $message['text']='用户不存在';
    echo json_encode($message);
    die();
  }

  if($type=="request" && $userid && $friendid){
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

    

  }else if($type=="response" && $userid && $friendid){
    //判断是否有status!=2记录 有则status=1 否则
    $response=$_POST["response"];

    $mysql_qry="SELECT * FROM friend WHERE userid=$userid AND friendid=$friendid AND status!=2"
      ." UNION ALL SELECT * FROM friend WHERE userid=$friendid AND friendid=$userid AND status!=2";
    $result = mysqli_query($conn,$mysql_qry);
    $rows=mysqli_num_rows($result);

    if($rows>0){
      $mysql_qry="UPDATE friend SET status=1 WHERE (userid=$userid AND friendid=$friendid) OR (userid=$friendid AND friendid=$userid)";
      $result = mysqli_query($conn,$mysql_qry);
      if($result){
        $message['status']=1;
        $message['text']='已成为好友';
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
