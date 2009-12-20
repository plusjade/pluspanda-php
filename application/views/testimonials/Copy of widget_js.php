






/* For testimonial widget mode =P */
$('head').append('<?php echo $stylesheet?>');

// attach event triggers.
$('body').click($.delegate({
 // sorting links.
	'.panda-reviews-sorters a, .panda-pagination a' : function(e){	
		var is_sort = e.target.href.indexOf('#');		
		var parent = (-1 == is_sort)
			? '.panda-pagination a'
			: '.panda-reviews-sorters a';
		var spltr = (-1 == is_sort) ? '?' : '#';

		$(parent).removeClass('selected');
		$(e.target).addClass('selected'); 	
		
		// get GET params from links TOD0: optimize this?
		var hash = e.target.href.split(spltr)[1].split('&');
		var params = {"tag":"all","sort":"newest","page":1};
		for(x in hash){
				var arr = hash[x].split('=');
				params[arr[0]] = arr[1]; 
		}
		pandaGetRevs(params.tag, params.sort, params.page);
		return false;
	}
}));

// build the initial interface.
$('#plusPandaYes').html('<div class="ajax_loading">Loading...</div>'); 	
function buildIt() { 
	var html = <?php echo $json_html?>;
	
	// do we display the submission form?
	if('#submit' == window.location.hash){
		$('#plusPandaYes').html(html.form + html.iframe);
	}
	else{
		// display testimonials view.
		//add to DOM
		$('#plusPandaYes').html(html.tag_list + '<div class="panda-tag-scope">' + html.sorters + '<div class="panda-reviews-list"></div></div>');
	}

	//ajaxify tag select list.	
	$('#panda-select-tags ul a').click(function(e){
		var tag = e.target.hash.substring(1);			
		//highlight the tag link
		$('#panda-select-tags ul a').removeClass('active');
		$(this).addClass('active');
		
		// update sorter links to tag scope.
		$('.panda-reviews-sorters a').each(function(){
			this.href = '#tag='+tag+'&sort=' + $(this).html().toLowerCase();
		});
		//quickhack to highlight correct sorter.
		$('.panda-reviews-sorters a').removeClass('selected');
		$('.panda-reviews-sorters a:first').addClass('selected'); 
		
		// load the reviews based on selection.
		pandaGetRevs(tag,'newest',1);
		return false;	
	});
	
	// init getting the data.
	$('#panda-select-tags ul a:first').click();
}
buildIt(); // init the build!

$('#panda-star-rating div').hover(function(){
		var text = {one:'Poor', two:'Lacking', three:'Average', four:'Pretty good!', five:'Fantastic!'};
		var rating = $(this).attr('class').split('-');
		$(this).parent().removeClass().addClass(rating[0]).attr({rel:rating[1]});
		$('.panda-rating-text').html(text[rating[0]]);
});

<?php
/*
 * working as a semi-hack. 
 * we CANT seem to get a response message by posting. 
 * we can get it by sending as JSONP GET.
 * SO if the comment is short enough, we GET, else we POST.
*/
?>
// ajaxify the review submission
$('#panda-add-review').ajaxForm({	 
	target :"#panda-iframe",
	iframe : true,
	url:"<?php echo $url?>", 
	beforeSubmit: function(fields, form){
		var rating = $('#panda-star-rating').attr('rel');	
		if(!rating){alert('Please select a rating'); return false};	
		if(! $("input, textarea", form[0]).jade_validate()) return false;
		$("input:first", form[0]).val(rating);		
		//$('button', form).attr('disabled', 'disabled').html('Submitting...');
		
		/*
		var qstr = form.formSerialize();
		if(7900 > qstr.length){
			$.ajax({ 
					type:'GET', 
					url:"<?php echo $url?>", 
					data: "submit=review&jsoncallback=pandaSubmitRsp&"+qstr, 
					dataType:'jsonp'
			});
		
		}else{
		*/
		// REMEMBER, this gets no response.
		console.log('ajaxForm post sent');
	}
});


// get the reviews as json. and call the function
function pandaGetRevs(tag, sort, page){
		$('.panda-reviews-list').html('<div class="ajax_loading">Loading...</div>');
		$.ajax({ 
				type:'GET', 
				url:"<?php echo $url?>", 
				data:"tag="+tag+"&sort="+sort+"&page="+page+"&jsoncallback=pandaLoadRev", 
				dataType:'jsonp'
		}); 
}

// --------- jsonp callbacks ------------	

// callback to format and inject reviews data.
function pandaDisplayRevs(reviews){
	var content = '';
	$(reviews).each(function(){	
		// format date
		var date = new Date(this.created*1000);
		var image_url = '<?php echo paths::testimonial_image_url(1)?>';
		if('' != this.image) {
			this.image = '<img src="'+image_url +'/'+ this.image + '" />';
		}else {
			this.image = '';
		}
		
		content += '<?php echo $testimonial_html?>';
	});
	$('#plusPandaYes .panda-reviews-list').html(content); 
	pandaClean();
}


// display the pagination html.
function pandaPages(html){
	$('.panda-reviews-list').append(html);
}



// cleanup our jsonp scripts after execution.
function pandaClean(){
	//$('head script[src^="<?php echo $url?>"]').remove();
}

<?php
/*
 -----------------
//TODO cross-site hash should work but doesnt =(
$('iframe').load(function(){
		//alert('it works');
		//console.log(window.frames['panda-iframe']);
		//var hash = window.frames['panda-iframe'].location.hash;
		//alert(hash);
});
*/
?>