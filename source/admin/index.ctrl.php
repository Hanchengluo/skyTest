<?php
class indexControl extends skymvc
{
	function __construct()
	{
		parent::__construct();
	}
	
	

	public function onDefault()
	{
		if(ISWAP){
			$this->smarty->assign("welcome","这是手机版哦，欢迎使用skymvc，让我们共同努力！");
		}else{
			$this->smarty->assign("welcome","欢迎使用<a href=\"http://www.skymvc.com\" target=\"_blank\">skymvc</a>，让我们共同努力！");
		}
		$this->hook("run","这是传入hook的数据");
		$this->smarty->assign("who",M("index")->test());
		$this->smarty->display("index.html");
	}
}

?>