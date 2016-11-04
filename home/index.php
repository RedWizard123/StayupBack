<?php
include("../php/MySQL.php");
$SQL="SELECT `sha1_password` FROM `user_list` WHERE
    `username`='{$_COOKIE["username"]}'
    ";
$r=mysql_query($SQL,$conn);
if($row=mysql_fetch_array($r)){
    if(isset($_COOKIE["username"])&&isset($_COOKIE["key"])){
        if($_COOKIE["key"]==sha1(sha1($_COOKIE["username"])."FREEDCRTTR".$row["sha1_password"])){
            //233333333
            //[FREEDCRTTR] is a code;
            goto jump_out;
        }else{
            $error="抱歉，你的Cookie出错，请重新登录！";
        }
    }else{
        $error="抱歉，没有Cookie数据，请重新登录！";
    }
}else{
    $error="抱歉，服务器出错，请刷新或重新登录。";
}
die("<!Doctype html>
<html>
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <link href=\"../css/main.css\" rel=\"stylesheet\" type=\"text/css\"/>
    <script src=\"../js/request.js\"></script>
    <script src=\"../js/sha1.js\"></script>
    <script src=\"../js/main.js\"></script>
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
        <li><a href=\"../index.html\">点我立即登录</a></li>
        <li><a href=\"../reg.html\">我还要再注册</a></li>
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
$sex1=($row["sex"]=="0")?"selected=\"\"":"";
$sex2=($row["sex"]=="0")?"":"selected=\"\"";
$user_type=($row["user_type"]=="0")?"管理员":"非管理员";//此处可拓展


?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../css/main.css" rel="stylesheet" type="text/css"/>
    <script src="../js/request.js"></script>
    <script src="../js/sha1.js"></script>
    <script src="../js/main.js"></script>
    <title>用户管理系统-个人中心</title>
</head>
<body>
<header>
    <ul>
        <li style="float: left"><p>用户管理系统</p></li>
        <li style="float: right">
            <a>欢迎，<?php echo($row["username"]);?></a>
            <?php if($row["user_type"]=="0"){echo("<a href=\"admin\">进入管理页面</a>");}?>
            <a href="">修改密码</a>
            <a href="Javascript:logout();">注销</a>
        </li>
    </ul>
</header>
<div class="main-edit-div">
    <div class="myself">
        <p>我的信息</p><button id="save" onclick="edit();">修改</button>
        <ul class="info_list">
            <li>
                <span>基本信息</span><br/>
                <label for="info1">用户名：</label><input id="info1" type="text" disabled="disabled" value="<?php echo($row['username']);?>"/>
                <label for="info2">性别：</label>
                <select id="info2" disabled="disabled">
                    <option value="0" <?php echo($sex1);?>>男性</option>
                    <option value="1" <?php echo($sex2);?>>女性</option>
                </select>
            </li>
            <li>
                <span>联系方式</span><br/>
                <label for="info3">电话号码：</label><input id="info3" type="text" disabled="disabled" value="<?php echo($row['phone']);?>"/>
                <label for="info4">E-mail：</label><input id="info4" type="text" disabled="disabled" value="<?php echo($row['email']);?>"/>
            </li>
            <li>
                <span>账号信息</span><br/>
                <label for="info5">账号类型：</label><input id="info5" type="text" disabled="disabled" value="<?php echo($user_type);?>"/>
                <label for="info5">密码信息：</label><a href="JavaScript:;">修改密码</a>
            </li>


        </ul>
    </div>



</div>
</body>
</html>