
		
// attach event triggers.
	$('body').click($.delegate({
		'.panda-reviews-sorters a' : function(e){
			$('.panda-reviews-sorters a').removeClass('selected');
			$(e.target).addClass('selected');
			$('.panda-reviews-list').html('<div class="ajax_loading">Loading...</div>'); 	
			var sort = $(e.target).html();
			pandaGetRevs(sort);
		}
	}));
	
// build the initial interface.
	$('#plusPandaYes').html('<div class="ajax_loading">Loading...</div>'); 	
	
	function buildIt() { 
		var html = <?php echo $blah?>
		var $iframe = ('<iframe name="panda-iframe" id="panda-iframe" style="display:none"> </iframe>');

		// add everything to DOM.
		$('#plusPandaYes')
		.html(html.tag_filer + html.sorters + html.add_form + '<div class="panda-reviews-list"></div>')
		.prepend($iframe);
		
		// init getting the data.
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
	function pandaGetRevs(sort) 
	{
			$.ajax({ 
					type:'GET', 
					url:"http://test.localhost.net", 
					data:"tag=all&sort=" + sort + "&format=json&jsoncallback=pandaDisplayRevs", 
					dataType:'jsonp'
			}); 
	}
	
	
	
// --------- JSONP callbacks ------------	

// jsonp callback to format and inject reviews data.
	function pandaDisplayRevs(reviews) {
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
		
		$('#panda-add-review').html(rsp.msg);
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
	