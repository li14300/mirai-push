<?php
/*
插件的配置页面，请按提示填写
*/
// 以下参数为主参数
// mirai酱推送地址 *单引号
$mirai_main_url = '<机器人http-api的推送地址>';
// mirai机器人qq *无需引号
$miraiid = 123456789;
// mirai认证密钥 *单引号
$miraikey = 'miraiexamplekey';
// 推送消息对象 私聊'/sendFriendMessage'，群聊'/sendGroupMessage'
$mirai_push_url = $mirai_main_url.'/sendFriendMessage';
// 需要推送的用户/群聊号码 *无需引号
$mirai_push_id = 789456123;
// Qmsg酱的推送地址（可以为群聊地址）*单引号
$qmsg_push_link = 'https://qmsg.zendee.cn:443/send/<qsmgkey>';
// 需要推送的QQ号（可以为群号）*单引号
$qmsg_push_id = '123456789';
// Server酱推送key *单引号
$serverkey = '<serverexamplekey>';
// 正文长度控制
$mirai_text_length = 100;

// 以下参数无需手动填写
$mirai_auth_url = $mirai_main_url.'//auth';
$mirai_verify_url = $mirai_main_url.'//verify';

?>