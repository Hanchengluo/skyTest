<?php
define("STATIC_SITE","http://".$_SERVER['HTTP_HOST']."/");
define("IMAGE_SITE","http://".$_SERVER['HTTP_HOST']."/");
define("APPINDEX","/index.php");
define("APPADMIN","/admin.php");
define("APPMODULE","/module.php");
//检测敏感字符串
define("AUTO_CHECK_BAD_WORD",false);
define("OB_GZIP",false);
//模板
define("SKINS","index");
//模板
define("WAPSKINS","wap");
define("WAP_DOMAIN","");
define("DOMAIN",$_SERVER['HTTP_HOST']);
define("COOKIE_DOMAIN",$_SERVER['HTTP_HOST']);

?>