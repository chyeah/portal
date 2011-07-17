<?php

$this->load->view('admin/header');

?>

<p class="info" id="create_news">Create new news</p>
<article id="news_form">
<?php
echo validation_errors();
echo form_open('admin/create_news');
echo "<p>" . form_label('Title') . form_input('title', set_value('title'), 'id=title') . "</p>";
echo "<p>" . form_label('News') . form_textarea('content', set_value('content'), 'id=newscontent') . "</p>";
echo "<p>" . form_submit('submit', 'Publish') . "</p>";
echo form_close();
?>
</article>
<?php

foreach($records as $row):
    echo "<article><h2 class=\"title\" id=\"" . $row->id . "\">" . $row->title . "</h2>
    <p class=\"news\" id=\"" . $row->id . "\">". nl2br($row->content) . "</p>
    <details open>Published by "
    
    . anchor_popup('user/profile/' . $row->user_id . '/' . $row->username, $row->username) .
    
    " at " . $row->added . " (" . timespan(human_to_unix($row->added), now()) . " ago)
    <span>";
    
    if($row->approved == 1)
    {
        echo anchor('admin/delete_news/' . $row->id, img('img/round_delete.png'));
    }
    else
    {
        echo anchor('admin/undelete_news/' . $row->id, img('img/round_checkmark.png'));
    }
    
    echo "</span>
    </details>
    </article>";
endforeach;
?>

<script>
    $(document).ready(function() {
        var csrf_token_hash = $.cookie('ci_csrf_token');
        $('.title').editable('<?php echo site_url('admin/edit_news_title'); ?>',{event:'dblclick',submit:'OK',cancel:'Cancel',id:'id',name:'title',submitdata:{'ci_csrf_token':csrf_token_hash}});
        $('.news').editable('<?php echo site_url('admin/edit_news_content'); ?>',{type:'textarea',event:'dblclick',submit:'OK',cancel:'Cancel',id:'id',name:'content', submitdata:{'ci_csrf_token':csrf_token_hash},data:function(value,settings){var retval=value.replace(/<br[\s\/]?>/gi, '\n');return retval;}});
        
        $("#create_news").click(function() {
            $("#news_form").slideToggle();
        });
        
        $("#contents ul li").mouseover(function() {
            $(this).find("span").show();
        }).mouseout(function() {
            $(this).find("span").hide();
        });
        
        $("#contents ul li p span a").click(function(e) {
            e.preventDefault();
            var a = $(this);
            var linkk = a.attr('href');
            
            $.ajax({
                url: linkk,
                success: function(data) {
                    if(data == 'true')
                    {
                        /*a.closest("li").slideUp("fast");*/
                        a.closest("li").css('outline', 'solid green');
                    }
                    if(data == 'empty')
                    {
                        a.closest("li").empty().append("<div>That's all :)</div>");
                        
                    }
                }
            });
        });
    });
    </script>

<?php

$this->load->view('footer');