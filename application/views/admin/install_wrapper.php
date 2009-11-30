
<style type="text/css">
textarea {width:100%; height:100px; margin-bottom:10px;}
.install-page div{padding:5px; margin-bottom:5px; font-size:0.9em;}
.install-page .info {margin-bottom:10px; background:#ffffcc;}
</style>

<h2>Install PlusPanda</h2>
<div class="install-page">
	<div class="info">
		Place the install code in the exact place on your page where you want your reviews to be displayed.
	</div>

	<h4>Standard</h4>
	<div>The standard install code is good for all websites!</div>
	<textarea>&lt;div id="plusPandaYes"&gt;&lt;a href="http://<?php echo $this->site_name .'.'. ROOTDOMAIN?>"&gt;View Our Customer Reviews!&lt;/a&gt;&lt;/div&gt;&lt;script type="text/javascript" src="http://<?php echo $this->site_name .'.'. ROOTDOMAIN?>/admin/js/widget" charset="utf-8"&gt;&lt;/script&gt;</textarea>

	<h4>Developer</h4>
	<div>
		Pluspanda uses the jquery javascript framework, which gets loaded by the standard embed code.
		If your website is already using jquery you can use the following code which omits loading jquery again.
	</div>
	<textarea>&lt;div id="plusPandaYes"&gt;&lt;a href="http://<?php echo $this->site_name.'.'. ROOTDOMAIN?>"&gt;View Our Customer Reviews!&lt;/a&gt;&lt;/div&gt;&lt;script type="text/javascript" src="http://<?php echo $this->site_name .'.'. ROOTDOMAIN?>/admin/js/widget?jquery=false" charset="utf-8"&gt;&lt;/script&gt;</textarea>
	
</div>