<?php

namespace app\admin\controller;


use app\BaseController;
use think\facade\Session;
use think\facade\View;

class Demo extends BaseController {

	public function index(){
		if ($this->request->isPost()){
			$captch = $this->request->param('captch');
			echo "<br>".$captch;
			if (captcha_check($captch)){
				echo "正确";
			}
		}
		return View::fetch('index');
	}

}