<?php echo doctype('html5'); ?>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    
    <?php
    echo link_tag('css/reset.css') . "
    " . link_tag('css/main.css') . "
    " . check_flashdata('fadeout', 3000);
    ?>
    <script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>js/jquery-1.5.2.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>js/autoresize.jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        // textarea counter
        $('#counter').html($('#post').val().length + '/350 characters');
        $('#post').keyup(function() {
            $('#counter').html($(this).val().length + '/350 characters');
        });
        $('#post').autoResize({extraSpace:0});
    });
    </script>
</head>
<body>
    <header>
        <ul>
            <li><?php echo anchor('show/posts', 'Posts'); ?></li>
            <li><?php echo anchor('show/stories', 'Stories'); ?></li>
            <li><?php echo anchor('add/post', 'Add post'); ?></li>
            <li><?php echo anchor('add/story', 'Add story'); ?></li>
            <li><?php echo anchor('show/newspaper', 'News'); ?></li>
            <?php if(!$this->auth->is_logged_in()): ?>
            <li><?php echo anchor('user/login', 'Login'); ?></li>
            <li><?php echo anchor('user/register', 'Register'); ?></li>
            <?php else: ?>
            <li><?php echo anchor('#', 'My profile'); ?></li>
            <li><?php echo anchor('user/logout', 'Logout'); ?></li>
            <?php endif; ?>
            <?php if($this->auth->is_admin()): ?>
            <li><?php echo anchor('admin/index', 'Admin'); ?></li>
            <?php endif; ?>
        </ul>
    </header>
    <div>
        <section id="content">
            <?php if($this->session->flashdata('success')){echo '<p id="success">' . $this->session->flashdata('success') . '</p>';}?>
            