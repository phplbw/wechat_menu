<?php
/***
 * 微信密钥管理控制器
 */
namespace Home\Controller;
use Think\Controller;

class WechatKeyController extends BaseController{
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$model = D('WechatKey');
		$param = array(
			'where' => '',
			'field' => '',
			'order' => 'id desc',
			'pagesize' => 10
		);
		$list = $model->getList($param);
		$this->assign('list', $list['select']);
		$this->assign('page', $list['page']);
		
		$this->display('WechatKey/index');
	}
	
	// 添加
	public function add(){
		if(IS_POST){
			$name = I('post.name');
			$appid = I('post.appid');
			$secretkey = I('post.secretkey');
			$token = I('post.token');
			$status = I('post.status');
			
			$data = array(
				'name' => $name,
				'appid' => $appid,
				'secretkey' => $secretkey,
				'token' => $token,
				'status' => $status
			);
			$model = D('WechatKey');
			$info = $model->addKey($data);
			$this->ajaxReturn($info);
		}
		
		$this->display('WechatKey/add');
	}
	
	// 编辑
	public function edit(){
		if(IS_POST){
			$id = I('post.id');
			$name = I('post.name');
			$appid = I('post.appid');
			$secretkey = I('post.secretkey');
			$token = I('post.token');
			$status = I('post.status');
			
			$data = array(
				'name' => $name,
				'appid' => $appid,
				'secretkey' => $secretkey,
				'token' => $token,
				'status' => $status
			);
			$model = D('WechatKey');
			$info = $model->editKey($id, $data);
			$this->ajaxReturn($info);
		}
		
		$id = I('get.id');
		$model = D('WechatKey');
		$info = $model->where(array('id' => $id))->find();
		$this->assign('info', $info);
		
		$this->display('WechatKey/edit');
	}
	
	// 删除
	public function del(){
		IS_AJAX or exit();
		$id = I('post.id');
		$model = D('WechatKey');
		$info = $model->delKey($id);
		$this->ajaxReturn($info);
	}
}
?>