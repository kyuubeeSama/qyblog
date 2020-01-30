<?php
namespace app\admin\controller;
use app\BaseController;
use think\App;
use think\facade\View;


/**
 * 随言碎语管理
 */
class Chat extends BaseController {
	// 定义数据
	private $db;

	public function __construct(App $app){
		parent::__construct($app);
		$this->db = new \app\admin\model\Chat();
	}

	// 随言碎语列表
	public function index(){
		$data=$this->db->getDataByState(0,'all');
		View::assign('data',$data);
		View::fetch('index');
//		$this->assign('data',$data);
//		$this->display();
	}

	// 添加随言碎语
	public function add(){
		if($this->request->isPost()){
			if($this->db->addData()){
				$this->success('随言碎语发表成功',url('Admin/Chat/add'));
			}else{
				$this->error('添加失败');
			}
		}else{
			View::fetch('add');
		}

	}

	// 修改随言碎语
	public function edit(){
		if($this->request->isPost()){
			if($this->db->editData()){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
		}else{
			$chid = $this->request->param('chid');
			$data=$this->db->getDataByLid($chid);
			View::assign('data',$data);
			View::fetch('edit');
		}
	}
}



