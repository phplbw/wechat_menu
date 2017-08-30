<?php
/**
 * 权限组模型
 */
namespace Home\Model;
use Think\Model;

class SysAuthModel extends Model{
	protected $autoCheckFields = false;
	protected $tableName = 'sys_auth' ;
	
	//获取所有的数据
	public function getAllData($role_id){
		$info = $this->where('role_id = '.$role_id)->select();
		return $info;
	}
	
	//删除绑定
	public function delBind($role_id){
		$is_bind = $this->field('id')->where(array('role_id'=>$role_id))->find();
		if($is_bind !== false && $is_bind !== null) {
			$this->where(array('role_id'=>$role_id))->delete();
		}
	}
	
	//重新绑定菜单
	public function bindMenu($role_id, $ids){
		$suc_num = 0;
		$err_num = 0;
		$count_num = count($ids);
		foreach($ids as $k=>$v) {
			$data = array('menu_id'=>$v, 'role_id'=>$role_id);
			$re = $this->add($data);
			if(false !== $re) {
				$suc_num++;
			} else {
				$err_num++;
			}
		}
		
		if($suc_num >= 1) {
			return array('msg' => '菜单总数量：'.$count_num.'　　绑定失败数量：'.$err_num .'　　绑定成功数量：'.$suc_num, 'result' => true);
		} else {
			return array('msg' => '菜单总数量：'.$count_num.'　　绑定失败数量：'.$err_num .'　　绑定成功数量：'.$suc_num, 'result' => false);
		}
	}
}
?>