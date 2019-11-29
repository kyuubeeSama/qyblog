-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2019-11-29 06:28:18
-- 服务器版本： 10.1.36-MariaDB
-- PHP 版本： 7.1.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `qyblog`
--

-- --------------------------------------------------------

--
-- 表的结构 `qy_article`
--

CREATE TABLE `qy_article` (
  `aid` int(10) UNSIGNED NOT NULL COMMENT '文章表主键',
  `title` char(100) NOT NULL DEFAULT '' COMMENT '标题',
  `author` varchar(15) NOT NULL DEFAULT '' COMMENT '作者',
  `content` mediumtext NOT NULL COMMENT '文章内容',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` char(255) NOT NULL DEFAULT '' COMMENT '描述',
  `is_show` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '文章是否显示 1是 0否',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除 1是 0否',
  `is_top` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否置顶 1是 0否',
  `is_original` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否原创',
  `click` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点击数',
  `addtime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  `cid` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `qy_article`
--

INSERT INTO `qy_article` (`aid`, `title`, `author`, `content`, `keywords`, `description`, `is_show`, `is_delete`, `is_top`, `is_original`, `click`, `addtime`, `cid`) VALUES
(17, '测试文章标题', '白俊遥', '&lt;p&gt;测试文章内容&lt;img alt=&quot;白俊遥博客&quot; src=&quot;/Upload/image/ueditor/20150601/1433171136139793.jpg&quot; title=&quot;白俊遥博客&quot;/&gt;&lt;/p&gt;', '关键词,多个', '测试文章描述', 1, 0, 1, 1, 376, 1432649909, 28);

-- --------------------------------------------------------

--
-- 表的结构 `qy_article_pic`
--

CREATE TABLE `qy_article_pic` (
  `ap_id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT '图片路径',
  `aid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属文章id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `qy_article_pic`
--

INSERT INTO `qy_article_pic` (`ap_id`, `path`, `aid`) VALUES
(11, '/Upload/image/ueditor/20150601/1433171136139793.jpg', 17);

-- --------------------------------------------------------

--
-- 表的结构 `qy_article_tag`
--

CREATE TABLE `qy_article_tag` (
  `aid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文章id',
  `tid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '标签id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `qy_article_tag`
--

INSERT INTO `qy_article_tag` (`aid`, `tid`) VALUES
(17, 20);

-- --------------------------------------------------------

--
-- 表的结构 `qy_category`
--

CREATE TABLE `qy_category` (
  `cid` tinyint(2) UNSIGNED NOT NULL COMMENT '分类主键id',
  `cname` varchar(15) NOT NULL DEFAULT '' COMMENT '分类名称',
  `keywords` varchar(255) DEFAULT '' COMMENT '关键词',
  `description` varchar(255) DEFAULT '' COMMENT '描述',
  `sort` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `pid` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级栏目id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `qy_category`
--

INSERT INTO `qy_category` (`cid`, `cname`, `keywords`, `description`, `sort`, `pid`) VALUES
(28, '测试分类', '测试分类关键词', '测试分类描述', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `qy_chat`
--

CREATE TABLE `qy_chat` (
  `chid` int(10) UNSIGNED NOT NULL COMMENT '碎言id',
  `date` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '发表时间',
  `content` text NOT NULL COMMENT '内容',
  `is_show` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否显示',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `qy_chat`
--

INSERT INTO `qy_chat` (`chid`, `date`, `content`, `is_show`, `is_delete`) VALUES
(2, 1432827004, '测试随言碎语', 1, 0),
(3, 1444529995, '测试碎言', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `qy_comment`
--

CREATE TABLE `qy_comment` (
  `cmtid` int(10) UNSIGNED NOT NULL COMMENT '主键id',
  `ouid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论用户id 关联oauth_user表的id',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1：文章评论',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级id',
  `aid` int(10) UNSIGNED NOT NULL COMMENT '文章id',
  `content` text NOT NULL COMMENT '内容',
  `date` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论日期',
  `status` tinyint(1) UNSIGNED NOT NULL COMMENT '1:已审核 0：未审核',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `qy_comment`
--

INSERT INTO `qy_comment` (`cmtid`, `ouid`, `type`, `pid`, `aid`, `content`, `date`, `status`, `is_delete`) VALUES
(19, 1, 1, 0, 17, '测试评论&lt;img src=&quot;/Public/emote/tuzki/t_0002.gif&quot; title=&quot;Love&quot; alt=&quot;白俊遥博客&quot;&gt;', 1445747059, 1, 0),
(21, 1, 1, 19, 17, '测试回复', 1447943018, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `qy_config`
--

CREATE TABLE `qy_config` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '配置项键名',
  `value` text COMMENT '配置项键值 1表示开启 0 关闭'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `qy_config`
--

INSERT INTO `qy_config` (`id`, `name`, `value`) VALUES
(1, 'WEB_NAME', '白俊遥博客'),
(2, 'WEB_KEYWORDS', '白俊遥,帅白,技术博客,个人博客,bjyblog'),
(3, 'WEB_DESCRIPTION', '白俊遥的个人技术博客,bjyblog官方网站'),
(4, 'WEB_STATUS', '1'),
(5, 'ADMIN_PASSWORD', '25f9e794323b453885f5181f1b624d0b'),
(6, 'WATER_TYPE', '1'),
(7, 'TEXT_WATER_WORD', 'baijunyao.com'),
(8, 'TEXT_WATER_TTF_PTH', './Public/static/font/ariali.ttf'),
(9, 'TEXT_WATER_FONT_SIZE', '15'),
(10, 'TEXT_WATER_COLOR', '#008CBA'),
(11, 'TEXT_WATER_ANGLE', '0'),
(12, 'TEXT_WATER_LOCATE', '9'),
(13, 'IMAGE_WATER_PIC_PTAH', './Upload/image/logo/logo.png'),
(14, 'IMAGE_WATER_LOCATE', '9'),
(15, 'IMAGE_WATER_ALPHA', '80'),
(16, 'WEB_CLOSE_WORD', '网站升级中，请稍后访问。'),
(17, 'WEB_ICP_NUMBER', '豫ICP备14009546号-3'),
(18, 'ADMIN_EMAIL', 'baijunyao@baijunyao.com'),
(19, 'COPYRIGHT_WORD', '本文为白俊遥原创文章,转载无需和我联系,但请注明来自白俊遥博客baijunyao.com'),
(20, 'QQ_APP_ID', ''),
(21, 'CHANGYAN_APP_ID', 'cyrKRbJ5N'),
(22, 'CHANGYAN_CONF', 'prod_c654661caf5ab6da98742d040413815b'),
(23, 'WEB_STATISTICS', ''),
(24, 'CHANGEYAN_RETURN_COMMENT', ''),
(25, 'AUTHOR', '白俊遥'),
(26, 'QQ_APP_KEY', ''),
(27, 'CHANGYAN_COMMENT', ''),
(28, 'BAIDU_SITE_URL', ''),
(29, 'DOUBAN_API_KEY', ''),
(30, 'DOUBAN_SECRET', ''),
(31, 'RENREN_API_KEY', ''),
(32, 'RENREN_SECRET', ''),
(33, 'SINA_API_KEY', ''),
(34, 'SINA_SECRET', ''),
(35, 'KAIXIN_API_KEY', ''),
(36, 'KAIXIN_SECRET', ''),
(37, 'SOHU_API_KEY', ''),
(38, 'SOHU_SECRET', ''),
(39, 'GITHUB_CLIENT_ID', ''),
(40, 'GITHUB_CLIENT_SECRET', ''),
(41, 'IMAGE_TITLE_ALT_WORD', '白俊遥博客'),
(42, 'EMAIL_SMTP', ''),
(43, 'EMAIL_USERNAME', ''),
(44, 'EMAIL_PASSWORD', ''),
(45, 'EMAIL_FROM_NAME', ''),
(46, 'COMMENT_REVIEW', '1'),
(47, 'COMMENT_SEND_EMAIL', '0'),
(48, 'EMAIL_RECEIVE', '');

-- --------------------------------------------------------

--
-- 表的结构 `qy_link`
--

CREATE TABLE `qy_link` (
  `lid` int(10) UNSIGNED NOT NULL COMMENT '主键id',
  `lname` varchar(50) NOT NULL DEFAULT '' COMMENT '链接名',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '排序',
  `is_show` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '文章是否显示 1是 0否',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除 1是 0否'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `qy_link`
--

INSERT INTO `qy_link` (`lid`, `lname`, `url`, `sort`, `is_show`, `is_delete`) VALUES
(2, '白俊遥博客', 'http://baijunyao.com', 1, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `qy_oauth_user`
--

CREATE TABLE `qy_oauth_user` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '关联的本站用户id',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '类型 1：QQ  2：新浪微博 3：豆瓣 4：人人 5：开心网',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '第三方昵称',
  `head_img` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `openid` varchar(40) NOT NULL DEFAULT '' COMMENT '第三方用户id',
  `access_token` varchar(255) NOT NULL DEFAULT '' COMMENT 'access_token token',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '绑定时间',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `login_times` int(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录次数',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `is_admin` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否是admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `qy_tag`
--

CREATE TABLE `qy_tag` (
  `tid` int(10) UNSIGNED NOT NULL COMMENT '标签主键',
  `tname` varchar(10) NOT NULL DEFAULT '' COMMENT '标签名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `qy_tag`
--

INSERT INTO `qy_tag` (`tid`, `tname`) VALUES
(20, '测试标签');

--
-- 转储表的索引
--

--
-- 表的索引 `qy_article`
--
ALTER TABLE `qy_article`
  ADD PRIMARY KEY (`aid`);

--
-- 表的索引 `qy_article_pic`
--
ALTER TABLE `qy_article_pic`
  ADD PRIMARY KEY (`ap_id`);

--
-- 表的索引 `qy_category`
--
ALTER TABLE `qy_category`
  ADD PRIMARY KEY (`cid`);

--
-- 表的索引 `qy_chat`
--
ALTER TABLE `qy_chat`
  ADD PRIMARY KEY (`chid`);

--
-- 表的索引 `qy_comment`
--
ALTER TABLE `qy_comment`
  ADD PRIMARY KEY (`cmtid`);

--
-- 表的索引 `qy_config`
--
ALTER TABLE `qy_config`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `qy_link`
--
ALTER TABLE `qy_link`
  ADD PRIMARY KEY (`lid`);

--
-- 表的索引 `qy_oauth_user`
--
ALTER TABLE `qy_oauth_user`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `qy_tag`
--
ALTER TABLE `qy_tag`
  ADD PRIMARY KEY (`tid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `qy_article`
--
ALTER TABLE `qy_article`
  MODIFY `aid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文章表主键', AUTO_INCREMENT=18;

--
-- 使用表AUTO_INCREMENT `qy_article_pic`
--
ALTER TABLE `qy_article_pic`
  MODIFY `ap_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `qy_category`
--
ALTER TABLE `qy_category`
  MODIFY `cid` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类主键id', AUTO_INCREMENT=29;

--
-- 使用表AUTO_INCREMENT `qy_chat`
--
ALTER TABLE `qy_chat`
  MODIFY `chid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '碎言id', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `qy_comment`
--
ALTER TABLE `qy_comment`
  MODIFY `cmtid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=22;

--
-- 使用表AUTO_INCREMENT `qy_config`
--
ALTER TABLE `qy_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=49;

--
-- 使用表AUTO_INCREMENT `qy_link`
--
ALTER TABLE `qy_link`
  MODIFY `lid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `qy_oauth_user`
--
ALTER TABLE `qy_oauth_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id';

--
-- 使用表AUTO_INCREMENT `qy_tag`
--
ALTER TABLE `qy_tag`
  MODIFY `tid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '标签主键', AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
