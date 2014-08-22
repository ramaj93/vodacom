<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(function() {
        $('form').form();
    });
</script>

<?php if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
} ?>


<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>

<?php $attrib = array('class' => 'form-horizontal');
echo form_open("module=customers&view=add", $attrib); ?>

<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("serial_no"); ?></label>
    <div class="controls"> <?php echo form_input('serial_no', (isset($_POST['serial_no']) ? $_POST['serial_no'] : $serial), 'class="span4" id="serial_no" pattern=".{2,55}" required="required" data-error="' . $this->lang->line("serial_no") . ' ' . $this->lang->line("is_required") . '"'); ?>
    </div>
</div> 
<div class="control-group">
    <label class="control-label" for="company"><?php echo $this->lang->line("company"); ?></label>
    <div class="controls"> <?php echo form_input($company, '', 'class="span4 tip" title="' . $this->lang->line("bypass") . '" id="company" pattern=".{1,55}" required="required" data-error="' . $this->lang->line("company") . ' ' . $this->lang->line("is_required") . '"'); ?>
    </div>
</div> 
<div class="control-group">
    <label class="control-label" for="address"><?php echo $this->lang->line("address"); ?></label>
    <div class="controls"> <?php echo form_input($address, '', 'class="span4" id="address" pattern=".{2,255}"  data-error="' . $this->lang->line("address") . ' ' . $this->lang->line("is_required") . '"'); ?>
    </div>
</div>  
<div class="control-group">
    <label class="control-label" for="city"><?php echo $this->lang->line("city"); ?></label>
    <div class="controls"> <?php echo form_input($city, '', 'class="span4" id="city" pattern=".{2,55}"  data-error="' . $this->lang->line("city") . ' ' . $this->lang->line("is_required") . '"'); ?>
    </div>
</div> 
<div class="control-group">
    <label class="control-label" for="postal_code"><?php echo $this->lang->line("postal_code"); ?></label>
    <div class="controls"> <?php echo form_input($postal_code, '', 'class="span4" id="postal_code"pattern=".{4,8}"  data-error="' . $this->lang->line("postal_code") . ' ' . $this->lang->line("is_required") . '"'); ?>
    </div>
</div> 
<div class="control-group">
    <label class="control-label" for="country"><?php echo $this->lang->line("country"); ?></label>
    <div class="controls"> <?php echo form_input($country, '', 'class="span4" id="country" pattern=".{2,55}" required="required" data-error="' . $this->lang->line("country") . ' ' . $this->lang->line("is_required") . '"'); ?>
    </div>
</div> 
<br>
<div class="control-group">
    <label class="control-label"><?php echo $this->lang->line("current_contacts"); ?></label>
    <div class="span9">
        <div>
            <label class="control-label" for="name"><?php echo $this->lang->line("name"); ?></label>
            <div class="controls"> <?php echo form_input('name', '', 'class="span4" id="name"' . '" required="required" data-error="' . $this->lang->line("name") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div> 
        </div>
        <br>
        <div>
            <label class="control-label" for="email"><?php echo $this->lang->line("email"); ?></label>
            <div class="controls"> <?php echo form_input('email', '', 'class="span4" id="email"' . ' required="required" data-error="' . $this->lang->line("email") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div>
        </div>
        <br>
        <div>
            <label class="control-label" for="phone"><?php echo $this->lang->line("phone"); ?></label>
            <div class="controls"> <?php echo form_input('phone', '', 'class="span4" id="phone"' . ' required="required" data-error="' . $this->lang->line("phone") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div> 
        </div>
        <br>
        <div>
            <label class="control-label" for="location"><?php echo $this->lang->line("location"); ?></label>
            <div class="controls"> <?php echo form_input('location', '', 'class="span4" id="location"' . '" required="required" data-error="' . $this->lang->line("location") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div>
        </div>
        <br>
        <div>
            <label class="control-label" for="name"><?php echo $this->lang->line("name"); ?></label>
            <div class="controls"> <?php echo form_input('name', '', 'class="span4" id="name"' . '" required="required" data-error="' . $this->lang->line("name") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div> 
        </div>
        <br>
        <div>
            <label class="control-label" for="email"><?php echo $this->lang->line("email"); ?></label>
            <div class="controls"> <?php echo form_input('email', '', 'class="span4" id="email"' . ' required="required" data-error="' . $this->lang->line("email") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div>
        </div>
        <br>
        <div>
            <label class="control-label" for="phone"><?php echo $this->lang->line("phone"); ?></label>
            <div class="controls"> <?php echo form_input('phone', '', 'class="span4" id="phone"' . ' required="required" data-error="' . $this->lang->line("phone") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div> 
        </div>
        <br>
        <div>
            <label class="control-label" for="location"><?php echo $this->lang->line("location"); ?></label>
            <div class="controls"> <?php echo form_input('location', '', 'class="span4" id="location"' . '" required="required" data-error="' . $this->lang->line("location") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div>
        </div>
    </div> 
</div> 
<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("add_customer"), 'class="btn btn-primary"'); ?> </div>
</div>
<?php echo form_close(); ?> 
