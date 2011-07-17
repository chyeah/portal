<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Content
 * 
 * Responsible for showing, adding and modifing of posts, stories and news.
 * 
 * @package Portal
 * @author Kristian Saarela <kivihiinlane@gmail.com>
 * @copyright 2011
 * @version 2.0
 * @access public
 */
class Content extends CI_Model
{
    /**
     * Content::count_approved_posts()
     * 
     * Returns number of approved posts.
     * 
     * @return integer Number of approved posts.
     */
    public function count_approved_posts()
    {
        return $this->count_approved('post');
    }
    
    /**
     * Content::count_unapproved_posts()
     * 
     * Returns number of unapproved posts.
     * 
     * @return integer Number of unapproved posts.
     */
    public function count_upapproved_posts()
    {
        $this->db->where('approved', 3)->from('post');
        return $this->db->count_all_results();
    }
    
    /**
     * Content::count_unapproved_stories()
     * 
     * Returns number of unapproved stories.
     * 
     * @return integer Number of unapproved stories.
     */
    public function count_upapproved_stories()
    {
        $this->db->where('approved', 3)->from('story');
        return $this->db->count_all_results();
    }
    
    /**
     * Content::count_approved_stories()
     * 
     * Returns number of approved stories.
     * 
     * @return integer Number of approved stories.
     */
    public function count_approved_stories()
    {
        return $this->count_approved('story');
    }
    
    /**
     * Content::count_approved_news()
     * 
     * Returns number of publicly viewable news.
     * 
     * @return integer Number of active news.
     */
    public function count_approved_news()
    {
        return $this->count_approved('news');
    }
    
    /**
     * Content::count_approved()
     * 
     * Counts how many approved posts, stories or news are there.
     * 
     * @param string $what
     * @return integer Number of approved posts, stories or news.
     */
    private function count_approved($what)
    {
        $this->db->where('approved', 1)->from($what);
        return $this->db->count_all_results();
    }
    
    /**
     * Content::show_post()
     * 
     * Shows appropriate post according to id.
     * 
     * @param integer $id Id of post to show.
     * @return array Data of post to show.
     *      @example array
     *                  0 =>
     *                      object(stdClass)
     *                          public 'id' => string '5'
     *                          public 'content' => 'string' ... etc.
     */
    public function show_post($id)
    {
        return $this->show('post', $id);
    }
    
    /**
     * Content::show_story()
     * 
     * Shows appropriate story according to id.
     * 
     * @param integer $id Id of story to show.
     * @return array Data of story to show.
     *       @example array
     *                  0 =>
     *                      object(stdClass)
     *                          public 'id' => string '5'
     *                          public 'content' => 'string' ... etc.
     */
    public function show_story($id)
    {
        return $this->show('story', $id);
    }
    
    /**
     * Content::show_news()
     * 
     * Shows appropriate news according to id.
     * 
     * @param mixed $id Id of news to show.
     * @return array Data of news to show.
     *       @example array
     *                  0 =>
     *                      object(stdClass)
     *                          public 'id' => string '5'
     *                          public 'content' => 'string' ... etc.
     */
    public function show_news($id)
    {
        return $this->show('news', $id);
    }
    
    /**
     * Content::show()
     * 
     * @param string $what
     * @param integer $id
     * @return array
     */
    private function show($what, $id)
    {
        $this->db->from($what . '_view')->where('id', $id);
        $query = $this->db->get();
        
        if($query->num_rows() > 0) return $query->result();
        else return false;
    }
    
    /**
     * Content::show_posts()
     * 
     * @param integer $num
     * @param integer $offset
     * @param integer $approved
     * @return array
     */
    public function show_posts($num, $offset, $approved = 1)
    {
        return $this->show_many('post', $num, $offset, $approved);
    }
    
    /**
     * Content::show_stories()
     * 
     * @param integer $num
     * @param integer $offset
     * @param integer $approved
     * @return array
     */
    public function show_stories($num, $offset, $approved = 1)
    {
        return $this->show_many('story', $num, $offset, $approved);
    }
    
    /**
     * Content::show_newspaper()
     * 
     * @param integer $num
     * @param integer $offset
     * @param integer $approved
     * @return array
     */
    public function show_newspaper($num, $offset, $approved = 1)
    {
        return $this->show_many('news', $num, $offset, $approved);
    }
    
    /**
     * Content::show_many()
     * 
     * @param string $what
     * @param integer $num
     * @param integer $offset
     * @param integer $approved
     * @return array
     */
    private function show_many($what, $num, $offset, $approved)
    {
        $this->db->from($what . '_view')->where('approved', $approved)->order_by('added', 'desc')->limit($num, $offset);
        $query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
                $data[] = $row;
            
            return $data;
        }
        else return false;
    }
    
    /**
     * Content::add_news()
     * 
     * @param string $title
     * @param string $content
     * @return boolean
     */
    public function add_news($title, $content)
    {
        $this->db->trans_start();
        $news_id = $this->add_story_news('news', $title, $content);
        $this->db->insert('user_to_news', array('news_id' => $news_id, 'user_id' => $this->session->userdata('user_id')));
        
        if($this->db->trans_complete()) return true;
        else return false;
    }
    
    /**
     * Content::add_story()
     * 
     * @param string $title
     * @param string $content
     * @return boolean
     */
    public function add_story($title, $content)
    {
        $this->db->trans_start();
        $story_id = $this->add_story_news('story', $title, $content);
        
        if($this->auth_model->is_logged_in())
        {
            $this->db->insert('user_to_story', array('story_id' => $post_id, 'user_id' => $this->session->userdata('user_id')));
        }
        else
        {
            //TODO: check anonymous using auth model or something.
            $this->db->insert('anonymous_to_story', array('story_id' => $post_id, 'anonymous_id' => 1));
        }
        
        if($this->db->trans_complete()) return true;
        else return false;
    }
    
    /**
     * Content::add_post()
     * 
     * @param string $content
     * @return boolean
     */
    public function add_post($content)
    {
        $this->db->trans_start();
        $post_id = $this->_add_post($content);
        
        if($this->auth_model->is_logged_in())
        {
            $this->db->insert('user_to_post', array('post_id' => $post_id, 'user_id' => $this->session->userdata('user_id')));
        }
        else
        {
            //TODO: check anonymous using auth model or something.
            $this->db->insert('anonymous_to_post', array('post_id' => $post_id, 'anonymous_id' => 1));
        }
        
        if($this->db->trans_complete()) return true;
        else return false;
    }
    
    /**
     * Content::_add_post()
     * 
     * @param string $content
     * @return integer
     */
    private function _add_post($content)
    {
        $this->db->insert('post', array('content' => $content));
        return $this->db->insert_id();
    }
    
    /**
     * Content::add_story_news()
     * 
     * @param string $what
     * @param string $title
     * @param string $content
     * @return integer
     */
    private function add_story_news($what, $title, $content)
    {
        $this->db->insert($what, array('title' => $title, 'content' => $content));
        return $this->db->insert_id();
    }
    
    /**
     * Content::edit_post()
     * 
     * @param integer $id
     * @param string $content
     * @return boolean
     */
    public function edit_post($id, $content)
    {
        $this->db->where('id', $id);
        
        if($this->db->update('post', array('content' => $content))) return true;
        else return false;
    }
    
    /**
     * Content::edit_news_title()
     * 
     * @param integer $id
     * @param string $title
     * @return boolean
     */
    public function edit_news_title($id, $title)
    {
        return $this->edit('news', array('title' => $title));
    }
    
    /**
     * Content::edit_news_content()
     * 
     * @param integer $id
     * @param string $content
     * @return boolean
     */
    public function edit_news_content($id, $content)
    {
        return $this->edit('news', $id, array('content' => $content));
    }
    
    /**
     * Content::edit_news()
     * 
     * @param integer $id
     * @param string $title
     * @param string $content
     * @return boolean
     */
    public function edit_news($id, $title, $content)
    {
        return $this->edit('news', $id, array('title' => $title, 'content' => $content));
    }
    
    /**
     * Content::edit_story_title()
     * 
     * @param integer $id
     * @param string $title
     * @return boolean
     */
    public function edit_story_title($id, $title)
    {
        return $this->edit('post', $id, array('title' => $title));
    }
    
    /**
     * Content::edit_story_content()
     * 
     * @param integer $id
     * @param string $content
     * @return boolean
     */
    public function edit_story_content($id, $content)
    {
        return $this->edit('post', $id, array('content' => $content));
    }
    
    /**
     * Content::edit_story()
     * 
     * @param integer $id
     * @param string $title
     * @param string $content
     * @return boolean
     */
    public function edit_story($id, $title, $content)
    {
        return $this->edit('post', $id, array('title' => $title, 'content' => $content));
    }
    
    /**
     * Content::edit()
     * 
     * @param string $what
     * @param integer $id
     * @param string $data
     * @return boolean
     */
    private function edit($what, $id, $data)
    {
        $this->db->where('id', $id);
        
        if($this->db->update($what, $data)) return true;
        else return false;
    }
}