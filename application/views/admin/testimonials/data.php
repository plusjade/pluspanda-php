
<form action ="/admin/testimonials" metho="GET">
	Publish <select name="publish">
		<option value="all">All</option>
		<option value="no">No</option>
		<option value="yes">Yes</option>
	</select>
	Category Tag 
	<?php 
		echo build_testimonials::tag_select_list(
			$tags, 
			$active_tag, 
			array('all'=>'All')
		);
	?>
	 <button type="submit">Submit</button>
</form>

<?php echo $pagination?>
<table class="testimonial-wrapper">
	<tr>
		<th>Name</th>
		<th width="200px">Company</th>
		<th width="200px">Category</th>
		<th width="60px">Featured</th>
		<th width="80px">Public Url</th>
		<th width="120px">Created</th>
		
	</tr>	
<?php foreach($testimonials as $testimonial):?>
		<tr>
			<td><a href="/admin/testimonials/edit?id=<?php echo $testimonial->id?>"><?php echo $testimonial->customer->name?></a></td>
			<td><?php echo $testimonial->customer->company?></td>
			<td><?php echo $testimonial->tag->name?></td>
			<td><?php echo $testimonial->featured?></td>
			<td><a href="<?php echo url::site("add/testimonials/{$this->site->subdomain}?ctk={$testimonial->customer->token}&ttk=$testimonial->token")?>">link</a></td>
			<td><?php echo build::timeago($testimonial->created)?></td>
		</tr>
<?php endforeach;?>
</table>
<?php echo $pagination?>


<div class="edit-window"></div>


