<?php

namespace app\admin\model;

use think\facade\Db;
use think\model;
use think\facade\Request;
use Tool\Data;

/**
 * 分类model
 */
class Category extends Model {
	// 自动验证
	protected $_validate = array(
		array( 'cname', 'require', '分类名不能为空' ),
		array( 'sort', 'number', '排序必须为数字' ),
	);

	//添加数据
	/**
	 * @var string
	 */
	public $error;

	public function addData() {
		$data    = Request::post();
		return $this->insert($data);
	}

	// 修改数据
	public function editData() {
		$data    = Request::post();
		return $this->where('cid',$data['cid'])->update($data);
	}

	// 删除数据
	public function deleteData( $cid = null) {
		$cid = is_null($cid) ? Request::param("cid") : $cid;
		$child   = $this->getChildData( $cid );
		if ( ! empty( $child ) ) {
			$this->error = '请先删除子分类';

			return false;
		}
		$articleModel = new Article();
		$articleData = $articleModel->getDataByCid( $cid );
		dump($articleData);
		if ( ! $articleData->isEmpty()) {
			$this->error = '请先删除此分类下的文章';

			return false;
		}
		if ( $this->where( 'cid', $cid)->delete() ) {
			return true;
		} else {
			return false;
		}
	}

	//传递数据库字段名 获取对应的数据
	//不传递获取全部数据
	public function getAllData( $field = 'all' ) {
		if ( $field == 'all' ) {
			$data = $this->order('sort','asc')->select();
			return $data;
		} else {
			return $this->column( "cid,$field" );
		}
	}

	//传递cid和field获取对应的数据
	public function getDataByCid( $cid, $field = 'all' ) {
		if ( $field == 'all' ) {
			return $this->where( 'cid', $cid )->find();
		} else {
			return $this->where('cid',$cid)->field($field)->find();
		}
	}

	// 传递cid获得所有子栏目
	public function getChildData( $cid ) {
		$data  = $this->getAllData( 'all', false );
		$dodata = new Data();
		$child = $dodata->channelList($data,$cid);
		$childs = array();
		foreach ( $child as $k => $v ) {
			$childs[] = $v['cid'];
		}

		return $childs;
	}

}
