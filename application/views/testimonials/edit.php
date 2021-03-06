<?php
  $testimonial->body = (empty($testimonial->body))
    ? "Hello this is your testimonial!\nOur survey questions below will guide you easily along.\nPlease answer them, but also feel free to write your own freeform testimonial right in this box.\n\nClear this text when you are ready!\nHave fun!"
    : $testimonial->body;

  $rand   = text::random('alnum',6);
  $image  = (empty($testimonial->image))
    ? ''
    : "<img src=\"$image_url/$testimonial->image?r=$rand\"/>";
?>


<div class="top-message">
  Hello, <?php echo $testimonial->name?>, Thanks for your help!
  <br/>Be sure to save the link to this page so you can edit your testimonial anytime!
</div>

<?php if($locked):?>
  <div class="attention" style="width:400px; margin:20px auto">This testimonial is <b>Locked</b> and can no longer be updated!</div>
<?php endif;?>

<div class="client-add-wrapper t-builder-wrapper">
<form action="" enctype="multipart/form-data" method="POST">
  <fieldset class="panda-image">
    Upload new headshot or logo <input type="file" name="image" />
  </fieldset>  
  
  <div class="t-view">
    <div class="t-details">
      <a href="<?php echo "$url&a=crop&image=$testimonial->image"?>" rel="facebox">Re-Crop Image</a>
  
      <div class="image" rel="<?php echo "$image_url/$testimonial->id"?>">
        <?php echo $image?>
      </div>
      
      <span class="label">Full Name</span>
      <span class="t-name">
        <input name="name" value="<?php echo $testimonial->name?>" />
      </span>
      
      <span class="label">Position at Company</span>
      <span class="t-position">
        <input name="position" value="<?php echo $testimonial->c_position?>" />
      </span>
      
      <span class="label">Company</span>
      <span class="t-company">
        <input name="company" value="<?php echo $testimonial->company?>" />
      </span>
      
      <span class="label">Location</span>
      <span class="t-location">
        <input name="location" value="<?php echo $testimonial->location?>" />
      </span>
      
      <span class="label">Website</span>
      <span class="t-url">
        <input name="url" value="http://<?php echo $testimonial->url?>" />
      </span>
    </div>
    
    <div class="t-content">
    
      <div class="t-rating-wrapper">
        <span style="display:none">
          <input type="hidden" name="rating" value="<?php echo $testimonial->rating?>">
          <?php echo common_build::rating_select_nice($testimonial->rating)?>
        </span>        
        <div class="rating-fallback">
          Select a rating <?php echo common_build::rating_select_dropdown($testimonial->rating);?>
        </div>
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
    <div class="clear:both;padding:10px;">
      <button class="submit-button" type="submit">Save Changes</button>
    </div>
  </div>    
      
      
<?php
/*
  <h1>Survey Questions</h1>
  <div class="slider-wrap">
    <div id="t-questions" class="t-questions">
      <div class="panelContainer">
  <?php $x = 0?>  
  <?php foreach($questions as $question):?>
        <div class="panel" title="<?php echo ++$x?>">
          <div class="wrapper">
            <label><?php echo $question->title?></label>
            <div class="info"><?php echo $question->info?></div>
            <textarea name="info[<?php echo $question->id?>]"><?php if(isset($info["$question->id"])) echo $info["$question->id"]?></textarea>
          </div>
        </div>
  <?php endforeach;?>
      </div>
    </div>
  </div>
</form>
</div>

*/
?>



