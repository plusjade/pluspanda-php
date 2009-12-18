<?php
	$testimonial->body = (empty($testimonial->body))
		? "Hello this is your testimonial!\nOur survey questions below will guide you easily along.\nPlease answer them, but also feel free to write your own freeform testimonial right in this box.\n\nClear this text when you are ready!\nHave fun!"
		: $testimonial->body;
		
	$image_src = (empty($testimonial->image))
		? '/static/images/sample-thumb.jpg'
		: "$image_url/$testimonial->image";
?>

<div class="client-add-wrapper">
	<!--
	Hello, thank you for your help.
	I appreciate your time very much. This should only take about 5 minutes.
	Feel free to be honest and specific- thanks!
	<br/><br/>
	-->
	
	<?php if(isset($errors)) echo val_form::show_error_box($errors);?>

	<form action="" enctype="multipart/form-data" method="POST" id="panda-add-review">

			
	<fieldset class="panda-submit">
		<button type="submit">Save Changes</button>
		Hello, <?php echo $testimonial->customer->name?>, Thanks for your help!
	</fieldset>		

	<fieldset class="panda-image">
		Upload Headshot or Logo <input type="file" name="image" />
		- <a href="<?php echo "$url&a=crop&image=$testimonial->image"?>" rel="facebox">Edit Image</a>
	</fieldset>	
	
	<div class="t-view">
		<div class="t-details">
			<div class="image">
				<img src="<?php echo $image_src?>"/>
			</div>
			
			<span class="label">Full Name</span>
			<span class="t-name">
				<input name="name" value="<?php echo $testimonial->customer->name?>" />
			</span>
			
			<span class="label">Position at Company</span>
			<span class="t-position">
				<input name="position" value="<?php echo $testimonial->customer->position?>" />
			</span>
			
			<span class="label">Company</span>
			<span class="t-company">
				<input name="company" value="<?php echo $testimonial->customer->company?>" />
			</span>
			
			<span class="label">Location</span>
			<span class="t-location">
				<input name="location" value="<?php echo $testimonial->customer->location?>" />
			</span>
			
			<span class="label">Website</span>
			<span class="t-url">
				http://<input name="url" value="<?php echo $testimonial->customer->url?>" />
			</span>
		</div>
		
		<div class="t-content">
			<div class="t-rating _<?php echo $testimonial->rating?>" title="Rating: <?php echo $testimonial->rating?> stars">&#160;</div>
			
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
	<div class="t-questions">	
<?php foreach($questions as $question):?>
		<fieldset>
			<label><?php echo $question->title?></label>
			<div class="info"><?php echo $question->info?></div>
			<textarea name="info[<?php echo $question->id?>]"><?php if(isset($info["$question->id"])) echo $info["$question->id"]?></textarea>
		</fieldset>
<?php endforeach;?>
	</div>
	
</form>

</div>





