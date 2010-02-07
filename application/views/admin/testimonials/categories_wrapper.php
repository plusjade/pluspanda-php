

<?php if(isset($response))echo $response?>


<form id="add-cat" action="/admin/testimonials/tags/add" method="POST">
  <h4 class="buttons">
    <button type="submit" id="add-cat" class="positive" style="float:right">Add Category</button>
    Add New Category
  </h4>  

  <ul class="cat-list">
    <li class="cat-item cat-add">    
        Name: <input type="text" name="name" value="" class="cat-name" rel="text_req">
        <br/>Description: <input type="text" name="desc" value="" class="cat-desc" rel="text_req">
    </li>
  </ul>
</form>


<div class="buttons">
  <button id="save_order" class="positive" rel="/admin/testimonials/tags/order" style="float:right">Save Order</button>
</div>

<ul id="sortable" class="cat-list">
<?php foreach($categories as $category):?>
  <li id="cat-<?php echo $category->id?>" class="cat-item cat-sort">
    <div class="cat-handle"></div>
    <form action="/admin/testimonials/tags/save?id=<?php echo $category->id?>" method="POST">
      <div class="cat-delete buttons"><a href="/admin/testimonials/tags/delete?id=<?php echo $category->id?>" class="del-cat" title="Delete Category" alt="Del">&#160;</a></div>
      <div class="cat-save buttons"><button type="submit" class="save-cat" rel="<?php echo $category->id?>" title="Save Changes to Category">&#160;</button></div>      
      Name: <input type="text" name="name" value="<?php echo $category->name?>" class="cat-name" rel="text_req">
      <br/>Description: <input type="text" name="desc" value="<?php echo $category->desc?>" class="cat-desc" rel="text_req">
    </form>
  </li>
<?php endforeach;?>
</ul>

<script type="text/javascript">
  $(document).ready(function(){
    $(document).trigger('tstml.tags');
  });
</script>