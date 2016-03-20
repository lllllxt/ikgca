<?php
/**
 * Created by LiuXiaoTang.
 * Date: 2016/1/26
 * Time: 18:10
 */
require 'connect.php';
try {
    $pdo = getConnect();
} catch (PDOException $e) {
    die("连接数据库异常：" . $e->getMessage());
}
date_default_timezone_set('Asia/Shanghai');//设置时区
//通过Id查询 done
if ($_GET['act'] == 'query') {
    if(isset($_GET['activityId'])){
        $sql="SELECT * FROM activity WHERE id=".$_GET['activityId'];//通过id查询
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $activityList = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 查询发布人姓名
        $user_sql="SELECT name FROM user WHERE id =".$row['userId'];
        $user_stmt = $pdo->prepare($user_sql);
        $user_stmt->execute();
        while ($user = $user_stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['userName'] = $user['name'];
        }
        // 查询负责人姓名
        $leader_sql="SELECT name FROM user WHERE id =".$row['leaderId'];
        $leader_stmt = $pdo->prepare($leader_sql);
        $leader_stmt->execute();
        while ($leader = $leader_stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['leaderName'] = $leader['name'];
        }
        array_push($activityList, $row);
    }
    echo json_encode($activityList);
}
//条件查询 done 升级版
elseif($_GET['act'] == 'queryAll'){
    $activity=$_POST['activity'];
    if(!empty($activity['userName'])){
        $sql_getUserId="SELECT id FROM user WHERE name LIKE '%".$activity['userName']."%'";
        $stmt_getUserId = $pdo->prepare($sql_getUserId);
        $stmt_getUserId->execute();
        $idList =-10086;
        while ($row = $stmt_getUserId->fetch(PDO::FETCH_ASSOC)) {
            $idList.=",".$row['id'];
        }
        $sql="SELECT * FROM activity WHERE userId IN (".$idList.")";

        if(!empty($activity['title'])){
            $sql.="AND title LIKE '%".$activity['title']."%'";
        }
        if($activity['acState'] != -1){
            $sql.="AND acState =".$activity['acState'];
        }

    }elseif(!empty($activity['title'])){
        $sql="SELECT * FROM activity WHERE title LIKE '%".$activity['title']."%'";
        if($activity['acState'] != -1){
            $sql.="AND acState =".$activity['acState'];
        }
    }elseif($activity['acState'] != -1){
        $sql="SELECT * FROM activity WHERE acState = ".$activity['acState'];
    }else{
        $sql="SELECT * FROM activity";
    }
    //查询符合条件的数据记录数
    $stmt = $pdo->query($sql);
    $count = $stmt->rowCount();
    //分页
    $pageNum = $_POST['pageNum'];
    $pageSize = $_POST['pageSize'];
    $pageSum = ceil($count/$pageSize);
    $start =($pageNum-1)*$pageSize;
    $sql.=" ORDER BY createDate desc LIMIT $start,$pageSize";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//        // 查询发布人姓名
//        $user_sql="SELECT name FROM user WHERE id =".$row['userId'];
//        $user_stmt = $pdo->prepare($user_sql);
//        $user_stmt->execute();
//        while ($user = $user_stmt->fetch(PDO::FETCH_ASSOC)) {
//            $row['userName'] = $user['name'];
//        }
        // 查询负责人姓名
        $leader_sql="SELECT name FROM user WHERE id =".$row['leaderId'];
        $leader_stmt = $pdo->prepare($leader_sql);
        $leader_stmt->execute();
        while ($leader = $leader_stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['leaderName'] = $leader['name'];
        }
        array_push($result, $row);
    }
    array_push($result, array('pageSum'=>$pageSum));
    echo json_encode($result);
}
//增加
elseif ($_GET['act'] == 'add') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $createDate = date('Y-m-d H:i:s',time());
    $userId = $_POST['userId'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $deadline = $_POST['deadline'];
    $leaderId = $_POST['leaderId'];
    $acAddress = $_POST['acAddress'];
    $fee = $_POST['fee'];
//    $signInList = $_POST['signInList'];
    $acState = $_POST['acState'];

//    $sql = "INSERT INTO activity(title, content, createDate, userId,startDate,endDate,deadline,leaderId,acAddress,fee,signInList,acState) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
    $sql = "INSERT INTO activity(title, content, createDate, userId,startDate,endDate,deadline,leaderId,acAddress,fee,acState) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);

//    $stmt->execute(array($title, $content, $createDate, $userId,$startDate, $endDate, $deadline, $leaderId, $acAddress, $fee, $signInList, $acState));
    $stmt->execute(array($title, $content, $createDate, $userId,$startDate, $endDate, $deadline, $leaderId, $acAddress, $fee,$acState));
    if ($stmt->rowCount() > 0) {
        echo '1000';//成功
    } else {
        echo "1001";//失败
    }
}
//通过Id删除 done
elseif ($_GET['act'] == 'del') {
    if(isset($_POST['activityId'])){
        $sql = "DELETE FROM activity WHERE id=".$_POST['activityId'];
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
    $title = $_POST['title'];
    $content = $_POST['content'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $deadline = $_POST['deadline'];
    $leaderId = $_POST['leaderId'];
    $acAddress = $_POST['acAddress'];
    $fee = $_POST['fee'];
//    $signInList = $_POST['signInList'];
    $acState = $_POST['acState'];
    $id = $_POST['id'];

//    $sql = "UPDATE activity SET title=?,content=?,startDate=?,endDate=?,deadline=?,leaderId=?,acAddress=?,fee=?,signInList=?,acState=? WHERE id=?";
    $sql = "UPDATE activity SET title=?,content=?,startDate=?,endDate=?,deadline=?,leaderId=?,acAddress=?,fee=?,acState=? WHERE id=?";
    $stmt = $pdo->prepare($sql);

//    $stmt->execute(array($title, $content, $startDate, $endDate, $deadline, $leaderId, $acAddress, $fee, $signInList, $acState, $id));
    $stmt->execute(array($title, $content, $startDate, $endDate, $deadline, $leaderId, $acAddress, $fee, $acState, $id));
    if ($stmt->rowCount() > 0) {
        echo '1000';//成功
    } else {
        echo "1001";//失败
    }
}

//本月活動概要
elseif ($_GET['act'] == 'huodong'){
    $dateNow=date('Y-m-d',time());
    $sql="SELECT * FROM activity WHERE startDate >= '$dateNow' ORDER BY startDate ASC ";
    $stmt = $pdo->prepare($sql);
    $stmt ->execute();
    $result = array();
    while($row = $stmt ->fetch(PDO::FETCH_ASSOC)){
        // 查询负责人姓名
        $leader_sql="SELECT name FROM user WHERE id =".$row['leaderId'];
        $leader_stmt = $pdo->prepare($leader_sql);
        $leader_stmt->execute();
        while ($leader = $leader_stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['leaderName'] = $leader['name'];
        }
        array_push($result,$row);
    }
    echo json_encode($result);
}