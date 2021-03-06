<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title><?php if(isset($title)) echo $title?> | PlusPanda</title>
  <meta name="description" content="<?php if(isset($meta)) echo $meta?>" />
  <meta name="google-site-verification" content="V80VygXkuzvIBgN6-03u1PMHN05e1C2q0iK--hl3vPQ" />
  <link type="text/css" href="/css/marketing.css" media="screen" rel="stylesheet" />
  
  <script type="text/javascript" src="/static/js/common/jquery.js"></script>
  <script type="text/javascript" src="/static/js/common/addon.js"></script>
</head>

<body>
<div id="outer">
  <div class="contentwidth main">  
    <a href="/admin/login" id="login-link"><?php echo $login_link?></a>  
    <a href="/"><img src="/static/images/marketing/pluspanda-logo.png" class="logo" alt="pluspanda logo"></a>

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
  $links['terms_of_service'] = 'TOS';
  $links['privacy_policy'] = 'Privacy Policy';
  foreach($links as $url => $text)
    echo "<li><a href=\"/$url\">$text</a></li>\n";
?>
        <li>
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


