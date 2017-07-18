<link rel="stylesheet" href="<?php echo $cdn_url; ?>/min/f=css/race.css&amp;4" />
<h2>Cá xem hộp nào nhanh nhất</h2>
<h3 id="leader">Hộp dẫn đầu:</h3>
<div id="game">
<?php
render_game();
?>
</div>
<script>
$(document).keypress(function(e){
	switch (e.which) {
		case 13:
			$('#start').trigger('click');
			break;
		case 32:
			$('#start').trigger('click');
			break;
		case 114:
			$('#restart').trigger('click');
			break;
	}
});
</script>