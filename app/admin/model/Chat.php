<?php
namespace app\admin\model;
use think\model;
use think\facade\Request;
/**
 * 闲言碎语model
 */
class Chat extends model{
	// 定义自动验证规则
	protected $_vachidate=[['content','require','内容必填']];

	// 添加数据
	public function addData(){
		$data = Request::post();
		if($this->create($data)){
			$data['date']=time();
			$data['is_delete']=0;
			$chid = $this->insertGetId($data);
			return $chid;
		}else{
			return false;
		}
	}

	// 修改数据
	public function editData(){
		$data = Request::post();
		if($this->create($data)){
			$this->where('chid',$data['chid'])->save($data);
			return true;
		}else{
			return false;
		}
	}

	// 传递is_delete和is_show获取对应数据
	public function getDataByState($is_delete='all',$is_show='all'){
		$is_delete=$is_delete==='all' ? '' : "is_delete=$is_delete";
		$is_show=$is_show==='all' ? '' : "is_show=$is_show";
		$where=trim(trim($is_delete.' and '.$is_show,' '),'and');
		return $this->where($where)->order('date','desc')->select();
	}

	// 传递chid获取单条数据
	public function getDataByLid($chid){
		return $this->where('chid',$chid)->find();
	}

	// 传递$map获取count数据
	public function getCountData($map=array()){
		return $this->where($map)->count();
	}


}
