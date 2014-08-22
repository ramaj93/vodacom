<style type="text/css">
    .loader { background-color: #CF4342; color: white; top: 30%; left: 50%; margin-left: -50px; position: fixed; padding: 3px; width:100px;	height:100px; background:url('<?php echo $this->config->base_url(); ?>assets/img/wheel.gif') no-repeat center; }
    .blackbg { z-index: 5000; background-color: #666; -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)"; filter: alpha(opacity=20); opacity: 0.2; width:100%; height:100%; top:0; left:0; position:absolute;}
</style>
<link href="<?php echo $this->config->base_url(); ?>assets/css/bootstrap-fileupload.css" rel="stylesheet">
<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>

<?php
if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
}
?>

<h3 class="title"><?php echo $page_title; ?></h3>
<p class="text-warning"><?php echo $this->lang->line("clr_msg_not"); ?> </p>

<?php $attrib = array('class' => 'form-horizontal');
echo form_open_multipart("module=messaging&view=clear",$attrib);
?>

<div class="control-group">
        <div class="control-group">
            <label class="control-label" for="clrmsg"><?php echo $this->lang->line("clrmsg"); ?></label>
            <div class="controls">
            <div class="controls"> <?php echo form_dropdown('clrmsg', $intervals, '', 'class="span4 tip" id="bandwidth" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("interval") . '" title="' . $this->lang->line("interval") . '" required="required" data-error="' . $this->lang->line("technology") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>  
        </div>

</div>
<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("apply"), 'class="btn btn-primary"'); ?> </div>
</div>
</div>
<?php echo form_close(); ?> 

