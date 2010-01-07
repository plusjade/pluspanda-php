
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
    
  // flag a review
    '.review-wrapper img' : function(e){
      var id = $(e.target).attr('rel');
      $('.flag-review.helper').remove();
      $('.flag-review input:first').val(id);
      $('.flag-review').clone().addClass('helper')
        .insertBefore($('table#review-'+ id));
    },

    
    
  // common add a category.
    'form#add-cat button' :function(e){
      $('form#add-cat').ajaxSubmit({
        dataType: 'json',
        beforeSubmit: function(fields, form){
          if(! $("input, textarea", form[0]).jade_validate()) return false;
          $('button', form).attr('disabled', 'disabled').html('Submitting...');
          $(document).trigger('submit.server');
        },
        success: function(rsp) {
          $(document).trigger('rsp.server', rsp);
          $('form#add-cat button').removeAttr('disabled').html('Add Category');
          $('form#add-cat').clearFields();
          $('#primary_content').load('/admin/testimonials/tags', function(){
              $('ul#sortable li:last').effect("highlight", {}, 5000);
          });
        }
      });
      return false;
    },
    
  // common save category edits.
    '.cat-save button' : function(e){
      var $form = $(e.target).parent('div').parent('form');
      $form.ajaxSubmit({
        dataType : 'json',
        beforeSubmit : function(fields, form){
          if(!$("input", form[0]).jade_validate()) return false;
          $(document).trigger('submit.server');
        },
        success : function(rsp){
          $(document).trigger('rsp.server', rsp);
          $form.parent('li').hide().fadeIn(600);
        }
      });
      return false;
    },
    
  // common delete a category.
    '.cat-delete a' : function(e){
      if(confirm('This cannot be undone!! Delete this category?')){
        $(document).trigger('submit.server');
        $.get(e.target.href, function(rsp){
          $(document).trigger('rsp.server', rsp);
          $(e.target).parent('div').parent('form').parent('li').remove();
        });
      }
      return false;
    },

  // common save categories/tags sort order.
    '#save_order' : function(e){
      var order = $("#sortable").sortable("serialize");
      if(!order){alert("No items to sort");return false;}
      var url = $(e.target).attr('rel');
      $(document).trigger('submit.server');
      $.get(url, order, function(rsp){
        $(document).trigger('rsp.server', rsp);
      });    
      return false;
    },
    
/*Testimonial Manager */
  // add a new testimonial profile.
    '#add-testimonial button' : function(e){
      $('#add-testimonial').ajaxSubmit({
        dataType : 'json',
        beforeSubmit : function(fields, form){
          if(!$("input", form[0]).jade_validate()) return false;
          $(document).trigger('submit.server');
        },
        success : function(rsp){
          // append new row html
          $.facebox.close();
          $(document).trigger('rsp.server', rsp);
          $("table.t-data tr:first").after(rsp.rowHtml);
          $("tr#tstml_" + rsp.id).effect("highlight", {}, 3000);
          $('abbr.timeago').timeago();
          return false;
        }
      });
      return false;
    },
  // load the edit view into the bottom container
    '.admin-new-testimonials-list table td.name a' : function(e){  
      $('.edit-window').html('Loading...');
      $.get(e.target.href, function(data){
        $('.edit-window').html(data);

        // upload the image given in the file input.
        $('.panda-image input').change(function(){
          var file = $(this).val();
          if(!file)return false;
          var ext = file.substring(file.lastIndexOf('.')).toLowerCase();
          var imgTypes = ['.jpg','.jpeg','.png','.gif','.tiff','.bmp'];
          var valid = false;
          $.each(imgTypes, function(){
            if(this == ext){valid = true; return false;}
          });
          if(!valid){alert('Filetype not supported'); return false};
          
          $(document).trigger('submit.server');
          var url = $('#save-testimonial').attr('action').replace('save', 'save_image');
          $('#save-testimonial').ajaxSubmit({
            dataType: 'json',
            type: 'post',
            url : url,
            success: function(rsp){
              if('success' == rsp.status){
                var imgUrl = $('.t-details .image').attr('rel') + '/' + rsp.image + '?r=' + new Date().getTime();
                newImg = new Image(); 
                newImg.src = imgUrl;
                $('div.t-details a:first').attr('href','/admin/testimonials/manage/crop?image='+rsp.image);
                $('div.t-details .image').html('<img src="'+ newImg.src +'">');
              }
              $(document).trigger('rsp.server', rsp);
              $('.panda-image input').val('');
            }
          });
        });
        // override the normal submit event
        $('#save-testimonial').submit(function(){
          $('#save-testimonial button').click();
          return false;
        });
      });
      return false;
    },
  // save the edit testimonial
    '#save-testimonial button' : function(e){
      $('#save-testimonial').ajaxSubmit({
        dataType : 'json',
        beforeSubmit: function(fields, form){
          $(document).trigger('submit.server');
          // json response acts up when we send a file =/
          $('.panda-image input').attr('disabled','disabled');
        },
        success: function(rsp){
          $('.panda-image input').removeAttr('disabled');
          $(document).trigger('rsp.server', rsp);
          if('success' == rsp.status){
            //update the item row html
            $("tr#tstml_" + rsp.id).replaceWith(rsp.rowHtml);
            $("tr#tstml_" + rsp.id).effect("highlight", {}, 3000);
            $('abbr.timeago').timeago();
          }
        }
      });
      return false;
    },

  // delete a testimonial
    '.t-data td.delete a' : function(e){
      if(confirm('This cannot be undone! Delete testimonial?')){
        $(document).trigger('submit.server');
        $.get(e.target.href, function(rsp){
          $(document).trigger('rsp.server', rsp);
          $(e.target).parent('td').parent('tr').remove();
        });
      }
      return false;
    },
    
  // save display testimonial theme.
    'button#save-theme' :function(e){
      var theme = $('select.switch-theme option:selected').val();
      $(document).trigger('submit.server');
      $.get('/admin/testimonials/display/save',{theme:theme},function(rsp){
        $(document).trigger('rsp.server', rsp);
      });
    }

    
    
  }));



}); // end document ready




