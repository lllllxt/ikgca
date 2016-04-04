<?php
/**
 * Created by LiuXiaoTang.
 * Date: 2016/1/24
 * Time: 23:25
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
    if(isset($_GET['noticeId'])){
        $sql="SELECT * FROM notice WHERE id=".$_GET['noticeId'];//通过id查询
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $noticeList = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 查询发布人姓名
        $user_sql="SELECT name FROM user WHERE id =".$row['userId'];
        $user_stmt = $pdo->prepare($user_sql);
        $user_stmt->execute();
        while ($user = $user_stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['userName'] = $user['name'];
        }
        array_push($noticeList, $row);
    }
    echo json_encode($noticeList);
}
//条件查询 done 升级版
elseif($_GET['act'] == 'queryAll'){
    $notice=$_POST['notice'];
    if(!empty($notice['userName'])){
        $sql_getUserId="SELECT id FROM user WHERE name LIKE '%".$notice['userName']."%'";
        $stmt_getUserId = $pdo->prepare($sql_getUserId);
        $stmt_getUserId->execute();
        $idList =-10086;
        while ($row = $stmt_getUserId->fetch(PDO::FETCH_ASSOC)) {
            $idList.=",".$row['id'];
        }
        $sql="SELECT * FROM notice WHERE userId IN (".$idList.")";

        if(!empty($notice['title'])){
            $sql.="AND title LIKE '%".$notice['title']."%'";
        }

    }elseif(!empty($notice['title'])){
        $sql="SELECT * FROM notice WHERE title LIKE '%".$notice['title']."%'";
    }else{
        $sql="SELECT * FROM notice";
    }
    //查询符合条件的数据记录数
    $stmt = $pdo->query($sql);
    $count = $stmt->rowCount();
    //分页
    $pageNum = $_POST['pageNum'];
    $pageSize = $_POST['pageSize'];
    $pageSum = ceil($count/$pageSize);
    $start =($pageNum-1)*$pageSize;
    $sql.=" ORDER BY createDate ASC LIMIT $start,$pageSize";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 查询发布人姓名
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
    $title = $_POST['title'];
    $content = $_POST['content'];
    $createDate = date('Y-m-d H:i:s',time());
    $userId = $_POST['userId'];

    $sql = "INSERT INTO notice(title, content, createDate, userId) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute(array($title, $content, $createDate, $userId));
    if ($stmt->rowCount() > 0) {
        echo '1000';//成功
    } else {
        echo "1001";//失败
    }
}
//通过Id删除 done
elseif ($_GET['act'] == 'del') {
    if(isset($_POST['noticeId'])){
        $sql = "DELETE FROM notice WHERE id=".$_POST['noticeId'];
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo '删除成功';//成功
    } else {
        echo "删除失败";//失败
    }
}
//更新信息
elseif ($_GET['act'] == 'update') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $id = $_POST['id'];

    $sql = "UPDATE notice SET title=?,content=? WHERE id=?";
    $stmt = $pdo->prepare($sql);

    $stmt->execute(array($title, $content, $id));
    if ($stmt->rowCount() > 0) {
        echo '1000';//成功
    } else {
        echo "1001";//失败
    }
}