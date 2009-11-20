

<?php
$page_name = (isset($page_name)) ? $page_name : '';
?>
<?php if(isset($errors)) echo val_form::show_error_box($errors);?>

<?php if(isset($widget)):?>
	<form action="http://<?php echo "$this->site_name." . ROOTDOMAIN .'/api'?>" target="panda-iframe"  method="POST" id="panda-add-review">
<?php else:?>
	<form action="/<?php echo $page_name?><?php if(isset($_GET['tag'])) echo '?tag='.$_GET['tag']?>#panda-add-review" method="POST" id="panda-add-review">
<?php endif;?>
		<h3>Add Review</h3>
	<?php
	$ratings = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5');
	$fields	 = array(
		'tag'					=> array('Review For','select','text_req', $tags, $active_tag),
		'rating'			=> array('Rating','select','text_req', $ratings,''),
		'body'				=> array('Review','textarea','text_req', '',''),
		'display_name'=> array('Name','input','text_req', '',''),
		'email'				=> array('Email','input','email_req', '',''),
	);
	if(!isset($values)) $values = array();
	if(!isset($errors)) $errors = array();
	echo val_form::generate_fields($fields, $values, $errors);
	?>
	<fieldset class="panda-submit">
		<button type="submit">Submit Review</button>
	</fieldset>
</form>