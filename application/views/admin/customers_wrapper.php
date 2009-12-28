

<h2>
  <span style="float:right">Total: <strong><?php echo $customers->count()?></strong></span>
  Manage Customers
</h2>

<?php if(isset($response))echo $response?>


<table class="customer-list">  
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Created</th>
  </tr>
<?php foreach($customers as $customer):?>
  <tr>
    <td><?php echo $customer->name?></td>
    <td><?php echo $customer->email?></td>
    <td><?php echo common_build::timeago($customer->created)?></td>
  </tr>
<?php endforeach;?>
</table>


<script type="text/javascript">
$('abbr.timeago').timeago();
</script>