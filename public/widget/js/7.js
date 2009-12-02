/* For widget mode =P */
$('head').append('<link type="text/css" href="http://panda.com/static/widget/css/gray.css" media="screen" rel="stylesheet" />');

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
	
function changeCat(){
	var tag = $('#panda-select-tags select option:selected').val();			
	// update sorter links to tag scope.
	$('.panda-reviews-sorters a').each(function(){
		this.href = '#tag='+tag+'&sort=' + $(this).html().toLowerCase();
	});
	//quickhack to highlight correct sorter.
	$('.panda-reviews-sorters a').removeClass('selected');
	$('.panda-reviews-sorters a:first').addClass('selected'); 
	
	// load the reviews based on selection.
	pandaGetRevs(tag,'newest',1);

	// update add-review-form to tag-scope
	$('#panda-add-review select[name="tag"] option').removeAttr('selected');
	$('#panda-add-review select[name="tag"] option[value="'+tag+'"]').attr('selected','selected');
	return false;
}
	
// build the initial interface.
$('#plusPandaYes').html('<div class="ajax_loading">Loading...</div>'); 	
function buildIt() { 
	var html = {"tag_list":"<form id=\"panda-select-tags\" action=\"\/\" method=\"GET\">Categories: <select name=\"tag\"><option value=\"all\">All<\/option><option value='15'>bball<\/option><option value='13'>Dj Gigs<\/option><\/select><input type=\"image\" src=\"http:\/\/panda.com\/static\/admin\/images\/magnify.png\" alt=\"Submit button\" style=\"position:relative;top:7px\"><!--<button type=\"submit\"><\/button>--><\/form>","add_wrapper":"<div class=\"panda-add-wrapper\"><a href=\"#panda-add-review\" id=\"add_review_toggle\">+ Add New Review<\/a><\/div>","summary":"<div class=\"panda-reviews-summary\"><table class=\"panda-graph\"><tr><th colspan=\"2\"><b>0.00<\/b> stars based on <span>1<\/span> reviews.<\/th><\/tr><tr><td>0 stars<\/td><td><div rel=\"1\">&#160;<\/div> <span>1<\/span><\/td><\/tr><\/table><\/div>","form":"<form action=\"http:\/\/panda.com\" target=\"panda-iframe\"  method=\"POST\" id=\"panda-add-review\"><input type=\"hidden\" name=\"rating\" value=\"0\" \/><div id=\"panda-star-rating\"><div class=\"one-1\"><\/div><div class=\"two-2\"><\/div><div class=\"three-3\"><\/div><div class=\"four-4\"><\/div><div class=\"five-5\"><\/div><\/div><div class=\"panda-rating-text\">Select a rating.<\/div><fieldset class=\"panda-tag \"><label>Review For <span class=\"jade_required_star\">*<\/span><\/label><select name='tag'><option value='15'>bball<\/option><option value='13'>Dj Gigs<\/option><\/select><\/fieldset><fieldset class=\"panda-body \"><label>Review <span class=\"jade_required_star\">*<\/span><\/label><textarea name='body' rel='text_req'><\/textarea><\/fieldset><fieldset class=\"panda-display_name \"><label>Name <span class=\"jade_required_star\">*<\/span><\/label><input type='text' name='display_name' value='' rel='text_req'\/><\/fieldset><fieldset class=\"panda-email \"><label>Email <span class=\"jade_required_star\">*<\/span><\/label><input type='text' name='email' value='' rel='email_req'\/><\/fieldset><fieldset class=\"panda-submit\"><button type=\"submit\">Submit Review<\/button><\/fieldset><\/form>","sorters":"<ul class=\"panda-reviews-sorters\"><li>Sort by:<\/li><li><a href=\"#sort=newest\" class=\"selected\">Newest<\/a><\/li><li><a href=\"#sort=oldest\">Oldest<\/a><\/li><li><a href=\"#sort=highest\">Highest<\/a><\/li><li><a href=\"#sort=lowest\">Lowest<\/a><\/li><\/ul>","iframe":"<iframe name=\"panda-iframe\" id=\"panda-iframe\" style=\"display:none\"><\/iframe>"};
	//add to DOM
	$('#plusPandaYes').html(html.iframe + html.add_wrapper + html.tag_list + '<div class="panda-tag-scope">' + html.summary + html.form + html.sorters + '<div class="panda-reviews-list"></div></div>');
	// init getting the data.
	$('.panda-reviews-sorters a:first').click();
	
	// hack to put the add-form in the add-wrapper.
	$("#add_review_toggle").after($("#panda-add-review"));
	//hide and slide the add-form.
	$("#add_review_toggle").click(function() {
		$("#panda-add-review").slideToggle("fast");
		return false;
	});
	$("#panda-add-review").hide();
	
	//ajaxify tag select form.	
	$('#panda-select-tags select').change(changeCat);
	$('#panda-select-tags').submit(changeCat);
}
buildIt(); // init the build!


$('#panda-star-rating div').hover(function(){
		var text = {one:'Poor', two:'Lacking', three:'Average', four:'Pretty good!', five:'Fantastic!'};
		var rating = $(this).attr('class').split('-');
		$(this).parent().removeClass().addClass(rating[0]).attr({rel:rating[1]});
		$('.panda-rating-text').html(text[rating[0]]);
});


// ajaxify the review submission
$('#panda-add-review').ajaxForm({	 
	target :"#panda-iframe",
	iframe : true,
	beforeSubmit: function(fields, form){
		var rating = $('#panda-star-rating').attr('rel');	
		if(!rating){alert('Please select a rating'); return false};	
		if(! $("input, textarea", form[0]).jade_validate()) return false;
		$("input:first", form[0]).val(rating);		
		//$('button', form).attr('disabled', 'disabled').html('Submitting...');
		var qstr = form.formSerialize();
		if(7900 > qstr.length){
			$.ajax({ 
					type:'GET', 
					url:"http://panda.com?apikey=7", 
					data: "submit=review&jsoncallback=pandaSubmitRsp&"+qstr, 
					dataType:'jsonp'
			});
			return false; // DON'T post the form!!
		}else{
			alert('comment was too long to submit via GET. (post is off for now)');
			return false;
		}
		console.log('ajaxForm post sent');
	}
});


// get the reviews as json.
function pandaGetRevs(tag, sort, page){
		$('.panda-reviews-list').html('<div class="ajax_loading">Loading...</div>');
		$.ajax({ 
				type:'GET', 
				url:"http://panda.com?apikey=7", 
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
		content += '<div class="review-wrapper"><div class="review-name">Review by <span>'+ this.display_name +'</span></div><div class="review-rating _'+ this.rating +'">&#160;</div><div class="review-body">'+ this.body +'</div> <div class="review-tag"><span>'+ this.tag_name +'</span></div> <div class="review-date"><abbr class="timeago">' + $.timeago(date) +'</abbr></div></div>';			
	});
	$('#plusPandaYes .panda-reviews-list').html(content); 
	pandaClean();
}

// callback to update summary data.
function pandaDisplaySum(ratingsDist){
	$('table.panda-graph tr:not(:first)').remove();
	var total = 0;
	var score_sum = 0;
	var maxValue = 0;
	$.each(ratingsDist, function(rating, tally){
		total += +tally;
		score_sum += +tally*rating;
		maxValue = Math.max(maxValue, tally);
	});
	var average = (score_sum/total).toFixed(2);
	average = isNaN(average) ? 0 : average;
	$('.panda-reviews-summary b').html(average);
	$('.panda-reviews-summary span').html(total);

	$.each(ratingsDist, function(rating, tally){
		var width = tally/maxValue*400;
		var bar = '<tr><td>'+ rating +' stars</td><td><div style="width:'+ width +'px"></div><span>'+ tally +'</span></td></tr>';
		$('table.panda-graph').append(bar);
	});
}

// display the pagination html.
function pandaPages(html){
	$('.panda-reviews-list').append(html);
}

// callback for submitting a review. response is an object with code and msg
function pandaSubmitRsp(rsp){
	var status = {"success":"<div class=\"panda-status-msg\"><div class=\"jade_form_status_box box_positive\">Your review has been submitted successfuly. <br\/>Thank you! =)<\/div><\/div>","error":"<div class=\"panda-status-msg\"><div class=\"jade_form_status_box box_negative\">There was a problem submitting the review. =( <br\/>Please try again later. Sorry!<\/div><\/div>"};
	if(5 == rsp.code){
		$('#panda-select-tags').after(status.error);
	}
	else if(1 == rsp.code){
		$('.panda-status-msg').remove();
		$('#panda-select-tags').after(status.success);
		$('#panda-add-review textarea').clearFields();
		$("#panda-add-review").hide();
		// load the updated results.
		var tag = $('#panda-add-review select[name="tag"] option:selected').val();
		$('#panda-select-tags select[name="tag"] option').removeAttr('selected');
		$('#panda-select-tags select[name="tag"] option[value="'+ tag +'"]').attr('selected','selected');
		$('#panda-select-tags select').change();
		$("#panda-add-review button").removeAttr("disabled").html('Submit Review');
	}
	setTimeout('$(".panda-status-msg").fadeOut(4000)', 1200);	
};

// cleanup our jsonp scripts after execution.
function pandaClean(){
	//$('head script[src^="http://panda.com?apikey=7"]').remove();
}


//cached 12.01.09 6:19am America/Los_Angeles