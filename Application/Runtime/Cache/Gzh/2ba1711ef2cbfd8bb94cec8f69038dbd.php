<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>忘记密码</title>
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<link rel="stylesheet" href="/gzh/staticV2/wap/css/reset.css">
    <link rel="stylesheet" href="/gzh/staticV2/wap/css/my.css">
	<link rel="stylesheet" href="/gzh/staticV2/wap/css/swiper3.1.0.min.css">
	<script src="/gzh/staticV2/wap/js/flexible.js" type="text/javascript"></script>
	<script src="/gzh/staticV2/wap/js/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script src="/gzh/staticV2/wap/js/swiper3.1.0.min.js" type="text/javascript"></script>
	<style>
		body {
			padding-top: 0.24rem;
		}
		html,body {
			height: 100%;
		}
        .forget li{
            padding-left: 5%;
            padding-right: 5%;
            height: 2.1rem;
            color: #999;
            font-size: 0.67rem;
            line-height: 2.1rem;
        }
        .forget li input {
            outline: none;
            width: 80%;
            height: 2.1rem;
        }
	</style>
</head>
<body>
	<form action="<?php echo PU('user/forgetresetpwd',['appid'=>$appid]);?>" class="forget ajaxForm" method="post">
		<ul>
			<li class="clearfix"><input type="text" name="mobile" id="mobile" placeholder="手机号"></li>
			<li class="clearfix code-text"><input type="text" name="code" placeholder="验证码"><span class="codes">获取验证码</span></li>
			<li class="clearfix"><input type="password" name="re_password" placeholder="新密码"></li>
			<li class="clearfix"><input type="password" name="password" placeholder="重复新密码"></li>
		</ul>
        <input type="hidden" name="appid" value="<?php echo ($appid); ?>">
		<div><a href="javascript:void(0)" title="" class="button" id="sub">修改</a></div>
	</form>
</body>
<script type="text/javascript">

$(function(){
	$('#sub').click(function(){
		$('form').submit();
	});
});

$(function() {
    var button = $('.codes');
    var click = 60;
    button.click(function() {
        var mobile = $('#mobile').val();
        if(mobile == '') jError("手机号不能为空！");
        var username = $('#username').val();
        var appid = '<?php echo ($appid); ?>';
        if (click < 60) {
            jError("还没到60秒！");
            return;
        }
        //提交数据
        $.ajax({
            type: 'get', //可选get
            url: '<?php echo U("user/forgetresetpwdsms");?>', //这里是接收数据的PHP程序
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