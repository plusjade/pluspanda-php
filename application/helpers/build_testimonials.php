<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Centralizes all the html modules needed to set up the interface. 
 * This way we can build html output in both standalone and widget mode.
 */
 
class build_testimonials_Core {


/*
 * build the html that each testimonial gets displayed in.
 */
	public static function testimonial_html($testimonial=NULL, $site_id=0)
	{
		# this is for the javascript callback =)
		if(empty($testimonial)):
		ob_start();
		?>
<div class="testimonial-wrapper">
	<div class="testimonial-details">
		<div class="image">'+ this.image +'</div>		
		<div class="testimonial-name">
			<span>'+ this.name +'</span>
		</div>
		<div class="testimonial-position">
			<span>'+ this.position +'</span>
		</div>	
		<div class="testimonial-company">
			<span>'+ this.company +'</span>
		</div>
		<div class="testimonial-location">
			<span>'+ this.location +'</span>
		</div>			
		<div class="testimonial-url">
			<a href="#">'+ this.url +'</a>
		</div>
	</div>
	<div class="testimonial-content">
		<div class="testimonial-rating _' +this.rating+ '" title="Rating: '+this.rating+ ' stars">&#160;</div>
		<div class="testimonial-body">' +this.body+ '</div>
		<div class="testimonial-tag"><span>' +this.tag_name+ '</span></div>
		<div class="testimonial-date"><abbr class="timeago">' + $.timeago(date) +'</abbr></div>
	</div>
</div>		
		<?php 
			return ob_get_clean();
			endif;
			
			$image = (empty($testimonial->image))
				? ''
				: "<img src=\"/data/$site_id/tstml/img/$testimonial->image\"/>";
		?>
<div class="testimonial-wrapper">

	<div class="testimonial-details">
		<div class="image"><?php echo $image?></div>
		
		<div class="testimonial-name">
			<span><?php echo $testimonial->customer->name?></span>
		</div>
		<div class="testimonial-position">
			<span><?php echo $testimonial->customer->position?></span>
			, <span><?php echo $testimonial->customer->company?></span>
		</div>
		<div class="testimonial-location">
			<span><?php echo $testimonial->customer->location?></span>
		</div>			
		<div class="link">
			<a href="#">http://mycoolstore.com</a>
		</div>
	</div>
	
	<div class="testimonial-content">
		<div class="testimonial-rating _<?php echo $testimonial->rating?>" title="Rating: <?php echo $testimonial->rating?> stars">&#160;</div>
		<div class="testimonial-body"><?php echo $testimonial->body?></div>
		<div class="testimonial-tag"><span><?php echo $testimonial->tag->name?></span></div>
		<div class="testimonial-date"><?php echo build::timeago($testimonial->created)?></div>
	</div>
</div>
		<?php
	}
	
	
/*
 * build the top tag select filter as a list.
 */
	public static function tag_list($tags, $active_tag=NULL, $extra=NULL)
	{
		ob_start();
		?>
		<div id="panda-select-tags">
			<span>Show testimonials from:</span>
			<ul>
				<li><a href="#all" class="active">Everyone</a></li>
			<?php
			if(!empty($extra))
				foreach($extra as $val => $text)
					echo "<li><a href=\"#$val\">$text</a></li>";
			foreach($tags as $tag)
				if($tag->id == $active_tag)
					echo "<li><a href='#$tag->id' class=\"active\">$tag->name</a></li>";
				else
					echo "<li><a href='#$tag->id'>$tag->name</a></li>";
			?>
			</ul>
		</div>
		<?php
		return ob_get_clean();
	}
	
/*
 * build the top tag select filter as a select list.
 */
	public static function tag_select_list($tags, $active_tag=NULL, $extra=NULL)
	{
		ob_start();
		?>
			<select name="tag">
			<?php
			if(!empty($extra))
				foreach($extra as $val => $text)
					echo "<option value=\"$val\">$text</option>";
			foreach($tags as $tag)
				if($tag->id == $active_tag)
					echo "<option value='$tag->id' SELECTED>$tag->name</option>";
				else
					echo "<option value='$tag->id'>$tag->name</option>";
			?>
			</select>
		<?php
		return ob_get_clean();
	}
	

/*
 * build the top tag select filter.
 */
	public static function tag_filter($tags, $active_tag, $page_name='')
	{
		ob_start();
		?>
		<form id="panda-select-tags" action="/<?php echo $page_name?>" method="GET">
			Show testimonials from: 
			<?php echo self::tag_select_list($tags, $active_tag, array('all'=> 'Everyone'))?>

			<input type="image" src="http://<?php echo ROOTDOMAIN?>/static/admin/images/magnify.png" alt="Submit button" style="position:relative;top:7px">

			<!--<button type="submit"></button>-->
		</form>
		<?php
		return ob_get_clean();
	}
	
	
/*
 * build the rating select filter
 */
	public static function rating_select_list($active=NULL)
	{
		ob_start();
		?>
			<select name="rating">
			<?php
			$ratings = array(
				'all' => 'All Star',
				'1' => 'One Star',
				'2' => 'Two Star',
				'3' => 'Three Star',
				'4' => 'Four Star',
				'5' => 'Five Star',
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
				'all' => 'All Time',
				'last7' => 'Last 7 days',
				'last14' => 'Last 14 days',
				'last30' => 'Last 30 days',
				'ytd' => 'YTD',
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
	
	
/*
 * build the add testimonial wrapper.
 */	
	public static function add_wrapper()
	{
		ob_start();
		?>
		<div class="panda-add-wrapper">
			<a href="#panda-add-testimonial" id="add_testimonial_toggle">+ Add New testimonial</a>
		</div>
		<?php
		return ob_get_clean();
	}
	

	
/*
 * build the testimonials sorting display.
 */
	public static function sorters($active_tag='all', $active_sort=NULL, $widget=NULL)
	{
		$sort_types = array('featured', 'newest', 'oldest');
		$url = (isset($widget))
			? "#sort="
			: "/?tag=$active_tag&sort=";
		ob_start();
		?>
		<ul class="panda-testimonials-sorters">
			<li>Sort testimonials by:</li>
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


/*
 * centralize the embed codes
 */
	public static function embed_code($apikey, $type=NULL, $jquery=TRUE)
	{
		$jquery = ($jquery) ? '': '&jquery=false';
		ob_start();
		?>
		<div id="plusPandaYes"></div>
		<script type="text/javascript" src="http://<?php echo ROOTDOMAIN?>?apikey=<?php echo $apikey?>&fetch=testimonials<?php echo $jquery?>" charset="utf-8"></script>
		<?php
		if('fake' == $type)
			return str_replace(
				array('<','>',"\n","\r","\t"),
				array('&lt;','&gt;'),
				ob_get_clean());
		
		return ob_get_clean();	
	}
	
	
} // end build helper