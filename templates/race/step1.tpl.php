<h2>Cá độ đua hộp</h2>
<h3>Hướng dẫn</h3>
<h4>Trò này rất <strong>đơn giản</strong>, bạn chỉ cần nhập số lượng hộp, điền tên/mã hộp nếu thích, hệ thống sẽ cho các hộp đua với nhau với tốc độ ngẫu nhiên, lúc tăng lúc giảm y như thật. Để thêm phần hấp dẫn, bạn nên chơi chung với bạn bè, người thân, đặt một khoản cược nho nhỏ, hộp của người nào hạng cao hơn thì người đó thắng. Luật chơi có thể thay đổi tùy ý bạn, vd bạn cũng có thể cá độ ngược, hộp nào về chậm nhất thì thắng, vv</h4>
<h3>Bắt đầu chơi</h3>
<h4>Nhập số lượng hộp đua (2 - 16 hộp)</h4>
<form id="form" action="/duahop/2/" method="post">
	<div class="m-input-prepend m-input-append">
		<span class="add-on">Số lượng hộp đua:</span>
		<input id="count" type="number" min="2" max="16" step="1" name="count" size="30" maxlength="128" class="required m-wrap">
		<a id="submit" class="m-btn green"><span>Bước 2</span></a>
	</div>
</form>
<div class="error_msg"></div>
<script>
$('#submit').click(function(){
	var count = $.trim($('#count').val());
	var error_msg = '';
	if (count.length == 0) {
		error_msg += '- Chưa nhập số hộp.</br>';
	}
	if (!$.isNumeric(count)) {
		error_msg += '- Nhập không đúng định dạng số.</br>';
	}
	if (count < 2 || count > 16) {
		error_msg += '- Số hộp đua nhập vào phải từ 2 đến 16.</br>';
	}
	if (error_msg != '') {
		$('.error_msg').html(error_msg).dialog({
			resizable: false,
			modal: false
		});
		$('.ui-dialog').draggable({
			containment: '#main'
		});
		return false;
	} else {
		$('#form').submit();
	}
});
</script>