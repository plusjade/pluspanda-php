<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php if(isset($title)) echo $title?> | PlusPanda</title>
	<meta name="description" content="Embedable customer reviews." />
	<link type="text/css" href="/static/home/css/global.css" media="screen" rel="stylesheet" />
	<link type="text/css" href="/static/home/css/forum.css" media="screen" rel="stylesheet" />
	<script type="text/javascript" src="/static/js/jquery.js"></script>
	<script type="text/javascript" src="/static/js/addon.js"></script>
</head>

<body>
<div id="outer">
	<div class="contentwidth main">	
		<a href="/admin/home" id="login-link"><?php echo $login_link?></a>	
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

$(document).ready(function(){

	function formRefresh(){
		// hide post preview
		$('div.post_comment').hide();
		// add friendly time.
		$("abbr[class*=timeago]").timeago();
	};
	formRefresh();
	
	// the main left panel links
	$("#forum_navigation_wrapper").click($.delegate({
		"a": function(e){
			$("#forum_content_wrapper")
			.html("<div class=\"ajax_loading\">Loading...</div>")
			.load(e.target.href, formRefresh);
			return false;				
		}
	}));
		
	$("#forum_content_wrapper").click($.delegate({
		
		// load links into main panel.
		"a.forum_load_main" : function(e){
			$("#forum_content_wrapper")
			.html("<div class=\"ajax_loading\">Loading...</div>")
			.load(e.target.href, formRefresh);
			return false;	
		},
		
		// sort tab actions
		"ul.sort_list a" : function(e){
			$("ul.sort_list a").removeClass("selected");
			var url = $(e.target).addClass("selected").attr("href");
			$("#list_wrapper")
			.html("<div class=\"ajax_loading\">Loading...</div>")
			.load(url, formRefresh);
			return false;
		},
		
		// vote links.
		".cast_vote" : function(e){
			var count = $(e.target).siblings("span").html();
			if(1 == $(e.target).attr("rel"))
				$(e.target).siblings("span").html(++count);
			else
				$(e.target).siblings("span").html(--count);
			
			$(e.target).parent("div").children("a").remove();
			
			//todo show status msg
			$.get(e.target.href, function(data){});
			return false;
		},
		
		// post preview toggle
		"a.preview": function(e){
			var id = $(e.target).attr("rel");
			$("div#preview_"+ id).slideToggle("fast");
			return false;
		}
		
	}));
});

</script>

<?php
if(file_exists(DOCROOT . 'tracker.html'))
	include_once(DOCROOT . 'tracker.html');
?>
</body>
</html>