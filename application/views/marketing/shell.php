<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title><?php if(isset($title)) echo $title?> | PlusPanda</title>
  <meta name="description" content="Embedable customer reviews." />
  
  <link type="text/css" href="/css/marketing.css" media="screen" rel="stylesheet" />
  
  <script type="text/javascript" src="/static/js/common/jquery.js"></script>
  <script type="text/javascript" src="/static/js/common/addon.js"></script>
</head>

<body>
<div id="outer">
  <div class="contentwidth main">  
    <a href="/admin/login" id="login-link"><?php echo $login_link?></a>  
    <div class="logo">
        <h1><a href="/">PlusPanda</a></h1>
    </div>
    <div class="menu">
      <ul class="clearfix">
<?php
  foreach($links as $url => $text)
    if($active == $url)
      echo "<li><a href=\"/$url\" class=\"active\">$text</a></li>\n";
    else
      echo "<li><a href=\"/$url\">$text</a></li>\n";
?>
      </ul>
    </div>    
    <?php echo $content?>
  </div>
    
  <div class="footer">
    <div class="contentwidth">
      <p><strong>&copy; 2009 PlusPanda =)</strong></p>
      <ul class="footer-menu">
<?php
  foreach($links as $url => $text)
    echo "<li><a href=\"/$url\">$text</a></li>\n";
?>
      </ul>
    </div>
  </div>
</div>

<script type="text/javascript">


</script>

<?php
if(file_exists(DOCROOT . 'tracker.html'))
  include_once(DOCROOT . 'tracker.html');
?>
</body>
</html>


