<?php

namespace app\index\controller;

use app\admin\model\Category;
use app\admin\model\Chat;
use app\admin\model\Comment;
use app\admin\model\Tag;
use app\BaseController;
use app\IndexBase;
use app\Request;
use think\console\Input;
use think\facade\Db;
use think\facade\Config;
use app\admin\model\Article;
use think\facade\View;
use think\facade\Cookie;
use think\Log;

class Index extends IndexBase {
	// 首页
	public function index() {
		$article  = new Article();
		$articles = $article->getPageData();
//        echo json_encode($articles['data']);
//        exit();
		$assign = [
			'articles' => $articles['data'],
			'page'     => $articles['page'],
			'cid'      => 'index'
		];
		View::assign( $assign );

		return View::fetch( 'index' );
	}

	// 分类
	public function category() {
		$cid = input( 'get.cid', '0', 'intval' );
		// 获取分类数据
		$categoryModel = new Category();
		$category      = $categoryModel->getDataByCid( $cid );
		var_dump( $category );
		// 如果分类不存在；则返回404页面
		if ( empty( $category ) ) {
			header( "HTTP/1.0  404  Not Found" );

			return View::fetch( './view/public/404.html' );
			exit( 0 );
		}
		// 获取分类下的文章数据
		$article  = new Article();
		$articles = $article->getPageData( $cid );
		$assign   = [
			'category' => $category,
			'articles' => $articles['data'],
			'page'     => $articles['page'],
			'cid'      => $cid
		];
		View::assign( $assign );

		return View::fetch( 'category' );
	}

	// 标签
	public function tag() {
		$tid = input( 'get.tid', 0, 'intval' );
		// 获取标签名
		$tag   = new Tag();
		$tname = $tag->getDataByTid( $tid, 'tname' );
		// 如果标签不存在；则返回404页面
		if ( empty( $tname ) ) {
			header( "HTTP/1.0  404  Not Found" );

			return View::fetch( './view/public/404.html' );
			exit( 0 );
		}
		// 获取文章数据
		$article  = new Article();
		$articles = $article->getPageData( 'all', $tid );
		$assign   = [
			'articles'   => $articles['data'],
			'page'       => $articles['page'],
			'title'      => $tname,
			'title_word' => '拥有<span class="b-highlight">' . $tname . '</span>标签的文章',
			'cid'        => 'index'
		];
		View::assign( $assign );

		return View::fetch( 'tag' );
	}

	// 文章内容
	public function article() {
		$aid         = \input( 'get.aid', 0, 'intval' );
		$cid         = intval( Cookie::get( 'cid' ) );
		$tid         = intval( Cookie::get( 'tid' ) );
		$search_word = Cookie::get( 'search_word' );
		$search_word = empty( $search_word ) ? 0 : $search_word;
		$read = unserialize(Cookie::get('read'));
		// 判断是否已经记录过aid
		if ( is_array($read) && array_key_exists( $aid, $read ) ) {
			// 判断点击本篇文章的时间是否已经超过一天
			if ( $read[ $aid ] - time() >= 86400 ) {
				$read[ $aid ] = time();
				// 文章点击量+1
				Db::table( 'qy_Article' )->where( 'aid', $aid )->inc( 'click', 1 );
			}
		} else {
			$read[ $aid ] = time();
			// 文章点击量+1
			Db::table( 'qy_Article' )->where( 'aid', $aid )->inc( 'click', 1 );
		}
		Cookie::set('read',serialize($read),864000);
		switch ( true ) {
			case $cid == 0 && $tid == 0 && $search_word == (string) 0:
				$map = array();
				break;
			case $cid != 0:
				$map = [ 'cid' => $cid ];
				break;
			case $tid != 0:
				$map = [ 'tid' => $tid ];
				break;
			case $search_word !== 0:
				$map = [ 'title' => $search_word ];
				break;
		}
		// 获取文章数据
		$articleModel = new Article();
		$article      = $articleModel->getDataByAid( $aid, $map );
//		echo json_encode($article);
//		exit();
		// 如果文章不存在；则返回404页面
		if ( empty( $article['current']['aid'] ) ) {
			header( "HTTP/1.0  404  Not Found" );

			return View::fetch( './view/public/404.html' );
			exit( 0 );
		}
		// 获取评论数据
		$commentModel = new Comment();
		$comment      = $commentModel->getChildData( $aid );
		$assign       = [
			'article' => $article,
			'comment' => $comment,
			'cid'     => $article['current']['cid'],
			'user_'
		];
		if ( ! empty( $_SESSION['user']['id'] ) ) {
			//TODO:待解决
			$userData             = Db::table( 'qy_oauth_user' )->where( 'id', $_SESSION['user']['id'] )->field( 'email' )->find();
			$assign['user_email'] = $userData['email'];
		}
		View::assign( $assign );

		return View::fetch( 'article' );
	}

	// 随言碎语
	public function chat() {
		$chat   = new Chat();
		$assign = [
			'data' => $chat->getDataByState( 0, 1 ),
			'cid'  => 'chat'
		];
		View::assign( $assign );

		return View::fetch( 'chat' );
	}

	// 开源项目
	public function git() {
		$assign = [
			'cid' => 'git'
		];
		View::assign( $assign );

		return View::fetch( 'git' );
	}

	// 站内搜索
	public function search() {
		$search_word = \input( 'get.search_word', '', 'trim' );
		$article     = new Article();
		$articles    = $article->getDataByTitle( $search_word );
		$assign      = [
			'articles'   => $articles['data'],
			'page'       => $articles['page'],
			'title'      => $search_word,
			'title_word' => '搜索到的与<span class="b-highlight">' . $search_word . '</span>相关的文章',
			'cid'        => 'index'
		];
		View::assign( $assign );

		return View::fetch( 'tag' );
	}

	// ajax评论文章
	public function ajax_comment() {
		$data = $this->request->post();
		if ( empty( $data['content'] ) || ! isset( $_SESSION['user']['id'] ) ) {
			die( '未登录,或内容为空' );
		} else {
			$comment = new Comment();
			$cmtid   = $comment->addData( 1 );
			echo $cmtid;
		}
	}
}
