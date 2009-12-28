

<h2>My Categories</h2>

<?php if(isset($response))echo $response?>

<div class="buttons">
<button id="save_order" class="positive" style="float:right">Save Order</button>
</div>

<ul id="sortable" class="cat-list">
<?php foreach($categories as $category):?>
  <li id="cat-<?php echo $category->id?>" class="cat-item cat-sort">
    <form action="/admin/reviews/categories/save" method="POST">
      <div class="cat-delete buttons"><a href="/admin/categories/delete?tag_id=<?php echo $category->id?>" class="del-cat" title="Delete Category" alt="Del">&#160;</a></div>
      <div class="cat-save buttons"><button type="submit" class="save-cat" rel="<?php echo $category->id?>" title="Save Changes to Category">&#160;</button></div>      
      <input type="hidden" name="id" value="<?php echo $category->id?>">
      Name: <input type="text" name="name" value="<?php echo $category->name?>" class="cat-name" rel="text_req">
      <br/>Description: <input type="text" name="desc" value="<?php echo $category->desc?>" class="cat-desc" rel="text_req">
    </form>
  </li>
<?php endforeach;?>
</ul>

<br/>
<h4>Add New Category</h4>

<form id="add-cat" action="/admin/reviews/categories/add" method="POST">
  <div style="text-align:right" class="buttons">
  <button type="submit" id="add-cat" class="positive" style="float:right">Add Category</button>
  </div>

  <ul class="cat-list">
    <li class="cat-item cat-add">    
        Name: <input type="text" name="name" value="" class="cat-name" rel="text_req">
        <br/>Description: <input type="text" name="desc" value="" class="cat-desc" rel="text_req">
    </li>
  </ul>
</form>

<script type="text/javascript">
  $("#sortable").sortable({
    //handle  : '.drag_box',
    axis  : 'y'
    //containment: '.common_main_panel'
  });
  
// add a category.
  $('form#add-cat').ajaxForm({     
    beforeSubmit: function(fields, form){
      if(! $("input, textarea", form[0]).jade_validate()) return false;
      $('button', form).attr('disabled', 'disabled').html('Submitting...');
      $(document).trigger('submit.server');
    },
    success: function(rsp) {
      $(document).trigger('rsp.server', rsp);
      $('form#add-cat button').removeAttr('disabled').html('Add Category');
      $('form#add-cat').clearFields();
      $('#primary_content').load('/admin/reviews/categories');
    }
  });
  
</script>