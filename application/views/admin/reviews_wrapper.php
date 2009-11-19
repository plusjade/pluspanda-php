

<h2>Manage Reviews</h2>

<?php if(isset($response))echo $response?>

<div class="buttons">
<button id="update" class="positive" style="float:right">Update</button>
</div>


<div class="panda-reviews-list">	
	<?php echo View::factory('live/reviews_data', array('reviews' => $reviews, 'pagination'=>$pagination))?>
</div>


<script type="text/javascript">
	
</script>