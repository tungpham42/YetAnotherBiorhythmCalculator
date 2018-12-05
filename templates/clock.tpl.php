<div id="clock" class="top-left-corner top-right-corner bottom-left-corner bottom-right-corner">
<style type="text/css">
.clock_local{border-radius:50%;background:transparent url('<?php echo $cdn_url; ?>/images/skins_clock/clock_normal.svg') no-repeat center;background-size:100%;height:264px;width:264px;margin:4px;float:left;position:relative;}.clock_local:after{background:red;border-radius:50%;content:"";position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:4%;height:4%;z-index:10}.minutes-container,.hours-container,.seconds-container{position:absolute;top:0;right:0;bottom:0;left:0}.hours{background:#ffffff;height:20%;left:48.75%;position:absolute;top:30%;transform-origin:50% 100%;width:2.5%}.minutes{background:#ffffff;height:40%;left:49%;position:absolute;top:10%;transform-origin:50% 100%;width:2%}.seconds{background:#dc0000;height:45%;left:49.5%;position:absolute;top:14%;transform-origin:50% 80%;width:1%;z-index:8}@keyframes rotate{100%{transform:rotateZ(360deg)}}.hours-container{animation:rotate 43200s infinite linear}.minutes-container{animation:rotate 3600s infinite linear}.seconds-container{animation:rotate 60s infinite linear}body.birthday .clock_local{background-image: url('<?php echo $cdn_url; ?>/images/skins_clock/clock_birthday.svg');}body.birthday .hours,body.birthday .minutes{background: #e35604;}body.birthday .seconds{background: #059a37;}
</style>
	<article class="clock_local">
		<div class="hours-container">
			<div class="hours"></div>
		</div>
		<div class="minutes-container">
			<div class="minutes"></div>
		</div>
		<div class="seconds-container">
			<div class="seconds"></div>
		</div>
	</article>
<script type="text/javascript">
var date=new Date;var seconds=date.getSeconds();var minutes=date.getMinutes();var hours=date.getHours();var hands=[{hand:'hours',angle:(hours*30)+(minutes/2)},{hand:'minutes',angle:(minutes*6)+(seconds/10)},{hand:'seconds',angle:(seconds*6)}];for(var j=0;j<hands.length;j++){var elements=document.querySelectorAll('.'+hands[j].hand);for(var k=0;k<elements.length;k++){elements[k].style.webkitTransform='rotateZ('+hands[j].angle+'deg)';elements[k].style.transform='rotateZ('+hands[j].angle+'deg)';if(hands[j].hand==='minutes'){elements[k].parentNode.setAttribute('data-second-angle',hands[j+1].angle)}}}
</script>
</div>