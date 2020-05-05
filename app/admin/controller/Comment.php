<?php
namespace app\admin\controller;

use app\AdminBase;
use app\BaseController;
use think\App;
use think\facade\Config;
use think\facade\Db;
use think\facade\View;

/**
 * 评论管理
 */
class Comment extends BaseController {
	// 定义数据
	private $db;

	public function __construct(App $app){
		parent::__construct($app);
//		$this->db=D('Comment');
		$this->db = new \app\admin\model\Comment();
	}

	// 评论列表
	public function index(){
		$data=$this->db->getDataByState(0);
		View::assign($data);
		View::assign('COMMENT_REVIEW',Config::get('webconfig.COMMENT_REVIEW'));
		return View::fetch('index');
	}

	// 通过或者取消审核
	public function change_status(){
//		$cmtid=I('get.cmtid',0,'intval');
//		$status=I('get.status',0,'intval');
		$cmtid = $this->request->param('cmtid');
		$status = $this->request->param('status');
//		M('Comment')->where(array('cmtid'=>$cmtid))->setField('status',$status);
		Db::table('qy_comment')->where('cmtid',$cmtid)->update(['status'=>$status]);
		$this->success('操作成功');
	}

	// 删除
	public function delete(){
		$cmtid = $this->request->param('cmtid');
		if ($this->db->where('cmtid',$cmtid)->delete()){
			$this->success('删除成功');
		}else{
			$this->success('删除失败');

		}
	}
}



