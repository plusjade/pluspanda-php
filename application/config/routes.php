<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @package  Core
 *
 * Sets the default route to "welcome"
 */
#$config['_default'] = 'welcome';

/*
 * if we are accessing the js or css controller,
 * require .js and .css extension
 */
$parts = explode("/", $_SERVER['REQUEST_URI']);
if(isset($parts[1]) AND ($parts[1] == 'js' OR $parts[1] == 'css'))
{
  $method = str_replace(".{$parts[1]}", '', $parts[2]);
  $config["{$parts[1]}/{$parts[2]}"] = "{$parts[1]}/$method";
} # end






