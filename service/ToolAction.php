<?php
/**
 * Created by LiuXiaoTang.
 * Date: 2016/1/19
 * Time: 18:11
 */
require 'connect.php';
$pdo = "";
try {
    $pdo = getConnect();
} catch (PDOException $e) {
    die("连接数据库异常：" . $e->getMessage());
}

date_default_timezone_set('Asia/Shanghai');//设置时区
$dateNow=date('Y-m-d',time());//当前年月日

/*--------------------------------工具DAO--------------------------------*/
//通过Id查询
if ($_GET['act'] == 'query') {

    if (isset($_GET['toolId'])) {
        $sql = "SELECT * FROM tool WHERE id=" . $_GET['toolId'];//通过id查询
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $toolList = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($toolList, $row);
    }
    echo json_encode($toolList);
} //条件查询
elseif ($_GET['act'] == 'queryAll') {
    $tool = $_POST['tool'];

    if (!empty($tool['name'])) {
        $sql = "SELECT * FROM tool WHERE name LIKE '%" . $tool['name'] . "%' ";//通过名称查询
        if ($tool['state'] != -1) {
            $sql .= " and state = " . $tool['state'];//通过名称+状态查询
        }
    } elseif ($tool['state'] != -1) {
        $sql = "SELECT * FROM tool WHERE state = " . $tool['state'];//通过状态查询

    } else {
        $sql = "SELECT * FROM tool";// 查询全部
    }
    //查询符合条件的数据记录数
    $stmt = $pdo->query($sql);
    $count = $stmt->rowCount();
    //分页
    $pageNum = $_POST['pageNum'];
    $pageSize = $_POST['pageSize'];
    $pageSum = ceil($count / $pageSize);
    $start = ($pageNum - 1) * $pageSize;
    $sql .= " ORDER BY name ASC limit $start,$pageSize";//按名称升序排列
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($result, $row);
    }
    array_push($result, array('pageSum' => $pageSum));
    echo json_encode($result);
} //增加
elseif ($_GET['act'] == 'add') {
    $name = $_POST['name'];
    $remark = $_POST['remark'];

    $sql = "INSERT INTO tool(name,remark) VALUES (?,?)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute(array($name, $remark));
    if ($stmt->rowCount() > 0) {
        echo '1000';//成功
    } else {
        echo "1001";//失败
    }
} //通过Id删除
elseif ($_GET['act'] == 'del') {
    try {
        //    开启事务
        $pdo->beginTransaction();
        if (isset($_POST['toolId'])) {
            $sql = "DELETE FROM tool WHERE id= ?";
        }
        $stmt = $pdo->prepare($sql);

        $flow_sql = "DELETE FROM tool_flow WHERE toolId= ?";
        $flow_stmt = $pdo->prepare($flow_sql);

        $stmt->execute(array($_POST['toolId']));
        $flow_stmt->execute(array($_POST['toolId']));

        //    提交事务
        $pdo->commit();
        echo '删除成功';
    } catch (PDOException $e) {
        echo "删除失败";
        die("异常：" . $e->getMessage());
        //    回滚事务
        $pdo->rollBack();
    }
} //更新信息
elseif ($_GET['act'] == 'update') {
    $name = $_POST['name'];
    $remark = $_POST['remark'];
    $id = $_POST['id'];

    $sql = "UPDATE tool SET name=?,remark=? WHERE id=?";
    $stmt = $pdo->prepare($sql);

    $stmt->execute(array($name, $remark, $id));
    if ($stmt->rowCount() > 0) {
        echo '1000';//成功
    } else {
        echo "1001";//失败
    }
} //查询空闲工具的名称，备注
elseif ($_GET['act'] == 'queryNR') {
    $tool = $_POST['tool'];
    if (!empty($tool['name'])) {
        $sql = "SELECT id,name,remark FROM tool WHERE name LIKE '%" . $tool['name'] . "%' and state = 0";
    } else {
        $sql = "SELECT id,name,remark FROM tool WHERE state = 0";
    }
    //查询符合条件的数据记录数
    $stmt = $pdo->query($sql);
    $count = $stmt->rowCount();
    //分页
    $pageNum = $_POST['pageNum'];
    $pageSize = $_POST['pageSize'];
    $pageSum = ceil($count / $pageSize);
    $start = ($pageNum - 1) * $pageSize;
    $sql .= " ORDER BY name ASC limit $start,$pageSize";//按名称升序排列
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($result, $row);
    }
    array_push($result, array('pageSum' => $pageSum));
    echo json_encode($result);
}

/*--------------------------------工具流动DAO--------------------------------*/
//通过Id查询
elseif ($_GET['act'] == 'queryFlow') {
    if (isset($_GET['flowId'])) {
        $sql = "SELECT * FROM tool_flow WHERE id=" . $_GET['flowId'];//通过id查询
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $flowList = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 查询出借人姓名
        $user_sql = "SELECT * FROM user WHERE id =" . $row['userId'];
        $user_stmt = $pdo->prepare($user_sql);
        $user_stmt->execute();
        while ($user = $user_stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['userName'] = $user['name'];
        }
        // 查询工具名称
        $tool_sql = "SELECT * FROM tool WHERE id =" . $row['toolId'];
        $tool_stmt = $pdo->prepare($tool_sql);
        $tool_stmt->execute();
        while ($tool = $tool_stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['toolName'] = $tool['name'];
        }
        array_push($flowList, $row);
    }
    echo json_encode($flowList);
} //条件查询
elseif ($_GET['act'] == 'queryAllFlow') {
    $flow = $_POST['flow'];
    $result = array();
    if (!empty($flow["userName"])) {
        // 查询出借人id
        $user_sql = "SELECT id FROM user WHERE name LIKE '%" . $flow["userName"] . "%'";
        $user_stmt = $pdo->prepare($user_sql);
        $user_stmt->execute();
        $userIds = array();
        while ($user = $user_stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($userIds, $user['id']);
        }
        if ($user_stmt->rowCount() == 0) {
            array_push($userIds, -1001);
        }
    }
    if (!empty($flow["toolName"])) {
        // 查询工具id
        $tool_sql = "SELECT id FROM tool WHERE name LIKE '%" . $flow['toolName'] . "%'";
        $tool_stmt = $pdo->prepare($tool_sql);
        $tool_stmt->execute();
        $toolIds = array();
        while ($tool = $tool_stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($toolIds, $tool['id']);
        }
        if ($tool_stmt->rowCount() == 0) {
            array_push($toolIds, -1001);
        }
    }

    if (!empty($toolIds)) {
        $sql = "SELECT * FROM tool_flow WHERE toolId IN (" . implode(',', $toolIds) . ")";//通过工具Id查询
        if (!empty($userIds)) {
            $sql .= " AND userId IN (" . $userIds . ")";//工具Id + 用户Id
            if ($flow['state'] == 1) {//已归还
                $sql = "AND returnDate NOT IN ('null','0000-00-00')";
            } elseif ($flow['state'] == 0) {//已归还
                $sql = "AND returnDate IN ('null','0000-00-00')";
            }
        }
    } elseif (!empty($userIds)) {
        $sql = "SELECT * FROM tool_flow WHERE userId IN (" . implode(',', $userIds) . ")";//通过用户Id查询
        if ($flow['state'] == 1) {//已归还
            $sql = "AND returnDate NOT IN ('null','0000-00-00')";
        } elseif ($flow['state'] == 0) {//已归还
            $sql = "AND returnDate IN ('null','0000-00-00')";
        }
    } elseif ($flow['state'] == 1) {//已归还
        $sql = "SELECT * FROM tool_flow WHERE returnDate IS NOT NULL AND returnDate!='0000-00-00'";
    } elseif ($flow['state'] == 0) {//未归还
        $sql = "SELECT * FROM tool_flow WHERE returnDate IS NULL OR returnDate='0000-00-00'";
    } else {
        $sql = "SELECT * FROM tool_flow";// 查询全部
    }
    //查询符合条件的数据记录数
    $stmt = $pdo->query($sql);
    $count = $stmt->rowCount();
    //分页
    $pageNum = $_POST['pageNum'];
    $pageSize = $_POST['pageSize'];
    $pageSum = ceil($count / $pageSize);
    $start = ($pageNum - 1) * $pageSize;
    $sql .= " ORDER BY lendDate ASC limit $start,$pageSize";//按名称升序排列

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 查询出借人姓名
        $user_sql = "SELECT * FROM user WHERE id =" . $row['userId'];
        $user_stmt = $pdo->prepare($user_sql);
        $user_stmt->execute();
        while ($user = $user_stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['userName'] = $user['name'];
        }
        // 查询工具名称
        $tool_sql = "SELECT * FROM tool WHERE id =" . $row['toolId'];
        $tool_stmt = $pdo->prepare($tool_sql);
        $tool_stmt->execute();
        while ($tool = $tool_stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['toolName'] = $tool['name'];
        }
        array_push($result, $row);
    }
    array_push($result, array('pageSum' => $pageSum));
    echo json_encode($result);
} //增加
elseif ($_GET['act'] == 'addFlow') {
    $toolId = $_POST['toolId'];
    $userId = $_POST['userId'];
    $lendDate = $_POST['lendDate'];
    $returnDate = $_POST['returnDate'];
    if ($returnDate >= $lendDate) {
        $toolState = 0;//空闲
    } else {
        $toolState = 1;//已出借
    }

    try {
        //    开启事务
        $pdo->beginTransaction();
        $sql = "INSERT INTO tool_flow(toolId,userId,lendDate,returnDate) VALUES (?,?,?,?)";
        $stmt = $pdo->prepare($sql);

        $tool_sql = "UPDATE tool SET state=? WHERE id=?";
        $tool_stmt = $pdo->prepare($tool_sql);

        $stmt->execute(array($toolId, $userId, $lendDate, $returnDate));
        $tool_stmt->execute(array($toolState, $toolId));

        //    提交事务
        $pdo->commit();
        echo '1000';//成功
    } catch (PDOException $e) {
        echo "1001";//失败
        die("异常：" . $e->getMessage());
        //    回滚事务
        $pdo->rollBack();
    }
} //通过Id删除
elseif ($_GET['act'] == 'delFlow') {
    if (isset($_POST['flowId'])) {
        $sql = "DELETE FROM tool_flow WHERE id=" . $_POST['flowId'];
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo '删除成功';
    } else {
        echo "删除失败";
    }
} //更新信息
elseif ($_GET['act'] == 'updateFlow') {
    $toolId = $_POST['toolId'];
    $userId = $_POST['userId'];
    $lendDate = $_POST['lendDate'];
    $returnDate = $_POST['returnDate'];
    $id = $_POST['id'];
    if ($lendDate > $returnDate) {
        die("归还时间不能早于".$lendDate."！");
    }elseif($dateNow<$returnDate){
        die("现在还没到".$returnDate."呢，你穿越了吗？！");
    }
    if ($returnDate >= $lendDate) {
        $toolState = 0;//空闲
    } else {
        $toolState = 1;//已出借
    }
    try {
        //    开启事务
        $pdo->beginTransaction();
        $sql = "UPDATE tool_flow SET toolId=?,userId=?,lendDate=?,returnDate=? WHERE id=?";
        $stmt = $pdo->prepare($sql);

        $tool_sql = "UPDATE tool SET state=? WHERE id=?";
        $tool_stmt = $pdo->prepare($tool_sql);

        $stmt->execute(array($toolId, $userId, $lendDate, $returnDate, $id));
        $tool_stmt->execute(array($toolState, $toolId));

        //    提交事务
        $pdo->commit();
        echo '1000';//成功
    } catch (PDOException $e) {
        die("异常：" . $e->getMessage());
        //    回滚事务
        $pdo->rollBack();
    }
} //工具情况
elseif ($_GET['act'] == "gongju") {
    $sql = "SELECT name,state FROM tool";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $toolList = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $isExist = false;
        for ($i = 0; $i < count($toolList); $i++) {
            if ($toolList[$i]["name"] == $row['name']) {
                $toolList[$i]["count"]++;
                $isExist = true;

                if ($row["state"] == 1) {
                    $toolList[$i]["lendCount"]++;
                }
            }
        }
        if (!$isExist) {
            if ($row["state"] == 1) {
                $row["lendCount"] = 1;
            } else {
                $row["lendCount"] = 0;
            }
            $row["count"] = 1;
            array_push($toolList, $row);
        }
    }
    echo json_encode($toolList);
}