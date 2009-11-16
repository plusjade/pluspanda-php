

<h2 class="aligncenter"><?php echo $site->name;?> Customer Reviews.</h2>

<?php echo $submit_form;?>

<br/><br/>

<form action="" method="GET">
	Review Categories: <select name="tag">
		<option value="all">All</option>
	<?php foreach($site->tags as $tag):?>
		<option value="<?php echo $tag->id?>"><?php echo $tag->name?></option>
	<?php endforeach;?>
	</select> <button>Show Reviews</button>
</div>

<br/><br/>

<ul class="panda-sorter-list">
	<li><a href="/?sort=newest">Newest</a></li>
	<li><a href="/?sort=oldest">Oldest</a></li>
	<li><a href="/?sort=highest">Highest</a></li>
	<li><a href="/?sort=lowest">Lowest</a></li>
</ul>

<br/><br/>

<div class="reviews">
	<?php echo $reviews_list?>
</div>

<script type="text/javascript">
	 $('abbr.timeago').timeago();
</script>
