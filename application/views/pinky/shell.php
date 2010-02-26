<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>PlusPanda Admin Panel</title>

  <!--  REQUIRED FOR IE6 SUPPORT -->
  <style type="text/css">img, div { behavior: url(iepngfix.htc) }</style> 
  <link href="/css/admin.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="/static/js/common/jquery.js"></script>
  <script type="text/javascript" src="/js/pinky.js"></script>
</head>

<body>
<div id="wrapper">
  <div id="top">
    <ul>
      <li><a href="/pinky/dashboard">Dashboard</a></li>
      <li><a href="/pinky/account">Account</a></li>
      <li><a href="/pinky/logout">Logout</a></li>
    </ul>
  </div>
  <div id="server_response">
    <span class="rsp"></span>
    <div class="load" style="display:none">
      <strong>Loading...</strong>
    </div>
  </div>
     
  <div id="header">
    <div>

    </div>
  </div>

  <div id="content_wrapper">
    <div id="sidebar">  
      <ul>
        <li>test</li>
      </ul> 
    </div>
    
    <div id="primary_content">
      <?php if(isset($content)) echo $content?>
    </div>
    
    <div id="footer">© Copyright 2009 PlusPanda =] | <a href="#">Top</a></div>
  </div>
  
</div>

</body>
</html>


