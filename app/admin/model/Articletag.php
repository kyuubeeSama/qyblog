<?php

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * 文章标签关联表model
 */
class Articletag extends Model {
	/**
	 * 添加数据
	 *
	 * @param string $aid 文章id
	 * @param array $tids 标签id
	 */
	public function addData( $aid, $tids ) {
		foreach ( $tids as $k => $v ) {
			$tag_data = [
				'aid' => $aid,
				'tid' => $v,
			];
			$this->insert( $tag_data );
		}
		return true;
	}

	// 传递aid删除相关数据
	public function deleteData( $aid ) {
		return $this->where( 'aid', $aid )->delete();
	}

	// 传递aid和true时获取tid数组；传递aid和tname获得键名为aid键值为tname的数组
	public function getDataByAid( $aid, $field = 'true' ) {
		if ( $field == 'all' ) {
			return Db::table( 'qy_articletag' )
			         ->join( 'tag', 'qy_articletag.tid = tag.tid', 'LEFT' )
			         ->where( 'qy_articletag.aid', $aid )
			         ->select();
		} else {
			return $this->where( 'aid', $aid )->column( 'tid' );
		}

	}


}




