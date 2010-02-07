
<?php
$rand   = text::random('alnum',6);
$image  = (empty($testimonial->image))
  ? ''
  : "<img src=\"$image_url/$testimonial->image?r=$rand\"/>";
?>
<div class="admin-testimonial-wrapper t-builder-wrapper">
<form id="save-testimonial" action="/admin/testimonials/manage/save" rel="<?php echo $this->testimonial_id?>" enctype="multipart/form-data" method="POST">
  
  <button class="submit-button" type="submit">Save Changes</button>

  
  <div class="t-publish">
    Publish? <input type="checkbox" name="publish" value="yes" <?php echo (empty($testimonial->publish)) ? '' : 'CHECKED'?>/> (yes)
    <!--<a href="#">Send Email</a>-->
  </div>
  
  
  <div class="panda-image">
    Upload new headshot or logo <input type="file" name="image" />
  </div>  
    
  <div class="t-view">
    <div class="t-details">
      <a href="/admin/testimonials/manage/crop?image=<?php echo $testimonial->image?>" rel="facebox">Edit Image</a>
      
      <div class="image" rel="<?php echo $image_url?>">
        <?php echo $image?>
      </div>
      
      <span class="label">Full Name</span>
      <span class="t-name">
        <input name="name" value="<?php echo $testimonial->patron->name?>" />
      </span>
      
      <span class="label">Position at Company</span>
      <span class="t-position">
        <input name="position" value="<?php echo $testimonial->patron->position?>" />
      </span>
      
      <span class="label">Company</span>
      <span class="t-company">
        <input name="company" value="<?php echo $testimonial->patron->company?>" />
      </span>
      
      <span class="label">Location</span>
      <span class="t-location">
        <input name="location" value="<?php echo $testimonial->patron->location?>" />
      </span>
      
      <span class="label">Website</span>
      <span class="t-url">
        http://<input name="url" value="<?php echo $testimonial->patron->url?>" />
      </span>
    </div>
    
    <div class="t-content">
      
      <span style="display:none">
        <input type="hidden" name="rating" value="<?php echo $testimonial->rating?>">
        <?php echo common_build::rating_select_nice($testimonial->rating)?>
      </span>        
      <div class="rating-fallback">
        Select a rating <?php echo common_build::rating_select_dropdown($testimonial->rating);?>
      </div>

      <div class="t-body">
        <textarea name="body"><?php echo $testimonial->body?></textarea>
      </div>
      
      <div class="t-date"><?php echo common_build::timeago($testimonial->created)?></div>
    
      <div class="t-tag">
        <?php 
          echo t_build::tag_select_list(
            $tags, 
            $testimonial->tag->id, 
            array('0'=>'(Select Category)')
          );
        ?>
      </div>
      
    </div>
    <br style="clear:both"/>
    <br/>Lock Testimonial? (yes) <input type="checkbox" name="lock" <?php if(1 == $testimonial->lock) echo 'CHECKED'?>/>
    <br/><small>Locking a testimonial will disallow any further updates from the public testimonial builder.</small>
  </div>

</form>
</div>

<!--
<h2>Survey Questions</h2>
<div class="questions-wrapper">
<?php foreach($questions as $question):?>
  <div><?php echo $question->title?></div>
  <p>
    <?php if(isset($info["$question->id"])) echo $info["$question->id"]?>
  </p>
<?php endforeach;?>
</div>
-->

<script type="text/javascript">
  $(document).ready(function(){
    $(document).trigger('tstml.edit');
  });
</script>











