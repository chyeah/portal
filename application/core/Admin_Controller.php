<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends MY_Controller
{
	function __construct()
    {
        parent::__construct();
        
        if(!$this->auth->is_admin())
        {
            $this->session->set_flashdata('error', 'You do not have permission to access that page!');
            redirect('show/posts');
        }
    }
}