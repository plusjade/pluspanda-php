

<h2 class="aligncenter"><?php echo $site->name;?> Customer Reviews.</h2>

<div id="plusPandaYes">

	<form id="panda-select-tags" action="" method="GET">
		Review Categories: <select name="tag">
			<option value="all">All</option>
		<?php foreach($site->tags as $tag)
			if($tag->id == $active_tag)
				echo "<option value='$tag->id' SELECTED>$tag->name</option>";
			else
				echo "<option value='$tag->id'>$tag->name</option>";
		?>
		</select> <button type="submit">Show Reviews</button>
	</form>

	<div class="panda-tag-scope">
		<?php echo $add_review?>
		<?php echo $get_reviews?>
	</div>
</div>

<script type="text/javascript">

	$("#add_review_toggle").click(function() {
		$("#panda-add-review").slideToggle("fast");
	});
	$("#panda-add-review").hide();
	
	//ajaxify the tag selector.
	$('#panda-select-tags').submit(function(){
		var tag = $('#panda-select-tags select option:selected').val();
		$('.panda-tag-scope').html('Loading...');
		$.get('/',{tag:tag},function(data){
			$('.panda-tag-scope').html(data);
		});
		return false;
		
	});
	
	
// attach event triggers.
	$('body').click($.delegate({
	 //ajaxify the sorters 
		'.panda-reviews-sorters a' : function(e){
			$('.panda-reviews-sorters a').removeClass('selected');
			$(e.target).addClass('selected');
			$('.panda-reviews-list').html('Loading...=D'); 	
			$.get(e.target.href, {output:'reviews'}, function(data){
				$('.panda-reviews-list').html(data); 	
			});
			return false;
		}
	}));
	
	
	
	$('.review_form').ajaxForm({		 
		beforeSubmit: function(fields, form){
		//	if(! $("input, textarea", form[0]).jade_validate()) return false;
			$(fields).each(function() {
				$('#supa_injector em#qwz_' + this.name).replaceWith(this.value);
			});
			$('#panda-add-review').html('<div class="ajax_loading">Loading...</div>');
		return false;
		},
		success: function(data) {
			$('#panda-add-review').replaceWith(data);
			// todo: this must be done only on success.
			$('#supa_injector').show();
			$('#add_review_toggle').remove();
		}
	});
		
		
	$('abbr.timeago').timeago();
		 
</script>