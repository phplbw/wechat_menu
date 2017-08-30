<?php
/**
 * 权限组控制器
 */
 
namespace Home\Controller;
use Think\Controller;

class SysRoleController extends BaseController{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        //查询sys_role表
        $model = D('SysRole');
        $param = array(
            'where' => '',
        );
        $info = $model ->getList($param);
        $this->assign('list', $info['select']);
        $this->assign('page', $info['page']);
        $this->display('SysRole/index');
    }
    
    //编辑权限组
    public function editRole(){
        if(IS_POST){
            $id = I('post.id');
            $name = I('post.rolename');
            $model = D('SysRole');
            $info = $model ->editRole($id, $name);
            $this->ajaxReturn($info);
        }
        $id = I('get.id');
        $model = D('SysRole');
        $info = $model->getOneData($id);
        $this->assign('info', $info);
        $this->display('SysRole/edit');
    }
    
    //删除权限组
    public function delRole(){
        IS_AJAX or exit();
        $id = I('post.id');
        $model = D('SysRole');
        $info = $model->delRole($id);
        $this->ajaxReturn($info);
    }
    
    //添加权限组
    public function addRole(){
        if(IS_POST){
            $rolename = I('post.rolename');
            $model = D('SysRole');
            $info = $model->addRole($rolename);
            $this->ajaxReturn($info);
        }
        $this->display('SysRole/add');
    }
    
    //权限绑定菜单
    public function bindMenu(){
        if(IS_POST){
            //首先验证传递过来的数组和当前权限不为空
            $ids = explode(',',trim(I('post.ids'),','));
            $role_id = I('post.roleid',0,'int');
            if(!is_array($ids)|| is_array($ids)&&empty($ids) || $role_id == 0)
            {
                $this->ajaxReturn(array('msg' => '参数错误', 'result'=> false));
            }
            
            $auth_model = D('SysAuth');
            //解除绑定
            $auth_model->delBind($role_id);
            //重新绑定
            $info = $auth_model->bindMenu($role_id, $ids);
            $this->ajaxReturn($info);
        }
        
        $role_id = I('get.id');
        $auth_model = D('SysAuth');
        $role_menu = $auth_model ->getAllData($role_id);
        $this->assign('role_menu', $role_menu);
        $this->assign('role_id', $role_id);
        
        $model = D('SysMenu');
        $menu_list = $model->getSortList();
        $this->assign('menu_list', $menu_list);
        
        $this->display('SysRole/bindmenu');
    }
}
?>