<?php
namespace app\admin\model;
use think\Db;
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
		if($this->where(array('tid'=>$tid))->delete()){
			return true;
		}else{
			return false;
		}
	}

	//获得全部数据
	public function getAllData(){
		$data=$this->select();
		foreach ($data as $k => $v) {
			$data[$k]['count']=Db::table('qy_Article_tag')->where(array('tid'=>$v['tid']))->count();
		}
		return $data;
	}

	// 根据tid获取单条数据
	public function getDataByTid($tid,$field='all'){
		if($field=='all'){
			return $this->where(array('tid'=>$tid))->find();
		}else{
			return $this->getFieldByTid($tid,'tname');
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
