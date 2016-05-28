<?php
 
define("MYSQL_CHARSET","utf8");
define("TABLE_PRE","sky_");
define("TESTAPP","skyshop");
//每条sql插入多少行
define("ONE_INSERT_ROWS",300);
//每个页执行多少次
define("ONE_MAXROW",300);
//每次同时执行几个表
define("MAXTHREADS",10);
 
 
 require ROOT_PATH."config/test/".TESTAPP.".php";
/*
$dbconfig["master"]=array(
	"host"=>"localhost","user"=>"root","pwd"=>"123","database"=>"xyo2o"
);
*/
/**其他分表库**/
/*
$dbconfig["user"]=array(
	"host"=>"localhost","user"=>"root","pwd"=>"123","database"=>"skyshop"
);

$dbconfig["article"]=array(
	"host"=>"localhost","user"=>"root","pwd"=>"123","database"=>"skycms"
);
*/ 

/*分库配置*/
/* 
$VMDBS=array(
	"article"=>"article",
	"forum"=>"article"
);
*/ 
 
/*缓存配置*/
$cacheconfig=array(
	"file"=>true,
	"php"=>true,
	"mysql"=>false,
	"memcache"=>false
);
/*用户自定义函数文件*/
$user_extends=array(
	"ex_fun.php",
);
/*Session配置 1为自定义 0为系统默认*/
define("SESSION_USER",0);
define("REWRITE_ON",0); 
define("REWRITE_TYPE","pathinfo");
define("TESTMODEL",1);//开发测试模式
define("SQL_SLOW_LOG",1);//记录慢查询
define("HOOK_AUTO",false);//开放全局hook
 
?>