<?php

namespace app\admin\tagLib;

use think\template\taglib;

class Mytag extends TagLib {
	protected $tags = [
		//标签定义： attr 属性列表，close 是否闭合（0或1，默认1），alias标签别名 level嵌套层次
		'bootstrapcss' => [ '', 'close' => '0' ],
		'bootstrapjs'  => [ '', 'close' => '0' ],
		'icheckjs'     => [ 'attr' => 'icheck', 'close' => 0 ],
		'icheckcss'    => [ '', 'close' => '0' ],
		'ueditor'      => [ 'attr' => 'name,content', 'close' => 0 ],

	];

	// 当不使用content的时候，闭合标签没有效果
	// 修改过此文件后，需要改动下模板的内容，否则模板有缓存不会执行新的内容。
	public function tagBootstrapcss() {
		$link = <<<php
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	    <link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bjy.css">
	    <link rel="stylesheet" type="text/css" href="__HOME__/css/index.css">
php;

		return $link;
	}

	public function tagBootstrapjs() {
		$link = <<<php
			<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
			<script>
			    logoutUrl="{:url('Home/User/logout')}";
			</script>
			
			<!--[if lt IE 9]>
			<script src="__PUBLIC__/js/html5shiv.min.js"></script>
			<script src="__PUBLIC__/js/respond.min.js"></script>
			<!--[endif]-->
			<script src="__PUBLIC__/pace/pace.min.js"></script>
			<script src="__HOME__/js/index.js"></script>
php;

		return $link;
	}

	/**
	 * 引入ickeck的js部分
	 *
	 * @param string $tag 颜色主题
	 *
	 * @return string
	 */
	public function tagicheckjs( $tag ) {
		$color = isset( $tag['color'] ) ? $tag['color'] : 'blue';
		$link  = <<<php
			<script src="__PUBLIC__/iCheck-1.0.2/icheck.min.js"></script>
			<script>
			$(document).ready(function(){
			    $('.icheck').iCheck({
			        checkboxClass: "icheckbox_square-$color",
			        radioClass: "iradio_square-$color",
			        increaseArea: "20%"
			    });
			});
			</script>
php;

		return $link;
	}

	public function tagicheckcss() {
		$link = <<<php
                <link rel="stylesheet" href="__PUBLIC__/iCheck-1.0.2/skins/all.css">
php;

		return $link;
	}
}
