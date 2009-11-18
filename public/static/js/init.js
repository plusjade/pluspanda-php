
/* Standalone ajax mode. =] */

//hack to place add-review form in the add-wrapper.
$("#add_review_toggle").after($("#panda-add-review"));
//hide and toggle the add-form
$("#add_review_toggle").click(function() {
	$("#panda-add-review").slideToggle("fast");
	return false;
});
$("#panda-add-review").hide();


//ajaxify tag select form.
$('#panda-select-tags').submit(function(){
	var tag = $('#panda-select-tags select option:selected').val();
	$('.panda-tag-scope').html('<div class="ajax_loading">Loading...</div>');
	$.get('/',{tag:tag},function(data){
		$('.panda-tag-scope').html(data);
		
		// update add-review-form to tag-scope
		$('#panda-add-review select[name="tag"] option').removeAttr('selected');
		$('#panda-add-review select[name="tag"] option[value="'+tag+'"]').attr('selected','selected');
	});
	return false;
});


// attach event triggers.
$('body').click($.delegate({
 //ajaxify the sorters 
	'.panda-reviews-sorters a' : function(e){
		$('.panda-reviews-sorters a').removeClass('selected');
		$(e.target).addClass('selected');
		$('.panda-reviews-list').html('<div class="ajax_loading">Loading...</div>'); 	
		$.get(e.target.href, {output:'reviews'}, function(data){
			$('.panda-reviews-list').html(data); 	
		});
		return false;
	}
}));


// ajaxify the add-review form.
$('form#panda-add-review').ajaxForm({		 
	beforeSubmit: function(fields, form){
		if(! $("input, textarea", form[0]).jade_validate()) return false;
		$('button', form).attr('disabled', 'disabled').html('Submitting...');
	},
	success: function(data) {
		var tag = $('#panda-add-review select[name="tag"] option:selected').val();
		$('.panda-status-msg').remove();
		$('#panda-select-tags').after(data);
		$('#panda-add-review textarea').clearFields();
		$("#panda-add-review").hide();
		
		// load the updated results.
		$('#panda-select-tags select[name="tag"] option').removeAttr('selected');
		$('#panda-select-tags select[name="tag"] option[value="'+tag+'"]').attr('selected','selected');
		$('#panda-select-tags').submit();
		
		$("#panda-add-review button").removeAttr("disabled").html('Submit Review');
	}
}); 