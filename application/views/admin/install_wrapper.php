
<style type="text/css">
textarea {width:100%; height:100px; margin-bottom:10px;}
.install-page div{padding:5px; margin-bottom:5px; font-size:0.9em;}
.install-page .info {margin-bottom:10px; background:#ffffcc;}
</style>

<h2>Install PlusPanda</h2>
<div class="install-page">
	<div class="info">
		Place the install code in the exact place on your page where you want your widget to be displayed.
	</div>

	<h4>Standard</h4>
	<div>The standard install code is good for all websites!</div>
	<textarea><?php echo $embed_code?></textarea>

	<h4>Developer</h4>
	<div>
		Pluspanda uses the jquery javascript framework, which gets loaded by the standard embed code.
		If your website is already using jquery you can use the following code which omits loading jquery again.
	</div>
	<textarea><?php echo $embed_code_lite?></textarea>

</div>