<div id="left">
    <div class="userimg">
        <!-- <div class="userpic"><img src="/static/index/images/file_bg.jpg"></div> -->
        <!-- <div class="userpic"><img src="{$headimg}"></div> <h1>墨色里了</h1> -->
        
    </div>
    <style type="text/css">
        .logintab{
            width: 190px;
            margin:0 auto;
        }
        .logintab_user {
            width: 190px;
            height: 40px;
            border: 1px solid #d1d1d1;
            overflow: hidden;
        }
        .logintab_user input {
            width: 155px;
            padding-left: 10px;
            height: 40px;
            float: left;
        }
        .logintab_user span {
            margin-top: 8px;
            display: block;
            float: left;
        }
        .logintab-div{margin-top: 15px;}
        .logintab-div label{
            height:20px;
            line-height: 20px;
            margin-bottom: 5px;
            display: block;
            font-size: 15px;
        }
        .logintab_a{margin-top: 30px;}
        .logintab_a .login {
            display: block;
            width: 100%;
            background: #B31C25;
            height: 35px;
            text-align: center;
            line-height: 35px;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            border:none;
        }
        .logintab_a .register{margin-top: 15px;}
        .forget{
            display: block;
            height: 30px;
            line-height: 30px;
            text-align: center;margin-top: 10px;
        }
        .forget:hover{color: #f47b22;}
        .userleft dd a span{display: inline-block;font-size: 12px;color: red;}
        .ieready{display: none;}
        .ieready-block{display: block;}
        .logintab_user .input-val{width:100px;}
    </style>
    <if condition="isset($_SESSION['user_auth'])">
        <dl class="userleft">
            <dt>导航菜单</dt>
            <dd><a href="/icenter/index" >职位信息</a></dd>
            <dd><a href="/icenter/index/resume">我的简历 <if  condition="!$issubmit"><span>未完成</span></if></a></dd>
            <if condition="$issubmit">
                <dd><a href="/icenter/index/upload">附件上传 <span class="istrue"></span></a></dd>
            </if>
            <dd><a href="/icenter/resumelist">申请记录</a></dd>
            <dd><a href="/icenter/usermessage">修改资料</a></dd>
            
            <dd><a href="/icenter/index/logout">退出登陆</a></dd>
        </dl>
    <else/>
        <form class="layui-form">
            <div class="logintab">
                <div class="logintab-div">
                    <label>账号登录</label>
                    <div class="logintab_user">
                        <input type="text" class="user" name="mobile" id="mobile" lay-verify="required" placeholder="请使用邮箱或手机号登录">
                        <span><i class="icon iconfont"></i></span>
                    </div>
                </div>
                <div class="logintab-div">
                    <label>密码</label>
                    <div class="logintab_user">
                        <input type="password" class="user" name="password" id="password" lay-verify="required" placeholder="请输入6-20位字符密码">
                        <span><i class="icon iconfont"></i></span>
                    </div>
                </div>

                <div class="logintab-div">
                    <label>验证码</label>
                    <div class="logintab_user">
                        <input type="text" class="user" name="code" id="code" lay-verify="required" placeholder="请输入验证码" style="width:80px;">
                        <img src="/Api/verify?w=100&h=40&type=3928&" id="yzm">
                    </div>
                </div>
               
                <div class="logintab_a">
                    <a href="javascript:;" lay-submit  lay-filter="login" class="login">登 陆</a>
                    <a href="/user/login/register" class="login register">注 册</a>
                    <a href="/user/login/reset" class="forget" target="_blank">忘记密码？点击找回</a>
                </div>
            </div>
        </form>
    </if>

    <div class="ieready" id="ieready">
        <p style="text-align: center;line-height: 22px;font-size: 14px;width: 200px;margin:0 auto;">请使用火狐<a style="text-decoration: underline;color: red;" href="/static/index/css/Firefox.exe" download="Firefox">点击下载</a>、谷歌<a style="text-decoration: underline;color: red;" href="/static/index/css/chrome_32.exe" download="chrome">点击下载</a>浏览器填写简历。</p>
    </div>
</div>
<script src="/static/index/js/jquery-3.5.1.min.js"></script>

<script src="/static/layui/layui.js"></script>
<script type="text/javascript">
    function isIE(){
        if (window.navigator.userAgent.indexOf("MSIE")>=1)    ieready.style.display='block'
        else return false; 
    }
    isIE()
</script>
<script>
    function refreshVerify(){
        $("#viewVerify").attr('src',$("#viewVerify").attr('src')+new Date().getTime());
    }
$(function(){
    yzmFun = function(){
        $('#yzm').attr('src',$('#yzm').attr('src')+new Date().getTime());
    }
    $("#yzm").on('click',function(){
        yzmFun();
    });
    layui.use(['layer','form'], function(){
    var form = layui.form,layer=layui.layer,istrue={$istrue};
    !istrue ? $('.istrue').html('未完成') : '';
    form.on('submit(login)', function(data){
            $.post('/user/login/ulogin',data.field,function(_data){
                if(_data.status == 1){
                    layer.msg(_data.info,{shift:-1,time:2000,icon:1},function(){
                        location.href="/icenter/index/";
                    });
                }else  layer.alert(_data.info,{
                        title:'登录失败错误信息',
                        icon:2
                    },function(){
                        window.location.reload();
                    });
                return false;
            },'JSON');
            return false;
        });
    });
})
</script>
