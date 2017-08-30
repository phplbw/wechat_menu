<?php
/***
 * 系统菜单管理控制器
 */
 
namespace Home\Controller;
use Think\Controller;

class SysMenuController extends BaseController{
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        //获取菜单列表
        $model = D('SysMenu');
        $menu_list = $model ->getSortList();
        $this->assign('list', $menu_list);
        
        $this->display('SysMenu/index');
    }
    
    //添加菜单
    public function addMenu(){
        if(IS_POST){
            $menuname = I('post.menuname');
            $pid = I('post.pid');
            $level = I('post.level');
            $mod = I('post.mod');
            $act = I('post.act');
            $display = I('post.display');;
            $sort = I('post.sort');
            $is_sys = I('post.is_sys');
            $param = array(
                'pid' => $pid,
                'name' => $menuname,
                'mod' => $mod,
                'act' => $act,
                'display' => $display,
                'level' => $level+1,
                'sort' => $sort,
                'is_sys' => $is_sys,
                'createtime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updatetime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            );
            
            $model = D('SysMenu');
            $info = $model->addMenu($param);
            $this->ajaxReturn($info);
        }
        //菜单列表
        $menu_list = array(
            0 => array(
                'pid' => 0,
                'name' => '无(默认父级ID为0)',
                'level' => 0
            ),
        );
        $model = D('SysMenu');
        $sortlist = $model->getSortList();
        $menu_list = array_merge($menu_list, $sortlist);
        $this->assign('menu_list', $menu_list);
        $this->display('SysMenu/add');
    }
    
    //编辑菜单
    public function editMenu(){
        if(IS_POST){
            $id = I('post.id');
            $menuname = I('post.menuname');
            $pid = I('post.pid');
            $level = I('post.level');
            $mod = I('post.mod');
            $act = I('post.act');
            $display = I('post.display');;
            $sort = I('post.sort');
            $is_sys = I('post.is_sys');
            $param = array(
                'pid' => $pid,
                'name' => $menuname,
                'mod' => $mod,
                'act' => $act,
                'display' => $display,
                'level' => $level+1,
                'sort' => $sort,
                'is_sys' => $is_sys,
                'updatetime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            );
            
            $model = D('SysMenu');
            $info = $model->editMenu($id, $param);
            $this->ajaxReturn($info);
        }
        //菜单列表
        $menu_list = array(
            0 => array(
                'id' => 0,
                'name' => '无(默认父级ID为0)',
                'level' => 0
            ),
        );
        $model = D('SysMenu');
        $sortlist = $model->getSortList();
        $menu_list = array_merge($menu_list, $sortlist);
        $this->assign('menu_list', $menu_list);
        //获取一条菜单信息
        $id = I('get.id');
        $info = $model->getOneData($id);
        $this->assign('info', $info);
        $this->display('SysMenu/edit');
    }
    
    //删除菜单
    public function delMenu(){
        IS_AJAX or exit();
        $id = I('post.id');
        $model = D('SysMenu');
        $info = $model->delMenu($id);
        $this->ajaxReturn($info);
    }
}
?>