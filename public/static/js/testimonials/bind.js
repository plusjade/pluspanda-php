

/* custom event bindings to init our ajaxed environments */


  // show server response.
  $(document).bind('rsp.server', function(e, data){
    $('#server_response .load').hide();
    $('#server_response .rsp').empty().html(data);
    setTimeout('$("#server_response span div").fadeOut(4000)', 1500);  
    return false;
  });
  // show submit icon
  $(document).bind('submit.server', function(e, data){
    $('#server_response .rsp').empty();
    $('#server_response div.load').show();
  });
  
  
/*
 * initialize singular testimonial edit interactions
 */
  $(document).bind('tstml.edit',function(e, data){
    
    // star rating stuff.
    $('.t-content .rating-fallback').remove();
    $('.t-content span').show();
    function pandaUpdateText(rating){
      var text = {1:'Poor', 2:'Lacking', 3:'Average', 4:'Pretty good!', 5:'Fantastic!'};
      $('.panda-rating-text').html(text[rating]);
    }
    $('#panda-star-rating div').hover(function(){
        var rating = $(this).attr('class').substr(1);  
        $(this).parent().removeClass().addClass('_'+rating);
        pandaUpdateText(rating);
      },function(){
        var old_rating = $(this).parent().attr('rel');
        $(this).parent().removeClass().addClass('_'+old_rating);  
        pandaUpdateText(old_rating);
      }
    );
    $('#panda-star-rating div').click(function(){
        var rating = $(this).attr('class').substr(1);  
        $(this).parent().removeClass().addClass('_'+rating).attr({rel:rating});      
        $('.t-rating-wrapper input').val(rating);
        pandaUpdateText(rating);
    });
    
  });
  
/*
 * initialize image crop interactions
 */
  $(document).bind('tstml.crop',function(e, data){
    $('.crop-image img').Jcrop({
      onChange: showPreview,
      onSelect: showPreview,
      aspectRatio: 1
    });
    
    function showPreview(coords){
      if (parseInt(coords.w) > 0){
        var rx = 100 / coords.w;
        var ry = 100 / coords.h;

        $('.crop-preview img').css({
          width: Math.round(rx * 500) + 'px',
          height: Math.round(ry * 370) + 'px',
          marginLeft: '-' + Math.round(rx * coords.x) + 'px',
          marginTop: '-' + Math.round(ry * coords.y) + 'px'
        });
      }
      $('.crop-wrapper button').attr('alt', coords.w +'|'+ coords.h +'|'+ coords.y +'|'+ coords.x);
    };
    
      // testimonial crop submit
    $('.crop-wrapper button').click(function(e){
      var url = $(this).attr('rel');
      var params = $(this).attr('alt');
      if(!params){alert('please select an area');return false;}
      
      $('.crop-msg').html('Saving...');
      $.post(url,{params:params}, function(data){
        $('.crop-msg').html(data);
        newImg = new Image(); 
        newImg.src = e.target.id;
        $('.t-details .image').html('<img src="'+ newImg.src +'">');
        $.facebox.close();
      });
      return false;  
    });
  });