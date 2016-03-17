<?php
/**
 * Created by LiuXiaoTang.
 * Date: 2016/2/22
 * Time: 3:20
 */

/******************************************************************************
使用说明:
1. 将PHP.INI文件里面的"extension=php_gd2.dll"一行前面的;号去掉,因为我们要用到GD库;
2. 将extension_dir =改为你的php_gd2.dll所在目录;
 *
 *
 * php.ini 设置
 * upload_max_filesiza 文件大小
 * post_max_size 上传大小
 ******************************************************************************/
////打印文件信息
//print_r($_FILES);
error_reporting(E_ALL || ~E_NOTICE);

require 'connect.php';
try {
    $pdo = getConnect();
} catch (PDOException $e) {
    die("连接数据库异常：" . $e->getMessage());
}

/*---------------------------设置文件要求------------------------------*/
//上传文件类型列表
$file_types=array(
    'image/jpg',
    'image/jpeg',
    'image/png',
    'image/pjpeg',
    'image/gif',
    'image/bmp',
    'image/x-png',
    'image/x-icon'
);

$max_file_size=2000000;     //上传文件大小限制, 单位BYTE  2MB
$destination_folder="../images/head/"; //上传文件路径

/*---------------------------设置文件要求 END------------------------------*/
$result=array();
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $result['fileInfo']= $_FILES["file"];
    if (!is_uploaded_file($_FILES["file"]['tmp_name']))
        //是否存在文件
    {
        $result['msg']="未选择或不存在该文件!";
        $result['file']= $_FILES["file"];

        echo json_encode($result);
        exit;
    }

    $file = $_FILES["file"];
    if($max_file_size < $file["size"])
        //检查文件大小
    {
        $result['msg']="文件太大!不能超过".($max_file_size/1000)."kb";
        echo json_encode($result);
        exit;
    }

    if(!in_array($file["type"], $file_types))
        //检查文件类型
    {
        $result['msg']="文件类型不符：".$file["type"];
        echo json_encode($result);
        exit;
    }

    if(!file_exists($destination_folder))
    {
        mkdir($destination_folder);
    }

    $file_tmp_name=$file["tmp_name"];
    $image_size = getimagesize($file_tmp_name);
    $path_info=pathinfo($file["name"]);
    $file_type=$path_info['extension'];
    $destination = $destination_folder.time().".".$file_type;  //文件路径
    if (file_exists($destination))
    {
        $result['msg']="同名文件已经存在了";
        echo json_encode($result);
        exit;
    }

    if(!move_uploaded_file ($file_tmp_name, $destination))//将临时文件移动到指定路径
    {
        $result['msg']="移动文件出错";
        $result['destination']=$destination;
        echo json_encode($result);
        exit;
    }else{
        $userImg_new = $destination;
        $id = $_POST['id'];
        //查询旧头像path
        $sql = "SELECT userImg FROM user WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userImg = $row['userImg'];
        //先保存新头像path至数据库
        $sql = "UPDATE user SET userImg=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($userImg_new,$id));
        if ($stmt->rowCount() > 0) {
            //保存path成功，删除旧头像文件
            unlink($userImg);
            $result['msg']= "更换头像成功";
            $result['url'] = $userImg_new;
            echo json_encode($result);
        } else {
            //保存path失败，删除新头像文件
            unlink($userImg_new);
            $result['msg']= "something wrong when save path to database";//失败
            $result['url'] = $userImg;
            echo json_encode($result);
        }
    }
}