<?php
namespace app\admin\controller;
use app\admin\controller\AdminBase;
use think\facade\Config;
use think\Model;
use think\App;
use think\facade\View;

/**
 * 文章管理
 */
class Article extends AdminBase{
	// 定义数据表
	private $db;
	private $viewDb;

	// 构造函数 实例化ArticleModel表
	public function __construct(App $app){
		parent::__construct($app);
		$this->db = new \app\admin\model\Article();
	}

	//文章列表
	public function index(){
		$data=$this->db->getPageData('all','all','all',0,15);
		View::assign([
			'data'=>$data['data'],
			'page'=>$data['page']
		]);
		return View::fetch('index');
	}
	// 添加文章
	public function add(){
		if($this->request->isPost()){
			if($aid=$this->db->addData()){
//				$baidu_site_url=config('webconfig.BAIDU_SITE_URL');
//				if(!empty($baidu_site_url)){
//					$this->baidu_site($aid);
//				}
				$this->success('文章添加成功',url('Article/index'));
			}else{
				$this->error('文章添加失败');
			}
		}else{
			$category = new \app\admin\model\Category();
			$allCategory=$category->getAllData();
			if($allCategory->isEmpty()){
				$this->error('请先添加分类');
			}
			$tag = new \app\admin\model\Tag();
			$allTag=$tag->getAllData();
			View::assign([
				'allCategory'=>$allCategory,
				'allTag'=>$allTag,
				'author'=>Config::get("webconfig.AUTHOR")
			]);
			return View::fetch('add');
		}
	}

	// 向同步百度推送
	public function baidu_site($aid){
		$urls=array();
		$urls[]=url('Home/Index/article',array('aid'=>$aid),'',true);
		$api=config('webconfig.BAIDU_SITE_URL');
		$ch=curl_init();
		$options=array(
			CURLOPT_URL=>$api,
			CURLOPT_POST=>true,
			CURLOPT_RETURNTRANSFER=>true,
			CURLOPT_POSTFIELDS=>implode("\n", $urls),
			CURLOPT_HTTPHEADER=>array('Content-Type: text/plain'),
		);
		curl_setopt_array($ch, $options);
		$result=curl_exec($ch);
		$msg=json_decode($result,true);
		if($msg['code']==500){
			curl_exec($ch);
		}
		curl_close($ch);
	}

	// 修改文章
	public function edit(){
		if($this->request->isPost()){
			if($this->db->editData()){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
		}else{
			$category = new \app\admin\model\Category();
			$tag = new \app\admin\model\Tag();
			$aid=input('aid');
			$data=$this->db->getDataByAid($aid);
			$allCategory=$category->getAllData();
			$allTag=$tag->getAllData();
			View::assign([
				'aid'=>$aid,
				'allCategory'=>$allCategory,
				'allTag'=>$allTag,
				'data'=>$data
			]);
			return View::fetch('edit');
		}
	}
}
