
$(document).ready(function(){
    

  $('a[rel*=facebox]').live('click',function(){
    $.facebox({ ajax: this.href });
    return false;
  });
  //$('a[rel*=facebox]').facebox();
  $('abbr.timeago').timeago();      
        
        
  $('body').click($.delegate({
  //main panel links
    '#sidebar ul li.ajax a' : function(e){
      $(document).trigger('submit.server');
      $('#primary_content').html('Loading...');
      $('#sidebar ul li a').removeClass('active');
      $(e.target).addClass("active");    
      $.get(e.target.href, function(data){
        $('#primary_content').html(data);
        $(document).trigger('rsp.server');
      });
      return false;
    },
    '.review-wrapper img' : function(e){
      var id = $(e.target).attr('rel');
      $('.flag-review.helper').remove();
      $('.flag-review input:first').val(id);

      $('.flag-review').clone().addClass('helper')
        .insertBefore($('table#review-'+ id));
    },
    
   // Save page sort order
    '#save_order' : function(e){
      var order = $("#sortable").sortable("serialize");
      if(!order){alert("No items to sort");return false;}
      
      $(document).trigger('submit.server');
      $.get("/admin/categories/order?"+order, function(data){
        $(document).trigger('rsp.server', data);
      })    
      return false;
    },
   // save the cat params.
    // we cant use ajaxForm cuz i think we should be needing to delegate.
    '.cat-save button' : function(e){
      var catId = $(e.target).attr('rel');
      var url = $('#cat-'+catId+' form').attr('action');
      if(! $("input, textarea", $('#cat-'+catId+' form')).jade_validate()) return false;
      var params = $('#cat-'+catId+' form').formSerialize();
      $(document).trigger('submit.server');
      $.post(url, params, function(data){
        $(document).trigger('rsp.server', data);
      });
      return false;
    },
   // delete a category.
    '.cat-delete a' : function(e){
      if(confirm('This cannot be undone!! Delete this category?')){
        $(document).trigger('submit.server');
        $.get(e.target.href, function(rsp){
          $(e.target).parent('div').parent('form').parent('li').remove();
          $(document).trigger('rsp.server', rsp);
        });
      }
      return false;
    },

    //testimonial page.
    
    // add a new testimonial profile.
    '#add-testimonial button' : function(e){
      $('#add-testimonial').ajaxSubmit({
        beforeSubmit : function(fields, form){
          if(!$("input", form[0]).jade_validate()) return false;
        },
        success : function(data){
          alert(data);
        }
      });
      return false;
    },
    
    // load the edit view into the bottom container
    '.admin-new-testimonials-list table td.name a' : function(e){  
      $('.edit-window').html('Loading...');
      $.get(e.target.href, function(data){
        $('.edit-window').html(data);
        // if body is empty , append all question data.
        if('' == $('.testimonial-body textarea').val()){
          var content = '';
          $('.questions-wrapper p').each(function(){
            content += $.trim($(this).html()) + ' ';
          });
          $('.testimonial-body textarea').val(content);
        }
        // ajaxify the testimonial save form.
        $('#save-testimonial').ajaxForm({   
          beforeSubmit: function(fields, form){
            $(document).trigger('submit.server');
          },
          success: function(rsp){
            var val = $('.panda-image input').val();
            $('.panda-image input').val('');
            if(val){
              var ext = val.substring(val.lastIndexOf('.'));
              if(ext){
                var imgUrl = $('.t-details .image').attr('rel') + ext + '?r=' + new Date().getTime();
                newImg = new Image(); 
                newImg.src = imgUrl;
                $('.t-details .image').html('<img src="'+ newImg.src +'">');
              }
            }
            $(document).trigger('rsp.server', rsp);
          }
        });
      });
      return false;
    },
    
    // delete a testimonial
    '.t-data td.delete a' : function(e){
      if(confirm('This cannot be undone! Delete testimonial?')){
        $(document).trigger('submit.server');
        $.get(e.target.href, function(rsp){
            $(document).trigger('rsp.server', rsp);
        });
      }
      return false;
    }
    
  }));

  

  
  
  
  
  
  
}); // end document ready




