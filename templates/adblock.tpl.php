<style>
#adblock_alert {
display: none;
padding: 20px 10px;
background: #D30000;
text-align: center;
font-weight: bold;
color: #fff;
border-radius: 5px;
position: fixed;
top: 0;
width: 100%;
z-index: 11;
}
</style>

<div id="adblock_alert" class="html">
<?php
echo translate_span('adblock');
?>
</div>
<script>
// Function called if AdBlock is not detected
function adBlockNotDetected() {
	$('#adblock_alert').hide();
}
// Function called if AdBlock is detected
function adBlockDetected() {
	$('#adblock_alert').show();
}

// Recommended audit because AdBlock lock the file 'blockadblock.js' 
// If the file is not called, the variable does not exist 'blockAdBlock'
// This means that AdBlock is present
if(typeof blockAdBlock === 'undefined') {
	adBlockDetected();
} else {
	blockAdBlock.onDetected(adBlockDetected);
	blockAdBlock.onNotDetected(adBlockNotDetected);
	// and|or
	blockAdBlock.on(true, adBlockDetected);
	blockAdBlock.on(false, adBlockNotDetected);
	// and|or
	blockAdBlock.on(true, adBlockDetected).onNotDetected(adBlockNotDetected);
}
</script>