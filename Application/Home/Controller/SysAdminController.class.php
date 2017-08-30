<?php
/**
 * 管理员管理控制器
 */
 
namespace Home\Controller;
use Think\Controller;

class SysAdminController extends BaseController{
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        $model = D('SysAdmin');
        $param = array(
            'where' => '',
        );
        $info = $model ->getList($param);
        $role_model = D('SysRole');
        $roles = $role_model->getAllData();
        $select = $info['select'];
        $list = array();
        foreach($select as $k=>$v){
            foreach($roles as $rk => $rv){
                if($v['role_id'] == $rv['id']){
                    $tmp_arr = $v;
                    $tmp_arr['rolename'] = $rv['name'];
                    $list[] = $tmp_arr;
                }
            }
        }
        $this->assign('list', $list);
        $this->assign('page', $info['page']);
        $this->display('SysAdmin/index');
    }
    
    //添加管理员
    public function addUser(){
        if(IS_POST){
            $username = I('post.username');
            $password = I('post.password');
            $roleid = I('post.roleid');
            $param = array(
                'uname' => $username,
                'pwd' => encrypt($password),
                'role_id' => $roleid,
                'createtime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updatetime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            );
            $model = D('SysAdmin');
            $info = $model -> addUser($param);
            $this->ajaxReturn($info);
        }
        $role_model = D('SysRole');
        $role_list = $role_model ->getAllData();
        $this->assign('role_list', $role_list);
        $this->display('SysAdmin/add');
    }
    
    //编辑管理员
    public function editUser(){
        if(IS_POST){
            $id = I('post.id');
            $username = I('post.username');
            $password = I('post.password');
            $roleid = I('post.roleid');
            $param = array(
                'uname' => $username,
                'pwd' => encrypt($password),
                'role_id' => $roleid,
                'updatetime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            );
            if($password == ''){
                unset($param['pwd']);
            }
            $model = D('SysAdmin');
            $info = $model->editUser($id, $param);
            $this->ajaxReturn($info);
        }
        $id = I('get.id');
        $model = D('SysAdmin');
        $info = $model->getOneData($id);
        $this->assign('info', $info);
        $role_model = D('SysRole');
        $role_list = $role_model ->getAllData();
        $this->assign('role_list', $role_list);
        $this->display('SysAdmin/edit');
    }
    
    //删除管理员
    public function delUser(){
        IS_AJAX or exit();
        $id = I('post.id');
        $model = D('SysAdmin');
        $info = $model->delUser($id);
        $this->ajaxReturn($info);
    }
}
?>