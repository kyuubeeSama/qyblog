<?php
namespace app\admin\controller;
use think\App;
use think\facade\View;
use app\AdminBase;

/**
 * 友情链接管理
 */
class Link extends AdminBase {
	// 定义数据
	private $db;

	public function __construct(App $app){
		parent::__construct($app);
//		$this->db=D('Link');
		$this->db = new \app\admin\model\Link();
	}

	// 友情链接列表
	public function index(){
		$data=$this->db->getDataByState(0,'all');
		View::assign('data',$data);
		return View::fetch('index');
//		$this->assign('data',$data);
//		$this->display();
	}

	// 添加友情链接
	public function add(){
		if($this->request->isPost()){
			if($this->db->addData()){
				$this->success('友情链接添加成功',url('Admin/Link/index'));
			}else{
				$this->error('添加失败');
			}
		}else{
			return View::fetch('add');
//			$this->display();
		}

	}

	// 修改友情链接
	public function edit(){
		if($this->request->isPost()){
			if($this->db->editData()){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
		}else{
//			$lid=I('get.lid');
			$lid = $this->request->param('lid');
			$data=$this->db->getDataByLid($lid);
			View::assign('data',$data);
			return View::fetch('edit');
//			$this->assign('data',$data);
//			$this->display();
		}
	}

	/**
	 * 排序
	 */
	public function sort(){
		$data=I('post.');
		if (!empty($data)) {
			foreach ($data as $k => $v) {
				$this->db->where(array('lid'=>$k))->save(array('sort'=>$v));
			}
		}
		$this->success('修改成功',U('Admin/Link/index'));
	}



}



