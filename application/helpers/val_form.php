<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * server-side validation for public facing forms.
 */
 
class val_form_Core {


/*
 * expects fields to be a nested array.
		fields shoul be an array of classes having:
		id/name
		title
		type
		req
		meta
		info
		selected
 */
	public static function make_fields($fields, $values, $errors)
	{
		ob_start();
		foreach($fields as $field)
		{
			# if the name is not set we treat as meta-data.
			# and define a meta name.
			if(isset($field->name))
			{
				$name = $field->name;
				$clean_name = $field->name;
				$value = (isset($values[$field->name]))
					? $values[$field->name]
					: null;
					
			}
			else
			{
				$name = "info[$field->id]";
				$clean_name = "info_$field->id";
				$value = (isset($values['info'][$field->id]))
					? $values['info'][$field->id]
					: null;
			}
			
			$req = empty($field->req)
				? NULL
				: $field->req;
				
			# was there an error with this field?
			$field_error = (empty($errors[$name]))
				? ''
				: 'field_error';	
						
			# is this field required?
			$req_star = (empty($req))
				? ''
				: '<span class="req_star">*</span>';
			?>
			
			<fieldset class="<?php echo "panda-$clean_name $field_error"?>">
				<label><?php echo $field->title?> <?php echo $req_star?></label>
				
				
				<?php
				if(!empty($field->info))
					echo "<div class=\"info\">$field->info</div>";
				
				switch($field->type)
				{
					case 'textarea':
						echo '<textarea name="'.$name .'" rel="'.$req . '">' . $value.'</textarea>';
						break;	
					
					case 'input':
						echo "<input type='text' name='$name' value='$value' rel='$req'/>";		
						break;

					case 'upload':
						echo "<input type='file' name='$name' value='$value' rel='$req'/>";		
						break;
						
					case 'password':
						echo "<input type='password' name='$name' value='$value' rel='$req'/>";		
						break;						
					
					case 'select':
						echo "<select name=\"$name\">";
						foreach($field->meta as $meta_value => $text)
							if($value == $meta_value OR $meta_value == $field->selected)
								echo "<option value='$meta_value' selected='selected'>$text</option>";
							else
								echo "<option value='$meta_value'>$text</option>";
						echo '</select>';
						break;
				}
				
				if(isset($errors[$clean_name]))
					echo "<br/><span class='error_msg'>{$errors[$clean_name]}</span>";
			
			echo '</fieldset>';	
		}
		
		#echo ob_get_clean();die();
		return ob_get_clean();
	}
	
	
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