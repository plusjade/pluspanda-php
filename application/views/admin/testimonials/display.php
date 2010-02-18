

<form id="theme-css-wrapper" action="/admin/testimonials/display/save_css" class="common-ajax" method="POST">
  <div class="round-box-top">
    <a href="#close" class="close">[X]</a>
    Customize CSS
  </div>
  <div class="round-box-body">
    <a href="#" class="update-css">update view</a>
     -- <a href="#" class="load-stock">load stock</a>
    <textarea name="css" style="width:99%; height:200px"><?php echo $stylesheet?></textarea>
    <div class="stock-css" style="display:none"><?php echo $stock?></div>
  
    <br/><br/><a href="#" class="toggle-html">Toggle HTML View</a> - <small>(use as reference for css customizations)</small>
    <br/><textarea name="html" style="display:none;width:99%;height:200px;"><?php echo $testimonials_html?></textarea>
    
    <div class="round-box-tabs buttons">
      <button type="submit" class="positive">Save CSS</button>
    </div> 
  </div>
</form>


<form id="display-settings" action="/admin/testimonials/display/save" method="POST">

  <div class="round-box-top">Configure Theme Settings</div>
  <div id="display-settings" class="round-box-body">
    <strong>Choose a Theme</strong> 
    <select name="theme" class="switch-theme">
    <?php 
    $themes = array('left','right','grid','gallery');
    foreach($themes as $theme)
      if($theme == $this->owner->tconfig->theme)
        echo "<option value=\"$theme\" selected=\"selected\">$theme</option>";
      else
         echo "<option value=\"$theme\">$theme</option>";
    ?>
    </select>
     || <strong>Testimonials per page: #</strong> <input type="text" name="per_page" value="<?php echo $this->owner->tconfig->per_page?>" maxlength="2" style="width:25px"/>
     || <strong>Order Testimonials by:</strong> 
      <select name="sort">
      <?php
        $sorters = array('created'=>'Creation Date', 'position'=>'Custom Positions');
        foreach($sorters as $val => $text)
          if($val == $this->owner->tconfig->sort)
            echo "<option value=\"$val\" selected=\"selected\">$text</option>";
          else
             echo "<option value=\"$val\">$text</option>";
      ?>
      </select>
      <div style="display:block;text-align:center;margin-top:5px;font-size:0.9em">Define custom order positions by arranging testimonials in the <a href="/admin/testimonials/manage">Manager Tab</a></div>
    <div class="round-box-tabs buttons" style="float:right">
      <button type="submit" class="positive">Update Settings</button>
    </div> 
  </div>
</form>


<br style="clear:both"/>
<style id="custom-css" type="text/css"></style>
<?php echo $embed_code?>

<script type="text/javascript">
  $('.common-ajax button, a.update-css').click(function(){
    $('head link#pandaTheme').remove();
    var css = $('textarea[name="css"]').val();
    $('style#custom-css').html(css);
  });
  
  $('a.load-stock').click(function(){
    var css = $('div.stock-css').html();
    $('textarea[name="css"]').val(css);
    return false;
  });
  
  $('a.toggle-html').click(function(){
    $('textarea[name="html"]').slideToggle('fast');
    return false;
  });
  
  $('form#theme-css-wrapper').hide();
  $('a.show-css').click(function(){
    $('form#theme-css-wrapper').slideDown('fast');
    $(this).addClass('active');
    return false;
  });
  
  $('#display-settings').ajaxForm({
    dataType : 'json',
    beforeSubmit: function(fields, form){
      $(document).trigger('submit.server');
    },
    success: function(rsp){
      $(document).trigger('rsp.server', rsp);
      $('#primary_content').html('<div class="ajax-loading">Loading...</div>');
      $.get('/admin/testimonials/display', function(data){
        $('#primary_content').html(data);
      });
    }
  });
  
  
</script>





