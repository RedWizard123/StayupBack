var Reg;
var switch_=false;
function q_a(s){
    return(document.querySelectorAll(s));
}
function q(s){
    return(document.querySelector(s));
}
function reg_check(){
    if(q("#username").value.length<2){
        alert("用户名输入太短！应在2~16个长度。");
        return(false);
    }
    Reg=/^[A-Za-zd]+([-_.][A-Za-zd]+)*@([A-Za-zd]+[-.])+[A-Za-zd]{2,5}$/;
    if(!Reg.test(q("#email").value)){
        alert("邮箱格式有误！");
        return(false);
    }
    Reg=/^1[34578]\d{9}$/;
    if(!Reg.test(q("#phone").value)){
        alert("电话号码输入有误！");
        return(false);
    }
    if(q("#sex").value=="-1"){
        alert("选择你的性别！");
        return(false);
    }
    if(q("#password").value!= "" && q("#password2").value!=""){
        if(q("#password").value!=q("#password2").value){
            alert("两次密码输入不一致");
            return(false);
        }
    }else{
        alert("请输入密码！");
        return(false);
    }
    if(!q("#document").checked){
        alert("若要注册，请同意协议！");
        return(false);
    }
    reqwest({
        url: 'php/query.php?operate=reg',
        method: 'post',
        data: {
            username: q("#username").value,
            email:q("#email").value,
            phone:q("#phone").value,
            sex:q("#sex").value,
            sha1_password:hex_sha1(q("#password").value),
            user_type:1
        },
        success: function(resp){
            eval("var d="+resp+";");
            if(d.result=="success"){
                window.location="result/reg_result.html";
            }else{
                //Duplicate entry 'RedWizard@qq.com' for key 'email'
                if(/^Duplicate entry '.*?' for key 'username'$/.test(d.reason)){
                    alert("用户名已存在！");
                }else if(/^Duplicate entry '.*?' for key 'email'$/.test(d.reason)){
                    alert("邮箱已存在！");
                }
            }
        }
    });
    return(true);
}

function login_check(){
    if(q("#username").value==""){
        alert("请输入用户名！");
        return(false);
    }
    if(q("#password").value==""){
        alert("请输入密码！");
        return(false);
    }
    reqwest({
        url: 'php/query.php?operate=login',
        method: 'post',
        data: {
            username: q("#username").value,
            sha1_password:hex_sha1(q("#password").value),
            remember:q("#remember").checked?"true":"false"
        },
        success: function(resp){
            eval("var d="+resp+";");
            if(d.result=="success"){
                window.location="home";
            }else{
                alert("登录失败：用户名不存在或密码错误");
            }
        }
    });
    return(true);

}
function edit(){
    var index;
    if(switch_==false){
        switch_=true;
        q("#save").innerHTML="保存";
        for(index=2;index<5;index++){
            q("#info"+index).removeAttribute("disabled");
        }
    }else{
        switch_=false;
        reqwest({
            url: '../php/query.php?operate=edit',
            method: 'post',
            data: {
                email:q("#info4").value,
                phone:q("#info3").value,
                sex:q("#info2").value
            },
            success: function(resp){
                eval("var d="+resp+";");
                if(d.result=="success"){
                    //alert("修改成功！");
                    history.go(0);
                }else{
                    alert("登录失败：用户名不存在或密码错误");
                }
            }
        });
        q("#save").innerHTML="修改";
        for(index=2;index<5;index++){
            q("#info"+index).setAttribute("disabled","");
        }
    }
}
function logout(){
    if(confirm("你确定要注销吗？")){
        reqwest({
            url: 'http://unique.duapp.com/php/query.php?operate=logout',
            method: 'get',
            success: function(resp){
                eval("var d="+resp+";");
                if(d.result=="success"){
                    window.location="http://unique.duapp.com/";
                }else{
                    alert("您的cookie已失效，已经注销");
                }
            }
        });
    }
}