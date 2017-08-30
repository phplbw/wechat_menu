<?php
// 基础控制器
// 所有类的基类，用于进行权限控制等需要统一的操作

namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller{
    public function __construct(){
        //继承基础控制的初始化
        parent::__construct();
        
        //检查登陆和权限
        $this->checklogin();
        //获取菜单列表
        $this->getMenuList();
        //检查当前用户是否有权限访问
        $this->checkAuth();
    }
    
    private function checklogin(){
        if(isset($_SESSION['uid'])){
            //严格控制session超时，超过60分钟则清空session
            if(NOW_TIME - $_SESSION['now_time'] > 3600) {
                session_unset(); 
                session_destroy();
                $this->redirect('Public/login');
            } else {
                //未超时则更新session时间
                $_SESSION['now_time'] = NOW_TIME;
            }
        } else {
            $this->redirect('Public/login');
        }
    }
    
    private function checkAuth(){
        $sys_menu = M('sys_menu');
        $menu_id = $sys_menu->where(array('mod' => CONTROLLER_NAME, 'act' => ACTION_NAME))->find();
        
        if(is_null($menu_id)){
            //菜单不存在
            $this->authError("该菜单不存在!");
        } else {
            $sys_auth = M('sys_auth');
            $info = $sys_auth->where(array('role_id' =>$_SESSION['role_id'], 'menu_id' => $menu_id['id']))->find();
            if(is_null($info)){
                //没有权限访问
                $this->authError("没有权限访问该模块");
            } else {
                //允许访问
                return true;
            }
        }
    }
    
    //权限错误
    private function authError($msg){
        $this->assign('msg', $msg);
        $this->display("Public/autherror");
        exit();
    }
    
    //返回数组形式的菜单列表
    private function getMenuList(){
        $model = D('SysMenu');
        $arr_menu = $model->getArrayList();
        $this->assign('arr_menu_list', $arr_menu);
    }
}

?>