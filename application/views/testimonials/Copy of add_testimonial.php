
Hi, Jade! Our Form only takes 3 minutes. Thank you for your help.
We appreciate your honesty and be as specific as you like - thanks!


<?php if(isset($errors)) echo val_form::show_error_box($errors);?>

<?php if(isset($widget)):?>
	<form action="http://<?php echo ROOTDOMAIN?>" target="panda-iframe"  method="POST" id="panda-add-review">
<?php else:?>
	<form action="#panda-add-review" method="POST" id="panda-add-review">
<?php endif;?>
	<input type="hidden" name="rating" value="0" />
	<?php
	$ratings = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5');
	$fields	 = array(
		'info[0]'	=> array('1. What Problem Did Pluspanda Help You Solve?','textarea','', '',''),
		'info[1]'	=> array('2. How Did Pluspanda Help You Solve This Problem?','textarea','', '',''),
		'info[2]'	=> array('3. Was there any special or unique way Pluspanda benefited you?','textarea','', '',''),
		'info[3]'	=> array('4. Would You Recommend Pluspanda To Others?','textarea','', '',''),
		'info[4]'	=> array('5. Any Other Feedback You\'d like To Convey?','textarea','', '',''),
		'tag'		=> array('Category You Identify With Most','select','text_req', $tags, $active_tag),
		
		'name'	=> array('Full Name','input','text_req', '',''),
		'email'	=> array('Email','input','email_req', '',''),
	
		'company'=> array('Company','input','', '',''),
		'position'	=> array('Position at Company','input','', '',''),
		'url'					=> array('Website','input','', '',''),
	);
	if(!isset($values)) $values = array();
	if(!isset($errors)) $errors = array();
	echo val_form::generate_fields($fields, $values, $errors);
	?>
	<br/><br/>
	<div id="panda-star-rating">
		<div class="one-1"></div>
		<div class="two-2"></div>
		<div class="three-3"></div>
		<div class="four-4"></div>
		<div class="five-5"></div>
	</div>
	<div class="panda-rating-text">Select a rating.</div>
	
	<br/><br/>
	<fieldset class="panda-submit">
		<button type="submit">Submit Testimonial</button>
	</fieldset>
</form>