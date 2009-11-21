<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Add and Manage Customer Reviews Instantly On Your Website | PlusPanda</title>
	<meta name="description" content="Embedable customer reviews." />
	<link type="text/css" href="/static/home/css/global.css" media="screen" rel="stylesheet" />
	<script type="text/javascript" src="/static/js/jquery.js"></script>
</head>
<body>

<div id="outer">
	<div class="contentwidth main">
		<div class="logo">
				<h1><a href="index.html">PlusPanda</a></h1>
		</div>
		<div class="menu">
				<ul class="clearfix">
<?php
	$links = array(
		'home' => 'Home',
		'start' => 'Get Started',
		'demo' => 'Demo',
		'contact' => 'Contact'
	);
	foreach($links as $url => $text)
		if($active == $url)
			echo "<li><a href=\"/$url\" class=\"active\">$text</a></li>";
		else
			echo "<li><a href=\"/$url\">$text</a></li>";
?>
				</ul>
		</div>    
		<?php echo $content?>
  </div>
    
	<div class="footer">
		<div class="contentwidth">
					<p><strong>&copy; 2009 PlusPanda =]</strong></p>
					<ul class="footer-menu">
							<li><a href="/">Home</a></li>
							<li><a href="/start">Get Started</a></li>
							<li><a href="/demo">Demo</a></li>
							<li><a href="/contact">Contact</a></li>
					</ul>
			</div>
	</div>
</div>

<script type="text/javascript"> 
	//<![CDATA[
	$(document).ready(function(){
		$(".fade-in").hide();
		$(".fade-in-slow").fadeIn(2000);
		$(".fade-in-med").fadeIn(1500);
		$(".fade-in-fast").fadeIn(1000);
	});
	//]]>
</script>

</body>
</html>