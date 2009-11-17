
$("#add_review_toggle").after($("#panda-add-review"));
$("#add_review_toggle").click(function() {
	$("#panda-add-review").slideToggle("fast");
	return false;
});
$("#panda-add-review").hide();

//ajaxify the tag selector.
$('#panda-select-tags').submit(function(){
	var tag = $('#panda-select-tags select option:selected').val();
	$('.panda-tag-scope').html('<div class="ajax_loading">Loading...</div>');
	$.get('/',{tag:tag},function(data){
		$('.panda-tag-scope').html(data);
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



$('form#panda-add-review').ajaxForm({		 
	beforeSubmit: function(fields, form){
		if(! $("input, textarea", form[0]).jade_validate()) return false;
		/*
		$(fields).each(function() {
			$('#supa_injector em#qwz_' + this.name).replaceWith(this.value);
		});
		$('#panda-add-review').html('<div class="ajax_loading">Loading...</div>');
		*/
		//return false;
	},
	success: function(data) {
		console.log(data);
		$('#panda-add-review').replaceWith(data);
		// todo: this must be done only on success.
		$('#supa_injector').show();
		$('#add_review_toggle').remove();
	}
}); 