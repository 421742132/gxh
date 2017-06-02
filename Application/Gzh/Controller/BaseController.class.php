<?php
namespace Gzh\Controller;
use Think\Controller;
class BaseController extends Controller
{
	public $model;
	protected $isSubmit;
	public $ca;
	public $usid;
	public $ucid;
	public $appid;
	public $umid;
	public $ucopenid;
	public $usinfo;
	public $uminfo;
	public $umrole;
	public $umopenid;
	public $snsopenid;
	public $snsinfo;

	public function _initialize()
	{
//		 session('snsinfo',['snsopenid'=>123123,'ucopenid'=>'41a4f6082246d208bfa93019c6ad7777','appid'=>'536b2322555f226f']);
//		 $this->ucopenid = '41a4f6082246d208bfa93019c6ad7777';
/*
		$this->setIsSubmit();
		$this->setMa();
		$this->footer();
		$this->setUminfo();
		$this->setCommon();
		$this->isLogin();
		$this->setUsinfo();
		$this->assign('appid',$this->appid);

		$this->setBusinessShare();
		*/
	}
	/*
	 * 业务类共享数据  省传参
	 */
	public function setBusinessShare()
	{
		Share::$ucinfo = $this->usinfo;
		Share::$uminfo = $this->uminfo;
		Share::$appid = $this->appid;
		Share::$ucid = $this->usid;
		Share::$ucopenid = $this->ucopenid;
		Share::$umid = $this->umid;
		Share::$umopenid = $this->umopenid;
		Share::$snsopenid = $this->snsopenid;
	}

	//获取分销商信息
    public function setUminfo()
	{
		if(!$this->appid)
		{
			$appid =$this->getRequest('appid');
			$this->appid = $appid['appid'];
		}
		if(!$this->umid)
		{
			$field = array();
			$field['user_merchant'] = 'umopenid';
			$field['user_merchant_app'] = 'umid';
			$join[] = array('user_merchant','umid','id','inner join');
			$data = $this->loadModel('userMerchantApp')->getJoinInfo($field,$join,array('appid'=>$this->appid));
			$this->umid = $data['umid'] ;
			$this->umopenid = $data['umopenid'];
		}

	}
	/*
	 * 公共
	 */
	public function setCommon()
	{
		$this->_setUserMerchant();
		$this->setSalerUcopenid();
	}

	public function setSalerUcopenid()
	{
        $saler_ucopenid = I('saler_ucopenid');
        if ($saler_ucopenid)
        {
            cookie('saler_ucopenid', $saler_ucopenid, 3600*24);
        }
    }

	public function setUsinfo()
	{
		$usinfo = session('usinfo');
		$snsInfo = session('snsinfo');
		if($usinfo)
		{
			$this->usid = $usinfo['id'];
			$this->ucid = $usinfo['id'];
			$this->ucopenid = $usinfo['ucopenid'];
			$this->usinfo['user_customer'] = $this->usinfo = $this->loadModel('userCustomer')->getInfo('*',array('id'=>$usinfo['id']));
			$this->appid = $this->usinfo['appid'];
			$this->umid = $this->usinfo['umid'];
			$umopenid = $this->loadModel('userMerchant')->getInfo('*',array('id'=>$this->umid));
			$this->umopenid = $umopenid['umopenid'];
			$this->uminfo['user_merchant'] = $umopenid;

			$user_merchant_app = $this->loadModel('userMerchantApp')->getInfo('*',array('umid'=>$this->umid));
			$this->uminfo['user_merchant_app'] = $user_merchant_app;

			$where = ['ucid'=>$this->usid];
			$user_customer_info = $this->loadModel('userCustomerInfo')->getInfo('*',$where);
			$this->usinfo['user_customer_info'] = $user_customer_info;
			$this->assign('usinfo',$this->usinfo);
		}else if($snsInfo)
		{
			$this->snsopenid = $snsInfo['snsopenid'];
			$this->ucopenid = $snsInfo['ucopenid'];
			$this->snsinfo = $snsInfo;
			$this->assign('snsinfo',$snsInfo);
		}
	}

	/*
	 * 商户
	 */
	private function _setUserMerchant()
	{
		$_params = $this->getRequest('appid');
		if(!$this->appid)
		{
			$this->assign('errortans','appid不能为空');
			$this->display('Login/close');
			exit;
		}
		$this->appid = $_params['appid'];
		$where = ['appid'=>$this->appid];
		$user_merchant_app = $this->loadModel('userMerchantApp')->getInfo('*',$where);
		if(!$user_merchant_app)
		{
			$this->assign('errortans','不存在该应用');
			$this->display('Login/close');
			exit;
		}
		$this->uminfo['user_merchant_app'] = $user_merchant_app;
		$this->umid = $this->uminfo['user_merchant_app']['umid'];

		$where = ['id'=>$this->umid];
		$user_merchant = $this->loadModel('userMerchant')->getInfo('*',$where);
		if($user_merchant['state'] != 'open')
		{
			$this->assign('errortans','应用处于异常，请联系客服');
			$this->display('Login/close');
			exit;
		}
		$this->uminfo['user_merchant'] = $user_merchant;
		$this->umopenid = $this->uminfo['user_merchant']['umopenid'];
		$this->umrole = $this->uminfo['user_merchant_app']['role'];
		$this->assign('appid',$this->appid);
		$this->assign('umidrole',$this->uminfo['user_merchant_app']['role']);
	}

	public function footer()
	{
		$action = strtolower($this->ca['c']."/".$this->ca['a']);
		$module = strtolower($this->ca['c']."/*");
		$index = array('index/index');
		$wealth = array();
		$show = array('buyshow/*');
		$user = array('order/*','user/*','setting/*','saler/favorite','saler/message');
		$message = array('index/exchangeindex');
		if(in_array($action,$index) || in_array($module,$index))
		{
			$this->assign('foot','index');
		}
		// elseif(in_array($action,$wealth) || in_array($module,$wealth))
		// {
		// 	$this->assign('foot','wealth');
		// }
		// elseif(in_array($action,$show) || in_array($module,$show))
		// {
		// 	$this->assign('foot','show');
		// }
		elseif(in_array($action,$user) || in_array($module,$user))
		{
			$this->assign('foot','user');
		}
		elseif(in_array($action,$message) || in_array($module,$message))
		{
			$this->assign('foot','message');
		}


	}

	public function setMa()
	{
		$this->ca['c'] = CONTROLLER_NAME;
		$this->ca['a'] = ACTION_NAME;
	}

	public function loadModel($model)
    {
        $this->model[$model] = D('Common/'.$model);
        return $this->model[$model];
    }

	//判断是否登录
	public function isLogin()
	{
		//微信第三方登录 测试入口（没有正式，暂时的入口）
		$snsinfo = session('snsinfo');
		if($_GET['weixin'] && !$snsinfo)
		{
			session('snsinfo', ['ucid'=> 0, 'ucopenid'=> '12345679123', 'appid'=> $_GET['appid']]);
		}
		//微信第三方登录 end
		$action = strtolower($this->ca['c']."/".$this->ca['a']);
		$module = strtolower($this->ca['c']."/");
		$nocheck = array('goods/appinfo','index/appindex','goods/info','goods/lists','goods/comment','goods/commentmore','base/login','base/logout','base/code','wechat/login','wechat/loginsave','index/index','user/register','user/regsms','user/index','user/forgetresetpwd','user/forgetresetpwdsms','content/','goods/appexchangeinfo');

		if (!session('usinfo') && !session('snsinfo') && !in_array($action,$nocheck) && !in_array($module,$nocheck))
		{
			$url = I('url', $_SERVER["REQUEST_URI"]);
            if ($url) $url = base64_encode($url);
			header("Location:".PU('base/login',['appid'=>$this->appid,'url' => $url]));
			exit;
		}
		//appid与用户所属appid不一致退出
		if (session('usinfo'))
		{
			$userappid = session('usinfo');
			if($userappid['appid'] != $this->appid)
			{
				if(!(($this->uminfo['user_merchant_app']['role'] == 3 && $userappid['is_bjg']) || ($this->uminfo['user_merchant_app']['role'] == 2 && $this->uminfo['user_merchant_app']['level'] == 1)))
				{
					session(null);
					cookie('saler_ucopenid',null);
				}
			}
		}elseif(session('snsinfo'))
		{
			$userappid = session('snsinfo');//dump($userappid);
			if($userappid['appid'] != $this->appid)
			{
				session(null);
				cookie('saler_ucopenid',null);
			}
		}
	}

	//登录界面
	public function login()
	{
		$url = I('url');
		$_params = $this->getRequest('mobile,password,appid');
		if (I('login'))
		{
			$this->vaild_params('is_empty',$_params['appid'],'appid不能为空');
			$this->appid = $_params['appid'];
			$this->vaild_params('is_empty',$_params['mobile'],'请输入手机号');
			$this->vaild_params('is_empty',$_params['password'],'请输入密码');
			$login['username'] = $_params['mobile'];
			$login['password'] = $_params['password'];
			$rs = $this->callApi('customer', 'login', $login);

			$where = ['ucopenid'=>$rs['base']['ucopenid']];
			$user_customer = $this->loadModel('userCustomer')->getInfo('*',$where);
			$this->vaild_params('is_empty', $user_customer , '用户不存在');
			if($user_customer['state'] == 3) $this->apiOut(false,'用户已冻结');

			session('access_token',$rs['base']['access_token']);
			session('usinfo',$user_customer);
			session('ucopenid',$rs['base']['ucopenid']);
			if ($url)
			{
				$action = strtolower($this->ca['c']."/".$this->ca['a']);
				$module = strtolower($this->ca['c']."/");
				$nocheck = array('ordershop/prepare','user/address','user/newaddress','pay/wap','cart/list');
				$url = base64_decode($url);
			}
			$re['href'] = $url?$url:PU('index/index',['appid'=>$this->appid]);
			if (in_array($action,$nocheck) && in_array($module,$nocheck))  $this->ajaxResponse(true,'登录成功！',PU('index/index',['appid'=>$this->appid]));
			$this->ajaxResponse(true,'登录成功！',$re['href']);
		}
		$this->assign('url',$url);
		$this->assign('appid',$_params['appid']);
		$this->display('Login/login');
	}



	//验证码
	public function code()
	{
		\Org\Util\Image::verify();
	}

	public function logout()
	{
		session(null);
		cookie('saler_ucopenid',null);
		header("Location:".PU('base/login',['appid'=>$this->appid]));
		exit;
	}



    /*
	*	接口响应输出
	*	@param			int		$result_code	响应代码
	*	@param			string	$result_msg		接口响应信息
	*	@param			array	$data					接口数据
	*/
	protected function response($result_code,$result_msg,$data = array(),$link='')
	{
		$response = array();
		$response['result']['code'] = $result_code;
		$response['result']['msg'] = $result_msg;
		$response['link'] = $link;
		$response['data'] = $data;
		echo json_encode($response);
		exit;
	}

	/*
	*	api输出
	*	@param			array		$data		要显示的接口数据或根据数据判断接口显示的结构体
	*	@param			bool		$show	是否显示数据结构体，如果false只显示result部分，不显示data部分
	*/
	protected function ajaxResponse($data = array(),$msg = '',$link = '')
	{
		if (!$data)
		{
            if (empty($msg)) $msg = '操作失败，请联系管理员';
			$this->response(99999,$msg,array(),$link);
		}
		else
		{
            if (empty($msg)) $msg = '操作成功';
            $this->response(10000,$msg,$data,$link);
		}
	}


	/*
	*	获取指定的请求参数
	*	@param			array			$field		需要获取的参数的字段名
	*	@param			string			$method		获取类型，可取值_request,_get,_post
	*/
	protected function getRequest($fields = "*")
	{
        $request = false;
        if ($fields == "*")
        {
            $request = I();
        }
        else
        {
            $_request = is_array($fields)?$fields:explode(",",$fields);
            foreach($_request as $item)
            {
                $request[$item] = I($item);
            }
        }
        return $request;
	}

	protected function setIsSubmit()
	{
		$this->isSubmit = IS_POST;
	}

	public function vaild_params($call,$params,$msg='',$rule = true)
	{
		if (!is_callable($call))
		{
			throw new Exception($call." can not callable!");
		}
		$params = !is_array($params)?array($params):$params;
		if (call_user_func_array($call,$params) == $rule)
		{
			return true;
		}
		else
		{
			$this->ajaxResponse(false,$msg);
		}

	}

	public function callApi($c,$a,$params=array(),$is_direct_return=false,$msg='',$link='')
	{
		$host = $_SERVER['HTTP_HOST'];
		$http = C('APP_SUB_DOMAIN_RULES');
		foreach ($http as $key => $value)
		{
			if ($value == 'api' or $value == 'Api')
				$size = $key;
		}
		foreach ($http as $key => $value)
		{
			if (stripos($host, $key.'.') === 0)
			{
				$hostname = str_replace($key.'.', $size.'.', $host);
				break;
			}
		}
		if($_SERVER['HTTPS'] && $_SERVER['HTTPS'] == 'on')
	    {
	        $apiurl = "https://".$hostname;
	    }
	    else
	    {
	        $apiurl = 'http://'.$hostname;
	    }
		$apiurl ='http://api.ysk.com';
		// $usarr['access_token'] = session('access_token');
		// if(session('ucopenid'))
		// {
		// 	$usarr['ucopenid'] = session('ucopenid');
		// }
		// $params = array_merge($params,$usarr);
		$params = http_build_query($params);
		$url = $apiurl.'/index.php/V1/'.$c.'/'.$a.'?&appid='.$this->appid.'&'.$params;
		//echo $url;exit();
		$rs = file_get_contents($url);
		$rs = json_decode($rs,true);//dump($rs);exit;
		if($rs['result']['code'] == 10000)
		{
			if($is_direct_return)
			{
				$this->ajaxResponse(true,$msg,$link);
			}
			return $rs['data'];
		}
		else
		{
			$this->ajaxResponse(false,$rs['result']['msg']);
		}
	}

	// public function callResponse($rs,$is_direct_return=false,$msg='',$link='')
	// {
	// 	$rs = json_decode($rs,true);
	// 	if($rs['result']['code'] == 10000)
	// 	{
	// 		if($is_direct_return)
	// 		{
	// 			$this->ajaxResponse(true,$msg,$link);
	// 		}
	// 		return $rs['data'];
	// 	}
	// 	else
	// 	{
	// 		$this->ajaxResponse(false,$rs['result']['msg']);
	// 	}
	// }

	/**
	 * 获取供应商的umid
	 * 当appid的role是合作商的时候，获取的商品是宝聚阁的商品
	 */
	public function get_supplier_umid()
	{
		if($this->uminfo['user_merchant_app']['role'] == UserMerchantStruct::BRAND_MERCHANT_TYPE)
		{
			return $this->umid;
		}else{
			$baojuge_info = getBaoJuGeInfo();
			return $baojuge_info['id'];
		}
	}

}