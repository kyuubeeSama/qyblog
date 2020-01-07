<?php
namespace app\admin\tagLib;
use think\template\taglib;

class Mytag extends TagLib{
	protected $tags = [
		//标签定义： attr 属性列表，close 是否闭合（0或1，默认1），alias标签别名 level嵌套层次
		'bootstrapcss' => ['','close'=>'0'],
		'bootstrapjs'  => ['','close'=>'0'],
	];

	// 当不使用content的时候，闭合标签没有效果
	// 修改过此文件后，需要改动下模板的内容，否则模板有缓存不会执行新的内容。
	public function tagBootstrapcss() {
		$link=<<<php
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="stylesheet" type="text/css" href="__PUBLIC__/bootstrap-3.3.7/css/bootstrap.min.css">
	    <link rel="stylesheet" type="text/css" href="__PUBLIC__/bootstrap-3.3.7/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" type="text/css" href="__PUBLIC__/font-awesome-4.7.0/css/font-awesome.min.css">
	    <link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bjy.css">
	    <link rel="stylesheet" type="text/css" href="__HOME__/css/index.css">
php;
		return $link;
	}

	public function tagBootstrapjs(){
		$link=<<<php
			<script src="__PUBLIC__/js/jquery-3.4.1.min.js"></script>
			<script>
			    logoutUrl="{:url('Home/User/logout')}";
			</script>
			<script src="__PUBLIC__/bootstrap-3.3.7/js/bootstrap.min.js"></script>
			<!--[if lt IE 9]>
			<script src="__PUBLIC__/js/html5shiv.min.js"></script>
			<script src="__PUBLIC__/js/respond.min.js"></script>
			<!--[endif]-->
			<script src="__PUBLIC__/pace/pace.min.js"></script>
			<script src="__HOME__/js/index.js"></script>
php;
		return $link;
	}

}
