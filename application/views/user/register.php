<?php $this->load->view('header'); ?>

<li>

<?php
$this->load->helper('form');
echo validation_errors();
echo form_open('user/register');
echo '<p>' . form_label('Username', 'username') . form_input('username', set_value('username')) . '</p>';
echo '<p>' . form_label('Password', 'password') . form_password('password') . '</p>';
echo '<p>' . form_label('Password', 'passconf') . form_password('passconf') . '</p>';
echo '<p>' . form_label('E-mail', 'email') . form_input('email', set_value('email')) . '</p>';
echo '<p>' . form_submit('register', 'Register') . '</p>';
echo form_close();
?>

</li>

<?php $this->load->view('footer');