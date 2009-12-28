<?php
/*
 * replace the stock kohana 404 event with our custom per-site one.
 */
function custom_404()
{
  # this automatically dies to a page or 404
  new Marketing_Controller('nonsense');
}
Event::clear('system.404');
Event::add('system.404', 'custom_404');

/* end */