
<?php
$rand = text::random('alnum',6);
$image = (empty($testimonial->image))
  ? ''
  : "<img src=\"$image_url/$testimonial->image?r=$rand\"/>";
?>


<h2>Publisher</h2>

<div class="testimonial-wrapper">

<form id="save-testimonial" action="/admin/testimonials/manage/save?id=<?php echo $testimonial->id?>" method="POST">
  
  <div class="edit-wrapper">
    <div class="save-list">
      Publish? <input type="checkbox" name="publish" value="yes" <?php echo (empty($testimonial->publish)) ? '' : 'CHECKED'?>/> (yes)
    
      <button type="submit" style="">Update Testimonial</button>
    </div>
    
    <a href="#">Send Email</a>
  </div>
  
  <fieldset class="panda-image">
    Upload new headshot or logo <input type="file" name="image" />
  </fieldset>  
    
  <div class="t-view">
    <div class="t-details">
      <a href="/admin/testimonials/manage/crop?image=<?php echo $testimonial->image?>" rel="facebox">Edit Image</a>
      
      <div class="image"><?php echo $image?></div>
      
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
  </div>
  
</form>
</div>


<h2>Survey Questions</h2>
<div class="questions-wrapper">
<?php foreach($questions as $question):?>
  <div><?php echo $question->title?></div>
  <p>
    <?php if(isset($info["$question->id"])) echo $info["$question->id"]?>
  </p>
<?php endforeach;?>
</div>

<script type="text/javascript">
$(document).ready(function(){
    
  $('a[rel*=facebox]').facebox();

    
// star rating stuff.
  $('.t-content .rating-fallback').remove();
  $('.t-content span').show();
  function pandaUpdateText(rating){
    var text = {1:'Poor', 2:'Lacking', 3:'Average', 4:'Pretty good!', 5:'Fantastic!'};
    $('.panda-rating-text').html(text[rating]);
  }
  $('#panda-star-rating div').hover(function(){
      var rating = $(this).attr('class').substr(1);  
      $(this).parent().removeClass().addClass('_'+rating);
      pandaUpdateText(rating);
    },function(){
      var old_rating = $(this).parent().attr('rel');
      $(this).parent().removeClass().addClass('_'+old_rating);  
      pandaUpdateText(old_rating);
    }
  );
  $('#panda-star-rating div').click(function(){
      var rating = $(this).attr('class').substr(1);  
      $(this).parent().removeClass().addClass('_'+rating).attr({rel:rating});      
      $('.t-rating-wrapper input').val(rating);
      pandaUpdateText(rating);
  });
  
}); // end document ready

</script>











