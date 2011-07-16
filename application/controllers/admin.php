<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller
{
    /**
     * Array where all view variables are stored.
     * This will be passed to view file at end.
     **/
    var $data = array();
    
	function index()
    {   
        $this->data['title'] = 'Admin panel | ' . $this->config->item('site_name');
        
        // Do something, probably show stats.
        $this->load->view('admin/index', $this->data);
    }
    
    /**
     * @param string $what posts | stories
     **/
    function approve($what = 'posts')
    {
        $this->load->model('content');
        
        $this->data['title'] = 'Approve posts | Admin panel | ' . $this->config->item('site_name');
        
        if(!$this->data['records'] = $this->content->show($what, FALSE, 3))
        {
            echo "no data";
        }
        
        $this->load->view('admin/approve-' . $what, $this->data);
    }
    
    function _ajax_edit($what = 'posts', $id, $data)
    {   
        $this->load->model('content');
        
        if($this->content->edit($what, $id, $data))
        {
            return true;
        }
        else
        {
            echo '<p id="failture">Something went wrong.</p>';
            return false;
        }
    }
    
    function edit_post()
    {
        $id = $this->input->post('id');
        $content = $this->input->post('content');
        
        $a = array('content' => $content);
        
        $this->_ajax_edit('posts', $id, $a);
        
        echo nl2br($content);
    }
    
    function edit_story()
    {
        $id = $this->input->post('id');
        $content = $this->input->post('content');
        
        $a = array('content' => $content);
        
        $this->_ajax_edit('stories', $id, $a);
        
        echo nl2br($content);
    }
    
    function edit_title()
    {
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        
        $a = array('title' => $title);
        
        $this->_ajax_edit('story', $id, $a);
        
        echo $title;
    }
    
    function i_approve($what, $id)
    {
        $this->load->model('content');
        
        if($what == 'post')
        {
            $this->content->edit('posts', $id, array('approved' => 1));
            echo "true";
        }
        else if($what == 'story')
        {
            $this->content->edit('stories', $id, array('approved' => 1));
            echo "true";
        }
    }
    
    function i_disapprove($what, $id)
    {
        $this->load->model('content');
        
        if($what == 'post')
        {
            $this->content->edit('posts', $id, array('approved' => 2));
            echo "true";
        }
        else if($what == 'story')
        {
            $this->content->edit('stories', $id, array('approved' => 2));
            echo "true";
        }
    }
    
    function newspaper()
    {
        $this->load->model('news');
        
        $total = $this->news->count_news();
        
        if($total <= 10)
        {
            $offset = 0;
        }
        else
        {
            $offset = $total - 10;
        }
        
        $this->data['title'] = 'Newspaper | Admin panel | ' . $this->config->item('site_name');
        $this->data['records'] = $this->news->show(FALSE, 'ALL', 10, $offset);
        
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
}

/*
show    index
show    approve posts
show    approve stories

post    edit title --- only for storyies ---
post    edit post
post    edit story
        edit (what=post|story) -- 
post    approve post
post    approve story
        approve(what=post|story, id=int)
post    disapprove post
post    disapprove story
        disapprove(what=post|story, id=int)
post    later post
post    later story


*/