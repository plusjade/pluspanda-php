

<h2 class="aligncenter"><?php echo $site->name;?> Customer Reviews.</h2>

<form id="panda-toggle-tags" action="" method="GET">
	Review Categories: <select name="tag">
		<option value="all">All</option>
	<?php foreach($site->tags as $tag)
	{
		if($tag->id == $active_tag)
			echo "<option value='$tag->id' SELECTED>$tag->name</option>";
		else
			echo "<option value='$tag->id'>$tag->name</option>";
	}
	?>
	</select> <button>Show Reviews</button>
</form>

<?php echo $add_review?>
<?php echo $get_reviews?>


<script type="text/javascript">

	$("#add_review_toggle").click(function() {
		$(".add-review").slideToggle("fast");
	});
	$(".add-review").hide();
	
	$('.review_form').ajaxForm({		 
		beforeSubmit: function(fields, form){
		//	if(! $("input, textarea", form[0]).jade_validate()) return false;
			$(fields).each(function() {
				$('#supa_injector em#qwz_' + this.name).replaceWith(this.value);
			});
			$('.add-review').html('<div class="ajax_loading">Loading...</div>');
		return false;
		},
		success: function(data) {
			$('.add-review').replaceWith(data);
			// todo: this must be done only on success.
			$('#supa_injector').show();
			$('#add_review_toggle').remove();
		}
	});
		
		
	$('abbr.timeago').timeago();
		 
</script>