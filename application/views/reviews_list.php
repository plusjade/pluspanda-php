

<?php foreach($reviews as $review):?>
	<div class="review-rating">Rating: <b><?php echo $review->rating?></b></div>
	<div class="review-tag">On: <?php echo $review->tag->name?></div>
	<div class="review-body"><?php echo $review->body?></div>
	<div class="review-name">
		<abbr class="timeago" title="<?php echo date("c", $review->created)?>"><?php echo date("M d y @ g:i a", $review->created)?></abbr>
		 by <?php echo $review->user->display_name?>
	</div>
<?php endforeach;?>