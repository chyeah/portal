<?php $this->load->view('header'); ?>

<article>
<h2>Tell us Your story</h2>
<?php
echo $errors;

echo form_open('post/story');
echo (isset($name)) ? '<p>' . $name . '</p>' : '';
echo '<p>' . $story_title . '</p>';
echo '<p>' . $content . '</p>';
echo (isset($email)) ? '<p>' . $email . '</p>' : '';
echo '<p>' . $submit . '</p>';
echo form_close();
?>

</article>

<?php $this->load->view('footer');