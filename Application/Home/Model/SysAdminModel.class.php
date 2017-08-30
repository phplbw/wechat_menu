<?php
/**
 * 管理员模型
 */
 
namespace Home\Model;
use Think\Model;

class SysAdminModel extends Model{
    protected $autoCheckFields = false;
    protected $tableName = 'sys_user' ;
    
    //检查用户
    public function checkUser($username, $password){
        $info = $this->where(array('uname'=> $username, 'pwd' => encrypt($password)))->find();
        if(is_null($info)){
            return array('msg' => '用户名或密码错误', 'result' => false);
        } else {
            return array('msg' => $info, 'result' => true);
        }
    }
    
    //添加用户
    public function addUser($data){
        $info = $this->add($data);
        if(false !== $info){
            return array('msg' => '添加成功!', 'result' => true);
        } else {
            return array('msg' => '添加失败!', 'result' => false);
        }
    }
    
    //删除用户
    public function delUser($id){
        $info = $this->where('id = '.$id)->delete();
        if( 0 === $info){
            return array('msg' => '没有数据被删除', 'result' => false);
        } else {
            return array('msg' => '成功删除'.$info.'条数据', 'result' => true);
        }
    }
    
    //编辑用户
    public function editUser($id, $data){
        $info = $this->where('id='.$id)->save($data);
        if(false !== $info){
            return array('msg' => '修改成功!', 'result' => true);
        } else {
            return array('msg' => '修改失败!', 'result' => false);
        }
    }
    
    // 获取列表
    public function getList($param){
        $param2 = array(
            'where' => $param['where'],
            'field' => 'id,uname, role_id',
            'order' => 'id desc',
            'pagesize' => 20
        );
        unset($param);
        $count = $this->where($param2['where'])->count();
        $select = $this->field($param2['field'])->where($param2['where'])->order($param2['order']);
        $pager = new \Think\Page($count, $param2['pagesize']);
        $select->limit( $pager->firstRow.",".$pager->listRows);
        $select = $select->select();
        $page = $pager->show( );
        return array('select' => $select, 'page' => $page );
    }
    
    //获取一条数据
    public function getOneData($id){
        $info = $this->where('id='.$id)->find();
        return $info;
    }
}
?>