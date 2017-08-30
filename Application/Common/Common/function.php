<?php
//密码加密函数
function encrypt($pwd){
	$pwd = trim($pwd);
	return md5(sha1($pwd));
}

function formatBytes($size) {
	$units = array(' B', ' KB', ' MB', ' GB', ' TB'); 
	for ($i = 0; $size >= 1024 && $i < 4; $i++) {
		$size /= 1024; 
	}
	return round($size, 2).$units[$i]; 
}


/**
 * @description GET请求方式
 * @param $url
 * @param int $uid
 * @param int $second
 * @return bool|mixed
 */
function http_get($url, $second = 0)
{
	$header = array('');
	$oCurl = curl_init();
	if(stripos($url,"https://")!==FALSE){
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
	}
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_HTTPHEADER , $header);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );

	$second = intval($second);
	if ($second) {
		curl_setopt($oCurl, CURLOPT_TIMEOUT, $second);
	}

	$sContent = curl_exec($oCurl);
	$aStatus = curl_getinfo($oCurl);
	curl_close($oCurl);
	if(intval($aStatus["http_code"])==200){
		return $sContent;
	} else {
		return false;
	}
}


/**
 * @description POST请求方式
 * @param $url
 * @param $param
 * @param int $second
 * @return bool|mixed
 */
function http_post($url,$param, $second = 0)
{
	$header = array('');
	$oCurl = curl_init();
	if(stripos($url,"https://")!==FALSE){
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
	}
	if (is_string($param)) {
		$strPOST = $param;
	} else {
		$aPOST = array();
		foreach($param as $key=>$val){
			$aPOST[] = $key."=".urlencode($val);
		}
		$strPOST =  join("&", $aPOST);
	}
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_HTTPHEADER , $header);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt($oCurl, CURLOPT_POST,true);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);

	$second = intval($second);
	if ($second) {
		curl_setopt($oCurl, CURLOPT_TIMEOUT, $second);
	}

	$sContent = curl_exec($oCurl);
	$aStatus = curl_getinfo($oCurl);
	curl_close($oCurl);
	if(intval($aStatus["http_code"])==200){
		return $sContent;
	}else{
		return false;
	}
}
?>