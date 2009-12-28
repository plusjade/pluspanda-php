<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>PlusPanda Feedback Engine</title>

	<!--  REQUIRED FOR IE6 SUPPORT -->
	<style type="text/css">img, div { behavior: url(iepngfix.htc) }</style> 
	<link href="/static/client/css/global.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="wrapper">
	<div id="server_response">
		<span class="rsp"></span>
		<div class="load" style="display:none">
			<strong>Loading...</strong>
		</div>
	</div>
	
	<div id="content_wrapper">
		<?php if(isset($content)) echo $content?>
		
		<div id="panda-powered">
			<img src="/static/home/images/panda.png" alt="pluspanda logo" />
			<h1>Want to add testimonials to your own site?</h1>
			<h2><a href="<?php echo url::site()?>"><?php echo url::site()?></a></h2>
		</div>
		<div id="footer">© Copyright 2009 PlusPanda =] | <a href="#">Top</a></div>
	</div>
	
</div>

<script type="text/javascript" src="/static/js/jquery.js"></script>
<script type="text/javascript" src="/js/add_testimonials"></script>

<script type="text/javascript">
	
$(document).ready(function(){
		
	$('a[rel*=facebox]').facebox();
	$("div#qs").codaSlider();
	$("div#t-questions").codaSlider();
		
// star rating stuff.
	$('.t-rating-wrapper .rating-fallback').remove();
	$('.t-rating-wrapper span').show();
	function pandaUpdateText(rating){
		var text = {1:'Poor', 2:'Lacking', 3:'Average', 4:'Pretty good!', 5:'Fantastic!'};
		$('.panda-rating-text').html(text[rating]);
	}
	$('#panda-star-rating div').hover(function(){
			var rating = $(this).attr('class').substr(1);	
			$(this).parent().removeClass().addClass('_'+rating);
			pandaUpdateText(rating);
		},function(){
			var old_rating = $(this).parent().attr('rel');
			$(this).parent().removeClass().addClass('_'+old_rating);	
			pandaUpdateText(old_rating);
		}
	);
	$('#panda-star-rating div').click(function(){
			var rating = $(this).attr('class').substr(1);	
			$(this).parent().removeClass().addClass('_'+rating).attr({rel:rating});			
			$('.t-rating-wrapper input').val(rating);
			pandaUpdateText(rating);
	});
	
}); // end document ready

</script>



	
</body>

</html>
