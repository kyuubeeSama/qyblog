<?php
namespace app;

use app\admin\model\Category;
use app\admin\model\Comment;
use app\admin\model\Link;
use app\admin\model\Tag;
use app\BaseController;
use think\facade\View;
use think\facade\Db;
use think\facade\Log;

class IndexBase extends BaseController {
    /**
     * 初始化方法
     */
    public function initialize(){
        parent::initialize();
        // 判断博客是否关闭
//        if(cache('WEB_STATUS')!=1){
//            return View::fetch("Public/web_close");
//            exit();
//        }
        // 组合置顶推荐where
        $recommend_map=[
            'is_show'=>1,
            'is_delete'=>0,
            'is_top'=>1
        ];
        // 获取置顶推荐文章
        $recommend = Db::table("qy_article")
            ->field('aid,title')
            ->where($recommend_map)
            ->order('aid desc')
            ->select();
        // 获取最新评论
        $comment = new Comment();
        $new_comment = $comment->getNewComment();
        // 判断是否显示友情链接
        $show_link = 1;
        // 分配常用数据
        $category = new Category();
        $tag = new Tag();
        $link = new Link();
        $assign=['categorys'=>$category->getAllData(),
                 'tags'=>$tag->getAllData(),
                 'links'=>$link->getDataByState(0,1),
                 'recommend'=>$recommend,
                 'new_comment'=>$new_comment,
                 'show_link'=>$show_link
	        ];
        View::assign($assign);
    }
}