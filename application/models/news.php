<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

/**
 * News module for displaying, creating, modifying and deleteing news.
 * Used by showing news to users and making changes thru admin panel.
 **/
class News extends CI_Model
{
    function count_active_news()
    {
        $this->db->where('active', 1)->from('news');
        return $this->db->count_all_results();
    }
    
    function count_news()
    {
        return $this->db->count_all('news_view');
    }
    
	function show($id = FALSE, $active = 'ALL', $num = 1, $offset = 1)
    {
        $this->db->select('*')->from('news_view');
        
        if($active == 'ALL')
        {
            // Do nothing.
        }
        else if($active) // 1
        {
            $this->db->where('active', 1);
        }
        else if(!$active) // 0
        {
            $this->db->where('active', 0);
        }
        
        if($id)
        {
            $this->db->where('news_id', $id);
        }
        else // If id is set, we don't need order by or limit clauses.
        {
            $this->db->order_by('added', 'desc');
            $this->db->limit($num, $offset);
        }
        
        
        $query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
            {
                $data[] = $row;
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }
    
    function edit_news($id, $data)
    {
        $this->db->where('news_id', $id);
        if($this->db->update('news', $data))
        {
            return true;
        }
        else return false;
    }
    
    function add($data)
    {
        if(isset($data['title'], $data['content']))
        {
            $this->db->trans_start();
            
            $this->db->insert('news', $data);
            
            $this->db->insert('users_to_news', array('news_id' => $this->db->insert_id(), 'user_id' => $this->session->userdata('user_id')));
            
            $this->db->trans_complete();
        }
        else return false;
    }
}