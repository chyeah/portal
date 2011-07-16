<?php $this->load->view('admin/header');

foreach($records as $row):
    echo "<li>"
    
    . (isset($row->title) ? "<h2 class=\"title\" id=\"" . $row->id . "\">" . $row->title . "</h2>" : "") .
    
    "<div class=\"" . (isset($row->title) ? "story" : "post") . "\" id=\"" . $row->id . "\">"
    
    . nl2br($row->content) .
    
    "</div>
    <p>Told by"
    
    . anchor_popup('user/profile/' . $row->user_id . '/' . $row->username, $row->username) .
    
    " at " . $row->added . " (" . timespan(human_to_unix($row->added), now()) . " ago)
    <span>"
    
    . anchor('admin/approve', img('img/round_checkmark.png'))
    . anchor('admin/disapprove', img('img/round_minus.png'))
    . anchor('admin/later', img('img/round_delete.png')) .
    
    "</span>
    </p>
    </li>";
endforeach;

$this->load->view('footer'); ?>