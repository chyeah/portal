<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<div id="newletter_form">
    <?php echo form_open('sendemail/send'); ?>
    
    <?php
    
    $name_data = array(
        'name' => 'name',
        'id' => 'name',
        'value' => set_value('name')
    );
    
    ?>
    
    <p>
        <label for="name">Name:</label>
        <?php echo form_input($name_data); ?>
    </p>
    <p>
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" value="<?php echo set_value('email'); ?>" />
    </p>
    <p>
        <?php echo form_submit('submit', 'singup'); ?>
    </p>
    
    
    <?php echo form_close(); ?>
    
    <?php echo validation_errors('<p class="error">'); ?>
</div>
</body>
</html>