<?php	
	$tally = array();
	ob_start();
	foreach($summary as $rating)
	{
		$tally[] = $rating->rating*$rating->total;
		echo "$rating->rating stars : ($rating->total)<br/>";
	}
	$total		= $reviews->count();
	$average	= number_format(array_sum($tally)/$total, 2);
	$data			= ob_get_clean();
	$sort_types = array('newest', 'oldest', 'highest', 'lowest');
?>	

<div class="panda-reviews-summary-title">Rating Summary</div>
<div class="panda-reviews-summary">
	<b><?php echo $average?></b> average based on <?php echo $total?> reviews.
	<p><?php echo $data ?></p>
</div>

<ul class="panda-reviews-sorters">
<?php 
	foreach($sort_types as $type)
		if($active_sort == $type)
			echo '<li><a href="/?tag='.$active_tag.'&sort='.$type.'" class="selected">'.ucfirst($type).'</a></li>';
		else
			echo '<li><a href="/?tag='.$active_tag.'&sort='.$type.'">'.ucfirst($type).'</a></li>';
?>
</ul>


<div class="panda-reviews-list">
<?php foreach($reviews as $review):?>
	<div class="review-rating">Rating: <b><?php echo $review->rating?></b></div>
	<div class="review-tag">On: <?php echo $review->tag->name?></div>
	<div class="review-body"><?php echo $review->body?></div>
	<div class="review-name">
		<abbr class="timeago" title="<?php echo date("c", $review->created)?>"><?php echo date("M d y @ g:i a", $review->created)?></abbr>
		 by <?php echo $review->user->display_name?>
	</div>
<?php endforeach;?>
</div>


