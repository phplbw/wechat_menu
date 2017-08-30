<?php
namespace Home\Model;
use Think\Model;

class WechatMsgModel extends BaseModel{
	protected $autoCheckFields = false;
	protected $tableName = 'key_msg' ;
	
	public function __construct(){
		parent::__construct();
		$this->tablePrefix = C('DB_PREFIX');
	}
	
	public function getList(){
		return $this->where()->select();
	}
	
}
?>