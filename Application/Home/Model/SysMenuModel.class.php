<?php
/**
 * 菜单模型
 */
namespace Home\Model;
use Think\Model;

class SysMenuModel extends Model{
    protected $arr = array();
    protected $str = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    
    protected $autoCheckFields = false;
    protected $tableName = 'sys_menu' ;
    
    //添加菜单
    public function addMenu($data){
        $info = $this->add($data);
        if(false !== $info){
            return array('msg' => '添加成功!', 'result' => true);
        } else {
            return array('msg' => '添加失败!', 'result' => false);
        }
    }
    
    //删除菜单
    public function delMenu($id){
        //系统菜单不能删除
        $menu_info = $this->where('id = '.$id)->find();
        if($menu_info['is_sys'] == 1){
            return array('msg' => '系统菜单不能删除，请先修改该菜单为非系统菜单', 'result' => false);
        }
        
        //先要查询是否有子菜单，如果有子菜单则不能删除
        $is_have = $this->where('pid = '. $id)->find();
        if(!is_null($is_have)){
            return array('msg' => '请先删除子菜单', 'result' => false);
        }
        
        $info = $this->where('id = '.$id)->delete();
        if( 0 === $info){
            return array('msg' => '没有数据被删除', 'result' => false);
        } else {
            //删除与该菜单关联的权限
            $auth = M('sys_auth');
            $auth_info = $auth->where('menu_id = '.$id)->delete();
            return array('msg' => '成功删除'.$info.'条数据,删除与该菜单关联的权限'.$auth_info.'条', 'result' => true);
        }
    }
    
    //编辑菜单
    public function editMenu($id, $data){
        if($id == $data['pid']){
            return array('msg' => '父菜单不能是自己', 'result' => false);
        }
        $info = $this->where('id='.$id)->save($data);
        if(false !== $info){
            return array('msg' => '修改成功!', 'result' => true);
        } else {
            return array('msg' => '修改失败!', 'result' => false);
        }
    }
    
    //获取单条信息
    public function getOneData($id){
        $info = $this->where('id='.$id)->find();
        return $info;
    }
    
    // 获取列表
    public function getList($param){
        $param2 = array(
            'where' => $param['where'],
            'field' => '',
            'order' => 'id desc',
        );
        unset($param);
        $count = $this->where($param2['where'])->count();
        $select = $this->field($param2['field'])->where($param2['where'])->order($param2['order'])->select();
        return $select;
    }
    
    //获取排序后的列表
    public function getSortList(){
        $this->arr = $this->order('sort desc, id desc')->select();
        $list = array();
        foreach($this->arr as $k => $v){
            if($v['pid'] == 0){
                $list[] = $v;
            }
        }
        if(!empty($list)){
            $alllist = array();
            foreach($list as $k => $v){
                //根据父节点的id递归获取所有的子节点
                $alllist = $this->get_sort_treelist($v['id'],$v['pid'],$this->str);
            }
            return $alllist;
        } else {
            return array();
        }
    }
    
    //根据父节点id返回所有的子节点
    private function get_sort_treelist($id, $pid, $str){
        static $list = array();
        if($pid == 0){
            foreach($this->arr as $k => $v){
                $v['name'] = $str . $v['name'];
                if($v['id'] == $id){
                    $list[] = $v;
                }
            }
        }
        $child = $this->get_sort_child($this->arr,$id);
        if(!empty($child)){
            foreach($child as $k=>$v){
                $cstr = '';
                for($i=0; $i < $v['level']; $i++){
                    $cstr .= $this->str;
                }
                $cstr = $cstr.'&nbsp;&nbsp;&nbsp;├─&nbsp;&nbsp;&nbsp;';
                $v['name'] = $cstr.$v['name'];
                $list[] = $v;
                $this->get_sort_treelist($v['id'],$v['pid'],$cstr);
            }
        }
        return $list;
    }
    
    private function get_sort_child($arr, $id){
        $arrlist = array();
        foreach($arr as $k=>$v) {
            if($v['pid'] == $id) {
                $arrlist[] = $v;
            }
        }
        return $arrlist;
    }
    
    
    
    
    //获取数组形式的菜单
    public function getArrayList(){
        $this->arr = $this->order('sort desc, id desc')->select();
        
        $list = array();
        foreach($this->arr as $k => $v){
            if($v['pid'] == 0){
                $v['childarr'] = $this->get_arr_treelist($v['id']);
                $list[] = $v;
            }
        }
        return $list;
    }
    
    //根据父节点id返回所有的子节点
    private function get_arr_treelist( $id ){
        static $arr_menu = array();

        $child_arr = array();
        foreach($this->arr as $k => $v) {
            if($v['pid'] == $id) {
                if($v['level'] <= 2){
                    $v['childarr'] = $this->get_arr_treelist($v['id']);
                    $child_arr[] = $v;
                }
            }
        }
        $arr_menu = $child_arr;
        return $arr_menu;
    }
}
?>