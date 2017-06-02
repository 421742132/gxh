<?php
namespace Gzh\Controller;
use Think\Controller;
class UserController extends BaseController
{

	public function index()
	{
		$this->display('index');
	}

	/*
	 * 注册
	 */

	public function register()
	{
		$_params = $this->getRequest('appid,pmobile'); //dump($_params['appid']);exit;
		if (IS_POST)
		{
			$_params = $this->getRequest('username,password,re_password,code,pmobile,reg_type,appid');
			$list = $this->callApi('customer', 'register', $_params, true, '注册成功', PU('base/login', ['appid' => $this->appid]));
		}

		$this->assign('appid', $_params['appid']);
		$this->assign('_params', $_params);
		$this->display('Login/register');
	}

		/*
	 * 忘记密码
	 */

	public function forgetresetpwd()
	{
		if (IS_POST)
		{
			$_params = $this->getRequest('mobile,password,re_password,code');
			if ($_params['password'] != $_params['re_password'])
				$this->ajaxResponse(false, '两次密码输入不一样');
			$list = $this->callApi('customer', 'resetpwd', $_params, true, '修改成功', PU('base/login', ['appid' => $this->appid]));
		}
		$this->display('Login/forgetresetpwd');
	}

	



}
