
<h2 class="aligncenter"><?php echo $site->name;?> Customer Reviews.</h2>

<div id="plusPandaYes">
	<?php echo build::add_wrapper()?>
		
	<?php echo build::tag_filter($site->tags, $active_tag, $this->page_name)?>
	

	<div class="panda-tag-scope">
		<?php echo $get_reviews?>
		<?php echo $add_review?>
	</div>
	
</div>
