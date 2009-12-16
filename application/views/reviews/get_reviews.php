

<?php echo build::summary($ratings_dist)?>
<?php echo build::sorters($active_tag, $active_sort, $this->page_name)?>

<div class="panda-reviews-list">	
	<?php echo View::factory('live/reviews_data', array('reviews' => $reviews, 'pagination'=>$pagination))?>
</div>



