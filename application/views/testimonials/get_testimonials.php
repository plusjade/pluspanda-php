

<?php echo build_testimonials::sorters($active_tag, $active_sort)?>

<div class="panda-reviews-list">	
	<?php echo View::factory(
		'testimonials/display_data',
		array(
			'reviews' => $reviews,
			'pagination'=>$pagination
		)
	)?>
</div>



