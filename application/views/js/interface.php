
// attach event triggers.
$('body').click($.delegate({
 // sorting links.
	'.panda-reviews-sorters a' : function(e){
		$('.panda-reviews-sorters a').removeClass('selected');
		$(e.target).addClass('selected'); 	
		
		// get GET params from links TOD0: optimize this?
		var hash = e.target.href.split('#')[1].split('&');
		var params = {"tag":"all","sort":"newest"};
		for(x in hash){
				var arr = hash[x].split('=');
				params[arr[0]] = arr[1]; 
		}
		pandaGetRevs(params.tag, params.sort);
	}
}));
	
	
// build the initial interface.
$('#plusPandaYes').html('<div class="ajax_loading">Loading...</div>'); 	
	
function buildIt() { 
	var html = <?php echo $json_html?>;
	//add to DOM
	$('#plusPandaYes').html(html.iframe + html.tag_list + html.add_wrapper + '<div class="panda-tag-scope">' + html.summary + html.form + html.sorters + '<div class="panda-reviews-list"></div></div>');
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
	
	//ajaxify the tag select form.	
	$('#panda-select-tags').submit(function(){
		var tag = $('#panda-select-tags select option:selected').val();			
		// update sorters to tag scope.
		$('.panda-reviews-sorters a').each(function(){
			this.href = '#tag='+tag+'&sort=' + $(this).html().toLowerCase();
		});
		
		//quickhack to highlight correct sorter.
		$('.panda-reviews-sorters a').removeClass('selected');
		$('.panda-reviews-sorters a:first').addClass('selected'); 
		
		pandaGetRevs(tag,'newest');
		window.location.hash = 'tag='+tag+'&sort=newest';
		return false;
	});
}
buildIt(); // init the build!


/*
 ******** ajaxify the review submission ********
 * working as a semi-hack. 
 * we CANT seem to get a response message by posting. 
 * we can get it by sending as JSONP GET.
 * SO if the comment is short enough, we GET, else we POST.
*/
$('#panda-add-review').ajaxForm({	 
	target :"#panda-iframe",
	iframe : true,
	beforeSubmit: function(fields, form){
		if(! $("input, textarea", form[0]).jade_validate()) return false;
		$('button', form)
		.attr('disabled', 'disabled')
		.html('Submitting...');
		
		var qstr = form.formSerialize();
		console.log(qstr);
		
		if(7900 > qstr.length){
			$.ajax({ 
					type:'GET', 
					url:"http://test.localhost.net", 
					data: qstr + "&submit=review&jsoncallback=pandaSubmit", 
					dataType:'JSONP'
			});
			// DON'T post the form!!
			return false;
		}
		else{
			alert('comment was too long to submit via GET. (post is off for now)');
			return false;
		}
		console.log('ajaxForm post sent');
	}
});


// ---------- get updated data ----------

// get the reviews as JSONP.
function pandaGetRevs(tag, sort){
		$('.panda-reviews-list').html('<div class="ajax_loading">Loading...</div>');
		$.ajax({ 
				type:'GET', 
				url:"http://test.localhost.net", 
				data:"tag="+tag+"&sort="+sort+"&format=json&jsoncallback=pandaLoadRev", 
				dataType:'jsonp'
		}); 
}



// --------- JSONP callbacks ------------	

// JSONP callback to format and inject reviews data.
function pandaDisplayRevs(reviews){
	var content = '';
	$(reviews).each(function(){	
		// format date
		var date = new Date(this.created*1000);   
		content += '<div class="review-rating">Rating: <b>'+ this.rating +'</b></div> <div class="review-body">'+ this.body +'</div> <div class="review-name">' + $.timeago(date) + ' -- ' + this.display_name +'</div>';			
	});
	$('#plusPandaYes .panda-reviews-list').html(content); 
	pandaClean();
}

// JSONP callback to update summary data.
function pandaDisplaySum(ratingsDist){
	var total = 0;
	var score_sum = 0;
	var dist = '';
	$.each(ratingsDist, function(rating, tally){
		total += +tally;
		score_sum += +tally*rating;
		dist += rating + ' stars : ('+tally+')<br/>';
	});
	var average = (score_sum/total).toFixed(2);
	
	$('.panda-reviews-summary b').html(average);
	$('.panda-reviews-summary span').html(total);
	$('.panda-reviews-summary p').html(dist);
}

/*
 * callback for submitting a review.
 * response is an object with code and msg
 */
function pandaSubmitRev(rsp){
	console.log(rsp);
	if(5 == rsp.code){
		// some error
	}
	else if(1 == rsp.code){
		$('.panda-reviews-sorters a:first').click();
	}
	
	$('#panda-add-review').html(rsp.msg);
};


// cleanup our jsonp scripts after execution.
function pandaClean(){
	//$('head script[src^="http://test.localhost.net"]').remove();
}


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
