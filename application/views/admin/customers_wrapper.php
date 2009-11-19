

<h2>Manage Customers</h2>

<?php if(isset($response))echo $response?>

<div class="buttons">
<button id="update" class="positive" style="float:right">Update</button>
</div>

Total Customers: <strong><?php echo $customers->count()?></strong>
<ul class="customer-list">	
<?php foreach($customers as $customer):?>
	<li>
		<?php echo $customer->display_name?>
		- <?php echo $customer->email?>
		- <abbr class="timeago" title="<?php echo date("c", $customer->created)?>"><?php echo date("M d y @ g:i a", $customer->created)?></abbr>
	</li>
<?php endforeach;?>
</ul>


<script type="text/javascript">
$('abbr.timeago').timeago();
</script>