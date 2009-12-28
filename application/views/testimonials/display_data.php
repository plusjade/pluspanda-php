

<?php 
  foreach($testimonials as $testimonial)
    echo t_build::item_html($testimonial);

  echo $pagination;
?>
<script type="text/javascript">$('abbr.timeago').timeago();</script>

