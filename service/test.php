<?php
$pass = "123546";
$name="正在";
$a="jx".$pass;
$a2="jx".$pass."1";
echo "无敌加密后".md5(crypt($a,"123"))."<br>"; // 现在让黑客如何破这个密码？？？
echo "无敌加密后".md5(crypt($a2,"123"))."<br>"; // 现在让黑客如何破这个密码？？？
echo "加密后".md5($a)."<br>"; // 现在让黑客如何破这个密码？？？
echo "加密后".md5($a2)."<br>"; // 现在让黑客如何破这个密码？？？

$str = '陶喆';
echo 'gb2312-'.$str;
echo '<br />';
$str = iconv( 'utf-8' ,'utf-8' , $str );
echo 'utf8-'.$str;
?>