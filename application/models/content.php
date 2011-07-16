<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Model
{
    // You know, fuck that. a as singular and m as plural.
    // Cause it's way better in your native language!
    var $singplur = array(
        0 => array(
            'a' => 'post',
            'm' => 'posts'
        ),
        1 => array(
            'a' => 'story',
            'm' => 'stories'
        ),
        2 => array(
            'a' => 'user',
            'm' => 'users'
        ),
        3 => array(
            'a' => 'anonymous',
            'm' => 'anonymous'
        )
    );
    
    function count_approved($what)
    {
        $this->db->where('approved', 1)->from($what);
        return $this->db->count_all_results();
    }
    
    /**
     * @param string $what posts | stories
     **/
	function show($what, $id = FALSE, $approved = 1, $num = FALSE, $offset = FALSE)
    {
        if($what == 'posts') $use = $this->singplur[0];
        else if($what == 'stories') $use = $this->singplur[1];
        
        $this->db->from($use['m'] . '_view')->where('approved', $approved);
        
        if($id)
        {
            $this->db->where('id', $id);
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
    
    /**
     * @param array $data [username, email]
     **/
    function add($post = true, $data)
    {
        if($post)
        {
            $use = $this->singplur[0];
        }
        else
        {
            $use = $this->singplur[1];
        }
        
        // Check if user is logged in or not.
        if($this->auth_model->is_logged_in())
        {
            $peep = $this->singplur[2];
        }
        else
        {
            $peep = $this->singplur[3];
        }
        
        $this->db->trans_start();
        
        if(!$post)
        {
            $temp = array($data['title'], $data['content']);
        }
        else
        {
            $temp = array($data['content']);
        }
        
        // Insert content.
        $this->db->query('INSERT INTO ' . $use['m'] . ' (' . ((!$post) ? 'title, ' : '') . 'content) VALUES (' . ((!$post) ? '?, ' : '') . '?)',
            $temp
        );
        
        // Just inserted content id.
        $cID = $this->db->insert_id();
        
        if(!$this->auth_model->is_logged_in())
        { // We have a anonymous!
            
            // Check if anonymous with similar data is already in database.
            if($userID = $this->auth_model->check_anonymous($data['email']))
            {
                // Match is found. Emm.. nothing to do here.
            }
            else
            {
                $this->db->query('INSERT INTO anonymous (username, email, ip, user_agent) VALUES (?, ?, ?, ?)',
                    array($data['username'], $data['email'], $this->input->ip_address(), $this->input->user_agent()));
                $userID = $this->db->insert_id();
            }
            
            $this->db->query('INSERT INTO anonymous_to_' . $use['m'] . ' (' . $peep['a'] . '_id, ' . $use['a'] . '_id) VALUES (?, ?)',
                array($userID, $cID));
        }
        else
        {
            $userID = $this->session->userdata('user_id');
            
            $this->db->query('INSERT INTO users_to_' . $use['m'] . ' (' . $peep['a'] . '_id, ' . $use['a'] . '_id) VALUES (?, ?)',
                array($userID, $cID));
        }

        $this->db->trans_complete();
        
        return true;
    }
    
    function site_statistics()
    {
        $this->db->query('SELECT COUNT(users.user_id) AS total_users FROM users WHERE users.permission > 0');
    }
    
    /**
     * @param $what string posts|stories
     **/
    function edit($what = 'posts', $id, $data)
    {
        if($what == 'posts')
        {
            $use = $this->singplur[0];
        }
        else
        {
            $use = $this->singplur[1];
        }
        
        $this->db->where($use['a'] . '_id', $id);
        
        if($this->db->update($use['m'], $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}