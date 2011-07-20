<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

class Show extends Public_Controller
{
    var $pconfig = array();
    
    function index()
    {
        $this->posts();
    }
    
    function posts()
    {
        $this->load->model('content');
        $this->data['title'] = 'Posts | ' . $this->config->item('site_name');
        
        $this->data['side_nav'] = array(anchor('show/moderate', 'Moderate'));
        
        if($this->content->count_approved_posts() == 0)
        {
            $this->_no_data_message();
        }
        else
        {
            $this->pconfig['base_url'] = site_url('show/posts');
            $this->pconfig['total_rows'] = $this->content->count_approved_posts();
            $this->_set_pagination_config();
            
            $this->pagination->initialize($this->pconfig);
            
            $offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : "0";
            
            $this->data['records'] = $this->content->show_posts($this->config->item('per_page'), $offset);
            $this->data['pagesystem'] = $this->pagination->prxt();
        }
        
        $this->load->view('show/posts', $this->data);
    }
    
    function stories()
    {
        $this->load->model('content');
        $this->data['title'] = 'Stories | ' . $this->config->item('site_name');
        
        if($this->content->count_approved_stories() == 0)
        {
            $this->_no_data_message();
        }
        else
        {
            $this->pconfig['base_url'] = site_url('show/stories');
            $this->pconfig['total_rows'] = $this->content->count_approved_stories();
            $this->_set_pagination_config();
            
            $this->pagination->initialize($this->pconfig);
            
            $offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : "0";
            
            $this->data['records'] = $this->content->show_stories($this->pconfig['per_page'], $offset);
            $this->data['pagesystem'] = $this->pagination->prxt();
            $this->data['what']['s'] = 'story';
        }
        
        $this->load->view('show/posts', $this->data);
    }
    
    function post($id)
    {
        if(!$id) // No id? Redirect back to posts.
        {
            redirect('show/posts');
        }
        
        $this->load->model('content');
        $this->data['records'] = $this->content->show_post($id);
        
        // Set the webpage title.
        $this->data['title'] = 'post | ' . $this->config->item('site_name');
        
        $this->load->view('show/post', $this->data);
    }
    
    function story($id)
    {
        if(!$id) // No id? Redirect back to posts.
        {
            redirect('show/stories');
        }
        
        // Load up the news model.
        $this->load->model('content');
        
        $this->data['records'] = $this->content->show_story($id);
        
        // Set the webpage title.
        $this->data['title'] = $this->data['records'][0]->title . ' | ' . $this->config->item('site_name');
        
        $this->load->view('show/post', $this->data);
    }
    
    function newspaper()
    {
        $this->load->model('content');
        
        $this->data['title'] = 'News | ' . $this->config->item('site_name');
        
        if($this->content->count_approved_news() == 0)
        {
            $this->_no_data_message();
        }
        else
        {
            /** Pagination **/
            $this->pconfig['base_url'] = site_url('show/newspaper');
            $this->pconfig['total_rows'] = $this->content->count_approved_news();
            $this->_set_pagination_config();
            $this->pagination->initialize($this->pconfig);
            
            $this->data['records'] = $this->content->show_newspaper($this->pconfig['per_page'], $this->uri->segment(3));
            $this->data['pagesystem'] = $this->pagination->prxt();
        }
        
        $this->data['what']['s'] = 'news';
        
        $this->load->view('show/posts', $this->data);
    }
    
    function news($id)
    {
        if(!$id) // No id? Redirect back to newspaper.
        {
            redirect('show/newspaper');
        }
        
        // Load up the news model.
        $this->load->model('content');
        
        $this->data['records'] = $this->content->show_news((int)$id);
        
        // Set the webpage title.
        $this->data['title'] = $this->data['records'][0]->title . ' | ' . $this->config->item('site_name');
        
        $this->load->view('show/post', $this->data);
    }
    
    private function _set_pagination_config()
    {
        /* Pagination config. */
        $this->pconfig['per_page'] = $this->config->item('per_page');
        $this->pconfig['uri_segment'] = $this->config->item('uri_segment');
        $this->pconfig['num_links'] = $this->config->item('num_links');
        $this->pconfig['page_query_string'] = $this->config->item('page_query_string');
        $this->pconfig['full_tag_open'] = $this->config->item('full_tag_open');
        $this->pconfig['full_tag_close'] = $this->config->item('full_tag_close');
        $this->pconfig['prev_link'] = '&larr; Older';
        $this->pconfig['next_link'] = 'Newer &rarr;';
    }
    
    private function _no_data_message()
    {
        $this->data['records'][0] = new no_data;
        $this->data['pagesystem'] = '';
        $this->data['what'] = 's';
    }
}

class no_data
{
    public $id = '#';
    public $title = 'No content';
    public $content = 'There is no content here yet. But it won\'t stay that way for long.';
    public $added = '';
    public $user_id = '#';
    public $username = 'system';
    
    function __construct()
    {
        $this->added = date("Y-m-d H:i:s", time());
    }
}