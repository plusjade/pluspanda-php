

<?php echo build::summary($ratings_dist)?>

<?php echo build::sorters($active_tag, $active_sort)?>

<div class="panda-reviews-list">
	<?php echo View::factory('reviews_data', array('reviews' => $reviews))?>
</div>

