
<div class="buttons" style="float:right">
  <a href="/admin/testimonials/manage/add_new" class="positive" rel="facebox">Add New</a>
</div>
<br/>

<form action ="/admin/testimonials/manage" metho="GET">
  Publish <select name="publish">
    <option value="all">All</option>
    <option value="no">No</option>
    <option value="yes">Yes</option>
  </select>
  Category Tag 
  <?php 
    echo t_build::tag_select_list(
      $tags, 
      $active_tag, 
      array('all'=>'All')
    );
  ?>
   <button type="submit">Submit Query</button>
   <!--<br/>Sort by : Name, Company, Created, Updated,-->
</form>


<?php echo $pagination?>
<table class="t-data">
  <tr>
    <th width="80px"></th>
    <th width="150px">Name</th>
    <th width="150px">Company</th>
    <th width="150px">Category</th>
    <th width="60px">Live</th>
    <th width="120px">Updated</th>
    <th width="120px">Created</th>
    <th width="20px">del</th>
    
  </tr>  
<?php
  foreach($testimonials as $testimonial)
    echo t_build::admin_table_row($testimonial, $this->site->subdomain);
?>

</table>
<!--
<ul class="with-selected">
  <li>With Selected:</li>
  <li>Send Email</li>
  <li>Publish</li>
  <li>Unpublish</li>
  <li>Set Category</li>
</ul>
-->

<div class="edit-window"></div>


