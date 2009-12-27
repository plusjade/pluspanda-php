

<a href="/admin/testimonials/manage/add_new" rel="facebox">Add New</a>

<br/>

<form action ="/admin/testimonials/manage" metho="GET">
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
	 <br/>Sort by : Name, Company, Created, Updated,
</form>


<?php echo $pagination?>
<table class="testimonial-wrapper">
	<tr>
    <th width="80px"></th>
		<th width="150px">Name</th>
		<th width="150px">Company</th>
		<th width="150px">Category</th>
		<th width="60px">Published</th>
		<th width="120px">Updated</th>
		<th width="120px">Created</th>
		
	</tr>	
<?php foreach($testimonials as $testimonial):?>
		<tr>
      <td>
				<input type="checkbox" name=""/>
				<a href="<?php echo url::site("add/testimonials/{$this->site->subdomain}?ctk={$testimonial->patron->token}&ttk=$testimonial->token")?>">link</a>
			
			</td>
			<td><a href="/admin/testimonials/manage/edit?id=<?php echo $testimonial->id?>"><?php echo $testimonial->patron->name?></a></td>
			<td><?php echo $testimonial->patron->company?></td>
			<td><?php echo $testimonial->tag->name?></td>
			<td><?php echo (empty($testimonial->publish)) ? 'no' : 'yes'?></td>
			
			<td><?php if(!empty($testimonial->updated)) echo build::timeago($testimonial->updated)?></td>
		
			<td><?php echo build::timeago($testimonial->created)?></td>
		
		
		</tr>
<?php endforeach;?>
</table>
<?php echo $pagination?>


<ul class="with-selected">
  <li>With Selected:</li>
  <li>Send Email</li>
  <li>Publish</li>
  <li>Unpublish</li>
  <li>Set Category</li>
</ul>


<div class="edit-window"></div>


