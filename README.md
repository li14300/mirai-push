# mirai-pushi

![](https://www.imut.xyz/wp-content/uploads/2020/10/mirai-push-1024x597.jpg)

推送机器人嘛，我第一时间想到的，当然是酷Q，于是连忙百度，啊嘞嘞，跑路了，几个知名的机器人都跑路了，TXNMSL，没办法，只能找找有没有开源的项目或者可以方便使用的推送接口。

最终我盯上了这几个项目，①基于[mirai框架](https://github.com/mamoe/mirai)的机器人，②只有两个参数的推送机器人[Qmsg酱](https://qmsg.zendee.cn/)，③以前就一直在用的[server酱](http://sc.ftqq.com/)（微信推送）

经过我这个渣渣3天的努力，终于写出了一个极其不完善的插件来实现文章发布或更新后推送到QQ及微信。

[title]三种推送的优缺点：[/title]

1. mirai框架机器人 
  - 优点：机器人由自己掌控，自由度较高，拓展性强，可以实现图片，语音等同步发送
  - 缺点：机器人需要单独运行，稳定性不如其他两种
2. Qmsg酱 
  - 优点：参数较少，上手容易，稳定性强
  - 缺点：只能实现文字内容发送，需要内测资格才能进群，除了推送无其它功能
3. Server酱
  
  - 优点：参数较少，上手容易，稳定性强，正文支持MD语法
  - 缺点：仅支持微信一对一推送

[title]本插件优缺点[/title]

- 优点：聚合了三种机器人的所有功能，QQ单独推送，群推送，微信一对一推送
- 缺点：很多很多，前期非常不完善，也是希望以后能够逐步完善的，目前已知 
  1. 没有独立的后台页面进行设置，只能手动修改config.php
  2. 第一次写代码东拼西凑写出来的，可能（肯定）有一堆bug
  3. mirai机器人由于会话有超时，每次投稿时，都会进行一遍会话校验和验证
  4. 推送正文修改较为麻烦，看不懂代码可能不会改

[title]部分代码原理[/title]

现阶段[v0.3.0]本代码有5个文件组成，目录结构如下

```markup
mirai-push
├── includes // 两个主要的php
│     ├── mirai-main.php // 推送主函数
│     └── mirai-virfy.php // 用于mirai机器人认证的函数
├── img //图片存放文件夹
│     └── no-imsge.jpg // 文章无图片时会发送这张图片
├── mirai-push.php // 插件主文件用于挂载钩子
└── config.php // 需要手动修改的配置文件
```

其中config.php用于填写三种推送渠道的地址、key、推送目标等参数

```markup
// mirai酱推送地址 *单引号<br></br>$mirai_main_url = '<机器人http-api的推送地址>';<br></br>// mirai机器人qq *无需引号<br></br>$miraiid = 123456789;<br></br>// mirai认证密钥 *单引号<br></br>$miraikey = 'miraiexamplekey';<br></br>// 推送消息对象 私聊'/sendFriendMessage'，群聊'/sendGroupMessage'<br></br>$mirai_push_url = $mirai_main_url.'/sendFriendMessage';<br></br>// 需要推送的用户/群聊号码 *无需引号<br></br>$mirai_push_id = 789456123;<br></br>// Qmsg酱的推送地址（可以为群聊地址）*单引号<br></br>$qmsg_push_link = 'https://qmsg.zendee.cn:443/send/<qsmgkey>';<br></br>// 需要推送的QQ号（可以为群号）*单引号<br></br>$qmsg_push_id = '1430059860';<br></br>// Server酱推送key *单引号<br></br>$serverkey = '<serverexamplekey>';<br></br>// 正文长度控制<br></br>$mirai_text_length = 100;
```

而mirai-main.php中的mirai推送部分，如增加群指令如@全体成员

增加方法为将代码28行左右的以下片段

```markup

$mirai_postdata = json_encode(array('sessionKey'=>$mirai_push_session,'target'=>$mirai_push_id,'messageChain'=>array(0=>array('type'=>'Plain','text'=>$text),1=>array('type'=>'Image','url'=>$mirai_push_image))));
```

更改为

```markup

$mirai_postdata = json_encode(array('sessionKey'=>$mirai_push_session,'target'=>$mirai_push_id,'messageChain'=>array(0=>array('type'=>'Plain','text'=>$text),1=>array('type'=>'Image','url'=>$mirai_push_image),2=>array('type'=>'AtAll'))));
```

[title]未来展望[/title]

虽说是未来展望，但作为一名专业为德语，没有系统学习过编程的渣渣来说，可能还是有些困难的，能不能实现就看我能不能坚持下去了

- 增加后台管理页面，不用再去修改文件，能把配置传到数据库
- 增加基于“[mirai-api-http](https://github.com/project-mirai/mirai-api-http)”的mirai机器人控制台（这个项目本来就是优先考虑mirai机器人，其他的俩都是副产物）
- 实现文章评论推送功能

[title]更新记录[/title]

- 2020-10-8 
  - ver 0.3.0 把所有可修改参数全部整理到config.php，增加对congfig.php内容的解释 
      - 已知BUG，文章内图片路径有中文时，mirai会推送失败，预计下一版本修复
  - ver 0.2.2 增加Server酱推送
- 2020-10-7 
  - ver 0.2.1 mirai机器人定时认证 未能实现，改为每次文章发布时运行一次，第一个可以连续运行的版本
  - ver 0.2.0 增加mirai机器人的会话开始-认证模块“mirai-verify.php”
- 2020-10-6 
  - ver 0.1.2 抄代码增加后台面板 ，结局惨淡，完全没有成功，放弃
  - ver 0.1.1 增加config.php，把部分参数挪了进去
  - ver 0.1.0 拆分各部分代码和函数，完成了插件的主体结构
- 2020-10.5 
  - ver 0.0.2 根据mirai机器人的http-api，写了第二个推送函数，POST出去的是json
  - ver 0.0.1 立项，以Qmsg酱作为目标，完成了第一个推送函数，成功发出了第一条推送
