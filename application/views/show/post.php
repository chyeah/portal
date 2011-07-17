<?php $this->load->view('header'); ?>

<?php foreach($records as $row): ?>

    <article>
        <?php echo (isset($row->title) ? '<h2>' . $row->title . '</h2>' : '') ?>
        <p><?php echo nl2br($row->content); ?></p>
        <details open>Told by <?php echo (empty($row->username)) ? 'anonymous' : $row->username ?> <?php echo timespan(human_to_unix($row->added), now()); ?> ago.</details>
    </article>

<?php endforeach; ?>

<?php $this->load->view('footer'); ?>