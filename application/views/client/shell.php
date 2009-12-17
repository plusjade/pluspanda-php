<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>PlusPanda Feedback Engine</title>

	<!--  REQUIRED FOR IE6 SUPPORT -->
	<style type="text/css">img, div { behavior: url(iepngfix.htc) }</style> 
	<link href="/static/client/css/global.css" rel="stylesheet" type="text/css" />
	<!--
	<link href="/static/testimonials/css/client.css" rel="stylesheet" type="text/css" />
	-->
</head>

<body>
<div id="wrapper">
	<div id="server_response">
		<span class="rsp"></span>
		<div class="load" style="display:none">
			<strong>Loading...</strong>
		</div>
	</div>
	
	<div id="header"></div>  
	
	<div id="content_wrapper">
		<?php if(isset($content)) echo $content?>
		<div id="footer">© Copyright 2009 PlusPanda =] | <a href="#">Top</a></div>
	</div>
	
</div>

	<script type="text/javascript" src="/static/js/jquery.js"></script>
	<script type="text/javascript" src="/static/js/addon.js"></script>
<!--
<script type="text/javascript" src="/static/admin/js/init.js"></script>
-->	
</body>

</html>
