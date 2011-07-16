<?php $this->load->view('header'); ?>

<li>
<h2>Tell us a post</h2>
<?php
echo $errors;

echo form_open('post/post');
echo (isset($name)) ? '<p>' . $name . '</p>' : '';
echo '<p>' . $content . '<m id="counter"></m></p>';
echo (isset($email)) ? '<p>' . $email . '</p>' : '';
echo '<p>' . $submit . '</p>';
echo form_close();
?>

</li>

<?php $this->load->view('footer');