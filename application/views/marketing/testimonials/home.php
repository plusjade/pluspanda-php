
<div class="home-intro">
  <h2 class="home-header">
    Add Great Looking Customer Testimonials
    <br/> to Your Website, <span>Fast and Easily.</span>
  </h2>

  <h6 class="home-main-text" style="display:none;">
    "Pluspanda lets you quickly build a professionally designed 'customer testimonials'
    <br/>web page for your marketing website.  It's as simple as 1 - 2 - 3"
  </h6>
  
  <div class="main-steps">
    <h4 class="active">Step 1 - Choose Your Layout.</h4>
    <div class="active">
      Customize colors and styles with full CSS access.
    </div>
    
    <h4>Step 2 - Add/Collect Testimonials.</h4>
    <div>
      Have existing testimonials? Easily upload and organize them
      or use our public testimonial collection form to collect testimonials.
    </div>
    
    <h4>Step 3 - Install On Your Website.</h4>
    <div>
      Fully export the finished code to place into your website
      or use our javascript widget to add automatically.
    </div>
    
  </div>
  
</div>

<!--
<div class="demo-switcher">

  <span>Select a layout to sample &#8594;</span>
  <ul>
  <?php foreach(Kohana::config('core.themes') as $theme):?>
    <li><a href="#<?php echo $theme?>"><?php echo ucfirst($theme)?></a></li>
  <?php endforeach;?>
  </ul>
</div>
-->
<div class="demo-widget">
  
  <a href="/admin/testimonials/display" class="layout" style="margin:0 auto 25px auto"></a>

  <?php echo t_build::embed_code(Kohana::config('core.apikey'), NULL,FALSE)?>

  <br style="clear:both"/>
  <a href="/admin/testimonials/display" class="layout" style="margin:25px auto;"></a>
</div>



<h2 class="home-header" style="margin-top:20px;">Benefits at a Glance</h2>

<div class="demo-text left">  
  <h6>Installs in One Minute.</h6>
  <div>
    PlusPanda works like a widget. Just add a piece of custom code wherever you want your testimonials to appear and that's it!
    
    <br/><br/>Sample Code:<br/>
    <textarea style="width:99%; height:80px; border:1px solid #777; background:#ffffcc;font-size:0.9em"><?php echo t_build::embed_code('abcd','fake')?></textarea>
  
  </div>
  
  <h6>Categorize Your Testimonials.</h6>
  <div>
    Specifying categories helps visitors to your website
    home in on use-cases specific to them.
    
    <br/><br/>
    A mother selling part-time from a yahoo store front
    would better identify with testimonials tagged "part-time ecommerce sales"
    as opposed to reading about how your product helped Oracle's internal IT deptartment.
  </div>
</div>

<div class="demo-text right">
  <h6>Fully Brandable.</h6>
  <div>
    Easily customize the look and feel of your testimonials - even specify your own
    custom css.
  </div>

  <h6>Beautifully Easy Management.</h6>
  <div>
    Control everything from one central, highly interactive admin panel.
    Never have the same handful of ancient testimonials you spent a week getting, again!
    Change layouts, customize css, sort, update, publish/unpublish testimonials, and manage collections easily!
  </div>
  
  <h6>You Have Better Things to Do.</h6>
  <div style="font-style:italic;">
    Adding testimonials to your website is a simple task.
    Ok, so maybe we aren't winning noble prizes here, and you can 
    certainly whip up a page with your nice testimonials in a couple of hours -
    but you have priorities indeed.
    
    <br/><br/>
    PlusPanda <u>trivializes</u> this one simple marketing strategy so you can
    concentrate on implementing other strategies.
    You <u>know</u> you have better things to do! 
    <br/>So let us handle it, we are good at it. Thanks!
  </div>
</div>


<script type="text/javascript">
 $('div.demo-switcher ul a:first').addClass('active');
  $('div.demo-switcher ul a').click(function(){
    var url = '<?php echo t_paths::css(Kohana::config('core.apikey'), 'url')?>/';
    var theme = $(this).attr('href').substring(1);
    $('div.demo-switcher ul a').removeClass('active');
    $(this).addClass('active');
    $('head link#pandaTheme').attr('href', url + theme + '.css');
    $('#panda-select-tags ul a:first').click();
  });
</script>

