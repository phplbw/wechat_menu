<?php
namespace Home\Controller;
use Think\Controller;

class PublicController extends Controller{
    public function login(){
        if(isset($_SESSION['uid'])){
            $this->redirect('Index/index');
        }
        
        if(IS_POST){
            $username = I('post.username');
            $password = I('post.password');
            $code = I('post.verify');
            if(!preg_match('/^[0-9a-zA-Z]+$/', $username)){
                $this->ajaxReturn(array('msg' => '用户名格式错误', 'result' => false));
            } else if(!preg_match('/^[0-9a-zA-Z]+$/', $password)){
                $this->ajaxReturn(array('msg' => '密码格式错误', 'result' => false));
            }
            if(!$this->checkVerify($code)){
                $this->ajaxReturn(array('msg' => '验证码错误！', 'result' => false));
            }
            
            $model = D('SysAdmin');
            $info = $model->checkUser($username, $password);
            if($info['result'] == true){
                //设置session
                $_SESSION['uid'] = $info['msg']['id'];
                $_SESSION['now_time'] = NOW_TIME;
                $_SESSION['role_id'] = $info['msg']['role_id'];
                $_SESSION['uname'] = $info['msg']['uname'];
            }
            $this->ajaxReturn($info);
        }
        $this->display('Public/login');
    }
    
    public function loginout(){
        //清除session
        session_unset(); 
        session_destroy(); 
        $this->redirect('Public/login');
    }
    
    /**
     * 验证码
     * 使用：U('Public/verify')
     */
    public function verify(){
        $config = array(
            'expire'    =>  120,
            'fontSize'  =>  20,              // 验证码字体大小(px)
            'useCurve'  =>  false,            // 是否画混淆曲线
            'useNoise'  =>  true,            // 是否添加杂点
            'imageH'    =>  50,               // 验证码图片高度
            'imageW'    =>  140,               // 验证码图片宽度
            'length'    =>  4,               // 验证码位数
            'fontttf'   =>  '',              // 验证码字体，不设置随机获取
            'bg'        =>  array(243, 251, 254),  // 背景颜色
            'reset'     =>  true,           // 验证成功后是否重置
            'fontttf'   =>  '4.ttf'
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry(1);
    }
    
    protected function checkVerify($code){
        $Verify = new \Think\Verify();
        return $Verify->check($code, 1);
    }
}
?>