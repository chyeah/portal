<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller
{
    var $data = array();
    var $pconfig = array();
    
	public function index()
    {   
        $this->data['title'] = 'Admin panel | ' . $this->config->item('site_name');
        
        // Do something, probably show stats.
        $this->load->view('admin/index', $this->data);
    }
    
    public function posts()
    {
        $this->load->model('content');
        $this->data['title'] = 'Posts | ' . $this->config->item('site_name');
        
        if($this->content->count_unapproved_posts() == 0)
        {
            $this->_no_data_message();
        }
        else
        {
            $this->pconfig['base_url'] = site_url('admin/posts');
            $this->pconfig['total_rows'] = $this->content->count_unapproved_posts();
            $this->_set_pagination_config();
            
            $this->pagination->initialize($this->pconfig);
            
            $offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : "0";
            
            $this->data['records'] = $this->content->show_posts($this->config->item('per_page'), $offset, 3);
            $this->data['pagesystem'] = $this->pagination->prxt();
        }
        
        $this->load->view('admin/approve-posts', $this->data);
    }
    
    public function stories()
    {
        $this->load->model('content');
        $this->data['title'] = 'Stories | ' . $this->config->item('site_name');
        
        if($this->content->count_unapproved_stories() == 0)
        {
            $this->_no_data_message();
        }
        else
        {
            $this->pconfig['base_url'] = site_url('admin/stories');
            $this->pconfig['total_rows'] = $this->content->count_unapproved_stories();
            $this->_set_pagination_config();
            
            $this->pagination->initialize($this->pconfig);
            
            $offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : "0";
            
            $this->data['records'] = $this->content->show_stories($this->config->item('per_page'), $offset, 3);
            $this->data['pagesystem'] = $this->pagination->prxt();
        }
        
        $this->load->view('admin/approve-stories', $this->data);
    }
    
    public function edit_post()
    {
        $this->load->model('content');
        
        if($this->content->edit_post($this->input->post('id'), $this->input->post('content')))
            echo nl2br($this->input->post('content'));
        else echo 'failture.';
    }
    
    public function edit_story_content()
    {
        $this->load->model('content');
        
        if($this->content->edit_story_content($this->input->post('id'), $this->input->post('content')))
            echo nl2br($this->input->post('content'));
        else echo 'failture';
    }
    
    public function edit_story_title()
    {
        $this->load->model('content');
        
        if($this->content->edit_story_title($this->input->post('id'), $this->input->post('title')))
            echo $this->input->post('title');
        else echo 'failture';
    }
    
    function i_approve($what, $id)
    {
        $this->load->model('content');
        
        if($what == 'post')
        {
            if($this->content->raw_edit_post($id, array('approved' => 1)))
                echo "true";
        }
        else if($what == 'story')
        {
            if($this->content->raw_edit_story($id, array('approved' => 1)))
                echo "true";
        }
    }
    
    function i_disapprove($what, $id)
    {
        $this->load->model('content');
        
        if($what == 'post')
        {
            if($this->content->raw_edit_post($id, array('approved' => 2)))
                echo "true";
        }
        else if($what == 'story')
        {
            if($this->content->raw_edit_story($id, array('approved' => 2)))
                echo "true";
        }
    }
    
    function newspaper()
    {
        /*$this->load->model('content');
        $this->data['title'] = 'Newspaper | Admin panel | ' . $this->config->item('site_name');
        
        if($this->content->count_unapproved_news() == 0)
        {
            $this->_no_data_message();
        }
        else
        {
            $this->pconfig['base_url'] = site_url('admin/posts');
            $this->pconfig['total_rows'] = $this->content->count_unapproved_posts();
            $this->_set_pagination_config();
            
            $this->pagination->initialize($this->pconfig);
            
            $offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : "0";
            
            $this->data['records'] = $this->content->show_posts($this->config->item('per_page'), $offset, 3);
            $this->data['pagesystem'] = $this->pagination->prxt();
        }
        
        $this->load->view('admin/approve-posts', $this->data);*/
        
        
        $this->load->model('content');
        
        $total = $this->content->count_approved_news();
        
        if($total <= 10)
        {
            $offset = 0;
        }
        else
        {
            $offset = $total - 10;
        }
        
        $this->data['title'] = 'Newspaper | Admin panel | ' . $this->config->item('site_name');
        $this->data['records'] = $this->content->show_newspaper(10, $offset);
        
        $this->load->view('admin/newspaper', $this->data);
    }
    
    function edit_news_title()
    {
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        
        $this->load->model('news');
        
        $dat = array('title' => $title);
        if($this->news->edit_news($id, $dat))
        {
            echo $title;
        }
        else echo "false";
    }
    
    function edit_news_content()
    {
        $id = $this->input->post('id');
        $content = $this->input->post('content');
        
        $this->load->model('news');
        $dat = array('content' => $content);
        if($this->news->edit_news($id, $dat)) echo $content;
        else echo "false";
    }
    
    function delete_news($id)
    {
        $this->load->model('news');
        $dat = array('active' => 0);
        if($this->news->edit_news($id, $dat)) echo "true";
        else echo "false";
    }
    
    function undelete_news($id)
    {
        $this->load->model('news');
        $dat = array('active' => 1);
        if($this->news->edit_news($id, $dat)) echo "true";
        else echo "false";
    }
    
    function create_news()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'title', 'required|max_lenght[50]|min_lenght[4]');
        $this->form_validation->set_rules('content', 'news', 'required|min_lenght[10]');
        $this->data['title'] = 'Newspaper | Admin panel | ' . $this->config->item('site_name');
        
        if($this->form_validation->run() == FALSE)
        {
            redirect('admin/newspaper', 'location');
        }
        else
        {
            $this->load->model('news');
            
            $dat['title'] = $this->input->post('title');
            $dat['content']  = $this->input->post('content');
            
            if($this->news->add($dat))
            {
                $this->session->set_flashdata('success', 'News is successfully published.');
                redirect('admin/newspaper', 'location');
            }
            else
            {
                redirect('admin/newspaper', 'location');
            }
        }
    }
    
    function users()
    {
        
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