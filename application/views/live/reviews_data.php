

<?php 
	foreach($reviews as $review):
		echo build::review_html($review);
	
	echo $pagination;
?>
<script type="text/javascript">$('abbr.timeago').timeago();</script>

