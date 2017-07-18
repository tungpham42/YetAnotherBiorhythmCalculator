<a class="button" href="/member/register/">Register new member</a>
<div class="clear"></div>
<input id="member_search" type="text" name="member_search" size="60" maxlength="128" />
<div id="admin_member">
<?php
echo list_members();
?>
</div>
<script>
$("#member_search").on({
	input: function(){
		$("#admin_member").load("/triggers/admin_member.php",{page:1,keyword:$("#member_search").val()});
	},
	change: function(){
		$("#admin_member").load("/triggers/admin_member.php",{page:1,keyword:$("#member_search").val()});
	}
});
</script>