<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line('update_info'); ?></p>
<?php $attrib = array('class' => 'form-horizontal');?>
<?php echo form_open("module=logging&view=clear", $attrib);?>
<div class="control-group">
    <label class="control-label">Clear Logging Data.</label>
    <div class="controls">
        <div class="control-group">
            <label class="control-label">Clear Data Logs</label> 
            <div class="controls">
                <?php echo form_checkbox('logdata', '1', FALSE, 'id=cblbr '); ?>
                <!input type="checkbox" name="logs[]" disabled="true" id="cblbr" value="1"-->
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Clear User Logs</label> 
            <div class="controls">
                <?php echo form_checkbox('logusr', '1', FALSE, 'id=cblusr '); ?>
                <!input type="checkbox" name="logs[]" disabled="true" id="cblusr" value="1"-->
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Clear Message Logs</label> 
            <div class="controls">
                <?php echo form_checkbox('logmsg', '1', FALSE, 'id=cblmsg '); ?>
                <!input type="checkbox" name="logs[]" disabled="true" id="cblmsg" value="1"-->
            </div>
        </div>
        <div id="log_det"></div>
    </div>
</div>
<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("apply"), 'class="btn btn-primary"'); ?> </div>
</div>
<?php echo form_close(); ?> 