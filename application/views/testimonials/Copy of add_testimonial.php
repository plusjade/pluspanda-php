<?php
	$testimonial->body = (empty($testimonial->body))
		? "Hello this is your testimonial!\nOur survey questions below will guide you easily along.\nPlease answer them, but also feel free to write your own freeform testimonial right in this box.\n\nClear this text when you are ready!\nHave fun!"
		: $testimonial->body;
		
	$image_src = (empty($testimonial->image))
		? '/static/images/sample-thumb.jpg'
		: "$image_url/$testimonial->image";
?>

<div class="client-add-wrapper">
<form action="" enctype="multipart/form-data" method="POST" id="panda-add-review">

	Hello, <?php echo $testimonial->customer->name?>, Thanks for your help!
			
	<button class="submit-button" type="submit">Save Changes</button>		

	<fieldset class="panda-image">
		Upload new headshot or logo <input type="file" name="image" />
	</fieldset>	
	
	<div class="t-view">
		<div class="t-details">
			<a href="<?php echo "$url&a=crop&image=$testimonial->image"?>" rel="facebox">Re-Crop Image</a>
	
			<div class="image">
				<img src="<?php echo $image_src?>"/>
			</div>
			
			<span class="label">Full Name</span>
			<span class="t-name">
				<input name="name" value="<?php echo $testimonial->customer->name?>" />
			</span>
			
			<span class="label">Position at Company</span>
			<span class="t-position">
				<input name="position" value="<?php echo $testimonial->customer->position?>" />
			</span>
			
			<span class="label">Company</span>
			<span class="t-company">
				<input name="company" value="<?php echo $testimonial->customer->company?>" />
			</span>
			
			<span class="label">Location</span>
			<span class="t-location">
				<input name="location" value="<?php echo $testimonial->customer->location?>" />
			</span>
			
			<span class="label">Website</span>
			<span class="t-url">
				http://<input name="url" value="<?php echo $testimonial->customer->url?>" />
			</span>
		</div>
		
		<div class="t-content">
			<div class="t-rating-wrapper">
				<span style="display:none">
					<input type="hidden" name="rating" value="<?php echo $testimonial->rating?>">
					<?php echo common::rating_select_nice($testimonial->rating)?>
				</span>				
				<div class="rating-fallback">
					Select a rating <?php echo common::rating_select_dropdown($testimonial->rating);?>
				</div>
			</div>
			
			<div class="t-body">
				<textarea name="body"><?php echo $testimonial->body?></textarea>
			</div>
			
			<div class="t-date"><?php echo build::timeago($testimonial->created)?></div>
		
			<div class="t-tag">
				<?php 
					echo build_testimonials::tag_select_list(
						$tags,
						$testimonial->tag->id,
						array('0'=>'(Select Category)')
					);
				?>
			</div>
			
		</div>
	</div>		
			
	<h1>Survey Questions</h1>
	<div class="t-questions">	
<?php foreach($questions as $question):?>
		<fieldset>
			<label><?php echo $question->title?></label>
			<div class="info"><?php echo $question->info?></div>
			<textarea name="info[<?php echo $question->id?>]"><?php if(isset($info["$question->id"])) echo $info["$question->id"]?></textarea>
		</fieldset>
<?php endforeach;?>
	</div>
	
</form>
</div>

<div class="slider-wrap">
	<div id="qs" class="csw">
		<div class="panelContainer">
		
			<div class="panel" title="1">
				<div class="wrapper">
					<h3>Panel 1</h3>
					<p>Coda-Slider v1.1 by Niall Doherty.</p>
					<p>For info and usage instructions please see <a href="http://www.ndoherty.com/coda-slider/">ndoherty.com</a></p>
					<p>Sed eu ligula eget eros vulputate tincidunt. Etiam sapien urna, auctor a, viverra sit amet, convallis a, enim. Nullam ut nulla. Nam laoreet massa aliquet tortor. Mauris in quam ut dui bibendum malesuada. Nulla vel erat. Pellentesque metus risus, aliquet eget, eleifend in, ultrices vitae, nisi. Vivamus non nulla. Praesent ac lacus. Donec augue turpis, convallis sed, lacinia et, vestibulum nec, lacus. Suspendisse feugiat semper nunc. Donec nisl elit, varius sed, sodales volutpat, commodo in, elit. Proin ornare hendrerit lectus. Sed non dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis suscipit. Mauris egestas tincidunt lectus. Phasellus sed quam et velit laoreet pretium. Nunc metus.</p>
					<p><a href="#5" class="cross-link" title="Go to Panel 5">&#171; Previous</a> | <a href="#2" class="cross-link" title="Go to Panel 2">Next &#187;</a></p>
				</div>
			</div>
			
			<div class="panel" title="1">
				<div class="wrapper">
					<h3>Panel 1</h3>
					<p>Coda-Slider v1.1 by Niall Doherty.</p>
					<p>For info and usage instructions please see <a href="http://www.ndoherty.com/coda-slider/">ndoherty.com</a></p>
					<p>Sed eu ligula eget eros vulputate tincidunt. Etiam sapien urna, auctor a, viverra sit amet, convallis a, enim. Nullam ut nulla. Nam laoreet massa aliquet tortor. Mauris in quam ut dui bibendum malesuada. Nulla vel erat. Pellentesque metus risus, aliquet eget, eleifend in, ultrices vitae, nisi. Vivamus non nulla. Praesent ac lacus. Donec augue turpis, convallis sed, lacinia et, vestibulum nec, lacus. Suspendisse feugiat semper nunc. Donec nisl elit, varius sed, sodales volutpat, commodo in, elit. Proin ornare hendrerit lectus. Sed non dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis suscipit. Mauris egestas tincidunt lectus. Phasellus sed quam et velit laoreet pretium. Nunc metus.</p>
					<p><a href="#5" class="cross-link" title="Go to Panel 5">&#171; Previous</a> | <a href="#2" class="cross-link" title="Go to Panel 2">Next &#187;</a></p>
				</div>
			</div>
			
		</div>
	</div>
</div>


<div class="slider-wrap">
	<div id="slider1" class="csw">
		<div class="panelContainer">
		
			<div class="panel" title="1">
				<div class="wrapper">
					<h3>Panel 1</h3>
					<p>Coda-Slider v1.1 by Niall Doherty.</p>
					<p>For info and usage instructions please see <a href="http://www.ndoherty.com/coda-slider/">ndoherty.com</a></p>
					<p>Sed eu ligula eget eros vulputate tincidunt. Etiam sapien urna, auctor a, viverra sit amet, convallis a, enim. Nullam ut nulla. Nam laoreet massa aliquet tortor. Mauris in quam ut dui bibendum malesuada. Nulla vel erat. Pellentesque metus risus, aliquet eget, eleifend in, ultrices vitae, nisi. Vivamus non nulla. Praesent ac lacus. Donec augue turpis, convallis sed, lacinia et, vestibulum nec, lacus. Suspendisse feugiat semper nunc. Donec nisl elit, varius sed, sodales volutpat, commodo in, elit. Proin ornare hendrerit lectus. Sed non dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis suscipit. Mauris egestas tincidunt lectus. Phasellus sed quam et velit laoreet pretium. Nunc metus.</p>
					<p><a href="#5" class="cross-link" title="Go to Panel 5">&#171; Previous</a> | <a href="#2" class="cross-link" title="Go to Panel 2">Next &#187;</a></p>
				</div>
			</div>
			
			<div class="panel" title="2">
				<div class="wrapper">
					<h3>Panel 2</h3>
					<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus porta tortor sed metus. Nam pretium. Sed tempor. Integer ullamcorper, odio quis porttitor sagittis, nisl erat tincidunt massa, eu eleifend eros nibh sollicitudin est. Nulla dignissim. Mauris sollicitudin, arcu id sagittis placerat, tellus mauris egestas felis, eget interdum mi nibh vel lorem. Aliquam egestas hendrerit massa. Suspendisse sed nunc et lacus feugiat hendrerit. Nam cursus euismod augue. Aenean vehicula nisl eu quam luctus adipiscing. Nunc consequat justo pretium orci. Mauris hendrerit fermentum massa. Aenean consectetuer est ut arcu. Aliquam nisl massa, blandit at, accumsan sed, porta vel, metus. Duis fringilla quam ut eros.</p>
					<p>Sed eu ligula eget eros vulputate tincidunt. Etiam sapien urna, auctor a, viverra sit amet, convallis a, enim. Nullam ut nulla. Nam laoreet massa aliquet tortor. Mauris in quam ut dui bibendum malesuada. Nulla vel erat. Pellentesque metus risus, aliquet eget, eleifend in, ultrices vitae, nisi. Vivamus non nulla. Praesent ac lacus. Donec augue turpis, convallis sed, lacinia et, vestibulum nec, lacus. Suspendisse feugiat semper nunc. Donec nisl elit, varius sed, sodales volutpat, commodo in, elit. Proin ornare hendrerit lectus. Sed non dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis suscipit. Mauris egestas tincidunt lectus. Phasellus sed quam et velit laoreet pretium. Nunc metus.</p>
					<p><a href="#1" class="cross-link" title="Go to Panel 1">&#171; Previous</a> | <a href="#3" class="cross-link" title="Go to Panel 3">Next &#187;</a></p>
				</div>
			</div>		
			<div class="panel" title="Panel 3">
				<div class="wrapper">
					<h3>Panel 3</h3>
					<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus porta tortor sed metus. Nam pretium. Sed tempor. Integer ullamcorper, odio quis porttitor sagittis, nisl erat tincidunt massa, eu eleifend eros nibh sollicitudin est. Nulla dignissim. Mauris sollicitudin, arcu id sagittis placerat, tellus mauris egestas felis, eget interdum mi nibh vel lorem. Aliquam egestas hendrerit massa. Suspendisse sed nunc et lacus feugiat hendrerit. Nam cursus euismod augue. Aenean vehicula nisl eu quam luctus adipiscing. Nunc consequat justo pretium orci. Mauris hendrerit fermentum massa. Aenean consectetuer est ut arcu. Aliquam nisl massa, blandit at, accumsan sed, porta vel, metus. Duis fringilla quam ut eros.</p>
					<p>Sed eu ligula eget eros vulputate tincidunt. Etiam sapien urna, auctor a, viverra sit amet, convallis a, enim. Nullam ut nulla. Nam laoreet massa aliquet tortor. Mauris in quam ut dui bibendum malesuada. Nulla vel erat. Pellentesque metus risus, aliquet eget, eleifend in, ultrices vitae, nisi. Vivamus non nulla. Praesent ac lacus. Donec augue turpis, convallis sed, lacinia et, vestibulum nec, lacus. Suspendisse feugiat semper nunc. Donec nisl elit, varius sed, sodales volutpat, commodo in, elit. Proin ornare hendrerit lectus. Sed non dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis suscipit. Mauris egestas tincidunt lectus. Phasellus sed quam et velit laoreet pretium. Nunc metus.</p>
					<p><a href="#2" class="cross-link" title="Go to Panel 2">&#171; Previous</a> | <a href="#4" class="cross-link" title="Go to Panel 4">Next &#187;</a></p>
				</div>
			</div>
			<div class="panel" title="Panel 4">
				<div class="wrapper">
					<h3>Panel 4</h3>
					<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus porta tortor sed metus. Nam pretium. Sed tempor. Integer ullamcorper, odio quis porttitor sagittis, nisl erat tincidunt massa, eu eleifend eros nibh sollicitudin est. Nulla dignissim. Mauris sollicitudin, arcu id sagittis placerat, tellus mauris egestas felis, eget interdum mi nibh vel lorem. Aliquam egestas hendrerit massa. Suspendisse sed nunc et lacus feugiat hendrerit. Nam cursus euismod augue. Aenean vehicula nisl eu quam luctus adipiscing. Nunc consequat justo pretium orci. Mauris hendrerit fermentum massa. Aenean consectetuer est ut arcu. Aliquam nisl massa, blandit at, accumsan sed, porta vel, metus. Duis fringilla quam ut eros.</p>
					<p>Sed eu ligula eget eros vulputate tincidunt. Etiam sapien urna, auctor a, viverra sit amet, convallis a, enim. Nullam ut nulla. Nam laoreet massa aliquet tortor. Mauris in quam ut dui bibendum malesuada. Nulla vel erat. Pellentesque metus risus, aliquet eget, eleifend in, ultrices vitae, nisi. Vivamus non nulla. Praesent ac lacus. Donec augue turpis, convallis sed, lacinia et, vestibulum nec, lacus. Suspendisse feugiat semper nunc. Donec nisl elit, varius sed, sodales volutpat, commodo in, elit. Proin ornare hendrerit lectus. Sed non dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis suscipit. Mauris egestas tincidunt lectus. Phasellus sed quam et velit laoreet pretium. Nunc metus.</p>
					<p><a href="#3" class="cross-link" title="Go to Panel 3">&#171; Previous</a> | <a href="#5" class="cross-link" title="Go to Panel 5">Next &#187;</a></p>
				</div>
			</div>
			<div class="panel" title="Panel 5">
				<div class="wrapper">
					<h3>Panel 5</h3>
					<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus porta tortor sed metus. Nam pretium. Sed tempor. Integer ullamcorper, odio quis porttitor sagittis, nisl erat tincidunt massa, eu eleifend eros nibh sollicitudin est. Nulla dignissim. Mauris sollicitudin, arcu id sagittis placerat, tellus mauris egestas felis, eget interdum mi nibh vel lorem. Aliquam egestas hendrerit massa. Suspendisse sed nunc et lacus feugiat hendrerit. Nam cursus euismod augue. Aenean vehicula nisl eu quam luctus adipiscing. Nunc consequat justo pretium orci. Mauris hendrerit fermentum massa. Aenean consectetuer est ut arcu. Aliquam nisl massa, blandit at, accumsan sed, porta vel, metus. Duis fringilla quam ut eros.</p>
					<p>Sed eu ligula eget eros vulputate tincidunt. Etiam sapien urna, auctor a, viverra sit amet, convallis a, enim. Nullam ut nulla. Nam laoreet massa aliquet tortor. Mauris in quam ut dui bibendum malesuada. Nulla vel erat. Pellentesque metus risus, aliquet eget, eleifend in, ultrices vitae, nisi. Vivamus non nulla. Praesent ac lacus. Donec augue turpis, convallis sed, lacinia et, vestibulum nec, lacus. Suspendisse feugiat semper nunc. Donec nisl elit, varius sed, sodales volutpat, commodo in, elit. Proin ornare hendrerit lectus. Sed non dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis suscipit. Mauris egestas tincidunt lectus. Phasellus sed quam et velit laoreet pretium. Nunc metus.</p>
					<p><a href="#4" class="cross-link" title="Go to Panel 4">&#171; Previous</a> | <a href="#1" class="cross-link" title="Go to Panel 1">Next &#187;</a></p>
				</div>
			</div>
			
		</div><!-- .panelContainer -->
	</div><!-- #slider1 -->
</div><!-- .slider-wrap -->


	<style type="text/css">

		a:focus { outline:none }
		
		img { border: 0 }
		
		h3 { border-bottom: 1px solid silver; margin-bottom: 5px; padding-bottom: 3px; text-align: left }
		

		.stripViewer .panelContainer .panel ul {
			text-align: left;
			margin: 0 15px 0 30px;
		}
		
		.slider-wrap { /* This div isn't entirely necessary but good for getting the side arrows vertically centered */
			margin: 20px 0;
			position: relative;
			width: 100%;
		}

		/* These 2 lines specify style applied while slider is loading */
		.csw {width: 100%; height: 460px; background: #fff; overflow: scroll}
		.csw .loading {margin: 200px 0 300px 0; text-align: center}

		.stripViewer { /* This is the viewing window */
			position: relative;
			overflow: hidden; 
			border: 5px solid #000; /* this is the border. should have the same value for the links */
			margin: auto;
			width: 700px; /* Also specified in  .stripViewer .panelContainer .panel  below */
			height: 460px;
			clear: both;
			background: #fff;
		}
		
		.stripViewer .panelContainer { /* This is the big long container used to house your end-to-end divs. Width is calculated and specified by the JS  */
			position: relative;
			left: 0; top: 0;
			width: 100%;
			list-style-type: none;
			/* -moz-user-select: none; // This breaks CSS validation but stops accidental (and intentional - beware) panel highlighting in Firefox. Some people might find this useful, crazy fools. */
		}
		
		.stripViewer .panelContainer .panel { /* Each panel is arranged end-to-end */
			float:left;
			height: 100%;
			position: relative;
			width: 700px; /* Also specified in  .stripViewer  above */
		}
		
		.stripViewer .panelContainer .panel .wrapper { /* Wrapper to give some padding in the panels, without messing with existing panel width */
			padding: 10px;
		}
		
		.stripNav { /* This is the div to hold your nav (the UL generated at run time) */
			margin: auto;
		}
		
		.stripNav ul { /* The auto-generated set of links */
			list-style: none;
		}
		
		.stripNav ul li {
			float: left;
			margin-right: 2px; /* If you change this, be sure to adjust the initial value of navWidth in coda-slider.1.1.1.js */
		}
		
		.stripNav a { /* The nav links */
			font-size: 10px;
			font-weight: bold;
			text-align: center;
			line-height: 32px;
			background: #c6e3ff;
			color: #fff;
			text-decoration: none;
			display: block;
			padding: 0 15px;
		}
		
		.stripNav li.tab1 a { background: #60f }
		.stripNav li.tab2 a { background: #60c }
		.stripNav li.tab3 a { background: #63f }
		.stripNav li.tab4 a { background: #63c }
		.stripNav li.tab5 a { background: #00e }
		
		.stripNav li a:hover {
			background: #333;
		}
		
		.stripNav li a.current {
			background: #000;
			color: #fff;
		}
		
		.stripNavL, .stripNavR { /* The left and right arrows */
			position: absolute;
			top: 230px;
			text-indent: -9000em;
		}
		
		.stripNavL a, .stripNavR a {
			display: block;
			height: 40px;
			width: 40px;
		}
		
		.stripNavL {
			left: 0;
		}
		
		.stripNavR {
			right: 0;
		}
		
		.stripNavL {
			background: url("/static/images/arrow-left.gif") no-repeat center;
		}
		
		.stripNavR {
			background: url("/static/images/arrow-right.gif") no-repeat center;
		}
		
	</style>




