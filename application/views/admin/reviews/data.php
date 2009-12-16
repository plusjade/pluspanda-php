
<?php echo $pagination?>
<?php foreach($reviews as $review):?>
	<table id="review-<?php echo $review->id?>" class="review-wrapper">
		<tr>
			<td class="review-rating"><b><?php echo $review->rating?></b></td>
			<td class="review-body"><?php echo $review->body?></td>	
			<td class="options"><img src="/static/admin/images/error.png" rel="<?php echo $review->id?>" title="Flag this review"/></td>
		</tr>
		<tr>
			<td class="review-info" colspan="3">
				<div class="review-tag">
					re: <span><?php echo $review->category->name?></span>
				</div>
				<?php echo build::timeago($review->created)?>
				by <span><?php echo $review->customer->name?></span>

			</td>
		</tr>
	</table>
<?php endforeach;?>
<?php echo $pagination?>

<script type="text/javascript">$('abbr.timeago').timeago();</script>

