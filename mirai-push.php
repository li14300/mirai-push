<?php
/*
Plugin Name: mirai push (mirai&Qmsg文章推送)
Plugin URI: https://www.imut.xyz/522
Description: 在文章发布后通过发送POST给mirai和Qmag实现文章通知推送，功能及其不完善，目前仅用于社团Blog及个人Blog
Version: 0.3.0
Author: 鹏宇的昵称已被占用
Author URI: https://www.imut.xyz
License: GPLv2
*/

// 将函数挂载 WordPress 各个接口
add_action('plugins_loaded','mirai_push_load_plugin');
add_filter('plugin_action_links','mirai_push_add_link',10,2);
add_action('publish_post','mirai_push',20,2);

// 加载插件文件
function mirai_push_load_plugin() {
	$load_includes = dirname(__FILE__).'/includes/';
	require($load_includes.'mirai-main.php');
	require($load_includes.'mirai-verify.php');
}

// 添加插件管理页面链接
function mirai_push_add_link($actions,$plugin_file) {
	static $plugin;
	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);
	if ($plugin == $plugin_file) {
        $site_link  = array('blog'=>'<a href="https://imut.xyz" target="_blank">博客</a>');
		$actions  = array_merge($site_link, $actions);
	}
return $actions;
}

?>