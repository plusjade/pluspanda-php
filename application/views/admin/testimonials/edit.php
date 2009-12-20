
<?php
$rand = text::random('alnum',6);
$image = (empty($testimonial->image))
	? ''
	: "<img src=\"$image_url/$testimonial->image?r=$rand\"/>";
?>


<h2>Publisher</h2>

<div class="testimonial-wrapper">

<form id="save-testimonial" action="/admin/testimonials/save?id=<?php echo $testimonial->id?>" method="POST">
	
	<div class="edit-wrapper">
		<div class="save-list">
			Publish? <input type="checkbox" name="publish" value="yes" <?php echo (empty($testimonial->publish)) ? '' : 'CHECKED'?>/> (yes)
		
			<button type="submit" style="">Update Testimonial</button>
		</div>
		
		<a href="#" class="toggle-edit">Toggle Edit Mode</a>
	</div>
	
	<fieldset class="panda-image">
		Upload new headshot or logo <input type="file" name="image" />
	</fieldset>	
		
	<div class="t-view">
		<div class="t-details">
			<a href="/admin/testimonials/crop?image=<?php echo $testimonial->image?>" rel="facebox">Edit Image</a>
			
			<div class="image"><?php echo $image?></div>
			
			<span class="label">Full Name</span>
			<span class="t-name">
				<input name="name" value="<?php echo $testimonial->customer->name?>" />
			</span>
			
			<span class="label">Position at Company</span>
			<span class="t-position">
				<input name="position" value="<?php echo $testimonial->customer->position?>" />
			</span>
			
			<span class="label">Company</span>
			<span class="t-company">
				<input name="company" value="<?php echo $testimonial->customer->company?>" />
			</span>
			
			<span class="label">Location</span>
			<span class="t-location">
				<input name="location" value="<?php echo $testimonial->customer->location?>" />
			</span>
			
			<span class="label">Website</span>
			<span class="t-url">
				http://<input name="url" value="<?php echo $testimonial->customer->url?>" />
			</span>
		</div>
		
		<div class="t-content">
			<div class="t-rating _<?php echo $testimonial->rating?>" title="Rating: <?php echo $testimonial->rating?> stars">&#160;</div>
			
			<div class="t-body">
				<textarea name="body"><?php echo $testimonial->body?></textarea>
			</div>
			
			<div class="t-date"><?php echo build::timeago($testimonial->created)?></div>
		
			<div class="t-tag">
				<?php 
					echo build_testimonials::tag_select_list(
						$tags, 
						$testimonial->tag->id, 
						array('0'=>'(Select Category)')
					);
				?>
			</div>
			
		</div>
	</div>
	
</form>
</div>


<h2>Survey Questions</h2>
<div class="questions-wrapper">
<?php foreach($questions as $question):?>
	<div><?php echo $question->title?></div>
	<p>
		<?php if(isset($info["$question->id"])) echo $info["$question->id"]?>
	</p>
<?php endforeach;?>
</div>

