
<?php
/*
<!--
<textarea><?php echo $css?></textarea>
<textarea><?php echo $html?></textarea>
-->

<style type="text/css">
  #plusPandaYes span.page-wrapper {display:block; overflow:auto; border:1px solid red;}
  <?php echo $css?>
</style>
<?php echo $html?>


<span id="page-1" class="page-wrapper"></span>
<script type="text/javascript">
  var count = $('#plusPandaYes div.t-wrapper').size();
  console.log(count);

  //$('#plusPandaYes div.t-wrapper:first')
  //  .before('<span id="page-1" class="page-wrapper">');
  //$('#plusPandaYes div.t-wrapper:last')
  //  .after('</span>');

  var page = 1;
  $('#plusPandaYes div.t-wrapper').each(function(i){
    
    if(0 == (i) % 3){
      console.log($("div.t-wrapper:nth-child("+ (i+3) +")"));
      console.log($(this).nextUntil("div.t-wrapper:nth-child("+ (i+3) +")"));
      $(this)
        .nextUntil("div.t-wrapper:nth-child("+ (i+3) +")")
        .add(this)
        .wrapAll('<span class="page-wrapper"></span>'); 
      
      //$("ul li:nth-child(2)").append("<span> - 2nd!</span>");
      //   .wrapAll('<span class="page-wrapper"></span>'); 
      console.log(i);
    }
    
    //if(0 == (i+1) % 3)
    //  $(this).after('</span><span id="page-'+ ++page +'" class="page-wrapper">');
  //$(this).wrap('<span id="page-'+ ++page +'" class="page-wrapper">');
  
  });
 
  // activate pagination emulation
  //$('#plusPandaYes span.page-wrapper:not(:first)').hide();
  //$('#plusPandaYes span.page-wrapper:not(:last)')
  //  .append('<a href="#" class="show-more">show more</a>');
  
  // activate show-more links
  $('a.show-more').click(function(){
    $(this).parent('span').next('span.page-wrapper').show();
    $(this).remove();
    return false;
  });
  
  // activate category tags
  $('#panda-select-tags ul a').click(function(){
    var tag = this.hash.substring(1); 
    $('#panda-select-tags ul a').removeClass('active');
    $(this).addClass('active');
    if('all'== tag)
      $('#plusPandaYes div.t-wrapper').show();
    else{
      $('#plusPandaYes div.t-wrapper').hide();
      $("#plusPandaYes div.t-wrapper.tag-"+ tag +'"').show();
    }
  });
</script>
<br/><br/>
*/
if(empty($this->owner->email)):?>
<h2 style="text-align:center; text-decoration:underline; color:#0066cc;">
 <img src="/static/images/admin/warning.png" />
  Remember to Save Your Account by Entering Your Email!
 <img src="/static/images/admin/warning.png" />
 <br/>
 Otherwise this code will expire in 24 hours =(
</h2>
<?php endif;?>

<div class="install-page">
  <div class="info">
    Place the install code in the exact place on your page where you want your widget to be displayed.
  </div>

  <h4>Standard</h4>
  <div>The standard install code is good for all websites!</div>
  <textarea><?php echo $embed_code?></textarea>

  <h4>Developer</h4>
  <div>
    Pluspanda uses the jquery javascript framework, which gets loaded by the standard embed code.
    If your website is already using jquery you can use the following code which omits loading jquery again.
  </div>
  <textarea><?php echo $embed_code_lite?></textarea>

  
  <br/><br/>
  <h2>Coming Soon: Export Your Layout</h2>
  We are working on adding exporting of all html,css, javascript and data assets
  to upload onto your own page. Please email <a href="mailto:plusjade@gmail.com">plusjade@gmail.com</a> if this is something you would be interested in, thanks!
</div>

<div id="help-page">
  <div class="help-page-inner">
    No help here yet!
  </div>
</div>

<div style="clear:both;margin-top:25px;text-align:center;">
  <h2>Success!</h2>
  <span style="color:#555">(yup that's it)</span>
</div>


