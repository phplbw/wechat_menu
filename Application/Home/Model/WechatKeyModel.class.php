<?php
namespace Home\Model;
use Think\Model;

class WechatKeyModel extends Model{
	protected $autoCheckFields = false;
	protected $tableName = 'wechat_keys' ;
	
	// 获取列表
	public function getList($param){
		$count = $this->where($param['where'])->count();
		$select = $this->field($param['field'])->where($param['where'])->order($param['order']);
		$pager = new \Think\Page($count, $param['pagesize']);
		$select->limit( $pager->firstRow.",".$pager->listRows);
		$select = $select->select();
		$page = $pager->show( );
		return array('select' => $select, 'page' => $page );
	}
	
	// 添加key
	public function addKey($data){
		$info = $this->add($data);
		if(false !== $info){
			return array('msg' => '添加成功!', 'result' => true);
		} else {
			return array('msg' => '添加失败!', 'result' => false);
		}
	}
	
	// 修改key
	public function editKey($id, $data){
		$info = $this->where(array('id' => $id))->save($data);
		if(false !== $info){
			return array('msg' => '修改成功!', 'result' => true);
		} else {
			return array('msg' => '修改失败!', 'result' => false);
		}
	}
	
	// 删除key
	public function delKey($id){
		$info = $this->where('id = '.$id)->delete();
		if( 0 === $info){
			return array('msg' => '没有数据被删除', 'result' => false);
		} else {
			return array('msg' => '成功删除'.$info.'条数据', 'result' => true);
		}
	}
	
	// 获取所有的列表
	public function getAllList(){
		return $this->where(array('status' => 1 ))->select();
	}
}
?>