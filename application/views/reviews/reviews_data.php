<?php 
  foreach($reviews as $review):
    echo r_build::item_html($review);
  
  echo $pagination;
?>
<script type="text/javascript">$('abbr.timeago').timeago();</script>

