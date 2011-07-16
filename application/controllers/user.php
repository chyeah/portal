<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

class User extends Public_Controller
{
    var $data;
    
	function index()
    {
        if($this->auth_model->is_logged_in())
        {
            $this->profile($this->auth_model->who_am_i());
        }
        else
        {
            $this->login();
        }
    }
    
    function login()
    {
        if($this->auth_model->is_logged_in())
        {
            redirect('show/posts', 'location');
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('username', 'Username', 'required');
    		$this->form_validation->set_rules('password', 'Password', 'required');

            
            if($this->form_validation->run() == FALSE)
            {
                $this->data['title'] = 'Login | ' . $this->config->item('site_name');
                $this->load->view('user/login', $this->data);
            }
            else
            {
                if($this->auth_model->login($this->input->post('username'), $this->input->post('password')))
                {
                    $this->session->set_flashdata('success', 'Login successful!');
                    redirect('show/posts', 'location');
                }
                else
                {
                    $this->data['title'] = 'Login | ' . $this->config->item('site_name');
                    $this->load->view('user/login', $this->data);
                }
            }
        }
    }
    
    function register()
    {
        if($this->auth_model->is_logged_in())
        {
            redirect('show/posts', 'location');
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('username', 'Username', 'trim|required|min_lenght[3]|max_lenght[25]|alpha_dash|callback__check_username');
            $this->form_validation->set_rules('password', 'Password', 'required|min_lenght[6]|max_lenght[128]');
            $this->form_validation->set_rules('passconf', 'Password confirmation', 'required|matches[password]');
            $this->form_validation->set_rules('email', 'Email', 'valid_email');

            
            if($this->form_validation->run() == FALSE)
            {
                $this->data['title'] = 'Register | ' . $this->config->item('site_name');
                $this->load->view('user/register', $this->data);
            }
            else
            {
                $dat = array(
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('password'),
                    'email' => $this->input->post('email')
                );
                
                if($this->auth_model->register($dat))
                {
                    $this->session->set_flashdata('success', 'Registration successful. You are now logged in');
                    redirect('show/posts', 'location');
                }
                else
                {
                    $this->data['title'] = 'Register | ' . $this->config->item('site_name');
                    $this->load->view('user/register', $this->data);
                }
            }
        }
    }
    
    function profile($username)
    {
        echo $username;
        //$this->load->view('user/profile');
    }
    
    function logout()
    {
        $this->auth_model->logout();
        $this->session->set_flashdata('success', 'You are now logged out!');
        redirect('show/posts', 'location');
    }
    
    function _check_username($username)
    {
        if($this->auth_model->is_username_taken($username))
        {
            $this->form_validation->set_message('_check_username', 'The %s is already taken.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}