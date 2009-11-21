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
	<div id="top"><a href="/admin?action=logout">Logout</a></div>
	<div id="server_response">
		<span class="rsp"></span>
		<div class="load" style="display:none">
			<strong>Loading...</strong>
		</div>
	</div>
		 
	<div id="header">
		<!--<a href=""><img src="/static/admin/images/panda.png"></a>-->
		<h1>PlusPanda</h1>
	</div>

    <div id="content_wrapper">


   <div id="sidebar">
			<h3>Administration</h3>
			<ul>
				<li><a href="/admin" <?php echo $active['dashboard']?>><img src="/static/admin/images/001_20.png" width="24" height="24" alt="Home" />Dashboard</a></li>
				<li><a href="/admin/categories" <?php echo $active['categories']?>><img src="/static/admin/images/001_43.png" width="24" height="24" alt="Folders" />Categories</a></li>
				<li><a href="/admin/reviews" <?php echo $active['reviews']?>><img src="/static/admin/images/information.png" width="24" height="24" alt="Comments" />Reviews</a></li>
				<li><a href="/admin/customers" <?php echo $active['customers']?>><img src="/static/admin/images/001_14.png" width="24" height="24" alt="Favourites" />Customers</a></li>
				<li><a href="/admin/embed" <?php echo $active['account']?>><img src="/static/admin/images/accept.png" width="24" height="24" alt="Embed Code" />Embed Codes</a></li>
				<li><a href="/admin/account" <?php echo $active['account']?>><img src="/static/admin/images/001_42.png" width="24" height="24" alt="Account" />Account</a></li>
				<li><a href="/admin?action=logout" <?php echo $active['account']?>><img src="/static/admin/images/delete.png" width="24" height="24" alt="Logout" />Logout</a></li>
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
