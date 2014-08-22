<script type="text/javascript">
    $(function() {
        $(".tip").tooltip();
    });
</script>

<script>
    $(document).ready(function() {
        $('#send').click(function() {
            //console.log('called');
            if ($('#message').val() == "") {
                bootbox.alert('You cannot Send Empty Message.');
                return false;
            }
            return true;
        })
    });
</script>
<style>
    .table th { text-align:center; }
    .table td { text-align:center; }
    .table a:hover { text-decoration: none; }
    .cl_wday { text-align: center; font-weight:bold; }
    .cl_equal { width: 14%; }
    .day { width: 14%; }
    .day_num { width: 100%; text-align:left; margin: -8px; padding:8px; } 
    .content { width: 100%;text-align:left; color: #2FA4E7; margin-top:10px; }
    .highlight { color: #0088CC; font-weight:bold; }
</style>

<?php
if ($message) {
    echo "<div class=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
}
?>
<?php
if ($success_message) {
    echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $success_message . "</div>";
}
?>

<h3 class="title"><?php echo $page_title; ?></h3>
<?php echo form_open("module=messaging&view=send&id=".$id); ?>
<h4 style="margin-top:10px;"><?php
    echo $this->lang->line("write_here");
    ?></h4>
<?php echo form_textarea('message', html_entity_decode($com->comment), 'class="input-block-level" id="message"'); ?>
<button type="submit" id="send" class="btn btn-primary" style="float:right; margin-top: 5px;"><?php echo $this->lang->line("send"); ?></button>
<?php echo form_close(); ?> 
<div class="clearfix"></div>