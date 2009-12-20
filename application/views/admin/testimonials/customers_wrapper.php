

<h2>
	<span style="float:right">Total: <strong><?php echo $testimonials->count()?></strong></span>
	Manage Customers
</h2>

<?php if(isset($response))echo $response?>

<form id="add-cat" action="/admin/categories/add" method="POST"> 
	<div style="text-align:right" class="buttons"> 
	<button type="submit" id="add-cat" class="positive" style="float:right">Add Customer</button> 
	</div> 
 
	<ul class="cat-list"> 
		<li class="cat-item cat-add">		
				Full Name: <input type="text" name="name" value="" class="cat-name" rel="text_req"> 
				<br/>email: <input type="text" name="email" value="" class="cat-desc" rel="text_req"> 
		</li> 
	</ul> 
</form> 


<table class="customer-list">	
	<tr>
		<th>Name</th>
		<th>Company</th>
		<th>Email</th>
		<th>Created</th>
	</tr>

<?php foreach($testimonials as $testimonial):?>
	<tr>
		<td><?php echo $testimonial->customer->name?></td>
		<td><?php echo $testimonial->customer->company?></td>
		<td><?php echo $testimonial->customer->email?></td>
		<td><?php echo build::timeago($testimonial->customer->created)?></td>
	</tr>
<?php endforeach;?>
</table>


<script type="text/javascript">
$('abbr.timeago').timeago();
</script>