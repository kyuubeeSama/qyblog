<?php
namespace app\admin\controller;
use app\AdminBase;
use app\BaseController;
use think\facade\View;
use think\facade\Db;

/**
 * 回收管理
 */
class Recycle extends AdminBase {
	//空操作 自动载入当前模板
	public function _empty($name){
		return View::fetch($name);
	}

	// 回收站首页
	public function index(){
		return View::fetch('index');
	}

	// 已删文章
	public function article(){
		$article = new \app\admin\model\Article();
		$data = $article->getPageData('all','all','all',1);
		View::assign([
			'data'=>$data['data'],
			'page'=>$data['page']
		]);
		return View::fetch('article');
	}

	// 已删评论
	public function comment(){
		$comment = new \app\admin\model\Comment();
		$data = $comment->getDataByState(1);
		View::assign([
			'data'=>$data['data'],
			'page'=>$data['page']
		]);
		return View::fetch('comment');
	}

	// 已删友情链接
	public function link(){
		/*
		$data=D('Link')->getDataByState(1);
		$this->assign('data',$data);
		// p($data);die;
		$this->display();
		*/
	}

	// 已删随言碎语
	public function chat(){
		$chat = new \app\admin\model\Chat();
		$data = $chat->getDataByState(1);
		View::assign('data',$data);
		return View::fetch('chat');
	}

	// 根据$_GET数组放入回收站
	public function recycle(){
		$data = $this->request->get();
		Db::table('qy'.$data['table_name'])->where($data['id_name'],$data['id_number'])->update('is_delete',1);
		$this->success('放入回收站成功');
	}

	// 根据$_GET数组恢复删除
	public function recover(){
		$data = $this->request->get();
//		$data=I('get.');
		Db::table('qy_'.$data['table_name'])->where($data['id_name'],$data['id_number'])->update('is_delete',0);
		$this->success('恢复成功');
	}

	// 根据$_GET数组彻底删除
	public function delete(){
		$data = $this->request->get();
		Db::table('qy_'.$data['table_name'])->where($data['id_name'],$data['id_number'])->delete();
		$this->success('删除成功');
	}



}





