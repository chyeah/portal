<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    function register($data = array())
    {
        if(empty($data))
        {
            return FALSE;
        }
        else
        {
            if(isset($data['username'], $data['password']))
            {
                log_message('debug', '***registration data is set ****');
                $sql = array(
                    'username' => $data['username'],
                    'password' => md5($data['password'])
                );
                
                if(isset($data['email']))
                {
                    $sql['email'] = $data['email'];
                }
                
                if($this->db->insert('users', $sql))
                
                //if($this->db->query($query))
                {
                    log_message('debug', '*** proceed to login method ***');
                    if($this->login($data['username'], $data['password']))
                    {
                        return TRUE;
                    }
                    else
                    {
                        return FALSE;
                    }
                }
                else
                {
                    return FALSE;
                }
            }
            else
            {
                return FALSE;
            }
        }
    }
    
	function login($username, $password)
    {
        $sql = 'SELECT user_id, username, permission FROM users WHERE username = ? AND password = ?';
        
        $query = $this->db->query($sql, array($username, md5($password)));
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
            {
                $data[] = $row;
            }
            
            $user = array(
                'user_id'    => $data[0]->user_id,
                'username'   => $data[0]->username,
                'permission' => $data[0]->permission
            );
            
            $this->session->set_userdata($user);
            
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    /**
     * Checks if user is logged in by checking if user_id is set in session
     * 
     * @return user_id on success. FALSE on failture.
     **/
    function is_logged_in()
    {
        $user_id = $this->session->userdata('user_id');
        
        if(isset($user_id))
        {
            return $user_id;
        }
        else
        {
            return FALSE;
        }
    }
    
    /**
     * Checks whatever the supplied username is taken or not.
     * @return bool TRUE if taken FALSE if not.
     **/
    function is_username_taken($username)
    {
        if(isset($username))
        {
            $query = $this->db->get_where('users', array('username' => $username));
            
            if($query->num_rows() > 0)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    /**
     * Checks if user has admin permissions.
     * @return bool TRUE if is admin, FALSE if not.
     **/
    function is_admin()
    {
        $per = $this->session->userdata('permission');
        
        if($per == 10)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    /**
     * @return bool TRUE
     **/
    function logout()
    {
        $remove = array('user_id' => '', 'username' => '', 'permission' => '');
        
        $this->session->unset_userdata($remove);
        
        return TRUE;
    }
    
    /**
     * Returns logged in username.
     * @return string username on success. FALSE if not logged in.
     **/
    function who_am_i()
    {
        if($this->is_logged_in())
        {
            return $this->session->userdata('username');
        }
        else
        {
            return FALSE;
        }
    }
    
    /**
     * Checks whatever there is anonymous in databased based
     * on ip and email or user agent.
     * @param string $email E-mail of anonymous to check.
     * @return anonymous id on success FALSE on failture.
     **/
    function check_anonymous($email = '')
    {
        $query = $this->db->query('SELECT anonymous_id FROM anonymous WHERE ip = ? AND ( email = ? OR user_agent = ?)',
            array($this->input->ip_address(), $email, $this->input->user_agent()));
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
            {
                $data[] = $row;
            }
            
            // Check whatever we can maybe update this anonymous.
            
            return $data[0]->anonymous_id;
        }
        else
        {
            return FALSE;
        }
    }
}