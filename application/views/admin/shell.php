<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>PlusPanda Admin Panel</title>

	<!--  REQUIRED FOR IE6 SUPPORT -->
	<style type="text/css">img, div { behavior: url(iepngfix.htc) }</style> 
	<link href="/static/admin/css/global.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/static/js/jquery.js"></script>

</head>

<body>
<div id="wrapper">
	<div id="top">
		<ul>
			<li><a href="/">Return Home</a></li>
			<li><a href="/forum">Go To Forum</a></li>
			<li><a href="/admin/account">Account</a></li>
			<li><a href="/admin/home/logout">Logout</a></li>
		
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
		<?php
			$services = array('testimonials', 'reviews');
			foreach($services as $link)
				if($service == $link)
					echo "<a href=\"/admin/$link/dashboard\" class=\"active\">$link</a> \n";
				else
					echo "<a href=\"/admin/$link/dashboard\">$link</a> \n";
		?>
		</div>
	</div>

  <div id="content_wrapper">
		<div id="sidebar">	
			<ul>
			<?php 
				if(empty($active))
					$active = 'dashboard';
				foreach(${"menu_$service"} as $data)
				{
					list($name, $link, $text, $class) = $data;
					$class = (empty($class)) ? '' : "class=\"$class\"";
					if($name == $active)
						echo "<li $class><a href=\"$link\" class=\"active\">$text</a></li>\n";
					else
						echo "<li $class><a href=\"$link\">$text</a></li>\n";
				}
			?>
			</ul> 
		</div>
    
		<div id="primary_content">
			<?php if(isset($content)) echo $content?>
		</div>
		
		<div id="footer">© Copyright 2009 PlusPanda =] | <a href="#">Top</a></div>
	</div>
	
</div>

<script type="text/javascript" src="/js/admin"></script>
</body>
</html>


