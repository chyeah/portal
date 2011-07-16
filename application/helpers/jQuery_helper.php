<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('check_flashdata'))
{
    function check_flashdata($function = 'hide', $timeout = 1000)
    {
        $CI = &get_instance();
        
        if($CI->session->flashdata('success'))
        {
            $CI->load->library('javascript');
            
            $output = $CI->javascript->external();
            $str = "$(document).ready(function() {setTimeout(function(){" . 
                $CI->javascript->$function('#success') . 
                "}, $timeout);});";
            $output .= $CI->javascript->inline($str);
            
            return $output;
        }
    }
}

if(!function_exists('jquery_inline_edit'))
{
    function jquery_inline_edit()
    {
        $CI = &get_instance();
        
        $CI->load->library('javascript');
        
        $output = $CI->javascript->external('jquery.jeditable.mini.js');
        $js = "$(document).ready(function() {".
            "});";
        $output .= $CI->javascript->inline($js);
        
        return $output;
    }
}