<div id="aid">0</div>
<script>
	var i=0;
	var it=setInterval(function(){
		i++;
		document.querySelector("#aid").innerHTML="已经执行"+i+"秒了";
	},1000);
</script>