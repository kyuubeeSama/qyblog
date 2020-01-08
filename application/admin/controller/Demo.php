<?php

namespace app\admin\controller;

use think\Controller;

class Demo extends Controller{
	public function index(){
		$image_title_alt_word = config( 'webconfig.WEB_CLOSE_WORD' );
		echo $image_title_alt_word;
	}
}