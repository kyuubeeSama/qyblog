<?php
namespace app\admin\controller;

use think\facade\View;
use think\facade\Request;
use think\App;

/**
 * 标签管理
 */
class Tag extends AdminBase {
	// 定义数据表
	private $db;

	// 构造函数 实例化TagModel
	public function __construct(App $app){
		parent::__construct($app);
		$this->db = new \app\admin\model\Tag();
	}

	// 标签首页
	public function index(){
		$data=$this->db->getAllData();
		View::assign('data',$data);
		return View::fetch("index");
	}

	// 添加标签
	public function add(){
		if($this->request->isPost()){
			if($this->db->addData()){
				$this->success('标签添加成功');
			}else{
				$this->error("操作失败");
			}
		}else{
			return View::fetch('add');
		}
	}

	// 修改标签
	public function edit(){
		if($this->request->isPost()){
			if($this->db->editData()){
				$this->success('修改成功','tag/index');
			}else{
				$this->error("操作失败");
			}
		}else{
			$tid = input('tid',0,'intval');
			$data=$this->db->getDataByTid($tid);
			View::assign('data',$data);
			return View::fetch('edit');
		}
	}

	// 删除标签
	public function delete(){
		if($this->db->deleteData()){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

}




