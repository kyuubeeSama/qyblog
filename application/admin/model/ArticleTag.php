<?php
namespace app\admin\model;

use think\Db;
use think\Model;

/**
 * 文章标签关联表model
 */
class ArticleTag extends Model {

	/**
	 * 添加数据
	 * @param string $aid 文章id
	 * @param array $tids 标签id
	 */
	public function addData($aid,$tids){
		foreach ($tids as $k => $v) {
			$tag_data=array(
				'aid'=>$aid,
				'tid'=>$v,
			);
			$this->insert($tag_data);
		}
		return true;
	}

	// 传递aid删除相关数据
	public function deleteData($aid){
		$this->where('aid',$aid)->delete();
		return true;
	}

	// 传递aid和true时获取tid数组；传递aid和tname获得键名为aid键值为tname的数组
	public function getDataByAid($aid,$field='true'){
		if($field=='all'){
			return Db::table('ArticleTag')
				->join('__TAG__ ON __ARTICLE_TAG__.tid=__TAG__.tid')
				->where(array('aid'=>$aid))
				->select();
		}else{
			return $this->where('aid',$aid)->column('tid',true);
		}

	}


}




