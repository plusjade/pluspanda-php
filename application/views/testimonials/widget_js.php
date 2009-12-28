


var pandaTheme = '<?php echo $theme?>';
var pandaApikey = '<?php echo $apikey?>';
var pandaAssetUrl = '<?php echo $asset_url?>';
var pandaHtml = <?php echo $json_html?>;





// cache

/* Event delegation*/
jQuery.delegate = function(rules) {return function(e) { var target = $(e.target); for (var selector in rules) if (target.is(selector)) return rules[selector].apply(this, $.makeArray(arguments));}}

/* For testimonial widget mode =P */
$('head').append('<link type="text/css" href="<?php echo url::site()?>static/testimonials/css/' + pandaTheme + '.css" media="screen" rel="stylesheet" />');

// attach event triggers.
$('body').click($.delegate({
 // sorting links.
  '.panda-testimonials-sorters a' : function(e){  
    var is_sort = e.target.href.indexOf('#');    
    var parent = (-1 == is_sort)
      ? 'a.show-more'
      : '.panda-testimonials-sorters a';
    var spltr = (-1 == is_sort) ? '?' : '#';

    $(parent).removeClass('selected');
    $(e.target).addClass('selected');   
    
    // get GET params from links TOD0: optimize this?
    var hash = e.target.href.split(spltr)[1].split('&');
    var params = {"tag":"all","sort":"newest","page":1};
    for(x in hash){
        var arr = hash[x].split('=');
        params[arr[0]] = arr[1]; 
    }
    
    $('.panda-testimonials-list').empty();
    pandaGetTstmls(params.tag, params.sort, params.page);
    return false;
  },
  'a.show-more' : function(e){  
    var is_sort = e.target.href.indexOf('#');    
    var parent = (-1 == is_sort)
      ? 'a.show-more'
      : '.panda-testimonials-sorters a';
    var spltr = (-1 == is_sort) ? '?' : '#';

    
    // get GET params from links TOD0: optimize this?
    var hash = e.target.href.split(spltr)[1].split('&');
    var params = {"tag":"all","sort":"newest","page":1};
    for(x in hash){
        var arr = hash[x].split('=');
        params[arr[0]] = arr[1]; 
    }
    $(e.target).remove();
    pandaGetTstmls(params.tag, params.sort, params.page);
    return false;
  },  
  
  
}));

// build the initial interface.
$('#plusPandaYes').html('<div class="ajax_loading">Loading...</div>');   
function buildIt() {   
  $('#plusPandaYes').html(pandaHtml.tag_list + '<div class="panda-tag-scope">' + pandaHtml.sorters + '<div class="panda-testimonials-list"></div></div>');
  
  //ajaxify tag select list.  
  $('#panda-select-tags ul a').click(function(e){
    var tag = e.target.hash.substring(1);      
    //highlight the tag link
    $('#panda-select-tags ul a').removeClass('active');
    $(this).addClass('active');
    
    // update sorter links to tag scope.
    $('.panda-testimonials-sorters a').each(function(){
      this.href = '#tag='+tag+'&sort=' + $(this).html().toLowerCase();
    });
    //quickhack to highlight correct sorter.
    $('.panda-testimonials-sorters a').removeClass('selected');
    $('.panda-testimonials-sorters a:first').addClass('selected'); 
    
    $('.panda-testimonials-list').empty();
    // load the testimonials based on selection.
    pandaGetTstmls(tag,'newest',1);
    return false;  
  });
  
  // init getting the data.
  $('#panda-select-tags ul a:first').click();
}
buildIt(); // init the build!

// get the testimonials as json.
function pandaGetTstmls(tag, sort, page){
    $('.panda-testimonials-list').append('<div class="ajax_loading">Loading...</div>');
    
    $.ajax({ 
        type:'GET', 
        url: '<?php echo url::site()?>', 
        data:"apikey="+pandaApikey+"&service=testimonials&tag="+tag+"&sort="+sort+"&page="+page+"&jsoncallback=pandaLoadRev", 
        dataType:'jsonp'
    }); 
}


// --------- jsonp callbacks ------------  

// callback to format and inject testimonials data.
function pandaDisplayTstmls(tstmls){
  var content = '';
  $(tstmls).each(function(){  
    var date = new Date(this.created*1000);
    this.image = pandaAssetUrl + '/' + this.image;
    content  += '<?php echo $item_html?>';
  });
  $('#plusPandaYes .panda-testimonials-list .ajax_loading').replaceWith(content); 
  pandaClean();
}

// callback to display the pagination html.
function pandaPages(nextPage, tag, sort){
  if(!nextPage)
    return false;
    
  var link = '<a href="<?php echo url::site()?>?apikey='+pandaApikey+'&service=testimonials&tag='+tag+'&sort='+sort+'&page='+ nextPage +'" class="show-more">Show More</a>';
  $('.panda-testimonials-list').append(link);
}

// cleanup our jsonp scripts after execution.
function pandaClean(){
  $('head script[src^="<?php echo url::site()?>"]').remove();
}



  
  
  