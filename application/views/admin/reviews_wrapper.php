

<h2>Manage Reviews</h2>

<?php if(isset($response))echo $response?>

<form action="" method="GET" style="text-align:center">
	Categories: <?php echo $categories?>
	<br/>
	Show <?php echo $ratings?> ratings.
	
	Timespan: <?php echo $range?>
	<br/><button type="submit">Submit</button>
</form>


<div class="admin-reviews-list">	
	<?php echo $reviews?>
</div>


<script type="text/javascript">
	
</script>