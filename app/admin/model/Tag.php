<?php
namespace app\admin\model;
use think\facade\Db;
use think\model;
/**
 * 标签model
 */
Class Tag extends model{
	// 添加标签
	public function addData(){
		$str=input('post.tnames');
		if(empty($str)){
			$this->error='标签名不能为空';
			return false;
		}else{
			$str=nl2br(trim($str));
			$tnames=explode("<br />", $str);
			foreach ($tnames as $k => $v) {
				$v=trim($v);
				if(!empty($v)){
					$data['tname']=$v;
					$this->insert($data);
				}
			}
			return true;
		}

	}

	// 修改数据
	public function editData(){
		$tid=input('post.tid');
		$data['tname']=input('post.tname');
		if(empty($data)){
			$this->error='标签名不能为空';
			return false;
		}else{
			return $this->where('tid',$tid)->update($data);
		}
	}

	// 删除数据
	public function deleteData(){
		$tid=input('tid',0,'intval');
		if($this->where('tid',$tid)->delete()){
			return true;
		}else{
			return false;
		}
	}

	//获得全部数据
	public function getAllData(){
		$data=$this->select();
		$articletag = new Articletag();
		foreach ($data as $k => $v) {
			$data[$k]['count']=$articletag->where('tid',$v['tid'])->count();
		}
		return $data;
	}

	// 根据tid获取单条数据

	/**
	 * @param $tid 标签id
	 * @param string $field 默认获取全部字段
	 *
	 * @return array|Model|null  获取指定id数据
	 */
	public function getDataByTid($tid,$field='all'){
		if($field=='all'){
			return $this->where('tid',$tid)->find();
		}else{
			return $this->field('tname')->where($tid,'tname')->find();
		}
	}

	/**
	 * 获取tname
	 * @param array $tids 文章id
	 * @return array $tnames 标签名
	 */
	public function getTnames($tids){
		foreach ($tids as $k => $v) {
			$tnames[]=$this->where('tid',$v)->value('tname');
		}
		return $tnames;
	}



}
