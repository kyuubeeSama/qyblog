<?php
namespace app\admin\model;

use think\facade\Db;
use think\Model;
use think\facade\Request;
use Tool\Page;

/**
 * 评论model
 */
class Comment extends Model {
	private $child = array();

	/**
	 * 添加数据
	 *
	 * @param integer $type 1：文章评论
	 */
	public function addData( $type ) {
		$data     = Request::post();
		$ouid     = $_SESSION['user']['id'];
		$nickname = $_SESSION['user']['nickname'];
//		$is_admin=M('Oauth_user')->getFieldById($ouid,'is_admin');
		$is_admin        = Db::table( 'qy_oauth_user' )->where( 'id', $ouid )->value( is_admin );
		$data['content'] = htmlspecialchars_decode( $data['content'] );
		$data['content'] = preg_replace( '/on.+\".+\"/i', '', $data['content'] );
		// 删除除img外的其他标签
		$comment_content = trim( strip_tags( $data['content'], '<img>' ) );
		$content         = htmlspecialchars( $comment_content );
		if ( empty( $content ) ) {
			return false;
		}
		$comment = [
			'ouid'    => $ouid,
			'type'    => $type,
			'aid'     => $data['aid'],
			'pid'     => $data['pid'],
			'content' => $content,
			'date'    => time(),
			'status'  => 1
		];
		$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
		// 如果用户输入邮箱；则将邮箱记录入oauth_user表中
		if ( preg_match( $pattern, $data['email'] ) ) {
			Db::table( "qy_Oauth_user" )->where( 'id', $_SESSION['user']['id'] )->update( [ 'email' => $data['email'] ] );
//			D('Oauth_user')->where(array('id'=>$_SESSION['user']['id']))->setField('email',$data['email']);
		}
		// 添加数据
		$cmtid = $this->insertGetId( $comment );
//		$cmtid=$this->add($comment);

		// 给站长发送通知邮件
		if ( config( 'webconfig.COMMENT_SEND_EMAIL' ) && $is_admin == 0 ) {
			$address = config( 'webconfig.EMAIL_RECEIVE' );
			if ( ! empty( $address ) ) {
//				$title=M('Article')->getFieldByAid($data['aid'],'title');
				$title = Db::table( 'qy_article' )->where( 'aid', $data['aid'] )->value( 'title' );
//				$url=U('Home/Index/article',array('aid'=>$data['aid']),'',true);
				$url     = url( 'index/index/article', [ 'aid' => $data['aid'] ], '', true );
				$date    = date( 'Y-m-d H:i:s' );
				$content = <<<html
站长你好：<br>
&emsp; $nickname $date 评论了您的文章 <a href="$url">$title</a> 内容如下:<br>
$comment_content

html;
				send_email( $address, $nickname . '评论了 ' . $title, $content );
			}
		}
		// 给用户发送邮件通知
		if ( config( 'webconfig.COMMENT_SEND_EMAIL' ) && $data['pid'] != 0 ) {
//			$parent_uid=$this->getFieldByCmtid($data['pid'],'ouid');
			$parent_uid = $this->where( 'cmtid', $data['pid'] )->value( 'ouid' );
//			$parent_data=M('Oauth_user')->field('nickname,email')->find($parent_uid);
			$parent_data    = Db::table( 'qy_oauth_user' )->field( 'nickname,email' )->where( 'id', $parent_uid )->find();
			$parent_address = $parent_data['email'];
			if ( ! empty( $parent_address ) ) {
				$parent_name = $parent_data['nickname'];
//				$title=M('Article')->getFieldByAid($data['aid'],'title');
				$title          = Db::table( 'qy_article' )->where( 'aid', $data['aid'] )->value( 'title' );
				$url            = url( 'index/index/article', [ 'aid' => $data['aid'] ], '', true );
				$date           = date( 'Y-m-d H:i:s' );
				$parent_content = <<<html
$parent_name你好：<br>
&emsp; $nickname $date 回复了您对 <a href="$url">$title</a> 的评论  内容如下:<br>
$comment_content

html;
				send_email( $parent_address, $nickname . '回复了 ' . $title, $parent_content );
			}

		}

		return $cmtid;
	}

	/**
	 * 获取分页数据供后台使用
	 *
	 * @param int   是否删除
	 *
	 * @return array 评论数据
	 */
	public function getDataByState( $is_delete ) {
		$count = Db::table( 'qy_comment' )
		           ->alias( 'c' )
//			->join('article a ON a.aid=c.aid')
                   ->join( 'article a', 'a.aid = c.aid', 'left' )
//			->join('oauth_user ou ON ou.id=c.ouid')
                   ->join( 'oauth_user ou', 'ou.id = c.ouid', 'left' )
		           ->where( 'c.is_delete', $is_delete )
		           ->count();
		$page  = new Page( $count, 15 );
		$list  = Db::table( 'qy_comment' )
		           ->field( 'c.*,a.title,ou.nickname' )
		           ->alias( 'c' )
//			->join('article a ON a.aid=c.aid')
                   ->join( 'article a', 'a.aid = c.aid', 'left' )
//			->join('oauth_user ou ON ou.id=c.ouid')
                   ->join( 'oauth_user ou', 'ou.id=c.ouid', 'left' )
		           ->where( 'c.is_delete', $is_delete )
		           ->limit( $page->firstRow, $page->listRows )
		           ->order( 'date desc' )
		           ->select();
		$data  = [
			'data' => $list,
			'page' => $page->show()
		];

		return $data;
	}

	/**
	 * 传递文章id获取树状结构的评论
	 *
	 * @param int $aid 文章id
	 *
	 * @return array    树状结构数组
	 */
	public function getChildData( $aid ) {
		// 组合where条件
		$status = config( 'webconfig.COMMENT_REVIEW' ) ? [ 'c.status' => 1 ] : [];
		$other  = [
			'c.aid'       => $aid,
			'c.pid'       => 0,
			'c.is_delete' => 0
		];
		$where  = array_merge( $status, $other );
		// 关联第三方用户表获取一级评论
		$data = Db::table( 'qy_comment' )
		          ->alias( 'c' )
		          ->field( 'c.*,ou.nickname,ou.head_img' )
//			->join('oauth_user ou ON c.ouid=ou.id')
                  ->join( 'oauth_user ou', 'c.ouid=ou.id', 'left' )
		          ->where( $where )
		          ->order( 'date desc' )
		          ->select();
		foreach ( $data as $k => $v ) {
			$data[ $k ]['content'] = htmlspecialchars_decode( $v['content'] );
			// 获取二级评论
			$this->child = array();
			$this->getTree( $v );
			$child = $this->child;
			if ( ! empty( $child ) ) {
				// 按评论时间asc排序
				uasort( $child, 'comment_sort' );
				foreach ( $child as $m => $n ) {
					// 获取被评论人id
//					$reply_user_id = $this->getFieldByCmtid( $n['pid'], 'ouid' );
					$reply_user_id = $this->where( 'cmtid', $n['pid'] )->value( 'outid' );
					// 获取被评论人昵称
//					$child[$m]['reply_name']=D('OauthUser')->getFieldById($reply_user_id,'nickname');
					$child[ $m ]['reply_name'] = Db::table( 'qy_oauthUser' )->where( 'id', $reply_user_id )->value( 'nickname' );
				}
			}
			$data[ $k ]['child'] = $child;
		}

		return $data;
	}

	// 递归获取树状结构
	public function getTree( $data ) {
		$child = Db::table( 'comment' )
		           ->field( 'c.*,ou.nickname,ou.head_img' )
		           ->alias( 'c' )
		           ->join( 'oauth_user ou', 'c.ouid=ou.id', 'left' )
//			->join('oauth_user ou ON c.ouid=ou.id')
                   ->where( 'pid', $data['cmtid'] )
		           ->select();
		if ( ! empty( $child ) ) {
			foreach ( $child as $k => $v ) {
				$v['content']  = htmlspecialchars_decode( $v['content'] );
				$this->child[] = $v;
				$this->getTree( $v );
			}
		}

	}

	/**
	 * 获取最新的评论
	 */
	public function getNewComment() {
		// 获取后台管理员
        $uids = Db::table("qy_oauth_user")
            ->where('is_admin',"1")
            ->field('id')
            ->find();
		// 如果没有设置管理员；显示全部评论
		if ( empty( $uids ) ) {
			$map = [ 'c.is_delete' => 0 ];
		} else {
			// 设置了管理员；则不显示管理员的评论
			$map = [
				'ou.id'       => array( 'notin', $uids ),
				'c.is_delete' => 0
			];
		}
		$data = Db::table( 'qy_comment' )
		          ->field( 'c.content,c.date,a.title,a.aid,ou.nickname,ou.head_img' )
		          ->alias( 'c' )
//			->join( 'article a ON c.aid=a.aid' )
                  ->join( 'article a', 'c.aid=a.aid', 'left' )
//			->join( 'oauth_user ou ON c.ouid=ou.id' )
                  ->join( 'oauth_user ou', 'c.ouid=ou.id', 'left' )
		          ->where( $map )
		          ->order( 'c.date desc' )
		          ->limit( 20 )
		          ->select();
		$data = $data->all();
		foreach ( $data as $k => $v ) {
			$data[ $k ]['date'] = word_time( $v['date'] );
			// 截取文章标题
			$data[ $k ]['title'] = re_substr( $v['title'], 0, 20 );
			// 处理有表情时直接截取会把img表情截断的问题
			$content = strip_tags( htmlspecialchars_decode( $v['content'] ) );
			if ( mb_strlen( $content ) > 10 ) {
				$data[ $k ]['content'] = re_substr( $content, 0, 40 );
			} else {
				$data[ $k ]['content'] = htmlspecialchars_decode( $v['content'] );
			}
		}

		return $data;
	}


}
