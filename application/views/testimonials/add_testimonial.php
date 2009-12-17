<?php
		$testimonial->body = ($fresh)
			? "Hello this is your testimonial!\nOur survey questions below will guide you easily along.\nPlease answer them, but also feel free to write your own freeform testimonial right in this box.\n\nClear this text when you are ready!\nHave fun!"
			: $testimonial->body;
		$welcome_msg = ($fresh)
			? "Hello, {$testimonial->customer->name}; Create a new testimonial!"
			: "Welcome back, {$testimonial->customer->name} !";
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
		<?php echo $welcome_msg?>
		<input type="text" name="token" value="<?php echo $testimonial->token?>" READONLY>
		
	</fieldset>		

	<fieldset class="panda-image">
		Upload Headshot or Logo <input type="file" name="image" />
	</fieldset>	
	
	<div class="t-view">
		<div class="t-details">
			<div class="image"><img src="/static/images/sample-thumb.jpg"/></div>
			
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
	<?php
	if(!isset($values)) $values = array();
	if(!isset($errors)) $errors = array();
	?>
	<div class="t-questions">
	
<?php foreach($questions as $question):?>
	<fieldset>
		<label><?php echo $question->title?></label>
		<div class="info"><?php echo $question->info?></div>
		<textarea><?php echo $info["$question->id"]?></textarea>
	</fieldset>
<?php endforeach;?>
	<?php #echo val_form::make_fields($questions, $values, $errors)?>
	
	
	</div>
	
	</form>

</div>





