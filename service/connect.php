<?php
/**
 * Created by LiuXiaoTang.
 * Date: 2016/1/15
 * Time: 23:32
 */
function getConnect(){
//    return new PDO("mysql:host=".SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT .";dbname=ikgca;charset=utf8",SAE_MYSQL_USER,SAE_MYSQL_PASS);
	return new PDO("mysql:host=localhost;dbname=ikgca;charset=utf8","root","root");
}