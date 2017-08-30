<?php
return array(
    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址127.0.0.1
    'DB_NAME'               =>  'wehchat_menu',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '123456',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'yy_',    // 数据库表前缀
    'DB_DEBUG'  			=>  true, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'		=>  false,  //关闭字段缓存
    
    //'SHOW_PAGE_TRACE' => true ,
    'TMPL_ACTION_SUCCESS' 	=> 'public:success', //成功时的跳转页面
    'TMPL_ACTION_ERROR'		=> 'public:error', //错误时的跳转页面
    
    'URL_MODEL'				=> 1,
    'URL_HTML_SUFFIX'		=> '',
    'TMPL_PARSE_STRING'		=> array(
        //'__PUBLIC__'	=> '/personal/test/Public'
    )
);