


<a href="#" id="add_review_toggle">+ Add a new Review</a>

<?php if(isset($errors)) echo val_form::show_error_box($errors);?>

<form action="" method="post" id="panda-add-review" class="review_form">
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


<div class="review_item" id="supa_injector" style="display:none">
	<div class="review_rating">
		<b>Rating</b> <em id="qwz_rating"></em>/5
	</div>
	<div class="review_body">
		<em id="qwz_body"></em>
	</div>
	<div class="review_name">
		- <i><em id="qwz_name"></em></i>
	</div>
</div>
	
