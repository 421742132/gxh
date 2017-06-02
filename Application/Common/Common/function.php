<?php

/*
 *	检查一个字符串或数组是否为空
 *	@
 */
function is_empty($str)
{
	return empty($str)?false:true;
}

/*
 *	用数组中的某个值重设数组键值
 *
 */
function reset_array_key($array,$key,$field = '')
{
	$_array = array();
	foreach($array as $value)
	{
		$_array[$value[$key]] = !empty($field)?$value[$field]:$value;
	}
	return $_array;
}

/*
 *
 *	比较函数,$oper为比较运算符
 */
function compare($num1,$num2,$oper=">")
{
	switch ($oper) {
		case '==':
			$result = ($num1==$num2);
			break;
		case '===':
			$result = ($num1===$num2);
			break;
		case '<':
			$result = ($num1<$num2);
			break;
		case '<=':
			$result = ($num1<=$num2);
			break;
		case '!=':
			$result = ($num1!=$num2);
			break;
		case '<>':
			$result = ($num1<>$num2);
			break;
		case '>=':
			$result = ($num1>=$num2);
			break;
		case '>':
			$result = ($num1>$num2);
			break;
		default:
			$result = false;
			break;
	}
	return $result;
}

/*
 *	检查两个指是否相等
 */
function eq($arg1,$arg2)
{
	return ($arg1 == $arg2)?true:false;
}



function array_values_to_string(&$array)
{
	foreach($array as $key=>&$value)
	{
		if (is_array($value))
		{
			array_values_to_string($value);
		}
		else
		{
			$value = (string) $value;
		}
	}
}

function getFilePath($fileUrl,$type='user')
{
	if($fileUrl)
	{
		$fileUrl = explode(',',$fileUrl);
		foreach ($fileUrl as $key => &$value)
		{
			$value = \Common\Library\File::tmp_to_final($value, 'image', $type);
			if(!$value) return false;
		}

		$filePath = implode(',',$fileUrl);
		return $filePath;
	}
	else
	{
		return false;
	}
}


function loadPlugin($plugin,$class,$method,$params)
{
    $pluginClass = "\\Common\\Plugin\\".$plugin."\\".ucfirst($class);
    if (!class_exists($pluginClass) || !method_exists($pluginClass,$method)) return false;
    $obj = new $pluginClass();
    return $obj->$method($params);

}




/*
*   毫秒时间戳
*/
function mtime($_time = '')
{
    if (empty($_time))
    {
        list($t1, $t2) = explode(' ', microtime());
        $time = (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
        $time = (string) $time;
    }
    else
    {
        $time = strtotime($_time)."000";
    }
    return $time;
}



function page($count,$psize = '')
{
	$psize = ($psize>0)?$psize:C('psize');
	$psize = $psize?$psize:40;
	$page = new \Think\Page($count,$psize);
	return $page->show();
}

/*
 *	视图模板中输出时间
 */
function vtime($format="Y-m-d",$time)
{
	if (!$time)
	{
		return "-";
	}
    $time=substr($time,0,10);
	return date($format,$time);
}

/*
 *  视图模板中输出时间
 */
function otime($time)
{
    if (!$time)
    {
        return "-";
    }
    $time=substr($time,0,10);
    return date('Y-m-d H:i:s',$time);
}


/*
 *	状态描述
 */
function status_desc($type = 'STATUS',$status)
{
	$status_arr = C($type);
	if (!$status_arr)
	{
		return false;
	}
	return $status_arr[$status];
}

/*
*   独占锁定，处理并发请求
*/
function lock($key)
{
    return apcu_add($key,'locked');
}

function unlock($key)
{
    return apcu_delete($key);
}

function _U($params = array())
{

    $_query = $_GET;
    unset($_query['_URL_']);
    unset($_query['p']);
    $_uri = explode('?',$_SERVER['REQUEST_URI']);
    $url = preg_replace("/\/p\/[0-9]*\//","/",rtrim($_uri[0],"/")."/");

    foreach($_query as $key=>$value)
    {
        if (strpos($url,$key."/") !== false)
        {
            $str = ($value == '')?'':"/".$key.'/'.$value.'/';

            $url = preg_replace("/\/".$key."[^\/]*\/[^\/]*\//",$str,$url);
        }
        else
        {
        $url .= $key.'/'.$value.'/';
        }
    }

    $url = preg_replace("/[^\/]*\/[\s]*\//","",$url);
    $url = rtrim($_SERVER['HTTP_HOST'].$url,'/').'/';

    if (empty($params)) return "http://".$url;
    foreach($params as $key=>$value)
    {
        if (strpos($url,$key."/") !== false)
        {
            $str = ($value === false)?'':$key.'/'.$value.'/';
            $url = preg_replace("/".$key."[^\/]*\/[^\/]*\//",$str,$url);
        }
        else
        {
            if ($value === false) continue;
            $url .= $key.'/'.$value.'/';
        }
    }

    return "http://".str_replace("//","/",$url);
}

/**
 * 格式化url参数
 * @param  array  $params [description]
 * @return [type]         [description]
 */
function urlParams($params = array())
{
    $params = empty($params) ? I("get.") : $params;
    foreach ($params as $key => $value) {
        if (is_array($value))
        {
            if (empty(array_filter($value)))
            {
                unset($params[$key]);
            }
            foreach($value as $k=>$v)
            {
                if ($v != '')
                {
                    $params[$key."[".$k."]"] = $v;
                }
            }
            unset($params[$key]);
        }
        if ($value == '') unset($params[$key]);
    }
    return $params;
}

function httppost($url,$content,$header = array())
{
    $return = array();
    $ch  =  curl_init ();
    curl_setopt ( $ch ,  CURLOPT_URL ,  $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($content) || !empty($header))
    {
        curl_setopt ( $ch ,  CURLOPT_POST ,  1 );
        curl_setopt ( $ch ,  CURLOPT_POSTFIELDS , $content);
    }
    if (!empty($header))
    {
        curl_setopt ($ch, CURLOPT_HTTPHEADER , $header);  //构造IP
    }
    curl_setopt ( $ch ,  CURLOPT_RETURNTRANSFER,  1 );
    $response = curl_exec($ch);
    $return = ['code'=>curl_errno($ch),'msg'=>curl_error($ch),'content'=>$response];
    curl_close($ch);
    return $return;
}

/**
 * 下划线转驼峰
 * @param  string  $str     需要转换的字符串
 * @param  boolean $ucfirst 首字母是否大写
 * @return string           转换后的字符串
 */
function convertUnder($str,$ucfirst = false)
{
    $str = ucwords(str_replace('_', ' ', $str));
    $str = str_replace(' ','',lcfirst($str));
    return $ucfirst ? ucfirst($str) : $str;
}

/*
*   递归创建目录
*   必须是绝对目录
*/
function xmkdir($pathurl)
{
    $path = "";
    $str = explode("/",$pathurl);
    foreach($str as $dir)
    {
        if (empty($dir)) continue;
        $path .= "/".$dir;
        if (!is_dir($path))
        {
            mkdir($path);
        }
    }
}

function datetime($time)
{
    return $time>0 ? date("Y-m-d H:i:s",substr($time,0,10)):'-';
}

function dateday($time)
{
    return $time>0 ? date("Y-m-d",substr($time,0,10)):'-';
}

/*
*   词典读取
*/
function dict($index,$value = NULL)
{
    $dict = C('dict');
    if (!$index || !$dict[$index])
    {
        return false;
    }
    return $value ? $dict[$index][$value] : $dict[$index];
}

/*
* 生成词典链接
*/
function dictlink($index,$controller='')
{
    if (empty($controller)) $controller = CONTROLLER_NAME;
    return U($controller.'/ajaxdict',['index'=>$index]);
}


/**
 * 扩展编码转换，可针对数组转换
 * @param  mix $mix         需要转换的参数
 * @param  string $in_charset  原编码
 * @param  string $out_charset 转换的编码
 * @return [type]              [description]
 */
function _iconv($mix,$in_charset = 'gbk',$out_charset = 'utf-8')
{
    if (is_string($mix))
    {
        $mix = iconv($in_charset, $out_charset, trim($mix));
    }
    else
    {
        foreach ($mix as &$str) {
            $str = iconv($in_charset, $out_charset, trim($str));
        }
    }
    return $mix;
}

function addCache($k,$v,$ttl = 180)
{
	return S($k,$v,$ttl);
    //return apcu_add($k,$v,$ttl);
}

/*
*	随机码生成
*	@param			int 		$length		长度
*/
function randcode($length)
{
	if (ENT_DEBUG) return 5555;
	$start = pow(10,($length-1));
	$end = pow(10,$length)-1;
	return rand($start,$end);
}

function unsetCache($k)
{
    return apcu_delete($k);
}

function isChain($url)
{
    $url = strtolower($url);
    if (substr($url,0,7) == 'http://' || substr($url,0,8) == 'https://')
    {
        return true;
    }
    return false;
}

function formatTime($time){
    return date('Y/m/d H:i',$time);
}
//生成短openid
function makeShortOpenid($id)
{
	return $id.time().rand(1000, 9999);
}

//将整型字符串转为数字
function intValue($int)
{
	return floor(floatval($int));
}

/*  查询物流明细
    logistics 物流名称
    logistics_no 物流单号
*/
function getExpress($logistics,$logistics_no)
{
    if ($logistics && $logistics_no) {
        Vendor('Express.Express','','.class.php');
        $exp = new Express();
        $express = $exp->getorder($logistics,$logistics_no);
    }
    if ($express['status']!='200') $express['data'][1]['context'] = '暂无物流信息';
    return $express;
}

function joinImg($img)
{
    if(!$img)
    {
        return '';
    }
    else
    {
        $img = explode(',',$img);
        foreach($img as &$val)
        {
            if(substr($val,0,1) == '/')
            $val = 'http://'.I('server.HTTP_HOST').$val;
        }
        $img = implode(',',$img);
    }
    return $img;
}

//极光推送
function jpush($receive='all',$content='',$content_message='')
{
    //调用推送,并处理
    Vendor('Jpush.jpush');//调用 极光 接口
    $pushObj = new jpush();
    $result = $pushObj->push($receive,$content,$m_type='',$m_txt='',$m_time='86400',$content_message);
}

//发送短信
function sendsms($mobile,$content)
{
    if (SMSDEBUG) return true;
    Vendor('SMS.SMSHelper');
    $useclass = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[1]['class'];

    if (in_array($useclass,array('yeepayAction','tftpayAction','payAction')))
    {
        $rs = SMSHelper::sendSMS($mobile, $content);
        send_sms_count(SEND_TYPE_SMS,$mobile, $content);

    }
    else
    {
        $rs = SMSHelper::sendSMSC2($mobile, $content);
        send_sms_count(SEND_TYPE_SMSC2,$mobile, $content);
    }

    return $rs;
}


/*
 *   生成短链接
 *   调用新浪API出错的话将调用百度的，也失败直接返回原URL
 */
function getShortUrl($url)
{
	$gate = 'http://api.t.sina.com.cn/short_url/shorten.json';
	$appkey = '4070656144';
	$rs = "{$gate}?source={$appkey}&url_long={$url}";
	$arr = json_decode(file_get_contents($rs), true);
	if (!$arr)
	{
		$gate = 'http://dwz.cn/create.php';
		$opts  = array(
				'http' =>array(
						'method' => "POST" ,
						'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
						'content'=>"url=".$url
				)
		);
		$context  =  stream_context_create ( $opts );
		$rs = file_get_contents ( $gate,  false ,  $context );
		$arr = json_decode($rs,true);
		if ($arr['tinyurl']) $url = $arr['tinyurl'];
	}
	else
	{
		if ($arr[0]['url_short']) $url = $arr[0]['url_short'];
	}
	return $url;
}



function PU($ma,$params=array())
 {
    // $array = array('us_usid'=>172);
    // if($params)
    // {
    //     $params = array_merge($params,$array);
    // }
    // else
    // {
    //     $params = $array;
    // }

    return U($ma,$params);

 }

 function get2Array2Array($array, $field='id')
 {
	 if(!$array)
	 {
		 return [];
	 }
	 $temp = [];
	 foreach ($array as $value)
	 {
		 if(is_array($value))
		 {
			 $temp[]= $value[$field];
		 }else{
			  $temp[]= $value;
		 }
	 }
	 return $temp;
 }


//判断是否是微信客户端
function is_weixin()
{
	if ( stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
	}
	return false;
}

//获取wap链接
function getWapLink()
{
    $host = $_SERVER['HTTP_HOST'];
    $http = C('APP_SUB_DOMAIN_RULES');
	foreach ($http as $key => $value)
    {
        if($value == 'wap' or $value == 'Wap') $size = $key;
    }
	foreach ($http as $key => $value)
    {
		if(stripos($host, $key.'.') === 0)
		{
			$wap = str_replace($key.'.', $size.'.', $host);
			break;
		}
    }
	if($_SERVER['HTTPS'] && $_SERVER['HTTPS'] == 'on')
	{
		$wap = "https://".$wap;
	}
	else
	{
		$wap = 'http://'.$wap;
	}
    return $wap;
}

/**
 * 获取宝聚阁信息
 */
function getBaoJuGeInfo()
{
	$where = ['role'=> \Common\Business\Struct\UserMerchantStruct::GLOAD_MERCHANT_TYPE];
	$app_info = D('UserMerchantApp')->where($where)->find();
	$merchant_info = D('UserMerchant')->find($app_info['umid']);
	$merchant['user_merchant_app'] = $app_info;
	$merchant['user_merchant'] = $merchant_info;
	$merchant['id'] = $merchant_info['id'];
	$merchant['umopenid'] = $merchant_info['umopenid'];
	return $merchant;
}
