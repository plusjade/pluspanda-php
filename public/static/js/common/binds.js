
// show server response.
$(document).bind('rsp.server', function(e, rsp){
  $('#server_response .load').hide();
  if(!rsp) return false;
  $('<div></div>')
    .addClass(rsp.status)
    .html(rsp.msg)
    .appendTo($('#server_response .rsp'));
  setTimeout('$("#server_response span div").fadeOut(4000)', 1500);
});
// show submit icon
$(document).bind('submit.server', function(e, data){
  $('#server_response .rsp').empty();
  $('#server_response div.load').show();
});
  