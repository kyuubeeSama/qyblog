<?php
namespace app;

use app\BaseController;
use think\facade\Db;
use think\facade\Log;

/**
 * 后台基类Controller
 */
class AdminBase extends BaseController {
	/**
	 * 初始化方法
	 */
	public function initialize(){
		parent::initialize();
		if(session('admin')!='is_login'){
			// 判断第三方登录账号中是否有admin
			$oauth_has_admin = Db::table('qy_Oauth_user')->where('is_admin','1')->count();
			// 如果有第三方账号有admin  则要求用第三方登录 否则用常规登录
			if ($oauth_has_admin) {
				Log::write('请在前台页面通过第三方账号登录');
				die('请在前台页面通过第三方账号登录');
			}else{
				Log::write("跳转到后台登录");
				redirect(url('Admin/Login/login'));
			}
		}
	}


}

