<?php

namespace app\admin\model;

use think\facade\Request;
use think\Model;

/**
 * 友情链接model
 */
class Link extends Model {
	// 定义自动验证规则
	protected $_validate = array(
		array( 'lname', 'require', '链接名称必填' ),
		array( 'url', 'require', '链接必填' ),
		array( 'sort', 'require', '排序必填' ),
	);

	// 添加数据
	public function addData() {
//		$data=I('post.');
		$data = Request::post();
		if ( $this->create( $data ) ) {
			$data['url'] = 'http://' . str_replace( ['http://', 'https://'], '', $data['url'] );
//			$lid=$this->add($data);
			$lid = $this->insert( $data );

			return $lid;
		} else {
			return false;
		}
	}

	// 修改数据
	public function editData() {
//		$data=I('post.');
		$data = Request::post();
		if ( $this->create( $data ) ) {
			$data['url'] = 'http://' . str_replace(['http://', 'https://'], '', $data['url'] );
			$this->where( 'lid', $data['lid'] )->save( $data );

			return true;
		} else {
			return false;
		}
	}

	// 传递is_delete和is_show获取对应数据
	public function getDataByState( $is_delete = 0, $is_show = 1 ) {
		$where = [
			'is_delete' => $is_delete,
			'is_show'   => $is_show,
		];
		// 如果是获取全部链接；则删除is_show限制
		if ( $is_show === 'all' ) {
			unset( $where['is_show'] );
		}

		return $this->where( $where )->order( 'sort' )->select();
	}

	// 传递lid获取单条数据
	public function getDataByLid( $lid ) {
		return $this->where( 'lid', $lid )->find();
	}
}
