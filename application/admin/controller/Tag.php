<?php
namespace app\admin\controller;
use think\Request;

/**
 * 标签管理
 */
class Tag extends AdminBase {
	// 定义数据表
	private $db;

	// 构造函数 实例化TagModel
	public function __construct(){
		parent::__construct();
		$this->db=model('Tag');
	}

	// 标签首页
	public function index(){
		$data=$this->db->getAllData();
		$this->assign('data',$data);
		return $this->fetch('tag/index');
	}

	// 添加标签
	public function add(Request $request){
		if($request->isPost()){
			if($this->db->addData()){
				$this->success('标签添加成功');
			}else{
				$this->error($this->db->getError());
			}
		}else{
			return $this->fetch('tag/add');
		}
	}

	// 修改标签
	public function edit(Request $request){
		if($request->isPost()){
			if($this->db->editData()){
				$this->success('修改成功');
			}else{
				$this->error($this->db->getError());
			}
		}else{
			$tid = input('tid',0,'intval');
			$data=$this->db->getDataByTid($tid);
			$this->assign('data',$data);
			return $this->fetch('tag/edit');
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




