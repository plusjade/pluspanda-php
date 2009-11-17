<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* dynamically build and possibly cache the 
	* javascript widget interface file.
 */

 class Js_Controller extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

/*
 * build the interface javascript environment for embeddable widgets.
 * note: we should be caching the result
 */
	public function widget()
	{
		# default
		$active_tag = 'all';
		$active_sort = 'newest';
		
		# get all the html interfaces.
		$site = ORM::factory('site', $this->site_id);
		$tag_list = build::tag_filter($site->tags, $active_tag);
		$add_wrapper = build::add_wrapper();
		$summary = build::summary(array(1));
		$sorters = build::sorters($active_tag, $active_sort, 'yes');
		# add review form
		$form = new View('add_review');
		$form->active_tag = $active_tag;
		$form->js = 'yes';
		
		# build an object to hold the html.
		$html = new StdClass();
		$html->tag_list			= ereg_replace("[\n\r\t]", '', $tag_list);
		$html->add_wrapper	= ereg_replace("[\n\r\t]", '', $add_wrapper);
		$html->summary			= ereg_replace("[\n\r\t]", '', $summary);
		$html->form					= ereg_replace("[\n\r\t]", '', $form->render());
		$html->sorters			= ereg_replace("[\n\r\t]", '', $sorters);
		$html->iframe				= '<iframe name="panda-iframe" id="panda-iframe" style="display:none"></iframe>';

		# load the interface view and place the html as json.
		$interface = new View('js/interface');
		$interface->json_html = json_encode($html);

		# output as javascript.
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: text/javascript');	
		
		die($interface);
		#echo kohana::debug($json_html);
		#echo kohana::debug($html);
	}
	
} // End js Controller
