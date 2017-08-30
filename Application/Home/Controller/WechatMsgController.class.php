<?php
//编辑图文消息等
namespace Home\Controller;
use Think\Controller;

class WechatMsgController extends BaseController{
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
		
		$model = D('WechatMsg');
		$list = $model->getList();
		//此处获取微信菜单并对比key，找到对应的菜单名称
		$option = array(
			'appid' => $wx_appid,
			'appsecret' => $wx_secret,
		);
		//初始化微信操作类
		$wechat = new Wechat($option);
		$menu = $wechat->getMenu();
		//$menu = false;
		if($menu !== false){
			$_tmp = array();
			foreach($menu['menu']['button'] as $k => $v){
				if(isset($v['type']) && $v['type'] == 'click'){
					$_tmp[$v['key']] = $v['name'];
				}
				if(isset($v['sub_button']) && !empty($v['sub_button'])){
					foreach($v['sub_button'] as $kk => $kv){
						if(isset($kv['type']) && $kv['type'] == 'click'){
							$_tmp[$kv['key']] = $kv['name'];
						}
					}
				}
			}
			foreach($list as $k => $v){
				if(array_key_exists($v['key'], $_tmp)){
					$list[$k]['name'] = $_tmp[$v['key']];
				} else {
					$list[$k]['name'] = '无';
				}
			}
		}
		
		$this->assign('list', $list);
		$this->assign('keys_id', $keys_id);
		
		$this->display('WechatMsg/index');
	}
	
	// 添加
	public function add(){
		if( IS_POST ){
			$json = $_POST['json'];
			$key = $_POST['key'];
			if(is_null(json_decode($json))){
				exit('error!');
			}
			$model = D('WechatMsg');
			$data = array(
				'key' => $key,
				'value' => $json
			);
			//查询key是否冲突，如果存在则不添加，返回错误提示
			$has_key = $model->where(array('key' => $key))->find();
			if(!empty($has_key)){
				$this->ajaxReturn(array('result' => false, 'msg' => '该key已经存在，不能添加，请选择编辑'));
			}
			$info = $model->add($data);
			if(false !== $info){
				$this->ajaxReturn(array('result' => true, 'msg' => '添加成功'));
			} else {
				$this->ajaxReturn(array('result' => false, 'msg' => '添加失败'));
			}
		}
		$this->display('WechatMsg/add');
	}
	
	// 编辑
	public function edit(){
		if( IS_POST ){
			$json = $_POST['json'];
			$key = $_POST['key'];
			if(is_null(json_decode($json))){
				exit('error!');
			}
			$model = D('WechatMsg');
			$info = $model->where(array('key' => $key))->setField('value', $json);
			if(false !== $info){
				$this->ajaxReturn(array('result' => true, 'msg' => '编辑成功'));
			} else {
				$this->ajaxReturn(array('result' => false, 'msg' => '编辑失败'));
			}
		}
		$id = (int)$_GET['id'];
		$model = D('WechatMsg');
		$info = $model->where(array('id' => $id))->find();
		$this->assign('info', json_decode($info['value'], true));
		$this->assign('info_key', $info['key']);
		
		$this->display('WechatMsg/edit');
	}
	
	// 删除
	public function del(){
		IS_AJAX or exit('error!');
		$id = (int)$_POST['id'];
		$model = D('WechatMsg');
		$info = $model->where(array('id' => $id))->delete();
		if(0 === $info){
			$this->ajaxReturn(array('result' => false, 'msg' => '没有数据被删除'));
		} else {
			$this->ajaxReturn(array('result' => true, 'msg' => '删除成功'));
		}
	}
	
	//上传到又拍云
	public function upload(){
		$upyun = new UpYun(C('UP_BUCKETNAME'), C('UP_USERNAME'), C('UP_PASSWORD'));
		//创建运营测试目录
		//$upyun->makeDir('/yunying_test');
		$fh = fopen($_FILES['file_data']['tmp_name'], 'rb');
		$ext = pathinfo($_FILES['file_data']['name'], PATHINFO_EXTENSION);
		$savename = '/yunying_test/' . date('Ym') . '/' .uniqid() . '.' . $ext;
		$fileinfo = $upyun->writeFile($savename, $fh, True);
		fclose($fh);
		
		//返回图片的地址
		$this->ajaxReturn(array('result' => true, 'info' => C('UP_SITEURL') . $savename));
	}
}

?>