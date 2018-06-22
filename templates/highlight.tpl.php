<style type="text/css">
#highlight {
  z-index: -1;
  background-color: rgba(255,255,255,0.1) !important;
  width: 84px;
  height: 84px;
  border-radius: 50%;
  display: block;
  position: fixed;
  box-shadow: 0 0 10px 3px rgba(255,255,255,0.255);
}
</style>
<div id="highlight"></div>
<script type="text/javascript">
var x, y;
document.addEventListener('mousemove', function(e) {
  x = e.clientX - 42;
  y = e.clientY - 42;
  console.log(x+' : '+y);
  document.getElementById('highlight').style.left = x+'px';
  document.getElementById('highlight').style.top = y+'px';
});
</script>