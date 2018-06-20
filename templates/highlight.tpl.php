<style type="text/css">
#highlight {
  z-index: 4200;
  background-color: rgba(255,255,255,0.1) !important;
  width: 42px;
  height: 42px;
  border-radius: 50%;
  display: block;
  position: fixed;
}
</style>
<div id="highlight"></div>
<script type="text/javascript">
var x, y;
$('main').mousemove(function(e) {
  x = e.clientX - this.offsetLeft;
  y = e.clientY - this.offsetTop;
  console.log(x+' : '+y);
  $('#highlight').css({'left':x+'px','right':y+'px'});
});
</script>