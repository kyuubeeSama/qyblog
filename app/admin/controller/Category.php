<?php

namespace app\admin\controller;

use app\AdminBase;
use think\App;
use app\BaseController;
use think\facade\Log;
use think\Request;
use think\facade\View;

class Category extends AdminBase {
	//定义数据表
	private $db;

	//定义category表数
	private $categoryData;

	//构造函数 实例化CategoryModel 并获得整张表的数据
	public function __construct(App $app) {
		parent::__construct($app);
		//初始化时实例化category model
		$this->db = new \app\admin\model\Category();
		//获取category数据并赋值给$categoryData
		$this->categoryData = $this->db->getAllData();
	}

	public function index() {
		View::assign( 'data', $this->categoryData );
//		dump($this->categoryData);
		return View::fetch( 'index' );
	}
	// 添加分类
	//FIXME:数据添加数据库成功，但是提示的却是失败
	public function add() {
		if ( $this->request->isPost() ) {
			if ( $this->db->addData() ) {
				$this->success( '分类添加成功' );
			} else {
				$this->error( "添加失败" );
			}
		} else {
			$cid = input( 'cid', 0, 'intval' );
			View::assign( [
				'cid'  => $cid,
				'data' => $this->categoryData
			] );
			return View::fetch( 'add' );
		}
	}


	// 修改分类
	public function edit() {
		if ( $this->request->isPost() ) {
			if ( $this->db->editData() ) {
				$this->success( '修改成功','category/index' );
			} else {
				$this->error( '修改失败' );
			}
		} else {
			$cid     = input( 'cid', 0, 'intval' );
			$onedata = $this->db->getDataByCid( $cid );
			$data    = $this->categoryData;
			$childs  = $this->db->getChildData( $cid );
			foreach ( $data as $k => $v ) {
				if ( in_array( $v['cid'], $childs ) ) {
					$data[ $k ]['_html'] = " disabled='disabled' style='background:#F0F0F0'";
				} else {
					$data[ $k ]['_html'] = " ";
				}
			}
			View::assign( [
				'data'    => $data,
				'onedata' => $onedata
			] );
			return View::fetch( 'category/edit' );
		}
	}

	// 删除分类
	public function delete() {
		if ( $this->db->deleteData() ) {
			$this->success( '删除成功' );
		} else {
			$this->error( '删除失败'.$this->db->error);
		}
	}

	/**
	 * 排序
	 */
	public function sort() {
		$data    = $this->request->post();
		if ( ! empty( $data ) ) {
			foreach ( $data as $k => $v ) {
				$this->db->where('cid',$k)->save(['sort'=>$v]);
			}
		}
		$this->success( '修改成功', url( 'Admin/Category/index' ) );
	}
}