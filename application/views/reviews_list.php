

<?php foreach($reviews as $review):?>
	<div class="review-rating">Rating: <b><?php echo $review->rating?></b></div>
	<div class="review-tag">On: <?php echo $review->tag->name?></div>
	<div class="review-body"><?php echo $review->body?></div>
	<div class="review-name"><?php echo $review->user->display_name?></div>
<?php endforeach;?>