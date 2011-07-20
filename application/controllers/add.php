<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

class Add extends Public_Controller
{
    var $data;
    
    function index()
    {
        $this->post();
    }
    
    function post()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('post', 'post', 'required|min_length[10]|max_length[350]');
        
        if(!$this->auth->is_logged_in())
        {
            $this->form_validation->set_rules('name', 'name', 'trim|min_length[3]|max_length[25]|alpha_dash');
            $this->form_validation->set_rules('email', 'e-mail', 'trim|valid_email');
        }
        
        $this->data['title'] = 'New post | ' . $this->config->item('site_name');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->_prepare_view(false, $this->data);
        }
        else
        {
            $this->load->model('content');
            
            if($this->content->add_post($this->input->post('post')))
            {
                $this->session->set_flashdata('success', 'Post added.');
                redirect('show/posts', 'location');
            }
            else
            {
                $this->_prepare_view(false, $this->data);
            }
        }
    }
    
    function story()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('post', 'story', 'required|min_length[10]|max_length[9500]');
		$this->form_validation->set_rules('title', 'title', 'required|max_length[50]|min_length[3]');
        
        if(!$this->auth->is_logged_in())
        {
            $this->form_validation->set_rules('name', 'name', 'trim|min_length[3]|max_length[25]|alpha_dash');
            $this->form_validation->set_rules('email', 'e-mail', 'trim|valid_email');
        }
        
        $this->data['title'] = 'New post | ' . $this->config->item('site_name');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->_prepare_view(true, $this->data);
        }
        else
        {
            $this->load->model('content');
            
            if($this->content->add_story($this->input->post('title'), $this->input->post('post')))
            {
                $this->session->set_flashdata('success', 'Story added.');
                redirect('show/posts', 'location');
            }
            else
            {
                $this->_prepare_view(true, $this->data);
            }
        }
    }
    
    private function _prepare_view($story = false, $dat = '')
    {
        $this->load->helper('form');
        
        $this->data['errors'] = validation_errors();
        $this->data['post'] = form_label(($story) ? 'Story' : 'Post', 'post') . form_textarea('post', set_value('post'), 'id="post" contenteditable');
        
        if(!empty($dat))
        {
            foreach($dat as $key => $val)
            {
                $data[$key] = $val;
            }
        }
        
        if(!$this->auth->is_logged_in())
        {
            $this->data['name'] = form_label('Name', 'name') . form_input('name', set_value('name'), 'id="name"');
            $this->data['email'] = form_label('E-mail', 'email') . form_input('email', set_value('email'), 'id="email"');
        }
        
        if($story)
        {
            $this->data['story_title'] = form_label('Title', 'title') . form_input('title', set_value('title'), 'id="title"');
            $this->data['submit'] = form_submit('new-story', 'Tell us Your story');
            
            $this->load->view('form/new-story', $this->data);
        }
        else
        {
            $this->data['submit'] = form_submit('new-post', 'New post');
            
            $this->load->view('form/new-post', $this->data);
        }
    }
}