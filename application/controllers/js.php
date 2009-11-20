<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* dynamically build and (should) cache the embeddable widget javascript file.
 */

 class Js_Controller extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

/*
 * build the embeddable widget javascript environment.
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
		$sorters = build::sorters($active_tag, $active_sort,'', 'widget');
		# add review form
		$form = new View('live/add_review');
		$form->active_tag = $active_tag;
		$form->tags = $site->tags->select_list('id','name');
		$form->widget = 'yes';
		
		# build an object to hold the html.
		$html = new StdClass();
		$html->tag_list			= ereg_replace("[\n\r\t]", '', $tag_list);
		$html->add_wrapper	= ereg_replace("[\n\r\t]", '', $add_wrapper);
		$html->summary			= ereg_replace("[\n\r\t]", '', $summary);
		$html->form					= ereg_replace("[\n\r\t]", '', $form->render());
		$html->sorters			= ereg_replace("[\n\r\t]", '', $sorters);
		$html->iframe				= '<iframe name="panda-iframe" id="panda-iframe" style="display:none"></iframe>';

		# build object to hold status msg views.
		$success	= View::factory('live/status', array('success'=>true))->render();
		$error		= View::factory('live/status', array('success'=>false))->render();
		$status = new StdClass();
		$status->success = ereg_replace("[\n\r\t]", '', $success);
		$status->error	 = ereg_replace("[\n\r\t]", '', $error);
		
		# load the widget_js view and place the html as json.
		$widget_js = new View('js/widget_js');
		$widget_js->url = 'http://' . $this->site_name . '.' . ROOTDOMAIN .'/api';
		$widget_js->json_html = json_encode($html);
		$widget_js->json_status = json_encode($status);
		
		# output as javascript.
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: text/javascript');	
		
		die($widget_js);
		#echo kohana::debug($json_html);
		#echo kohana::debug($html);
	}
	
} // End js Controller
