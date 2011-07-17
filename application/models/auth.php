<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Model
{
    public function register($data = array())
    {
        if(empty($data)) return false;
        else
        {
            if(isset($data['username'], $data['password']))
            {
                log_message('debug', '***registration data is set ****');
                $sql = array(
                    'username' => $data['username'],
                    'password' => md5($data['password'])
                );
                
                if(isset($data['email'])) $sql['email'] = $data['email'];
                
                if($this->db->insert('user', $sql))
                {
                    log_message('debug', '*** proceed to login method ***');
                    
                    if($this->login($data['username'], $data['password'])) return true;
                    else return false;
                }
                else return false;
            }
            else return false;
        }
    }
    
    public function login($username, $password, $data = array())
    {
        $sql = 'SELECT user_id, username, permission FROM user WHERE username = ? AND password = ?';
        
        $query = $this->db->query($sql, array($username, md5($password)));
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
                $data[] = $row;
            
            $user = array(
                'user_id'    => $data[0]->user_id,
                'username'   => $data[0]->username,
                'permission' => $data[0]->permission
            );
            $this->session->set_userdata($user);
            
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function is_logged_in()
    {
        $user_id = $this->session->userdata('user_id');
        
        if(isset($user_id)) return $user_id;
        else return false;
    }
    
    public function is_admin()
    {
        if($this->is_logged_in() && $this->session->userdata('permission') >= 10) return true;
        else return false;
    }
    
    public function logout()
    {
        $remove = array('user_id' => false, 'username' => false, 'permission' => false);
        $this->session->unset_userdata($remove);
        
        return true;
    }
    
    public function who_am_i()
    {
        if($this->is_logged_in()) return $this->session->userdata('username');
        else return false;
    }
    
    public function check_anonymous($email = '')
    {
        $query = $this->db->query('SELECT anonymous_id FROM anonymous WHERE ip = ? AND ( email = ? OR user_agent = ?)',
            array($this->input->ip_address(), $email, $this->input->user_agent()));
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row) $data[] = $row;
            
            // Check whatever we can maybe update this anonymous.
            
            return $data[0]->anonymous_id;
        }
        else return false;
    }
}