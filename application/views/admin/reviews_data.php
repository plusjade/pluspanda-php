
<?php echo $pagination?>
<?php foreach($reviews as $review):?>
	<div class="review-wrapper">
		<img src="/static/admin/images/error.png" title="Flag this review"/>
		<div class="review-body"><?php echo $review->body?></div>	
		<div class="review-rating"><b><?php echo $review->rating?></b></div>
		<div style="clear:both;"></div>
		
		<div class="review-tag">re: <span><?php echo $review->tag->name?></span></div>
		
		<div class="review-name">
			<?php echo build::timeago($review->created)?>
			 by <span><?php echo $review->customer->display_name?></span>
		</div>
	</div>
<?php endforeach;?>
<?php echo $pagination?>

<script type="text/javascript">$('abbr.timeago').timeago();</script>

