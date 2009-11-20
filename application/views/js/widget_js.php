/* For widget mode =P */

// TODO: ADD THE TEXT/CSS FILE TO THE HEAD.

// attach event triggers.
$('body').click($.delegate({
//TODO: combine these two.
 // sorting links.
	'.panda-reviews-sorters a' : function(e){
		$('.panda-reviews-sorters a').removeClass('selected');
		$(e.target).addClass('selected'); 	
		
		// get GET params from links TOD0: optimize this?
		var hash = e.target.href.split('#')[1].split('&');
		var params = {"tag":"all","sort":"newest","page":1};
		for(x in hash){
				var arr = hash[x].split('=');
				params[arr[0]] = arr[1]; 
		}
		pandaGetRevs(params.tag, params.sort, params.page);
	},
 //ajaxify the pagination links.
	'.panda-pagination a' : function(e){
		$('.panda-pagination a').removeClass('selected');
		$(e.target).addClass('selected');

		// get GET params from links TOD0: optimize this?
		var hash = e.target.href.split('?')[1].split('&');
		var params = {"tag":"all","sort":"newest", "page":1};
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
	
	//ajaxify tag select form.	
	$('#panda-select-tags').submit(function(){
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
		window.location.hash = 'tag='+tag+'&sort=newest';
		
		// update add-review-form to tag-scope
		$('#panda-add-review select[name="tag"] option').removeAttr('selected');
		$('#panda-add-review select[name="tag"] option[value="'+tag+'"]').attr('selected','selected');
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
		$('button', form).attr('disabled', 'disabled').html('Submitting...');
		var qstr = form.formSerialize();
		if(7900 > qstr.length){
			$.ajax({ 
					type:'GET', 
					url:"<?php echo $url?>", 
					data: qstr + "&submit=review&jsoncallback=pandaSubmitRsp", 
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


// get the reviews as JSONP.
function pandaGetRevs(tag, sort, page){
		$('.panda-reviews-list').html('<div class="ajax_loading">Loading...</div>');
		$.ajax({ 
				type:'GET', 
				url:"<?php echo $url?>", 
				data:"tag="+tag+"&sort="+sort+"&page="+page+"&jsoncallback=pandaLoadRev", 
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
		content += '<div class="review-rating">Rating: <b>'+ this.rating +'</b></div> <div class="review-tag">re: <span>'+ this.tag_name +'</span></div> <div class="review-body">'+ this.body +'</div> <div class="review-name"><abbr class="timeago">' + $.timeago(date) +'</abbr> by <span>'+ this.display_name +'</span></div>';			
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

// display the pagination html.
function pandaPages(html){
	$('.panda-reviews-list').prepend(html).append(html);
}
/*
 * callback for submitting a review.
 * response is an object with code and msg
 */
function pandaSubmitRsp(rsp){
	var status = <?php echo $json_status?>;
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
		$('#panda-select-tags select[name="tag"] option[value="'+tag+'"]').attr('selected','selected');
		$('#panda-select-tags').submit();
		
		$("#panda-add-review button").removeAttr("disabled").html('Submit Review');
	}
};

// cleanup our jsonp scripts after execution.
function pandaClean(){
	//$('head script[src^="<?php echo $url?>"]').remove();
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
