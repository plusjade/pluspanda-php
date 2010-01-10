
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

<div id="create-new" class="buttons" style="text-align:center">
  <a href="/admin/testimonials/manage/edit?id=0" class="positive">Create New</a>
</div>
<table class="t-data">
  <tr>
    <th width="25px"></th>
    <th>Edit</th>
    <th width="150px">Name</th>
    <th width="150px">Company</th>
    <th width="150px">Category</th>
    <th width="60px">Live</th>
    <th width="120px">Updated</th>
    <th width="120px">Created</th>
    <th width="60px">Share</th>
    <th width="20px">del</th>
    
  </tr>

<?php
  foreach($testimonials as $testimonial)
    echo t_build::admin_table_row($testimonial, $this->site->apikey);
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


<div id="share-window" style="display:none">
  <div class="share-data">
  <h3>Public Testimonial Editing Link</h3>
  
  Share this link with the person you want to fill out this testimonial.
  
  
  <input type="text" value="url">
  
  <br/><b>Note:</b> Anyone with this link will be able to edit the testimonial.
  <br/>Be sure to lock the testimonial when you are satisfied with the edits.
    
</div>
</div>




