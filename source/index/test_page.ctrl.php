<?php
/******
*Author 雷日锦
*本代码在skymvc中使用 http://www.skymvc.com
*页面测试 自动爬行所有页面
*******/
class test_pageControl extends skymvc{
	
	public $para;
	public $action;
	public $id;
	public $per_page;
	public $maxThreads;
	public $token;
	public $site;
	public $TEST_URLS;
	public $TABLE_NODO;
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		require ROOT_PATH."config/test/".TESTAPP.".php";
		$this->para=$TEST_PARA;
		$this->action=$TEST_ACTION;
		$this->id=123;
		$this->per_page=1;
		$this->maxThreads=MAXTHREADS;
		$this->token=$TESTTOKEN;
		$this->site=$TESTSITE;
		$this->TEST_URLS=$TEST_URLS;
		if(empty($TABLE_NODO)){
			 $this->TABLE_NODO=array("skymvcnodothetable");
		 }else{
			 $this->TABLE_NODO=$TABLE_NODO;
		 }
	}
	
	public function onDefault(){
		
	}
	
	public function getTables(){
		$res=M("article")->query("show tables");
		$data=M("article")->fetch_array(PDO::FETCH_NUM);
		foreach($data as $k=>$t){
			$tables[]=str_replace(TABLE_PRE,"",$t[0]);
		}
		return $tables;
	}
	
	public function onSetAction($a=array()){
		 
		if(!empty($a)){
			$this->action=array_merge($this->action,$a);
		}		
		 

	}
	public function onSetPara($a=array()){
		 
		if(!empty($a)){
			$this->para=array_merge($this->para,$a);
		}		
	 
	}
	
	public function getUrls(){
		$tables=$this->getTables();
		$para="";
		foreach($this->para as $q){
			$para.="&$q=".$this->id;
		}
		$para.="&token=".$this->token."&per_page=".$this->per_page;
		if(!empty($this->TEST_URLS)){ 
			foreach($this->TEST_URLS as $v){
				$urls[]=$this->site.$v.$para;
			}
		}
		foreach($tables as $m){
			$uk=0;
			foreach($this->action as $k=>$a){
				$urls[]=$this->site."m=$m&a=$a".$para;
				if($k%$this->maxThreads==($this->maxThreads-1)){
					$uk++;
				}
			}
		}
		return $urls;
	}
	 
	public function onStart(){
		set_time_limit(0);
		ob_implicit_flush(true);
		$urls=$this->getUrls();
		$uk=0;
		foreach($urls as $k=>$url){
			$purls[$uk][]=$url;
			if($k%$this->maxThreads==($this->maxThreads-1)){
				$uk++;
			}
		}
		echo "开始<br>";
		foreach($purls as $k=>$urls){
			echo "第".$k."部分<br>";
			flush();
			@ob_flush();
			$this->loadClass("spider");
			$st=microtime(true);
			$this->spider->start($urls,function($data){
				echo $data['url']." <br>";
				flush();
				@ob_flush();
			});
			echo "本次花费时间：".(microtime(true)-$st)."<br>";
		}
		echo "测试结束";
	}
	
}

?>