


<?php if(isset($errors)) echo val_form::show_error_box($errors);?>
<?php if(isset($js) AND 'yes' == $js):?>
	<form action="http://<?php echo "$this->site_name." . ROOTDOMAIN?>" target="panda-iframe"  method="POST" id="panda-add-review">
<?php else:?>
	<form action="#panda-add-review" method="POST" id="panda-add-review">
<?php endif;?>
	<h3>Add Review For: <span></span></h3>
	<input type="hidden" name="tag" value="<?php echo $active_tag?>">
	<?php
	$ratings = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5');
	
	$fields = array(
		'rating'			=> array('Rating','select','text_req', $ratings),
		'body'				=> array('Review','textarea','text_req', ''),
		'display_name'=> array('Name','input','text_req', ''),
		'email'				=> array('Email','input','email_req', ''),
	);
	if(!isset($values)) $values = array();
	if(!isset($errors)) $errors = array();
	echo val_form::generate_fields($fields, $values, $errors);
	?>
	<fieldset class="panda-submit">
		<button type="submit">Submit Review</button>
	</fieldset>
</form>