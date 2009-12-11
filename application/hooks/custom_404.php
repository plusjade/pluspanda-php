<?php
/*
 * replace the stock kohana 404 event with our custom per-site one.
 */
function custom_404()
{
	$home = new Home_Controller('nonsense');
	die($home->_custom_404());
}
Event::clear('system.404');
Event::add('system.404', 'custom_404');

/* end */