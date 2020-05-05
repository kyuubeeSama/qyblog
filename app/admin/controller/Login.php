<?php
namespace app\admin\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\View;

class Login extends BaseController
{
	public function index() {
		return View::fetch('index');
	}

	public function login(){
		$password = input('password','','trim');
		$verify = input('verify','','trim');
		if(captcha_check($verify)){
			$passwordData = Db::table('qy_config')->where('name','ADMIN_PASSWORD')->value('value');
			if ( md5( $password ) == $passwordData ) {
				session( 'admin', 'is_login' );
				session( 'ADMIN_PASSWORD', null );
				$this->success('登录成功','admin/index/index');
			} else {
				$this->error( '密码输入错误', 'admin/login/login');
			}
		}else{
			$this->error("验证码输入错误","admin/login/index");
		}
	}

	// 退出登录
	public function logout(){
		session('admin',null);
		$this->success('退出成功',url('admin/login/login'));
	}

	// 生成验证码
	public function showVerify(){
		show_verify();
	}
}
