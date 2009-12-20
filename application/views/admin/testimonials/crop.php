
<div class="crop-wrapper">

	<div class="crop-image">
		<img src="<?php echo $img_src?>"/>
	</div>
	
	<div class="crop-preview">
		<div class="crop-preview-holder">
			<img src="<?php echo $img_src?>"/>
		</div>
		
		<button type="submit" id="<?php echo $thmb_src?>" rel="<?php echo $action_url?>" alt="">Save Thumbnail</button>
		<div class="crop-msg" style="font-weight:bold"></div>
	</div>
		
</div>
	
<script type="text/javascript">
	$('.crop-image img').Jcrop({
		onChange: showPreview,
		onSelect: showPreview,
		aspectRatio: 1
	});
	
	function showPreview(coords){
		if (parseInt(coords.w) > 0){
			var rx = 100 / coords.w;
			var ry = 100 / coords.h;

			$('.crop-preview img').css({
				width: Math.round(rx * 500) + 'px',
				height: Math.round(ry * 370) + 'px',
				marginLeft: '-' + Math.round(rx * coords.x) + 'px',
				marginTop: '-' + Math.round(ry * coords.y) + 'px'
			});
		}
		$('.crop-wrapper button').attr('alt', coords.w +'|'+ coords.h +'|'+ coords.y +'|'+ coords.x);
	};
	
		// testimonial crop submit
	$('.crop-wrapper button').click(function(e){
		var url = $(this).attr('rel');
		var params = $(this).attr('alt');
		if(!params){alert('please select an area');return false;}
		
		$('.crop-msg').html('Saving...');
		$.post(url,{params:params}, function(data){
			$('.crop-msg').html(data);
			newImg = new Image(); 
			newImg.src = e.target.id;
			$('.t-details .image').html('<img src="'+ newImg.src +'">');
			$.facebox.close();
		});
		return false;	
	});
	
</script>
