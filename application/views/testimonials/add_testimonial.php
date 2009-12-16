
Hello, thank you for your help.
I appreciate your time very much. This should only take about 5 minutes.
Feel free to be honest and specific- thanks!
<br/><br/>

<?php if(isset($errors)) echo val_form::show_error_box($errors);?>

<?php if(isset($widget)):?>
	<form action="http://<?php echo ROOTDOMAIN?>" enctype="multipart/form-data" target="panda-iframe"  method="POST" id="panda-add-review">
<?php else:?>
	<form action="" enctype="multipart/form-data" method="POST" id="panda-add-review">
<?php endif;?>
	<?php
	$fields = array();
	
	$field = new stdClass();
	$field->name = 'name';
	$field->title = 'Full Name';
	$field->type = 'input';
	$field->req = 'text_req';
	$field->info = 'You full name please.';
	$fields[] = $field;

	$field = new stdClass();
	$field->name = 'email';
	$field->title = 'Email';
	$field->type = 'input';
	$field->req = 'email_req';
	$field->info = 'Your email is never published. It is only used should we need to contact you.';
	$fields[] = $field;

	$field = new stdClass();
	$field->name = 'company';
	$field->title = 'Company';
	$field->type = 'input';
	$field->info = 'You company name will appear alongside your review.';
	$fields[] = $field;

	$field = new stdClass();
	$field->name = 'position';
	$field->title = 'Position at Company';
	$field->type = 'input';
	$field->info = 'Your position or role at the company you are representing. Will appear alongside your testimonial';
	$fields[] = $field;

	$field = new stdClass();
	$field->name = 'url';
	$field->title = 'Website';
	$field->type = 'input';
	$field->info = 'Your company or professional website. This will appear alongside your testimonial and is a great way to market your website.';
	$fields[] = $field;

	$field = new stdClass();
	$field->name = 'location';
	$field->title = 'Location';
	$field->type = 'input';
	$field->info = 'The location of your primary area of business. Helps our customers relate better and additionally markets your company!';
	$fields[] = $field;		
	
	$field = new stdClass();
	$field->name = 'image';
	$field->title = 'Headshot or Logo';
	$field->type = 'upload';
	$field->info = 'Your photo will be resized to 125px by 125px and will appear alongside your testimonial. This further markets your company and also makes our site look nice!';
	$fields[] = $field;	

	
	if(!isset($values)) $values = array();
	if(!isset($errors)) $errors = array();
	#echo kohana::debug($values);die();
	echo val_form::make_fields($questions, $values, $errors);
	echo val_form::make_fields($fields, $values, $errors);

	?>
	<fieldset class="panda-rating">
		<label></label>
		<div class="info">Please rate your overall satisfaction with working with us.</div>
		
		<select name="rating">
			<option value="5">5 stars - Excellent!</option>
			<option value="4">4 stars - </option>
			<option value="3">3 stars - </option>
			<option value="2">2 stars - </option>
			<option value="1">1 stars - </option>
		</select>
		
		<span class="star-rating-wrapper" style="display:none">
			<div id="panda-star-rating">
				<div class="one-1"></div>
				<div class="two-2"></div>
				<div class="three-3"></div>
				<div class="four-4"></div>
				<div class="five-5"></div>
			</div>
			<div class="panda-rating-text">Select a rating.</div>
			<input type="hidden" name="rating" value="0" disabled="disabled"/>
		</span>
	
	</fieldset>
	
	<fieldset class="panda-submit">
		<button type="submit">Submit Testimonial</button>
	</fieldset>
</form>