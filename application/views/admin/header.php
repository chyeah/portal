<?php
echo doctype('html5');
?>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    
    <?php echo link_tag('css/reset.css') . "\n";
    echo link_tag('css/main.css');
    echo check_flashdata('fadeout', 3000);
    ?>
    <script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>js/jquery-1.5.2.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>js/jquery.jeditable.mini.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>js/jquery.cookie.js"></script>
</head>
<body>
<header>
    <ul>
        <li><?php echo anchor('show/posts', 'Home'); ?></li>
        <li><?php echo anchor('admin/index', 'Index'); ?></li>
        <li><?php echo anchor('admin/posts', 'Posts'); ?></li>
        <li><?php echo anchor('admin/stories', 'Stories'); ?></li>
        <li><?php echo anchor('admin/newspaper', 'Newspaper'); ?></li>
        <!--<li><?php echo anchor('admin/login', 'Login'); ?></li>
        <li><?php echo anchor('user/register', 'Register'); ?></li>
        <li><?php echo anchor('user/profile/', 'My profile'); ?></li>
        <li><?php echo anchor('user/logout', 'Logout'); ?></li>
        <li><?php echo anchor('admin/index', 'Admin'); ?></li>-->
    </ul>
</header>
<div>
<section id="content">    
    <?php
    if($this->session->flashdata('success'))
    {
        echo '<p id="success">' . $this->session->flashdata('success') . '</p>';
    }
    ?>
