

/* Event delegation*/
jQuery.delegate = function(rules) {return function(e) { var target = $(e.target); for (var selector in rules) if (target.is(selector)) return rules[selector].apply(this, $.makeArray(arguments));}}

/* For testimonial widget mode =P */
$('head').append('<link type="text/css" id="pandaTheme" href="' + pandaAssetUrl + '/<?php echo t_paths::css_dir?>/' + pandaTheme + '.css" media="screen" rel="stylesheet" />');

// attach event triggers.
$('body').click($.delegate({
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
  }
}));

// build the initial interface.
$('#plusPandaYes').html('<div class="ajax_loading">Loading...</div>');   
function buildIt() {   
  $('#plusPandaYes').html(pandaStructure);
  
  // ajaxify tag list.  
  $('div.panda-tags ul a').click(function(e){
    var tag = e.target.hash.substring(1);
    // highlight the tag link
    $('div.panda-tags ul a').removeClass('active');
    $(this).addClass('active');

    // load the testimonials based on selection.
    $('div.panda-container').empty();
    pandaGetTstmls(tag,'newest',1);
    return false;  
  });
  // init getting the data.
  $('div.panda-tags ul a:first').click();
  // track
  $.get('<?php echo url::site("track")?>',{key:pandaApikey,url:parent.location.href});
}
buildIt(); // init the build!

// get the testimonials as json.
function pandaGetTstmls(tag, sort, page){
    $('div.panda-container').append('<div class="ajax_loading">Loading...</div>');
    
    $.ajax({ 
        type:'GET', 
        url: '<?php echo url::site()?>', 
        data:"apikey="+pandaApikey+"&service=testimonials&tag="+tag+"&sort="+sort+"&page="+page+"&jsoncallback=pandaLoadTstml", 
        dataType:'jsonp'
    }); 
}


// --------- jsonp callbacks ------------  

// callback to format and inject testimonials data.
function pandaDisplayTstmls(tstmls){
  var content = '';
  var date = new Date();
  $(tstmls).each(function(i){
    this.created = new Date(this.created*1000).toGMTString();
    //this.created = d.getDate() + '/' + (1+d.getMonth()) + '/' + d.getFullYear();
    this.image = pandaAssetUrl + '/<?php echo t_paths::image_dir?>/' + this.image;
    this.url = (0 == this.url.length) ? '' : 'http://' + this.url;
    this.alt = (0 == (i+1) % 2) ? 'even' : 'odd';
    content  += pandaItemHtml(this);
  });
  $('#plusPandaYes div.panda-container .ajax_loading')
    .replaceWith(content);
  pandaInteractions();
  pandaClean();
}

// callback to display the pagination html.
function pandaShowMore(nextPage, tag, sort){
  if(!nextPage)
    return false;
    
  var link = '<a href="<?php echo url::site()?>?apikey='+pandaApikey+'&service=testimonials&tag='+tag+'&sort='+sort+'&page='+ nextPage +'" class="show-more">Show More</a>';
  $('div.panda-container').append(link);
}


/*
 * utilities
 */
 
// bind/unbind javascript interactions based on theme.
function pandaInteractions(){
  $('div.t-details div.image img').unbind();
  var theme = ('' == window.location.hash.substring(1))
    ? pandaTheme
    : window.location.hash.substring(1);
    
  if('gallery' == theme){
    $('div.t-details div.image img').hover(function(){
        $('div.t-content').hide();
        $(this).parent().parent().next('.t-content').toggle();
      },
      function(){
        $('div.t-content').hide();
      });
  }
}

// cleanup our jsonp scripts after execution.
function pandaClean(){
  $('head script[src^="<?php echo url::site()?>"]').remove();
}


