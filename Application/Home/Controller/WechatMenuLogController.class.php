<?php
namespace Home\Controller;
use Think\Controller;

class WechatMenuLogController extends BaseController{
	public function lists(){
		$model = D('WechatMenuLog');
		$param = array(
			'where' => '',
			'field' => '',
			'order' => 'id desc',
			'pagesize' => 10
		);
		$list = $model->getList($param);
		$this->assign('list', $list['select']);
		$this->assign('page', $list['page']);
		$this->display('WechatMenuLog/lists');
	}
}
?>