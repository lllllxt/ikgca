<?php
/**
 * Created by LiuXiaoTang.
 * Date: 2016/1/15
 * Time: 23:25
 */
require 'connect.php';
try {
    $pdo = getConnect();
} catch (PDOException $e) {
    die("连接数据库异常：" . $e->getMessage());
}
$LIST_INFO="id,name,sex,phone,shortPhone,address";
//通过Id查询
if ($_GET['act'] == 'query') {
    if(isset($_GET['userId'])){
        $sql="SELECT * FROM user WHERE id=".$_GET['userId'];//通过id查询
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $userList = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($userList, $row);
    }
    echo json_encode($userList);
}
//条件查询
elseif($_GET['act'] == 'queryAll'){
    if(isset($_POST['user'])){
        $user=$_POST['user'];
        if(!empty($user['name'])){
            $sql="SELECT  $LIST_INFO FROM user WHERE name LIKE '%".$user['name']."%' ";//通过姓名查询
            if(!empty($user['phone'])){
                $sql.=" and phone LIKE '%".$user['phone']."%'";//通过姓名+手机查询
                if($user['sex']!=-1) {
                    $sql.= " and sex='" . $user['sex'] . "'";//通过姓名+手机+性别查询
                }
            }else{
                if($user['sex']!=-1) {
                    $sql .= "and sex='" . $user['sex'] . "'";//通过姓名+性别查询
                }
            }
        }elseif(!empty($user['phone'])){
            $sql="SELECT  $LIST_INFO FROM user WHERE phone LIKE '%".$user['phone']."%'";//通过手机查询
            if($user['sex']!=-1){
                $sql.=" and sex='".$user['sex']."'";//通过手机+性别查询
            }
        }elseif($user['sex']!=-1){
            $sql="SELECT  $LIST_INFO FROM user WHERE sex='".$user['sex']."'";//通过性别查询
        }else{
            $sql="SELECT  $LIST_INFO FROM user";// 查询全部
        }
    }else{
        $sql="SELECT $LIST_INFO FROM user";// 查询全部
    }
    //查询符合条件的数据记录数
    $stmt = $pdo->query($sql);
    $count = $stmt->rowCount();
    //分页
    $pageNum = $_POST['pageNum'];
    $pageSize = $_POST['pageSize'];
    $pageSum = ceil($count/$pageSize);
    $start =($pageNum-1)*$pageSize;
    $sql.=" ORDER BY name ASC LIMIT $start,$pageSize";//按姓名升序排列
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if($row['sex']=='male'){
            $row['sex']='男';
        }elseif($row['sex']=='female'){
            $row['sex']='女';
        }
        if($row['phone']==null || $row['phone']==''){
            $row['phone']= "无";
        }
        if($row['shortPhone']==null || $row['shortPhone']==''){
            $row['shortPhone']= "无";
        }
        if($row['address']==null || $row['address']==''){
            $row['address']= "无";
        }
        array_push($result, $row);
    }
    array_push($result, array('pageSum'=>$pageSum));
    echo json_encode($result);
}
//增加
elseif ($_GET['act'] == 'add') {
    $name = $_POST['name'];
    $birthDate = $_POST['birthDate'];
    $sex = $_POST['sex'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $shortPhone = $_POST['shortPhone'];
    $password = md5($phone);
    $qq = $_POST['qq'];
    $power = $_POST['power'];

    if(checkPhone($phone)){
        $sql = "INSERT INTO user(name, birthDate, sex, address, phone, shortPhone, password, qq, power) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute(array($name, $birthDate, $sex, $address, $phone,$shortPhone,$password,$qq,$power));
        if ($stmt->rowCount() > 0) {
            echo '1000';//成功
        } else {
            echo "提交失败";//失败
        }
    }
}
//通过Id删除
elseif ($_GET['act'] == 'del') {
    if(isset($_POST['userId'])){
        $sql = "DELETE FROM user WHERE id=".$_POST['userId'];
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
    $name = $_POST['name'];
    $birthDate = $_POST['birthDate'];
    $sex = $_POST['sex'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $shortPhone = $_POST['shortPhone'];
    $qq = $_POST['qq'];
    $power = $_POST['power'];
    $id = $_POST['id'];

   if(checkPhone($phone)){
       $sql = "UPDATE user SET name=?,birthDate=?,sex=?,address=?,phone=?,shortPhone=?,qq=?,power=? WHERE id=?";
       $stmt = $pdo->prepare($sql);

       $stmt->execute(array($name, $birthDate, $sex, $address, $phone,$shortPhone,$qq,$power,$id));
       if ($stmt->rowCount() > 0) {
           echo '信息已更新';//成功
       } else {
           echo "信息没有改动，无需提交";//失败
       }
   }
}
//查询姓名，电话
elseif($_GET['act'] == 'queryNP'){
    $user=$_POST['user'];
    if(!empty($user['name'])){
        $sql="SELECT id,name,phone FROM user WHERE name LIKE '%".$user['name']."%'";
        if(!empty($user['power'])){
            $sql.=" AND power >=".$user['power'];
        }
    }else{
        $sql="SELECT id,name,phone FROM user ";
        if(!empty($user['power'])){
            $sql.=" WHERE power >=".$user['power'];
        }
    }


    //查询符合条件的数据记录数
    $stmt = $pdo->query($sql);
    $count = $stmt->rowCount();
    //分页
    $pageNum = $_POST['pageNum'];
    $pageSize = $_POST['pageSize'];
    $pageSum = ceil($count/$pageSize);
    $start =($pageNum-1)*$pageSize;
    $sql.=" ORDER BY name ASC LIMIT $start,$pageSize";//按姓名升序排列
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($result, $row);
    }
    array_push($result, array('pageSum'=>$pageSum));
    echo json_encode($result);
}

//登录
elseif($_GET['act']== 'login'){

    $sql="SELECT id,password,name,power FROM user WHERE phone = ".$_POST['phone'];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount()==0) {
        echo "账号不存在";
    }
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        if($row["power"]!=3){
            echo "该用户非管理员，无法登录后台";
        }else{
            if($row['password']== md5($_POST['password'])){
                $row['password']="";
                echo json_encode($row);
            }else{
                echo "账号或密码错误";
            }
        }
    }
}
//修改密码
elseif($_GET['act']== 'changePwd'){
    $sql="SELECT password FROM user WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($_POST['loginId']));
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        if($row['password']== md5($_POST['pwd'])){
            $sql_changePwd="UPDATE user SET password = ? WHERE id = ?";
            $stmt_changePwd = $pdo->prepare($sql_changePwd);
            $stmt_changePwd->execute(array(md5($_POST['newPwd']),$_POST['loginId']));
            if($stmt_changePwd->rowCount() > 0){
                echo '修改成功';
            }
        }else{
            echo '旧密码错误';
        }
    }
}
//删除头像
elseif($_GET['act']=='removeUserImg'){
    if(isset($_POST['id'])){
        //查询头像path
        $sql = "SELECT userImg FROM user WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($_POST['id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userImg = $row['userImg'];
        //清除头像path
        $sql = "UPDATE user SET userImg = NULL WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($_POST['id']));
        if( $stmt->rowCount() > 0 ){
            //删除头像文件
            unlink($userImg);
            echo '清除成功';
        }
    }
}


/*-----------------function---------------------*/
function checkPhone($phone){
    try {
        $pdo = getConnect();
    } catch (PDOException $e) {
        die("连接数据库异常：" . $e->getMessage());
    }
   if($_GET["act"]=="add"){
       $sql = "SELECT phone FROM user";
       $stmt = $pdo->prepare($sql);
       $stmt->execute();
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
           if($row['phone']==$phone){
               echo '该手机号已被占用';
               return false;
           }
       }
   }
    //手机号码长度不对
    if(strlen($phone)!=11){
        echo '手机号码必须为11位数';
        return false;
    }
    //手机号码不正确
    elseif(!preg_match("/^1[34578]{1}\d{9}$/",$phone)){
        echo "请输入正确手机号";
        return false;
    }
    else{
        return true;
    }
}