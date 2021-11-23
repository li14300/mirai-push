<?php
/*
mirai会话开始-认证-校验
*/
//  输入变量(配合前置代码使用，无需手动填写)
//  $mirai_auth_url = 'http://<seit>//auth';
//  $mirai_verify_url = 'http://<seit>//verify';
//  $miraiid = mirai的QQ号;
//  $miraikey = '认证key';

// 加载认证参数
    $load_mirai_config = WP_PLUGIN_DIR.'/mirai-push/';
	require ($load_mirai_config.'config.php');

// mirai会话开始-认证-获取session
// 组合POST数据
    $mirai_auth_data = json_encode(array('verifyKey'=>$miraikey));
    $mirai_auth_opts = array('http'=>array('method'=>'POST','header'=>'Content-type: application/json','content'=>$mirai_auth_data));
    $mirai_auth_context = stream_context_create($mirai_auth_opts);
// 发送对mirai酱的POST请求
    $mirai_auth_return = file_get_contents($mirai_auth_url, false, $mirai_auth_context);
// 截取session
    $mirai_push_session = substr($mirai_auth_return,21,8);
// mirai会话校验
// 组合POST数据
    $mirai_verify_data = json_encode(array('sessionKey'=>"$mirai_push_session",'qq'=>$miraiid));
    $mirai_verify_opts = array('http'=>array('method'=>'POST','header'=>'Content-type: application/json','content'=>$mirai_verify_data));
    $mirai_verify_context = stream_context_create($mirai_verify_opts);
// 发送对mirai酱的POST请求
    $mirai_verify_return = file_get_contents($mirai_verify_url, false, $mirai_verify_context);
