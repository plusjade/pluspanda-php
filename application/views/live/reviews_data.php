

<?php foreach($reviews as $review):?>
<div class="review-wrapper">
	<div class="review-name">
		Review by <span><?php echo $review->customer->display_name?></span>
	</div>
	<div class="review-rating _<?php echo $review->rating?>" title="Rating: <?php echo $review->rating?> stars">&#160;</div>
	<div class="review-body"><?php echo $review->body?></div>
	<div class="review-tag"><span><?php echo $review->tag->name?></span></div>
	<div class="review-date"><?php echo build::timeago($review->created)?></div>
</div>	
<?php endforeach;?>
<?php echo $pagination?>

<script type="text/javascript">$('abbr.timeago').timeago();</script>

