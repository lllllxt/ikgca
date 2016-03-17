<?php
/**
 * Created by LiuXiaoTang.
 * Date: 2016/1/23
 * Time: 23:25
 */
require 'connect.php';
try {
    $pdo = getConnect();
} catch (PDOException $e) {
    die("连接数据库异常：" . $e->getMessage());
}
date_default_timezone_set('Asia/Shanghai');//设置时区
//通过Id查询
if ($_GET['act'] == 'query') {
    if(isset($_GET['hrId'])){
        $sql="SELECT * FROM home_repair WHERE id=".$_GET['hrId'];//通过id查询
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $hrList = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 查询出借人姓名
        $user_sql="SELECT name FROM user WHERE id =".$row['userId'];
        $user_stmt = $pdo->prepare($user_sql);
        $user_stmt->execute();
        while ($user = $user_stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['userName'] = $user['name'];
        }
        array_push($hrList, $row);
    }
    echo json_encode($hrList);
}
//条件查询
elseif($_GET['act'] == 'queryAll'){
    $hr=$_POST['hr'];

    if(!empty($hr['userName'])){
        if($hr['userName']=="undefined"){
            $sql="SELECT * FROM home_repair WHERE userId = 0 ";
        }else{
            $user_sql = "SELECT id FROM user WHERE name LIKE '%".$hr['userName']."%'";
            $user_stmt =$pdo->prepare($user_sql);
            $user_stmt ->execute();
            $userIds=array();
            while ($user = $user_stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($userIds,$user['id']);
            }
            if($user_stmt->rowCount()==0){
                array_push($userIds,-1001);
            }
            $sql="SELECT * FROM home_repair WHERE userId IN (".implode(',',$userIds).")";
        }
        if($hr['state']!=-1) {
            $sql .= "AND state =" . $hr['state'];
        }
    }elseif($hr['state']!=-1){
        $sql="SELECT * FROM home_repair WHERE state =".$hr['state'];
    }
    else{
        $sql="SELECT * FROM home_repair";// 查询全部
    }


    //查询符合条件的数据记录数
    $stmt = $pdo->query($sql);
    $count = $stmt->rowCount();
    //分页
    $pageNum = $_POST['pageNum'];
    $pageSize = $_POST['pageSize'];
    $pageSum = ceil($count/$pageSize);
    $start =($pageNum-1)*$pageSize;
    $sql.=" ORDER BY id desc LIMIT $start,$pageSize";//按创建时间升序排列
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 查询出借人姓名
        $user_sql="SELECT name FROM user WHERE id =".$row['userId'];
        $user_stmt = $pdo->prepare($user_sql);
        $user_stmt->execute();
        while ($user = $user_stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['userName'] = $user['name'];
        }
        array_push($result, $row);
    }
    array_push($result, array('pageSum'=>$pageSum));
    echo json_encode($result);
}
//增加
elseif ($_GET['act'] == 'add') {
    $content = $_POST['content'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $userId = $_POST['userId'];
    $state = $_POST['state'];
    $remark = $_POST['remark'];
    $createDate = date('Y-m-d H:i:s',time());

    $sql = "INSERT INTO home_repair(content, address, phone, userId, state, remark, createDate) VALUES (?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute(array($content, $address, $phone, $userId, $state,$remark,$createDate));
    if ($stmt->rowCount() > 0) {
        echo '1000';//成功
    } else {
        echo "1001";//失败
    }
}
//通过Id删除
elseif ($_GET['act'] == 'del') {
    if(isset($_POST['hrId'])){
        $sql = "DELETE FROM home_repair WHERE id=".$_POST['hrId'];
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo '1000';//成功
    } else {
        echo "1001";//失败
    }
}
//更新信息
elseif ($_GET['act'] == 'update') {
    $content = $_POST['content'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $userId = $_POST['userId'];
    $state = $_POST['state'];
    $remark = $_POST['remark'];
    $id = $_POST['id'];

    $sql = "UPDATE home_repair SET content=?,address=?,phone=?,userId=?,state=?,remark=? WHERE id=?";
    $stmt = $pdo->prepare($sql);

    $stmt->execute(array($content, $address, $phone, $userId, $state,$remark,$id));
    if ($stmt->rowCount() > 0) {
        echo '1000';//成功
    } else {
        echo "1001";//失败
    }
}