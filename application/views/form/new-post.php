<?php $this->load->view('header'); ?>

<article>
<h2>Add new post</h2>
<?php
echo $errors;

echo form_open('add/post');
echo (isset($name)) ? '<p>' . $name . '</p>' : '';
echo '<p>' . $content . '<m id="counter"></m></p>';
echo (isset($email)) ? '<p>' . $email . '</p>' : '';
echo '<p>' . $submit . '</p>';
echo form_close();
?>

</article>

<?php $this->load->view('footer');