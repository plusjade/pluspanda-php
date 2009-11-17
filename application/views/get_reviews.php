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
	<?php echo View::factory('reviews_data', array('reviews' => $reviews))?>
</div>


