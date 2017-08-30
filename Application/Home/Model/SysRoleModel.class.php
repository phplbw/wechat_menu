<?php
/**
 * 权限组模型
 */
namespace Home\Model;
use Think\Model;

class SysRoleModel extends Model{
    protected $autoCheckFields = false;
    protected $tableName = 'sys_role' ;
    
    //添加权限组
    public function addRole($rolename){
        $data = array(
            'name' => $rolename,
            'createtime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            'updatetime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
        );
        $info = $this->add($data);
        if(false !== $info){
            return array('msg' => '添加成功!', 'result' => true);
        } else {
            return array('msg' => '添加失败!', 'result' => false);
        }
    }
    
    //删除权限组
    public function delRole($id){
        //这里需要查询该权限组是否有用户，如果没有用户则可以删除，有用户则不能删除
        //判断sys_user表中是否有该id
        //同时需要删除sys_auth表中已经绑定的权限
        $user = M('sys_user');
        $is_has = $user->where('role_id='.$id)->find();
        if(!is_null($is_has)){
            return array('msg' => '该权限已绑定用户，不能删除。要删除请先解除绑定', 'result'=> false);
        }
        $info = $this->where('id = '.$id)->delete();
        //没有数据被删除
        if( 0 === $info){
            return array('msg' => '没有数据被删除', 'result' => false);
        } else {
            $auth = M('sys_auth');
            $auth_info = $auth->where('role_id = '.$id)->delete();
            return array('msg' => '成功删除'.$info.'条数据,删除该权限绑定的菜单'.$auth_info.'条', 'result' => true);
        }
    }
    
    //编辑权限组
    public function editRole($id, $name){
        $data = array(
            'name' => $name,
            'updatetime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
        );
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
            'field' => 'id,name',
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
    
    //获取所有的数据
    public function getAllData(){
        $info = $this->select();
        return $info;
    }
}
?>