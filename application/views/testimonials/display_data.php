

<?php 
	foreach($reviews as $review)
		echo build_testimonials::testimonial_html($review);

	echo $pagination;
?>
<script type="text/javascript">$('abbr.timeago').timeago();</script>

