 
<style type="text/css">
.dashboard-page div{padding:5px;margin:5px;}
</style>
 
<h2>Hello there, <span class="theme_highlight"><?php echo $owner->username?></span>!</h2>
<div class="dashboard-page">

<?php if(0 < $tags->count()):?>
	<div class="attention">
		&#160; &#160; &#160; &#160; &#160; &#160;
		Create Your First Category by clicking the <a href="/admin/categories">Categories</a> tab!
	</div>
<?php endif;?>

	<h3>10 Latest Reviews</h3>
	<?php foreach($reviews as $review):?>
		<div><?php echo $review->body?></div>
	<?php endforeach;?>

	<h3>10 Newest Customers</h3>
	<?php foreach($customers as $customer):?>
		<div><?php echo $customer->display_name?></div>
	<?php endforeach;?>
</div>