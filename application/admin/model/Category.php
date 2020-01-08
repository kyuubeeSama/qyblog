<?php

namespace app\admin\model;

use think\Db;
use think\model;
use think\Request;
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
	public function addData() {
		$request = new Request();
		$data    = $request->post();
		if ( $this->create( $data ) ) {
			return $this->insert();
		} else {
			return false;
		}
	}

	// 修改数据
	public function editData() {
		$request = new Request();
		$data    = $request->post();
		return $this->where('cid',$data['cid'])->update($data);
	}

	// 删除数据
	public function deleteData( $cid = null) {
		$request = \request();
		$cid     = is_null( $cid ) ? $request->param( "cid" ) : $cid;
		$child   = $this->getChildData( $cid );
		if ( ! empty( $child ) ) {
			$this->error = '请先删除子分类';

			return false;
		}
		$articleData = \model( 'Article' )->getDataByCid( $cid );
		if ( ! empty( $articleData ) ) {
			$this->error = '请先删除此分类下的文章';

			return false;
		}
		if ( $this->where( array( 'cid' => $cid ) )->delete() ) {
			return true;
		} else {
			return false;
		}
	}

	//传递数据库字段名 获取对应的数据
	//不传递获取全部数据
	public function getAllData( $field = 'all', $tree = true ) {
		//FIXME:使用$this可以获取数据，但是获取的数据结果并不是直接结果
		if ( $field == 'all' ) {
			$data = Db::table( 'qy_category' )->order( 'sort' )->select();
//			var_dump($rightdata);
//			echo "<br>";
//			$data = $this->order('sort')->select();
//			var_dump( $data);
			if ( $tree ) {
				$doData = new Data();
				return $doData->tree( $data, 'cname' );
			} else {
				return $data;
			}
		} else {
			return $this->column( "cid,$field" );
		}
	}

	//传递cid和field获取对应的数据
	public function getDataByCid( $cid, $field = 'all' ) {
		if ( $field == 'all' ) {
			return $this->where( 'cid', $cid )->find();
		} else {
			return $this->where( 'cid', $cid )->column( $field );
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
