

<?php
	$body_msg = "Hello this is your testimonial!\nOur survey questions below will guide you easily along.\nPlease answer them, but also feel free to write your own freeform testimonial right in this box.\n\nClear this text when you are ready!\nHave fun!";
?>
<div class="client-add-wrapper">
	<!--
	Hello, thank you for your help.
	I appreciate your time very much. This should only take about 5 minutes.
	Feel free to be honest and specific- thanks!
	<br/><br/>
	-->
	
	<?php if(isset($errors)) echo val_form::show_error_box($errors);?>

	<?php if(isset($widget)):?>
		<form action="http://<?php echo ROOTDOMAIN?>" enctype="multipart/form-data" target="panda-iframe"  method="POST" id="panda-add-review">
	<?php else:?>
		<form action="" enctype="multipart/form-data" method="POST" id="panda-add-review">
	<?php endif;?>
		
			
	<fieldset class="panda-submit">
		<button type="submit">Save Changes</button>
		Token <input type="text" name="email" />
	</fieldset>		

	<fieldset class="panda-image">
		Upload Headshot or Logo <input type="file" name="image" />
	</fieldset>	
	
	<div class="t-view">
		<div class="t-details">
			<div class="image"><img src="/static/images/sample-thumb.jpg"/></div>
			
			<span class="label">Full Name</span>
			<span class="t-name">
				<input name="name" value="" />
			</span>
			
			<span class="label">Position at Company</span>
			<span class="t-position">
				<input name="position" value="" />
			</span>
			
			<span class="label">Company</span>
			<span class="t-company">
				<input name="company" value="" />
			</span>
			
			<span class="label">Location</span>
			<span class="t-location">
				<input name="location" value="" />
			</span>
			
			<span class="label">Website</span>
			<span class="t-url">
				http://<input name="url" value="" />
			</span>
		</div>
		
		<div class="t-content">
			<div class="t-rating _5" title="Rating: 5 stars">&#160;</div>
			
			<div class="t-body">
				<textarea name="body"><?php echo $body_msg?></textarea>
			</div>
			
			<div class="t-date">datae</div>
		
			<div class="t-tag">
				<?php 
					echo build_testimonials::tag_select_list(
						$tags,
						null,
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
	<?php echo val_form::make_fields($questions, $values, $errors)?>
	</div>
	
	</form>

</div>





