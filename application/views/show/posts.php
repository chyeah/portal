<?php $this->load->view('header');?>

<?php foreach($records as $row): ?>

    <article>
        <?php echo (isset($row->title) ? '<h2><a href="' . site_url('show/' . $what['s'] . '/' . $row->id . '/' . url_title($row->title, 'dash', true)) . '">' . $row->title . '</a></h2>' : '') ?>
        <p><?php echo (isset($row->title) ? nl2br($row->content) : '<a href="' . site_url('show/post/' . $row->id . '/') . '">' . nl2br($row->content) . '</a>'); ?></p>
        <details>Told by <?php echo (empty($row->username)) ? 'anonymous' : $row->username ?> <?php echo timespan(human_to_unix($row->added), now()); ?> ago.</details>
    </article>

<?php endforeach; ?>

<?php echo $pagesystem; ?>
<?php $this->load->view('footer'); ?>