<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class WechatMenuController extends BaseController{
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$model = D('WechatKey');
		$keylist = $model->getAllList();
		$this->assign('keylist', $keylist);
		
		$keys_id = I('get.keys_id', 0);
		$wx_appid = '';
		$wx_secret = '';
		
		if($keys_id != 0){
			$info = $model->where(array('id' => $keys_id))->order('id asc')->find();
		} else {
			$info = $model->order('id asc')->find();
		}
		if(!empty($info) ){
			$wx_appid = $info['appid'];
			$wx_secret = $info['secretkey'];
			$keys_id = $info['id'];
		} else {
			$wx_appid = C('WX_APPID');
			$wx_secret = C('WX_SECRET');
		}
		
		$option = array(
			'appid' => $wx_appid,
			'appsecret' => $wx_secret,
		);
		//初始化微信操作类
		$wechat = new Wechat($option);
		$menu = $wechat->getMenu();
		if($menu !== false){
			$this->assign('menus', json_encode($menu['menu']));
			$this->assign('keys_id', $keys_id);
		} else {
			$this->assign('menus', json_encode(array('button' => array())));
			$this->assign('keys_id', $keys_id);
		}
		
		$this->display("WechatMenu/index");
	}
	
	public function edit(){
		IS_AJAX or exit('error!');
		//编辑微信菜单
		$data = $_POST['data'];
		//$data = '{"button":[{"type":"click","name":"今日歌曲","key":"V1001_TODAY_MUSIC"},{"name":"菜单","sub_button":[{"type":"view","name":"搜索","url":"http://www.soso.com/"},{"type":"view","name":"视频","url":"http://v.qq.com/"},{"type":"click","name":"赞一下我们","key":"V1001_GOOD"}]}]}';
		$data = json_decode($data, true);
		//把key保存到本地的数据库，然后查询设置信息
		$key_arr = array();
		foreach($data['button'] as $k => $v){
			if(isset($v['type']) && $v['type'] == 'click') {
				array_push($key_arr, trim($v['key']));
			}
			if( isset($v['sub_button']) && !empty($v['sub_button']) ){
				foreach($v['sub_button'] as $kk => $kv){
					if(isset($kv['type']) && $kv['type'] == 'click') {
						array_push($key_arr, trim($kv['key']));
					}
				}
			}
		}
		//iconv('GB2312', 'UTF-8', $v)
		array_filter($key_arr);
		//已经存在的key不重复保存
		//遍历$key_arr,把值保存到数据库
		$model = D('WechatMsg');
		foreach($key_arr as $k => $v) {
			$_tmp = $model->where(array('key' => $v))->find();
			if(empty($_tmp)){
				$model->addData(array(
					'key' => $v,
					'value' => ''
				));
			}
		}
		$model = D('WechatKey');
		$keys_id = I('get.keys_id', 0);
		$wx_appid = '';
		$wx_secret = '';
		if($keys_id != 0){
			$info = $model->where(array('id' => $keys_id))->order('id asc')->find();
		} else {
			$info = $model->order('id asc')->find();
		}
		if(!empty($info) ){
			$wx_appid = $info['appid'];
			$wx_secret = $info['secretkey'];
			$keys_id = $info['id'];
		} else {
			$wx_appid = C('WX_APPID');
			$wx_secret = C('WX_SECRET');
		}
		
		//通过接口修改菜单
		$option = array(
			'appid' => $wx_appid,
			'appsecret' => $wx_secret,
		);
		$wechat = new Wechat($option);
		$re = $wechat->createMenu($data);
		$info = empty($info) ? $info : array('name' => '默认');
		if($re === true){
			$model = D('WechatMenuLog');
			$arr = array(
				'content' => json_encode($data),
				'result' => $info['name'].'___success'
			);
			$model->addLog($arr);
			$this->ajaxReturn(array('errcode' => 0, 'errmsg' => ''));
		} else {
			$model = D('WechatMenuLog');
			$arr = array(
				'content' => json_encode($data),
				'result' => $info['name'].'___faild errcode:'.$wechat->errCode
			);
			$model->addLog($arr);
			$this->ajaxReturn(array('errcode' => $wechat->errCode, 'errmsg' => $wechat->errMsg));
		}
	}
}
?>