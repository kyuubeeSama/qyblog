<?php
namespace app\admin\model;
use think\facade\Request;
use think\Model;

/**
 * 配置项model
 */
class Config extends Model {

	// // 自动验证
	// protected $_validate=array(
	//  array('old_password','require','原密码不能为空'),
	//  array('ADMIN_PASSWORD','require','新密码不能为空'),
	//  array('re_password','require','重复密码不能为空'),
	//  array('re_password','ADMIN_PASSWORD','两次密码不一致',0,'confirm'),
	//  );

	// 获取全部数据
	/**
	 * @var string
	 */
	private $error;

	public function getAllData(){
//		return $this->getField('name,value');
		return $this->field('name,value')->find();
	}

	// 修改密码
	public function changePassword(){
//		$data=I('post.');
		$data = Request::post();
		if($data['ADMIN_PASSWORD']==$data['re_password']){
//			$old_password=$this->getFieldByName('ADMIN_PASSWORD','value');
			$old_password = $this->field('old_password')->where('value' , 'ADMIN_PASSWORD')->find();
			if(md5($data['old_password'])==$old_password){
				$password=md5($data['ADMIN_PASSWORD']);
//				$this->where(['name'=>'ADMIN_PASSWORD'])->setField('value',$password);
				$this->where(['name'=>'ADMIN_PASSWORD'])->update(['value'=>$password]);
				return true;
			}else{
				$this->error='原密码输入错误';
			}
		}else{
			$this->error='两次密码不一致';
			return false;
		}
	}
}
