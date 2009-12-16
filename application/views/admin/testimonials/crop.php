
<?php $random = text::random('alnum',6)?>
<div class="crop-wrapper">

	<div class="crop-image">
	<img src="<?php echo "$image_url/full_$filename?r=$random"?>"/>
	</div>
	
	<div class="crop-preview">
		<div class="crop-preview-holder">
			<img src="<?php echo "$image_url/full_$filename?r=$random"?>"/>
		</div>
		
		<button type="submit" id="<?php echo "$image_url/$filename?r=$random"?>" rel="/admin/testimonials/crop?id=<?php echo $id?>" alt="">Save Thumbnail</button>
		
	</div>
		
</div>
	
<script type="text/javascript">
	$('.crop-image img').Jcrop({
		onChange: showPreview,
		onSelect: showPreview,
		aspectRatio: 1
	});
</script>
</div>