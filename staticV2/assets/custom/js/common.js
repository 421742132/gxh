// $(document).ready(function(){

	var tbSelect;
	

	$("body").delegate(".ajax-form","submit",function(){ 
		App.blockUI();
		var jform = $(this);
		var requestData = new FormData($(this)[0]);
		$.ajax({
			cache: false,
			processData: false,
			contentType: false,
			type: "POST",
			url:$(this).attr('action'),
			data:requestData,
			async: true,
			error: function(request) {
				App.unblockUI();
			    alert("Connection error");
			},
			success: function(response) {
				App.unblockUI();
				response = eval("("+response+")");
				if(response.result.code == 'SUCCESS')
				{
					layer.msg(response.result.msg,{icon:1,time:1000},function(){
						var formcallback = jform.attr('data-call');
						if (formcallback)
						{
							doCallback(eval(formcallback),[response.data]);
						}
						else
						{	
							window.location.href = response.link;
						}
					});
					/*
					jSuccess(response.result.msg, {
					VerticalPosition : 'center',
					HorizontalPosition : 'center',
					TimeShown : 800,
					onClosed:function(){
						var formcallback = jform.attr('data-call');
						if (formcallback)
						{
							doCallback(eval(formcallback),[response.data]);
						}
						else
						{	
							window.location.href = response.link;
						}

					}
					});	
					*/

				}
				else
				{
					// alert('aa');
					// alert(response.result.msg);
					layer.msg(response.result.msg,{icon:2});
					// jError(response.result.msg);
				}
			}
		});
		return false;

	});

	$("select.ajax-data").each(function(){
		var dom = $(this);
		var url = dom.attr('url');
		var sid = dom.attr('sid');
		// alert(sid);
		$.ajax({
			url:url,
			dataType:'json',
			success:function(response){
				if(response.result.code == 'SUCCESS')
				{
					for(i in response.data)
					{	
						dom.append('<option value="'+response.data[i].id+'">'+response.data[i].name+'</option>');		
						
					}
					if (sid) dom.val(sid);
				}
			},
			error:function(){}
		});
	});
	
	$(".allcheck").click(function(){
		if($(this).is(':checked'))
		{
			$(this).parents("table").find("input[type=checkbox]").prop("checked",true);
		}
		else
		{
			$(this).parents("table").find("input[type=checkbox]").prop("checked",false);
		}
	});
	
	
		
	//选择弹出层
	$(".popup").on('click',function(){
		var dom = $(this);
		tbSelect = $(this);
		var url = dom.attr('url');
		layer.open({
					title:'',
					type: 2,
					area: ['850px','600px'],
					shadeClose: true, //点击遮罩关闭
					content: url
				});
		
	});
	
	$(".selectid").on('click',function(){
		var foreign_key = $(this).attr('foreign_key');
		parent.$("input[name="+foreign_key+"]").val($(this).attr('value'));
		parent.layer.closeAll();
	});
	
	$('.date-picker').datepicker({orientation: "left",autoclose: true,format: "yyyy-mm-dd"});
	$('.form_datetime').datetimepicker({orientation: "left",autoclose: true,format: "yyyy-mm-dd hh:ii",language:"zh-CN"});


	//ajax 请求 只支持get请求方法
	function ajaxurl(url)
	{
		$.ajax({
			url:url,
			data:'is_ajax=1',
			type:'get',
			dataType:'json',
			success: function(response) {
				App.unblockUI();
				//response = eval("("+response+")");
				if(response.result.code == 'SUCCESS')
				{
					layer.msg(response.result.msg,{icon:1,time:1000},function(){
						window.location.href = response.link;
						/*var formcallback = jform.attr('data-call');
						if (formcallback)
						{
							doCallback(eval(formcallback),[response.data]);
						}
						else
						{	
							console.log(response.link);
							window.location.href = response.link;
						}*/
					});
					/*
					jSuccess(response.result.msg, {
					VerticalPosition : 'center',
					HorizontalPosition : 'center',
					TimeShown : 800,
					onClosed:function(){
						var formcallback = jform.attr('data-call');
						if (formcallback)
						{
							doCallback(eval(formcallback),[response.data]);
						}
						else
						{	
							window.location.href = response.link;
						}

					}
					});	
					*/

				}
				else
				{
					// alert('aa');
					// alert(response.result.msg);
					layer.msg(response.result.msg,{icon:2});
					// jError(response.result.msg);
				}
			}
			
			/*success:function(response)
			{
				//response = eval("("+response+")");
				if(response.result.code == 'SUCCESS')
				{

					jSuccess(response.result.msg, {
						TimeShown : 800,
						onClosed:function(){
							window.location.href = response.link;
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
			}*/
		});
	}

	//确认对话框请求
	function confirm_url(msg,url)
	{
		if (confirm(msg))
		{
			ajaxurl(url);
		}
	}

	/**
	 * 执行回调函数
	 * @param  {Function} fn   [description]
	 * @param  {[type]}   args [description]
	 * @return {[type]}        [description]
	 */
	function doCallback(fn,args)
	{
	    fn.apply(this, args);
	}
		
