


<a href="#" id="add_review_toggle">Add a new Review</a>
<div class="add-review">

	<?php if(isset($errors)) echo val_form::show_error_box($errors);?>

	<form action="" method="post" class="review_form">
	
		Regarding: <select name="tag">
			<option>All</option>
		<?php foreach($site->tags as $tag):?>
			<option value="<?php echo $tag->id?>"><?php echo $tag->name?></option>
		<?php endforeach;?>
		</select>
		
		<?php
		$ratings = array('1'=>'-1-','2'=>'-2-','3'=>'-3-','4'=>'-4-','5'=>'-5-');
		$fields = array(
			'rating' => array('Rating','select','text_req', $ratings),
			'body'	 => array('Comments','textarea','text_req', ''),
			'display_name'	 => array('Display Name','input','text_req', ''),
			'email'	 => array('Email','input','email_req', ''),
		);
		if(!isset($values)) $values = array();
		if(!isset($errors)) $errors = array();
		echo val_form::generate_fields($fields, $values, $errors);
		?>	
		<button type="submit">Submit Review</button>
	</form>
</div>

<div class="review_item" id="supa_injector" style="display:none">
	<div class="review_rating">
		<b>Rating</b> <em id="qwz_rating"></em>/5
	</div>
	<div class="review_body">
		<em id="qwz_body"></em>
	</div>
	<div class="review_name">
		- <i><em id="qwz_name"></em></i>
	</div>
</div>
	
<script type="text/javascript">


function test(data){alert(data)};


		$("#add_review_toggle").click(function() {
			$(".add-review").slideToggle("fast");
		});
		$(".add-review").hide();
		
		$('.review_form').ajaxForm({		 
			beforeSubmit: function(fields, form){
			//	if(! $("input, textarea", form[0]).jade_validate()) return false;
				$(fields).each(function() {
					$('#supa_injector em#qwz_' + this.name).replaceWith(this.value);
				});
				$('.add-review').html('<div class="ajax_loading">Loading...</div>');
			
			
				$.post( 'http://test.localhost.net?callback=?',
								{ param: "data" }, 
								function(data) {
										console.log(data);
								}, 'jsonp'
				);
			
			return false;
			
			
			
			},
			success: function(data) {
				$('.add-review').replaceWith(data);
				// todo: this must be done only on success.
				$('#supa_injector').show();
				$('#add_review_toggle').remove();
			}
		});
</script>
