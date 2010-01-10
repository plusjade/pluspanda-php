

<div class="message-wrapper">
  <?php echo $this->site->tstml_msg?>
</div>

<?php if(isset($errors)) echo val_form::show_error_box($errors);?>
  
<div id="public-top">Thank You For Your Time!</div>
<form id="public-add" action="" method="POST">
<?php
$fields = array();
if($settings->require_key)
  $fields['key'] = array('Access Key','input','text_req','','');
$fields['name'] = array('Full Name','input','text_req','','');
if($settings->email)
  $fields['email'] = array('Email','input','email_req','','');
if($settings->meta)
  $fields['meta'] = array($settings->meta,'input','text_req','','');

if(!isset($values)) $values = array();
if(!isset($errors) OR !is_array($errors)) $errors = array();
?>
<?php echo val_form::generate_fields($fields, $values, $errors);?>


<fieldset class="buttons">
  <button type="submit" class="positive">Build Your Testimonial!</button>
</fieldset>
</form>



