<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>登录</title>
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<link rel="stylesheet" href="/gzh/staticV2/wap/css/reset.css">
	<link rel="stylesheet" href="/gzh/staticV2/wap/css/login.css">
	<link rel="stylesheet" href="/gzh/staticV2/wap/css/swiper3.1.0.min.css">
	<script src="/gzh/staticV2/wap/js/flexible.js" type="text/javascript"></script>
	<script src="/gzh/staticV2/wap/js/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script src="/gzh/staticV2/wap/js/swiper3.1.0.min.js" type="text/javascript"></script>
</head>
<body>
	<form action="<?php echo PU('base/login',['appid'=>$appid]);?>" class="login ajaxForm" method="post" >
		<a href="<?php echo PU('index/index',['appid'=>$appid]);?>" title="" class="back"><h2 style="height:4.36rem;"></h2></a>
		<div class="in">
			<div class="clearfix">
				<span></span>
				<input type="text" name='mobile' placeholder="请输入您的手机号">
				<!-- <span></span> -->
			</div>
			<div class="clearfix">
				<span></span>
				<input type="password" name='password' placeholder="请输入密码">
				<!-- <span></span> -->
			</div>
		</div>
		<input type="hidden" name='appid' value="<?php echo ($appid); ?>">
		<input name="url" type="hidden" value="<?php echo ($url); ?>">
		<a href="javascript:void(0);" class="button" id='login'>登录</a>
		<a href="<?php echo PU('user/forgetresetpwd',['appid'=>$appid]);?>" title="" class="for" style="top:14.4rem;">忘记密码?</a>
		<p>我还没有账号<a href="<?php echo PU('user/register',['appid'=>$appid]);?>" title="">马上创建</a></p>
		<input type="hidden" name='login' value='1'>
	</form>


</body>

<script type="text/javascript">

$(function(){
	$('#login').click(function(){
		$('.ajaxForm').submit();
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