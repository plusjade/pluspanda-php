<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>PlusPanda Admin Panel</title>

	<!--  REQUIRED FOR IE6 SUPPORT -->
	<style type="text/css">img, div { behavior: url(iepngfix.htc) }</style> 
	<link href="/static/admin/css/global.css" rel="stylesheet" type="text/css" />
	<link href="/static/admin/css/facebox.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/static/js/jquery.js"></script>
	<script type="text/javascript" src="/static/js/jquery.ui.js"></script>
	<script type="text/javascript" src="/static/js/addon.js"></script>
</head>

<body>
<?php
if(isset($login)):
	echo $login;
	echo '</body>';
else:
?>
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
					echo "<a href=\"/admin/home?service=$link\" class=\"active\">$link Manager</a> ";
				else
					echo "<a href=\"/admin/home?service=$link\">$link Manager</a> ";
		?>
		</div>
	</div>

    <div id="content_wrapper">


   <div id="sidebar">
			<!--<h3>Admin Panel</h3>-->			
			<ul>
				<?php 
					if(empty($active))
						$active = 'dashboard';
					foreach(${"menu_$service"} as $data)
					{
						list($name, $link, $text, $class) = $data;
						$class = (empty($class)) ? '' : "class=\"$class\"";
						if($name == $active)
							echo "<li $class><a href=\"$link\" class=\"active\">$text</a></li>";
						else
							echo "<li $class><a href=\"$link\">$text</a></li>";
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
</body>
<script type="text/javascript" src="/static/admin/js/init.js"></script>
<?php endif;?>

</html>
