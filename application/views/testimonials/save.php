<?php
	$testimonial->body = (empty($testimonial->body))
		? "Hello this is your testimonial!\nOur survey questions below will guide you easily along.\nPlease answer them, but also feel free to write your own freeform testimonial right in this box.\n\nClear this text when you are ready!\nHave fun!"
		: $testimonial->body;
		
	$image_src = (empty($testimonial->image))
		? '/static/images/sample-thumb.jpg'
		: "$image_url/$testimonial->image";
?>


<div class="top-message">
	Hello, <?php echo $testimonial->patron->name?>, Thanks for your help!
</div>

<div class="client-add-wrapper">
<form action="" enctype="multipart/form-data" method="POST" id="panda-add-review">		
	<button class="submit-button" type="submit">Save Changes</button>		

	<fieldset class="panda-image">
		Upload new headshot or logo <input type="file" name="image" />
	</fieldset>	
	
	<div class="t-view">
		<div class="t-details">
			<a href="<?php echo "$url&a=crop&image=$testimonial->image"?>" rel="facebox">Re-Crop Image</a>
	
			<div class="image">
				<img src="<?php echo $image_src?>"/>
			</div>
			
			<span class="label">Full Name</span>
			<span class="t-name">
				<input name="name" value="<?php echo $testimonial->patron->name?>" />
			</span>
			
			<span class="label">Position at Company</span>
			<span class="t-position">
				<input name="position" value="<?php echo $testimonial->patron->position?>" />
			</span>
			
			<span class="label">Company</span>
			<span class="t-company">
				<input name="company" value="<?php echo $testimonial->patron->company?>" />
			</span>
			
			<span class="label">Location</span>
			<span class="t-location">
				<input name="location" value="<?php echo $testimonial->patron->location?>" />
			</span>
			
			<span class="label">Website</span>
			<span class="t-url">
				http://<input name="url" value="<?php echo $testimonial->patron->url?>" />
			</span>
		</div>
		
		<div class="t-content">
    
			<div class="t-rating-wrapper">
				<span style="display:none">
					<input type="hidden" name="rating" value="<?php echo $testimonial->rating?>">
					<?php echo common::rating_select_nice($testimonial->rating)?>
				</span>				
				<div class="rating-fallback">
					Select a rating <?php echo common::rating_select_dropdown($testimonial->rating);?>
				</div>
			</div>
			
			<div class="t-body">
				<textarea name="body"><?php echo $testimonial->body?></textarea>
			</div>
			
			<div class="t-date"><?php echo build::timeago($testimonial->created)?></div>
		
			<div class="t-tag">
				<?php 
					echo build_testimonials::tag_select_list(
						$tags,
						$testimonial->tag->id,
						array('0'=>'(Select Category)')
					);
				?>
			</div>
			
		</div>
	</div>		
			
	<h1>Survey Questions</h1>
	<div class="slider-wrap">
		<div id="t-questions" class="t-questions">
			<div class="panelContainer">
	<?php $x = 0?>	
	<?php foreach($questions as $question):?>
				<div class="panel" title="<?php echo ++$x?>">
					<div class="wrapper">
						<label><?php echo $question->title?></label>
						<div class="info"><?php echo $question->info?></div>
						<textarea name="info[<?php echo $question->id?>]"><?php if(isset($info["$question->id"])) echo $info["$question->id"]?></textarea>
					</div>
				</div>
	<?php endforeach;?>
			</div>
		</div>
	</div>
</form>
</div>
