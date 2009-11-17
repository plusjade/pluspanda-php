<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>standalone</title>

	<link type="text/css" rel="stylesheet" href="/static/css/dev.css" media="screen" />
	<link type="text/css" rel="stylesheet" href="/static/css/static_helpers.css" media="screen" />
	<script type="text/javascript" src="/static/js/live.js" charset="utf-8"></script>
</head>
<body>
	<?php echo $content ?>

	<p class="copyright">
		Rendered in {execution_time} seconds, using {memory_usage} of memory
	</p>
</body>
<script type="text/javascript" src="/static/js/init.js" charset="utf-8"></script>
</html>