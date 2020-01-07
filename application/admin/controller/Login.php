<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Login extends Controller
{
	public function index()
	{
		return view('login/index');
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
}
