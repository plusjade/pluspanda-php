

<?php 
	foreach($testimonials as $testimonial)
		echo build_testimonials::testimonial_html($testimonial);

	echo $pagination;
?>
<script type="text/javascript">$('abbr.timeago').timeago();</script>

