<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>注册</title>
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<link rel="stylesheet" href="/gzh/staticV2/wap/css/reset.css">
    <link rel="stylesheet" href="/gzh/staticV2/wap/css/my.css">
    <link rel="stylesheet" href="/gzh/staticV2/wap/css/login.css">
	<link rel="stylesheet" href="/gzh/staticV2/wap/css/swiper3.1.0.min.css">
	<script src="/gzh/staticV2/wap/js/flexible.js" type="text/javascript"></script>
	<script src="/gzh/staticV2/wap/js/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script src="/gzh/staticV2/wap/js/swiper3.1.0.min.js" type="text/javascript"></script>
	<style>
		body {
			padding-top: 0.24rem;
		}
		html,body {
			background: white;
		}
	</style>
</head>
<body>
	<form action="<?php echo PU('user/register',['appid'=>$appid]);?>" class="forget ajaxForm" method="post">
		<ul>
			<li class="clearfix"><input type="text" name='username' id="mobile" placeholder="手机号"></li>
			<li class="clearfix code-text"><input type="text" name='code' placeholder="验证码"><span class="codes">获取验证码</span></li>
			<li class="clearfix"><input type="password" name='password' placeholder="新密码"></li>
			<li class="clearfix"><input type="password" name='re_password' placeholder="重复新密码"></li>
			<li class="clearfix"><input type="text" name='pmobile'  <?php if($_params['pmobile']): ?>value="<?php echo ($_params["pmobile"]); ?>" readonly="true"<?php else: ?>placeholder="邀请人手机号"<?php endif; ?>></li>
		</ul>
        <input type="hidden" name='reg_type' value="1"></li>
        <input type="hidden" name='appid' id="appid" value="<?php echo ($appid); ?>"></li>
		<p class="clause"><span id="clau"></span><a href="<?php echo PU('content/index',['id'=>2,'appid'=>$appid]);?>">我已阅读并同意注册服务协议</a></p>
		<div><a href="javascript:void(0)" title="" class="button" id="sub">注册</a></div>
		<p class="text">我已经有账号<a href="<?php echo PU('base/login',['appid'=>$appid]);?>" title="">马上登录</a></p>
	</form>
</body>
<script type="text/javascript">
var i=0;

$(function() {
    // $('#sub').click(function(){
    //     $('form').submit();
    // });

    $("#clau").click(function() {
        if(i==0){
            $("#clau").css("background-image", "url(/gzh/staticV2/wap/images/clause.png)");
            i=1;
        }else {
            $("#clau").css("background-image", "url(/gzh/staticV2/wap/images/pay_flase.png)");
            i=0;
        }
    });

    $(".button").click(function() {
        if (i == 0) {
            jError("请勾选已阅读并同意注册服务协议！");
            return;
        }else{
            $("form").submit();
        }
    });
    var button = $('.codes');
    var click = 60;
    button.click(function() {
        var mobile = $('#mobile').val();
        var appid = $('#appid').val();
        var username = $('#username').val();
        if (click < 60) {
            jError("还没到60秒！");
            return;
        }
        //提交数据
        $.ajax({
            type: 'get', //可选get
            url: '<?php echo U("user/regsms");?>', //这里是接收数据的PHP程序
            data: 'mobile='+mobile+'&appid='+appid, //传给PHP的数据，多个参数用&连接
            dataType: 'Json', //服务器返回的数据类型 可选XML ,Json jsonp script html text等
            success: function(msg) {

                if (msg.result.code == '10000') {
                    jSuccess(msg.result.msg, {TimeShown: 400});
                    var set = setInterval(function() {
                        button.text(click + '秒重新获取');
                       // $('.tjsfhqyzm').css('background', '#aaa');
                        if (click == 0) {
                            button.text('获取验证码');
                           // $('.tjsfhqyzm').css('background', '#26d7cf');
                            clearInterval(set);
                            click = 60;
                            return;
                        }
                        click = click - 1;
                    }, 1000);
                } else {
                    jError(msg.result.msg);
                }
                //这里是ajax提交成功后，PHP程序返回的数据处理函数。msg是返回的数据，数据类型在dataType参数里定义！
            },
            error: function() {

                jError("提交失败！");
            }
        });
    });
});
</script>

 <script type="text/javascript" src="/gzh/staticV2/wap/js/jquery.form.min.js"></script>
<script type="text/javascript" src="/gzh/staticV2/wap/plugins/jNotify/jNotify.js"></script>
<link rel="stylesheet" type="text/css" href="/gzh/staticV2/wap/plugins/jNotify/jNotify.css" />
<link href="/gzh/staticV2/assets/custom/js/skin/layer.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src='/gzh/staticV2/assets/custom/js/layer.js' ></script>
<script type="text/javascript">
$(function(){
	//ajax表单提交
	$('.ajaxForm').ajaxForm(function(response){
		if (typeof(response) == 'string') response = eval("("+response+")");
		if(response.result.code == '10000'){	//添加成功
			jSuccess(response.result.msg, {
				TimeShown : 400,
				onClosed:function(){
					window.location.href = response.link;
				}
			});
		}
		else{
			jError(response.result.msg,{TimeShown : 1000});
		}
	});
});

//ajax 请求 只支持get请求方法
function ajaxurl(url)
{
	$.ajax({
		url:url,
		data:'is_ajax=1',
		type:'get',
		dataType:'json',
		success:function(response)
		{
			//response = eval("("+response+")");
			if(response.result.code == '10000')
			{
				jSuccess(response.result.msg, {
					TimeShown : 800,
					onClosed:function(){
						window.location.href = response.link;
						//window.location.reload();
					}
				});
			}
			else
			{
				jError(response.result.msg);
			}
		},
		error:function()
		{
			alert('请求失败');
		}
	});
}

//确认对话框请求
function confirm_url(msg,url)
{
	layer.confirm(msg, {
	  	btn: ['取消','确定'], //按钮
	  	area: ['12rem', ''],
	}, function(index){
		layer.close(index);
	}, function(){
		ajaxurl(url);
	});
	// if (confirm(msg))
	// {
	// 	ajaxurl(url);
	// }
}


</script>
</html>