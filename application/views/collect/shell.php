<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>PlusPanda Feedback Engine</title>

  <!--  REQUIRED FOR IE6 SUPPORT -->
  <style type="text/css">img, div { behavior: url(iepngfix.htc) }</style> 
  <link href="/css/t_collect.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="wrapper">
  <div id="server_response">
    <span class="rsp"></span>
    <div class="load" style="display:none">
      <strong>Loading...</strong>
    </div>
  </div>
  
  <div id="content_wrapper">
    <?php if(isset($content)) echo $content?>
    
    <div id="panda-powered">
      <img src="/static/images/marketing/panda.png" alt="pluspanda logo" />
      <h1>Want to add testimonials to your own site?</h1>
      <h2><a href="<?php echo url::site()?>"><?php echo url::site()?></a></h2>
    </div>
    <div id="footer">© Copyright 2009 PlusPanda =] | <a href="#">Top</a></div>
  </div>
  
</div>

<script type="text/javascript" src="/static/js/common/jquery.js"></script>
<script type="text/javascript" src="/js/t_collect.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  $('a[rel*=facebox]').facebox();
  $("div#qs").codaSlider();
  $("div#t-questions").codaSlider();
  $(document).trigger('tstml.edit');
});
</script>

</body>
</html>
