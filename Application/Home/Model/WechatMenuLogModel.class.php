<?php
// 微信菜单日志模型
//

namespace Home\Model;
use Think\Model;

class WechatMenuLogModel extends BaseModel{
	protected $autoCheckFields = false;
	protected $tableName = 'wechat_logs' ;
	
	public function __construct(){
		parent::__construct();
		$this->tablePrefix = C('DB_PREFIX');
	}
	
	// 添加一条数据
	public function addLog($data){
		$arr = array(
			'content' => $data['content'],
			'uinfo' => $_SESSION['ups_id'] . '_' . $_SESSION['ups_nickName'],
			'result' => $data['result'],
			'createtime' => date('Y-m-d H:i:s')
		);
		$this->add($arr);
	}
}
?>