

<?php echo t_build::sorters($active_tag, $active_sort)?>

<div class="panda-reviews-list">  
  <?php echo View::factory(
    'testimonials/display_data',
    array(
      'testimonials' => $testimonials,
      'pagination'=>$pagination
    )
  )?>
</div>



