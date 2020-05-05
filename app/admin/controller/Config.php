<?php
namespace app\admin\controller;
use think\App;
use think\console\command\make\Model;
use think\facade\View;
use app\AdminBase;

/**
 * 网站设置
 */
class Config extends AdminBase {
	// 定义数据表
	private $db;

	// 构造函数 实例化TagModel
	public function __construct(App $app){
		parent::__construct($app);
		$this->db = new \app\admin\model\Config();
	}

	// 网站配置首页
	public function index(){
		if($this->request->isPost()){
			if($this->db->editData()){
				$this->success('修改成功',url('Admin/Config/index'));
			}else{
				$this->error('修改失败');
			}
		}else{
			$data=$this->db->getAllData();
			View::assign('data',$data);
			return View::fetch('index');
		}
	}

	// 修改密码
	public function change_password(){
		if($this->request->isPost()){
			if($this->db->changePassword()){
				$this->success('修改成功');
			}else{
				$this->error($this->db->getError());
			}
		}else{
			return View::fetch('change_password');
		}
	}


}




