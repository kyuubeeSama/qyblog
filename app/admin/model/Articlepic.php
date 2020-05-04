<?php
namespace app\admin\model;
use think\facade\Db;
use think\facade\Log;
use think\model;
/**
 * 文章图片关联表model
 */
class Articlepic extends model{

	/**
	 * 添加数据
	 * @param strind $aid 文章id
	 * @param array $tids 图片路径
	 */
	public function addData($aid,$image_path){
		foreach ($image_path as $k => $v) {
			$pic_data=array(
				'aid'=>$aid,
				'path'=>$v,
			);
			$this->insert($pic_data);
		}
		return true;
	}

	// 传递aid删除相关图片
	public function deleteData($aid){
		return $this->where('aid',$aid)->delete();
	}

	// 传递aid获取第一条数据作为文章的封面图片
	public function getDataByAid($aid){
		$data=$this->field('path')
			->where('aid',$aid)
			->order('ap_id','asc')
			->limit(1)
			->select();
		$root_path=rtrim($_SERVER['SCRIPT_NAME'],'/index.php');
		if ($data->isEmpty()){
			//FIXME:设置默认图片
			return "https://www.baidu.com";
		}else{
			$data[0]['path']=$root_path.$data[0]['path'];
			return $data[0]['path'];
		}
	}

}




