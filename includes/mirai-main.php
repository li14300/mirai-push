<?php
/*
插件内容推送代码，请勿随意修改，更改配置请修改config.php
*/

// 获取推送文字部分
function mirai_push($post_id){
    // 手动更新session，并加载参数
    $load_mirai_includes = WP_PLUGIN_DIR.'/mirai-push/includes/';
	require ($load_mirai_includes.'mirai-verify.php');
    
    // 获取站点标题，文章内容，首张缩略图，并获取作者昵称
    $home_url = home_url();
    $blog_title = get_bloginfo('name');
    $post = get_post($post_id);
    $nickname = get_the_author_meta('nickname', $post->post_author);
    $mirai_push_image = mirai_catch_that_image($post_id);
    
    // 控制文章正文推送长度
    $post_content_short = wp_trim_words($post->post_content, $mirai_text_length, '...' );
    echo $post_content_short;
    
    // 根据自己需求，加入相关描述，可以包括文章标题、作者、内容、文章链接、发布日期等
    $topic = "$blog_title 更新文章《 $post->post_title 》";
    $text = $topic . "\n\t作者:$nickname\n\t$post_content_short\n\t点击查看全文：$home_url/$post_id \n" ;

// 以下为mirai推送
    // 数据转化为json格式
    $mirai_postdata = json_encode(array('sessionKey'=>$mirai_push_session,'target'=>$mirai_push_id,'messageChain'=>array(0=>array('type'=>'Plain','text'=>$text),1=>array('type'=>'Image','url'=>$mirai_push_image))));//此处可自行添加指令，如at全体成员
    // 组合POST数据
    $mirai_opts = array('http'=>array('method'=>'POST','header'=>'Content-type: application/json','content'=>$mirai_postdata));
    $mirai_context = stream_context_create($mirai_opts);
    // 发送对mirai酱的POST请求
    $mirai_result = file_get_contents($mirai_push_url, false, $mirai_context);
    
// 以下内容为Qmsg推送
    // 将上述内容封装为一个对象，其 msg 字段即为我们需要推送到 QQ 的消息内容
    $qmsg_postdata = http_build_query(array('msg'=>$text,'qq'=>$qmsg_push_id));
    // 组合POST数据
    $qmsg_opts = array('http'=>array('method'=>'POST','header'=>'Content-type: application/x-www-form-urlencoded','content'=>$qmsg_postdata));
    $qmsg_context = stream_context_create($qmsg_opts);
    // 发送对Qmsg酱的POST请求
    $qmsg_result = file_get_contents($qmsg_push_link, false, $qmsg_context);
    
// 以下内容为Srever酱推送
    // 将上述内容封装为，其text字段即为标题，desp字段即为正文
    $ser_postdata = http_build_query(array('text'=>$topic,'desp'=>$text));
    // 组合POST数据
    $ser_opts = array('http'=>array('method'=>'POST','header'=>'Content-type: application/x-www-form-urlencoded','content'=>$ser_postdata));
    $ser_context = stream_context_create($ser_opts);
    // 发送对Qmsg酱的POST请求
    $ser_result = file_get_contents('http://sc.ftqq.com/'.$serverkey.'.send', false, $ser_context);
}

// 获取文章第一张缩略图（本功能代码源于网络）
function mirai_catch_that_image($post_id) {
    $post = get_post($post_id);
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img*.+src=[\'"]([^\'"]+)[\'"].*>/iU', wp_unslash($post->post_content), $matches);
	if(empty($output)){ 
	    $first_img = WP_PLUGIN_URL."/mirai-push/img/no-image.jpg";
	}else {
	    $first_img = $matches [1][0];
	}
	return $first_img;
}

?>