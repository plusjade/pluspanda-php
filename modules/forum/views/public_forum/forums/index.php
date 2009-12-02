

<h2 class="header">User Feedback Forum</h2>
<div class="forum-note">
<?php if(!$this->owner->logged_in()):?>
	Posting in the forum requires a Pluspanda account. 
	<br/><a href="/admin?ref=/forum">Login</a> or <a href="/start">Get Yours Free!</a>
<?php else:?>
	Hello, <?php echo $this->owner->get_user()->username?> =)
<?php endif;?>
</div>
<div id="forum_navigation_wrapper">

	<a href="<?php echo url::site("$page_name/submit")?>" id="submit_new">Submit New</a>	
	
	<h3>Categories</h3>	
	<ul id="forum_categories">
		<li><a href="<?php echo url::site("$page_name/category/all")?>">All</a></li>
	<?php
		if(FALSE !== $categories)
			foreach($categories as $cats):?>
		<li><a href="<?php echo url::site("$page_name/category/$cats->url")?>"><?php echo $cats->name?></a></li>
	<?php endforeach;?>
	</ul>
	
	<h3>My Stuff</h3>
	<?php if($this->owner->logged_in()):?>
	<ul>
		<li><a href="<?php echo url::site("$page_name/my/posts")?>">Posts</a></li>
		<li><a href="<?php echo url::site("$page_name/my/comments")?>">Comments</a></li>
		<!--<li><a href="<?php echo url::site("$page_name/my/starred")?>">Starred</a></li> -->
	</ul>
	<?php else:?>
		<div style="margin:0 0 10px 10px">
			<a href="/admin?ref=<?php echo $_SERVER['REQUEST_URI']?>">Login</a>
			<br/> to manage your posts/comments.	
		</div>
	<?php endif;?>
	
</div>

<div id="forum_content_wrapper">
	<?php echo $content?>
</div>
