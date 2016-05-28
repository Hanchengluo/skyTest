<?php
class indexControl extends skymvc
{
	function __construct()
	{
		parent::__construct();
	}
	
	

	public function onDefault()
	{
		$key=TESTAPP."autoid_article";
  	 
		 
		 
		if(ISWAP){
			$this->smarty->assign("welcome","这是手机版哦，欢迎使用skymvc，让我们共同努力！");
		}else{
			$this->smarty->assign("welcome","欢迎使用<a href=\"http://www.skymvc.com\" target=\"_blank\">skymvc</a>，让我们共同努力！");
		}
		$this->hook("run","这是传入hook的数据");
		m("article")->getRow("select id,title from sky_article where user_catid=99");
		//m("article")->getRow("select id,title from sky_article where id=99");
		$this->smarty->assign("who",M("index")->test());
		$this->smarty->display("index.html");
	}
	
	public function getId($table){
		$key=TESTAPP."autoid_$table";
		if(!$v=cache()->get($key)){
			$v=1;
			cache()->set($key,$v,3600*24);
			
		}else{
			echo "存在".$v."  ";
			$v++;
			cache()->set($key,$v,3600*24);
		}
		return $v;
	 
	}
}

?>