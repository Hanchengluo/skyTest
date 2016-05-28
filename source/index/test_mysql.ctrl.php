<?php
/******
*Author 雷日锦
*本代码在skymvc中使用
*skymvc mysql数据库测试
*******/
class test_mysqlControl extends skymvc{
	public $maxrow;//每次最多生成多少千行
	public $maxThreads;
	public $TEST_DEL_ROWS=10000; 
	public $TABLE_ONLY=false;
	public $TABLE_ROW_NUM;
	public $TABLE_NODO;
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		require ROOT_PATH."config/test/".TESTAPP.".php";
		
		 $this->maxrow=ONE_MAXROW;
		 $this->maxThreads=MAXTHREADS;
		 $this->TABLE_ROW_NUM=$TABLE_ROW_NUM;
		 $this->TABLE_ROW_DEFAULT=$TABLE_ROW_DEFAULT;
		 $this->TEST_DEL_ROWS=$TEST_DEL_ROWS;
		 $this->TABLE_ONLY=$TABLE_ONLY;
		 
		 if(empty($TABLE_NODO)){
			 $this->TABLE_NODO=array("skymvcnodothetable");
		 }else{
			 $this->TABLE_NODO=$TABLE_NODO;
		 }
	}
	
	public function onDefault(){
		 
	}
	
	
	
	public function onReset(){
		/**清空缓存**/
		delfile(ROOT_PATH."temp/filecache");
		/**自增id***/
		$this->onIncrement();
	}
	
	public function getNum($table){
		//设置表所需要的记录数
		$cf=$this->TABLE_ROW_NUM;
		if(!isset($cf[$table])){
			return $this->TABLE_ROW_DEFAULT;
		}else{
			return $cf[$table];
		}
	}
	
	public function onAutoDelete(){
		set_time_limit(0);
		ob_implicit_flush(true);
		$res=M("article")->query("show tables");
		$data=M("article")->fetch_array(PDO::FETCH_NUM);
		$this->loadClass("spider");
		if($data){
			$uk=0;
			foreach($data as $k=>$t){
				$table=str_replace(TABLE_PRE,"",$t[0]);
				if(in_array($table,$this->TABLE_NODO)) continue;
				
				$urls[$uk][]="http://".$_SERVER['HTTP_HOST']."/index.php?m=test_mysql&a=delete&table=".$table;
				
				if($k%$this->maxThreads==($this->maxThreads-1)){
					$uk++;
				}
				
			}
			echo "删除开始<br>";
			echo '<div id="aid">0</div>
<script>
	var i=0;
	var it=setInterval(function(){
		i++;
		document.querySelector("#aid").innerHTML="已经执行"+i+"秒了";
	},1000);
</script>';
				foreach($urls as $k=>$us){
					echo "第".$k."部分<br>";
					flush();
						@ob_flush();
					$this->spider->start($us,function($data){
					
						echo $data['url']." <br>".$data['content']."<br>";
						flush();
						@ob_flush();
					},600);
				}
		}
		echo "本次删结束<br>";
			flush();
			@ob_flush();
		 	 
		echo "<script>
	setTimeout(function(){
		window.location.reload();
	},1000);
</script>";
	}
	
	public function onDelete(){
		$table=get('table','h');
		M($table)->query("delete from ".table($table)." where 1=1 limit 50000");
		echo "delete $table success";
	}
	/*更新自增id*/
	public function onIncrement(){
		$res=M("article")->query("show tables");
		$data=M("article")->fetch_array(PDO::FETCH_NUM);
		if($data){
			foreach($data as $k=>$t){
				$table=str_replace(TABLE_PRE,"",$t[0]);
				if(in_array($table,$this->TABLE_NODO)) continue;
				M($table)->query("ALTER TABLE `sky_".$table."` AUTO_INCREMENT=1;");
			}
		}
		echo "update increment";
	}
	
	public function onAutoInsert(){
		set_time_limit(0);
		ob_implicit_flush(true);
		 
		$urls=array();
		if($this->TABLE_ONLY){
			$data=$this->TABLE_ROW_NUM;
			$uk=0;
			foreach($data as $table=>$v){
				 $urls[$uk][]="http://".$_SERVER['HTTP_HOST']."/index.php?m=test_mysql&a=insert&table=".$table;
				
			}
		}else{
			$res=M("article")->query("show tables");
			$data=M("article")->fetch_array(PDO::FETCH_NUM);
			
		
			if($data){
				$uk=0;
				foreach($data as $k=>$t){
				 	$table=str_replace(TABLE_PRE,"",$t[0]);
					if(in_array($table,$this->TABLE_NODO)) continue;
					$urls[$uk][]="http://".$_SERVER['HTTP_HOST']."/index.php?m=test_mysql&a=insert&table=".$table;
					if($k%$this->maxThreads==($this->maxThreads-1)){
						$uk++;
					}
				}
			}
		}
		if($data){
			$this->loadClass("spider");
			echo "开始<br>";
			echo '<div id="aid">0</div>
<script>
	var i=0;
	var it=setInterval(function(){
		i++;
		document.querySelector("#aid").innerHTML="已经执行"+i+"秒了";
	},1000);
</script>';
			 
			foreach($urls as $k=>$us){
				echo "第".$k."部分<br>";
				flush();
			@ob_flush();
				$this->spider->start($us,function($data){
					
					echo $data['url']." <br>".$data['content']."<br>";
					flush();
					@ob_flush();
				},600);
			}
			echo "结束<br>";
			flush();
			@ob_flush();
		}
		 // exit;
		echo "<script>
	setTimeout(function(){
		window.location.reload();
	},1000);
</script>";
	}
	public function onInsert($table=''){
		$inauto=true;
		if(!$table){  
			set_time_limit(0);
			$inauto=false;
		}
		
		$table=$table?$table:get('table','h');
		if(!$table){
			$table="article";
		}
		
		$fields=$this->getFIelds($table);
		
		//为什么只执行到266 百思不得其解
		$jnum=$this->getNum($table);
	 	for($j=0;$j<$this->maxrow;$j++){
			$rscount=M($table)->selectOne(array(
				"fields"=>" count(1)"
			));
			if($rscount>=$jnum){
				echo $table."测试数据已经够了<br>";
				 break;
			}
			 
			if(!inauto){
				echo "正在插入第".$j."千条<br>";
				flush();
				@ob_flush();
				ob_clean();
			}
			$data=array();
			
			 
			for($i=0;$i<ONE_INSERT_ROWS;$i++){
				$d=$this->dbPost($table);
				if($d){
					$data[]=$d;
				}
			}
			 
			$sql=" insert into ".table($table)."(".implode(",",$fields).") values ";
			foreach($data as $k=>$v){
				if($k>0){
					$sql.=",";
				}
				$sql.="("._implode($v).")";
			}
			$sql.=";";
			
			M($table)->query($sql);
			unset($data);
			unset($sql);
		}
		
		echo "success"; 
	}
	public function getFIelds($table){
		$fields=M($table)->getFields();
		foreach($fields as $k=>$v){
			//if($k==0) continue;
			$data[]=$v['Field'];
		}
		return $data;
	}
	public function dbPost($table,$msg=''){
		if(isset($_SESSION["field_".$table])){
			$fields=$_SESSION["field_".$table];
		}else{
			$fields=M($table)->getFields();
			$_SESSION["field_".$table]=$fields;
		}
		
		$msg=$msg?$msg:"skymvc是".date("Ymdhis")."最贴心的".date("Ymdhis")."php开发框架，快来使用吧！";
		$data=array();
		$gid=$this->getId($table);
		$err=0;
		foreach($fields as $k=>$v){
			//if($k==0) continue;
			if(preg_match("/tinyint/i",$v['Type'])){
				$data[$v['Field']]=rand(0,3);
			}elseif(preg_match("/smallint/i",$v['Type'])){
				$data[$v['Field']]=rand(0,65000);
			}elseif(preg_match("/int/i",$v['Type'])){
				if($v['Field']=='dateline'){
					$data[$v['Field']]=time();
				}else{
					$data[$v['Field']]=$gid;
				}
			}elseif(preg_match("/decimal/i",$v['Type'])){
				$data[$v['Field']]=rand(1,100);
			}elseif(preg_match("/datetime/i",$v['Type']) ){
				$data[$v['Field']]=date("Y-m-d H:i:s");
			}elseif($v['Field']=='bstatus'){
				$data[$v['Field']]=1;
			}else{
				$data[$v['Field']]=$msg;
			}
			if($k==0){
				if(preg_match("/tinyint/i",$v['Type'])){
					if($gid>120){
						$err=1;
					}
				}
				
				if(preg_match("/smallint/i",$v['Type'])){
					if($gid>65000){
						$err=1;
					}
				}
				$data[$v['Field']]=$gid;
			}
			
		}
		if(!$err){
			return $data;
		}
	}
	
	public function getId($table){
		$key=TESTAPP."autoid_$table";
		if(!$v=cache()->get($key)){
			$v=1;
			cache()->set($key,$v,3600*24*1000);
		}else{
			$v++;
			cache()->set($key,$v,3600*24*1000);
		}
		return $v;
	 
	}
	
	
	
}

?>