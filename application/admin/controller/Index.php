<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
	// 后台首页
	public function index(){
		return view('index/index');
	}
	// 欢迎页面
	public function welcome(){
		$assign=[
			'all_article'=>Db::table('qy_article')->count(),
			'delete_article'=>Db::table('qy_article')->where('is_delete','1')->count(),
			'hide_article'=>Db::table('qy_article')->where('is_show','0')->count(),
			'all_chat'=>Db::table('qy_chat')->count(),
			'delete_chat'=>Db::table('qy_chat')->where('is_delete','0')->count(),
			'hide_chat'=>Db::table('qy_chat')->where('is_show','0')->count(),
			'all_comment'=>Db::table('qy_comment')->count()
		];
		$this->assign($assign);
		return view('index/welcome');
	}
}
