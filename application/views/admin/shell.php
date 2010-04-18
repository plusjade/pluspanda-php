<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>PlusPanda Admin Panel</title>

  <!--  REQUIRED FOR IE6 SUPPORT -->
  <style type="text/css">img, div { behavior: url(iepngfix.htc) }</style> 
  <link href="/css/admin.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="/static/js/common/jquery.js"></script>
  <script type="text/javascript" src="/js/admin.js"></script>
</head>

<body>
<div id="wrapper">
  <div id="top">
    <a href="/admin/testimonials/display">
      <img src="/static/images/admin/panda.png" />
    </a>
    
    <ul>
      <li><a href="/">Return Home</a></li>
      <li><a href="/admin/account">Account</a></li>
      <li><a href="/admin/login/logout">Logout</a></li>
    </ul>
    
    <?php if(empty($this->owner->email)):?>
    <div class="user-status guest">
      <b>This Guest account will expire in 24 hours...</b>
      <br/>Would You Like to Save Your Progress?
      <form action="/admin/account/save" method="POST" class="common-ajax">
        <fieldset>
          <label>your email:</label> <input type="text" name="email" rel="email_req"/> 
          <input type="submit" value="Save My Progress" class="save-account"/>
        </fieldset>
      </form>
    </div>
    <?php else:?>
    <div class="user-status owner">
      Logged in as <?php echo $this->owner->email?>
    </div>
    <?php endif;?>
  </div>  
  <div id="server_response">
    <span class="rsp"></span>
    <div class="load" style="display:none">
      <strong>Loading...</strong>
    </div>
  </div>
     
  <div id="parent_nav">
    <?php echo navigation::render($parent_nav, $this->parent_nav_active)?>
  </div>

  <div id="content_wrapper">
    <div class="child_nav">
      <?php
      if($child_nav)
        echo navigation::render($child_nav, $this->child_nav_active);
      ?>
    </div>
    
    <div class="grandchild_nav">
      <?php
      if($grandchild_nav)
        echo navigation::render($grandchild_nav, $this->grandchild_nav_active);
      ?>
    </div>

    <div id="primary_content">
      <?php if(isset($content)) echo $content?>
    </div>
    
    <div id="footer">© Copyright 2009 PlusPanda =] | <a href="#">Top</a></div>
  
  </div>
  
</div>

<?php
if(file_exists(DOCROOT . 'tracker.html'))
  include_once(DOCROOT . 'tracker.html');
?>
</body>
</html>


