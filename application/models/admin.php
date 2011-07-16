<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Model
{
	function show_all_users()
    {
        $this->db->select('id, username, email, permission')->from('users');
        
        $this->db->result();
    }
}