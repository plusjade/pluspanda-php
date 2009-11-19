<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * output nice alerts.
 */
 
class alerts_Core {

/*
 * output a formatted html alert based on response msg
 * (attention, success, fail, information)
 */
	public static function display($response)
	{
		ob_start();
		foreach($response as $code => $msg):
		?>
		<div class="<?php echo $code?>">
			<?php echo $msg?>
		</div>
		<?php
		endforeach;
		return ob_get_clean();
	}
	

	
} // end alerts helper