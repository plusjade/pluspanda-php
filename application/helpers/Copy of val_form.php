<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * server-side validation for public facing forms.
 */
 
class val_form_Core {

/*
 * expects fields to be a nested array.
 */
	public static function generate_fields($fields, $values, $errors)
	{
		ob_start();
		foreach($fields as $name => $data)
		{
			list($label, $type, $rel, $options, $selected) = $data;
			
			# was there an error with this field?
			$jade_error = (empty($errors[$name]))
				? ''
				: 'jade_error';	
		
			# is this field required?
			$required_star = (empty($rel))
				? ''
				: '<span class="jade_required_star">*</span>';
			
			# does a previous field value exist?
			if(empty($values[$name]))
				$values[$name] = '';
			?>
			
			<fieldset class="<?php echo "panda-$name $jade_error"?>">
				<label><?php echo $label?> <?php echo $required_star?></label>
				<?php
				switch($type)
				{
					case 'textarea':
						echo "<textarea name='$name' rel='$rel'>$values[$name]</textarea>";
						break;	
					case 'input':
						echo "<input type='text' name='$name' value='$values[$name]' rel='$rel'/>";		
						break;
					case 'password':
						echo "<input type='password' name='$name' value='$values[$name]' rel='$rel'/>";		
						break;						
					case 'select':
						echo "<select name='$name'>";
						foreach($options as $value => $text)
							if($value == $values[$name] OR $value == $selected)
								echo "<option value='$value' selected='selected'>$text</option>";
							else
								echo "<option value='$value'>$text</option>";
						echo '</select>';
						break;
				}
				
				if(isset($errors[$name]))
					echo "<br/><span class='error_msg'>$errors[$name]</span>";
			
			echo '</fieldset>';	
		}
		
		return ob_get_clean();
	}
/*
	expects and errors array produced from the validation library.
*/
	public static function show_error_box($errors)
	{
		ob_start();
		if(is_array($errors))
		{
			?>
			<div class="jade_form_status_box box_negative">
				<div><span>Form Not Sent!</span></div>
				<div class="error_count">
					Only <?php echo count($errors)?> more fields to go...
				</div>
			</div>
			<?php
		}
		else
		{
			?>
			<div class="jade_form_status_box box_negative">
				<div><span><?php echo $errors?></span></div>
			</div>
			<?php
		}
		
		return ob_get_clean();
	}

/*
	expects and errors array produced from the validation library.
*/
	public static function show_invalid_box($message)
	{
		ob_start();
		?>
		<div class="jade_form_status_box box_negative">
			<div><span><?php echo $message?></span></div>
		</div>
		<?php
		return ob_get_clean();
	}
	
	
} // end val_form helper