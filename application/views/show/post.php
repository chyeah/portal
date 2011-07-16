<?php $this->load->view('header'); ?>

<?php foreach($records as $row): ?>

    <li>
        <?php echo (isset($row->title) ? '<h2>' . $row->title . '</h2>' : '') ?>
        <div><?php echo nl2br($row->content); ?></div>
        <p><?php echo $row->added; ?></p>
    </li>

<?php endforeach; ?>

<?php $this->load->view('footer'); ?>