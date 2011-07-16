<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Outputs given variable(s) with formatting and location
 * 
 * @param mixed variabes to be output
 **/
function dump()
{
    list($callee) = debug_backtrace();
    $arguments = func_get_args();
    $total_arguments = count($arguments);
    
    echo '<fieldset style="background: #fefefe!important;border:2px solid red;padding:5px;color:#000">';
    echo '<legend style="background:lightgrey;padding:5px;">' . $callee['file'] . ' @ line:' . $callee['line'] . '</legend><pre>';
    $i = 0;
    
    foreach($arguments as $argument)
    {
        echo '<br><strong>Debug #' . (++$i) . ' of ' . $total_arguments . '</strong>';
        var_dump($argument);
    }
    
    echo '</pre></fieldset>';
}