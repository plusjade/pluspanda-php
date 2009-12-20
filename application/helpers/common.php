<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * common helpers for our system. 
 */
 
class common_Core {


/*
 * return the timeago html
 */	
	public static function timeago($date)
	{
		ob_start();
		?>
		<abbr class="timeago" title="<?php echo date("c", $date)?>"><?php echo date("M d y @ g:i a", $date)?></abbr>
		<?php
		return ob_get_clean();	
	}
	
/*
 * return the timeago html
 */	
	public static function rating_select_nice($selected=0)
	{
		$ratings = array(1,2,3,4,5);
		ob_start();
		?>
		<div id="panda-star-rating" class="_<?php echo $selected?>" rel="<?php echo $selected?>">
		<?php 
		foreach($ratings as $rating)
			echo "<div class=\"_$rating\"></div>";
		?>
		</div>
		<div class="panda-rating-text">Select a rating.</div>			
		<?php
		return ob_get_clean();	
	}
	

					
/*
 * centralize the timeago html
 */	
	public static function rating_select_dropdown($selected=NULL)
	{
		$ratings = array(
			5 => '5 Stars',
			4 => '4 Stars',
			3 => '3 Stars',
			2 => '2 Stars',
			1 => '1 Star'
		);
		ob_start();
		?>
		<select name="rating">
			<?php
			foreach($ratings as $val => $text)
				if($selected == $val)
					echo "<option value=\"$val\" SELECTED>$text</option>";
				else
					echo "<option value=\"$val\">$text</option>";
			?>
		</select>
		<?php
		return ob_get_clean();	
	}
	

	
/*
 * build the rating select filter
 */
	public static function range_select_list($active=NULL)
	{
		ob_start();
		?>
			<select name="range">
			<?php
			$ratings = array(
				'all'			=> 'All Time',
				'last7'		=> 'Last 7 days',
				'last14'	=> 'Last 14 days',
				'last30'	=> 'Last 30 days',
				'ytd'			=> 'YTD',
			);
			foreach($ratings as $val => $text)
				if($val == $active)
					echo "<option value='$val' SELECTED>$text</option>";
				else
					echo "<option value='$val'>$text</option>";
			?>
			</select>
		<?php
		return ob_get_clean();
	}
	

	

	
} // end build helper