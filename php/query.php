<?php
include("MySQL.php");
function sha1_($str){
    return(strtoupper(sha1($str)));
}
switch ($_GET["operate"]){
    case "reg":
        $SQL="INSERT INTO `kxvvFqSopwFJKrIMEjPT`.`user_list`(
        `username`,
        `email`,
        `phone`,
        `sex`,
        `sha1_password`,
        `id`,
        `user_type`,
        `tips`,
        `time`)
        VALUES (
        '{$_POST["username"]}',
        '{$_POST["email"]}',
        '{$_POST["phone"]}',
        '{$_POST["sex"]}',
        '{$_POST["sha1_password"]}',
        NULL,
        '{$_POST["user_type"]}',
        'NULL',
        CURRENT_TIMESTAMP
        )";
        if(mysql_query($SQL,$conn)){
            $d=[
                "result"=>"success"
            ];
            setcookie("username","",time()-3600,"/");
            setcookie("key","",time()-3600,"/");
            //setcookie("username",$_POST["username"],"/");
            //setcookie("key",sha1(sha1($_POST["username"])."FREEDCRTTR".$_POST["sha1_password"]),"/");
            die(json_encode($d));
        }else{
            $d=[
                "result"=>"failed",
                "reason"=>mysql_error()
            ];
            die(json_encode($d));
            break;
        }
    case "login":
        $SQL = "SELECT `username`,`sha1_password` FROM `user_list` WHERE
        `username`='{$_POST["username"]}'
        AND
        `sha1_password`='{$_POST["sha1_password"]}'
        ";
        $r=mysql_query($SQL,$conn);
        if($row=mysql_fetch_array($r)){
            $d=[
                "result"=>"success",
                "username"=>$row["username"]
            ];
            if($_POST["remember"]=="true"){
                setcookie("username",$row["username"],time()+3600*24*30,"/");
                setcookie("key",sha1(sha1($_POST["username"])."FREEDCRTTR".$row["sha1_password"]),time()+3600*24*30,"/");
            }else{
                setcookie("username",$row["username"],"/");
                setcookie("key",sha1(sha1($_POST["username"])."FREEDCRTTR".$row["sha1_password"]),"/");
            }
            die(json_encode($d));
        }else{
            $d=[
                "result"=>"failed",
                "reason"=>"Wrong Password"
            ];
            die(json_encode($d));
        }
    case "check_cookie":

        $SQL = "SELECT `sha1_password` FROM `user_list` WHERE
        `username`='{$_COOKIE["username"]}'
        ";
        $r=mysql_query($SQL,$conn);
        if($row=mysql_fetch_array($r)){
            if(isset($_COOKIE["username"])&&isset($_COOKIE["key"])){
                if($_COOKIE["key"]==sha1(sha1($_COOKIE["username"])."FREEDCRTTR".$row["sha1_password"])){
                    //233333333
                    //[FREEDCRTTR] is code;
                    $d=[
                        "result"=>"success",
                    ];
                    die(json_encode($d));
                }else{
                    $d=[
                        "result"=>"failed",
                        "reason"=>"Wrong Cookie"
                    ];
                    die(json_encode($d));
                }
            }else{
                $d=[
                    "result"=>"failed",
                    "reason"=>"No Cookie"
                ];
                die(json_encode($d));
            }
        }else{
            $d=[
                "result"=>"failed",
                "reason"=>"Database Error"
            ];
            die(json_encode($d));
        }


    case "edit":
        $SQL = "SELECT `sha1_password` FROM `user_list` WHERE
        `username`='{$_COOKIE["username"]}'
        ";
        $r=mysql_query($SQL,$conn);
        if($row=mysql_fetch_array($r)){
            if(isset($_COOKIE["username"])&&isset($_COOKIE["key"])){
                if($_COOKIE["key"]==sha1(sha1($_COOKIE["username"])."FREEDCRTTR".$row["sha1_password"])){

                    $SQL = "UPDATE `user_list` SET 
                    `email`='{$_POST["email"]}',
                    `phone`='{$_POST["phone"]}',
                    `sex`='{$_POST["sex"]}'
                    WHERE `username`='{$_COOKIE["username"]}'";
                    if(mysql_query($SQL,$conn)){
                        $d=[
                            "result"=>"success",
                        ];
                        die(json_encode($d));
                    }
                }else{
                    $d=[
                        "result"=>"failed",
                        "reason"=>"Wrong Cookie"
                    ];
                    die(json_encode($d));
                }
            }else{
                $d=[
                    "result"=>"failed",
                    "reason"=>"No Cookie"
                ];
                die(json_encode($d));
            }
        }else{
            $d=[
                "result"=>"failed",
                "reason"=>"Database Error"
            ];
            die(json_encode($d));
        }

    case "logout":
        $SQL = "SELECT `sha1_password` FROM `user_list` WHERE
        `username`='{$_COOKIE["username"]}'
        ";
        $r=mysql_query($SQL,$conn);
        if($row=mysql_fetch_array($r)){
            if(isset($_COOKIE["username"])&&isset($_COOKIE["key"])){
                if($_COOKIE["key"]==sha1(sha1($_COOKIE["username"])."FREEDCRTTR".$row["sha1_password"])){
                    setcookie("username","",time()-3600,"/");
                    setcookie("key","",time()-3600,"/");
                    $d=[
                        "result"=>"success",
                    ];
                    die(json_encode($d));
                }else{
                    $d=[
                        "result"=>"failed",
                        "reason"=>"Wrong Cookie"
                    ];
                    die(json_encode($d));
                }
            }else{
                $d=[
                    "result"=>"failed",
                    "reason"=>"No Cookie"
                ];
                die(json_encode($d));
            }
        }else{
            $d=[
                "result"=>"failed",
                "reason"=>"Database Error"
            ];
            die(json_encode($d));
        }
    case "get_data":
        $SQL = "SELECT `sha1_password` FROM `user_list` WHERE
        `username`='{$_COOKIE["username"]} ' AND `user_type`='0'
        ";
        $r=mysql_query($SQL,$conn);
        if($row=mysql_fetch_array($r)){
            if(isset($_COOKIE["username"])&&isset($_COOKIE["key"])){
                if($_COOKIE["key"]==sha1(sha1($_COOKIE["username"])."FREEDCRTTR".$row["sha1_password"])){

                    $SQL = "SELECT `username`,`email`,`phone`,`sex`,`user_type` FROM `user_list` WHERE 
                    `username`<>'{$_COOKIE["username"]}'
                    ";
                    if($r=mysql_query($SQL,$conn)){
                        $array=array();
                        while($row=mysql_fetch_assoc($r)){
                            $array[]=$row;
                        }
                        $d=[
                            "result"=>"success",
                            "data"=>$array
                        ];
                        die(json_encode($d));
                    }
                }else{
                    $d=[
                        "result"=>"failed",
                        "reason"=>"Wrong Cookie"
                    ];
                    die(json_encode($d));
                }
            }else{
                $d=[
                    "result"=>"failed",
                    "reason"=>"No Cookie"
                ];
                die(json_encode($d));
            }
        }else{
            $d=[
                "result"=>"failed",
                "reason"=>"Database Error"
            ];
            die(json_encode($d));
        }
}
?>


