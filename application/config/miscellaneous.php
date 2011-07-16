<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Name of the site */
$config['site_name'] = 'portal';

/* TRUE for open. FALSE for closed */
/* TODO: Page to show if site is closed */
$config['site_open'] = TRUE;

/* Portal version */
/* &#120572; - alpha <> &#120573; - beta */
$config['portal_version'] = '0.6&#120572;';


$config['root'] = $_SERVER['DOCUMENT_ROOT'];
/*$config['javascript_location'] = $config['base_url'] . 'js/jquery-1.5.2.min.js';
$config['javascript_ajax_img'] = $config['base_url'] . 'img/ajax-loader.gif';*/

/* Set correct timezone, otherwise date helper is fucked. */
date_default_timezone_set('Europe/Tallinn');
