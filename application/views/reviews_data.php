

<?php foreach($reviews as $review):?>
	<div class="review-rating">Rating: <b><?php echo $review->rating?></b></div>
	<div class="review-tag">re: <span><?php echo $review->tag->name?></span></div>
	<div class="review-body"><?php echo $review->body?></div>
	<div class="review-name">
		<abbr class="timeago" title="<?php echo date("c", $review->created)?>"><?php echo date("M d y @ g:i a", $review->created)?></abbr>
		 by <span><?php echo $review->user->display_name?></span>
	</div>
<?php endforeach;?>

<script type="text/javascript">$('abbr.timeago').timeago();</script>

