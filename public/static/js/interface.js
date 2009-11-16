
		
// attach event triggers.
	$('body').click($.delegate({
		'.panda-reviews-sorters a' : function(e){
			$('.panda-reviews-sorters a').removeClass('selected');
			$(e.target).addClass('selected');
			$('.panda-reviews-list').html('Loading...=D'); 	
			var sort = $(e.target).html();
			pandaLoadReviews(sort);
		}
	}));
	
// build the initial interface.
	$('#plusPandaYes').html('Loading...'); 	
	function buildIt() { 	
		var sorters = '';
		sorters = '<ul class="panda-reviews-sorters">';
		sorters += '<li><a href="#sort=newest">Newest</a></li>';
		sorters += '<li><a href="#sort=oldest">Oldest</a></li>';
		sorters += '<li><a href="#sort=highest">Highest</a></li>';
		sorters += '<li><a href="#sort=lowest">Lowest</a></li>';
		sorters += '</ul>';	
		var add_form = "<h3>Add Review</h3>";
		add_form += "<form action='http://test.localhost.net#yahboi' target='panda-iframe' method='post' id='add-review' class='ajax_Form'>";
		add_form += "<fieldset>Regarding:<select name='tag'></select></fieldset>";
		add_form += "<fieldset><label>Rating <span class='jade_required_star'>*</span></label><select name='rating'><option value='1'>-1-</option><option value='2'>-2-</option><option value='3'>-3-</option><option value='4'>-4-</option><option value='5'>-5-</option></select></fieldset>";
		add_form += "<fieldset><label>Comments <span class='jade_required_star'>*</span></label><textarea name='body' rel='text_req'></textarea></fieldset>";
		add_form += "<fieldset><label>Display Name <span class='jade_required_star'>*</span></label><input type='text' name='display_name' value='' rel='text_req'/></fieldset>";
		add_form += "<fieldset><label>Email <span class='jade_required_star'>*</span></label><input type='text' name='email' value='' rel='email_req'/></fieldset>";
		add_form += "<fieldset><button type='submit'>Submit Review</button></fieldset>";
		add_form += "</form>";

		// emulate dynamic objest for category tags.
		var tags = new Array();
		var tag0 = {"val":"all", "name":"All"};
		var tag1 = {"val":1, "name":"Customer Experience"};
		var tag2 = {"val":2, "name":"Jun"};	
		tags.push(tag0);
		tags.push(tag1);
		tags.push(tag2);

		// setup dynamic tag cats from object.
		var options = '';
		$(tags).each(function(){
			options += '<option value="' + this.val + '">' + this.name + '</option>';
		});
	
		// setup the iframe.
		var $iframe = ('<iframe name="panda-iframe" id="panda-iframe" style="display:none"> </iframe>');

		// add everything to DOM.
		$('#plusPandaYes')
		.html(sorters + add_form + '<div class="panda-reviews-list"></div>')
		.prepend($iframe);
		$('select[name="tag"]').html(options);
		$('.panda-reviews-sorters a:first').click();
	}	
	
// init the build!
	buildIt();

	

	
	
// ******** ajaxify the review submission ********
	/*
	 * working as a semi-hack. 
	 * we CANT seem to get a response message by posting. 
	 * we can get it by sending as jsonp GET.
	 * SO if the comment is short enough, we GET, else we POST.
	*/
	$('#add-review').ajaxForm({	 
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
						dataType:'jsonp'
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

 // retrieves the reviews as json.
	function pandaLoadReviews(sort) 
	{
			$.ajax({ 
					type:'GET', 
					url:"http://test.localhost.net", 
					data:"tag=all&sort=" + sort + "&format=json&jsoncallback=pandaLoadRev", 
					dataType:'jsonp'
			}); 
	}
	
	
	
// --------- JSONP callbacks ------------	

// jsonp callback to format and inject reviews data.
	function pandaLoadRev(reviews) {
		var content = '';
		$(reviews).each(function(){	
			// format date
			var date = new Date(this.created*1000);   
			content += '<div class="review-rating">Rating: <b>'+ this.rating +'</b></div> <div class="review-body">'+ this.body +'</div> <div class="review-name">' + $.timeago(date) + ' -- ' + this.display_name +'</div>';			
		});
		$('#plusPandaYes .panda-reviews-list').html(content); 	
	}


// callback for submitting a review.
	// response is an object with code and msg
	function pandaSubmitRev(rsp){
		console.log(rsp);
		if(5 == rsp.code){
			// some error
		}
		else if(1 == rsp.code){
			$('.panda-reviews-sorters a:first').click();
		}
		
		$('#add-review').html(rsp.msg);
	};
	
	
	
	
//-----------------------------------
	
	/*
	//TODO cross-site hash should work but doesnt =(
	$('iframe').load(function(){
			//alert('it works');
			//console.log(window.frames['panda-iframe']);
			//var hash = window.frames['panda-iframe'].location.hash;
			//alert(hash);
	});
	*/
	