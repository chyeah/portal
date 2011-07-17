<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    /**
     * Base data array.
     * This is where all the variables are stored into.
     **/
    var $data = array();
    
	function __construct()
    {
        parent::__construct();
        
        // Set title, just in case I forget it later.
        $this->data['title']   = $this->config->item('site_name');
        $this->data['version'] = $this->config->item('portal_version');
    }
}