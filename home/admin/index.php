<?php
include("../../php/MySQL.php");
$SQL="SELECT `sha1_password`,`user_type` FROM `user_list` WHERE
    `username`='{$_COOKIE["username"]}'
    ";
$r=mysql_query($SQL,$conn);
if($row=mysql_fetch_array($r)){
    if(isset($_COOKIE["username"])&&isset($_COOKIE["key"])){
        if($_COOKIE["key"]==sha1(sha1($_COOKIE["username"])."FREEDCRTTR".$row["sha1_password"])){
            if($row["user_type"]=="0"){
                goto jump_out;
            }else{
                $error="你无权访问本页面！";
            }
        }else{
            $error="你无权访问本页面！";
        }
    }else{
        $error="你无权访问本页面！";
    }
}else{
    $error="抱歉，服务器出错，请重试。";
}
die("<!Doctype html>
<html>
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <link href=\"../../css/main.css\" rel=\"stylesheet\" type=\"text/css\"/>
    <script src=\"../../js/request.js\"></script>
    <script src=\"../../js/sha1.js\"></script>
    <script src=\"../../js/main.js\"></script>
    <title>用户管理系统-登录失败</title>
</head>
<body>
<header>
    <ul>
        <li style=\"float:left\"><p>用户管理系统</p></li>
    </ul>
</header>
<div class=\"main-result-div\">
    <ul>
        <li><p>登录失败</p></li>
        <li>{$error}</li>
        <li><a href=\"../../index.html\">点我再次登录</a></li>
        <li><a href=\"../../reg.html\">我要注册</a></li>
    </ul>
</div>
</body>
");
jump_out:
$SQL="SELECT * FROM `user_list` WHERE
    `username`='{$_COOKIE["username"]}'
    ";
$r=mysql_query($SQL,$conn);
$row=mysql_fetch_array($r);
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../../css/main.css" rel="stylesheet" type="text/css"/>
    <script src="../../js/request.js"></script>
    <script src="../../js/sha1.js"></script>
    <script src="../../js/main.js"></script>
    <title>用户管理系统-管理员界面</title>
</head>
<body>
<header>
    <ul>
        <li style="float: left"><p>用户管理系统</p></li>
        <li style="float: right">
            <a>欢迎，<?php echo($row["username"]);?></a>
            <a href="">修改密码</a>
            <a href="Javascript:logout();">注销</a>
        </li>
    </ul>
</header>



</body>
</html>
