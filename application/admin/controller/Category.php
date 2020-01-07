<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Category extends Controller
{
	//定义数据表
	private $db;

	//定义category表数
	private $categoryData;

	//构造函数 实例化CategoryModel 并获得整张表的数据
	public function __construct(){
		parent::__construct();
		//初始化时实例化category model
		$this->db = model('Category');
		//获取category数据并赋值给$categoryData
		$this->categoryData = $this->db->getAllData();
	}
  public function index(){
	  $this->assign('data',$this->categoryData);
	  return $this->fetch('category/index');
  }
	// 添加分类
	public function add(){
		$requst = new Request();
		if($requst->isPost()){
			if($this->db->addData()){
				$this->success('分类添加成功');
			}else{
				$this->error($this->db->getError());
			}
		}else{
			$cid = input('cid',0,'intval');
			if($cid){
				$this->assign('cid',$cid);
			}
			$this->assign('data',$this->categoryData);
			return $this->fetch('category/add');
		}
	}

	// 修改分类
	public function edit(){
		$request = new Request();
		if($request->isPost()){
			if($this->db->editData()){
				$this->success('修改成功');
			}else{
				$this->error($this->db->getError());
			}
		}else{
			$cid = input('get.cid',0,'intval');
			$onedata=$this->db->getDataByCid($cid);
			$data=$this->categoryData;
			$childs=$this->db->getChildData($cid);
			foreach ($data as $k => $v) {
				if(in_array($v['cid'], $childs)){
					$data[$k]['_html']=" disabled='disabled' style='background:#F0F0F0'";
				}else{
					$data[$k]['_html']=" ";
				}
			}
			$this->assign('data',$data);
			$this->assign('onedata',$onedata);
			return $this->fetch('category/edit');
		}
	}

	// 删除分类
	public function delete(){
		if($this->db->deleteData()){
			$this->success('删除成功');
		}else{
			$this->error($this->db->getError());
		}

	}

	/**
	 * 排序
	 */
	public function sort(){
		$request = new Request();
		$data = $request->post();
		if (!empty($data)) {
			foreach ($data as $k => $v) {
				$this->db->where('cid',$k)->field('sort',$v);
			}
		}
		$this->success('修改成功',url('Admin/Category/index'));
	}
}