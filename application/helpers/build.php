<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Centralizes all the html modules needed to set up the interface. 
 * This way we can build html output in both standalone and widget mode.
 */
 
class build_Core {

/*
 * build the top tag select filter.
 */
	public static function tag_filter($tags, $active_tag)
	{
		ob_start();
		?>
		<form id="panda-select-tags" action="" method="GET">
			Review Categories: <select name="tag">
				<option value="all">All</option>
			<?php foreach($tags as $tag)
				if($tag->id == $active_tag)
				{
					$active_tag_name = $tag->name;
					echo "<option value='$tag->id' SELECTED>$tag->name</option>";
				}
				else
					echo "<option value='$tag->id'>$tag->name</option>";
			?>
			</select> <button type="submit">Show Reviews</button>
		</form>		
		
		<?php
		return ob_get_clean();
	}
	

/*
 * build the add review wrapper.
 */	
	public static function add_wrapper()
	{
		ob_start();
		?>
		<div class="panda-add-wrapper">
			<a href="#panda-add-review" id="add_review_toggle">+ Add New Review</a>
		</div>
		<?php
		return ob_get_clean();
	}
	
/*
 * build the reviews summary display.
 */
	public static function summary($ratings_dist)
	{
		$total_reviews = 0;
		$score_sum = 0;
		foreach($ratings_dist as $rating => $tally)
		{
			$total_reviews += $tally;
			$score_sum += $tally*$rating;
		}
		$average_score = number_format($score_sum/$total_reviews, 2);
		
		ob_start();
		?>
		<div class="panda-reviews-summary-title">Rating Summary</div>
		<div class="panda-reviews-summary">
			<div>
				<b><?php echo $average_score?></b> stars based on <span><?php echo $total_reviews?></span> reviews.
			</div>
			<p>
			<?php foreach($ratings_dist as $rating => $total):?>
				<?php echo $rating?> stars : (<?php echo $total?>)<br/>
			<?php endforeach;?>
			</p>
		</div>	
		<?php
		return ob_get_clean();
	}
	
	
/*
 * build the reviews sorting display.
 */
	public static function sorters($active_tag='all', $active_sort=null, $js=null)
	{
		$sort_types = array('newest', 'oldest', 'highest', 'lowest');
		$url = (isset($js)) ? "#sort=" : "/?tag=$active_tag&sort=";
		ob_start();
		?>
		<ul class="panda-reviews-sorters">
		<?php 
			foreach($sort_types as $type)
				if($active_sort == $type)
					echo '<li><a href="'.$url.$type.'" class="selected">'.ucfirst($type).'</a></li>';
				else
					echo '<li><a href="'.$url.$type.'">'.ucfirst($type).'</a></li>';
		?>
		</ul>
		<?php
		return ob_get_clean();
	}
	
	
} // end build helper