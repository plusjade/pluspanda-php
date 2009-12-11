
<?php echo $pagination?>
<?php foreach($reviews as $review):?>
	<table id="review-<?php echo $review->id?>" class="review-wrapper">
		<tr class="flag-status">
			<td colspan="2">status:</td>
		</tr>
		<tr>
			<td class="review-rating"><b><?php echo $review->rating?></b></td>
			<td class="review-body"><?php echo $review->body?></td>	
		</tr>
		<tr>
			<td class="review-info" colspan="2">
				<div class="review-tag">
					re: <span><?php echo $review->tag->name?></span>
				</div>
				<?php echo build::timeago($review->created)?>
				by <span><?php echo $review->customer->display_name?></span>

			</td>
		</tr>		
	</table>
<?php endforeach;?>
<?php echo $pagination?>

<script type="text/javascript">$('abbr.timeago').timeago();</script>

