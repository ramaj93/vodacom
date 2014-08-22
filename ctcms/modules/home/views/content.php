<script type="text/javascript">
    $(function() {
        $(".tip").tooltip();
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
<?php echo form_open("module=home&view=update_comment"); ?>
<h4 style="margin-top:10px;"><?php
    if ($this->ion_auth->in_group('owner')) {
        echo $this->lang->line("admin_comment");
    } else {
        echo 'User Comment';
    }
    ?></h4>
<?php echo form_textarea('comment', html_entity_decode($com->comment), 'class="input-block-level" id="note"'); ?>
<button type="submit" class="btn btn-primary" style="float:right; margin-top: 5px;"><?php echo $this->lang->line("update_comment"); ?></button>
<?php echo form_close(); ?> 
<div class="clearfix"></div>
<?php echo $calendar; ?>
<div class="clearfix"></div>

<!-- Big buttons -->

<?php if ($this->ion_auth->in_group(array('owner', 'admin', 'configuration','project'))) { ?>
    <ul class="dash">

        <li>
            <a class="tip" href="index.php?module=branches" title="<?php echo $this->lang->line("list_branches"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/products.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("branches"); ?></span></span>
            </a>
        </li>
        <?php if (!$this->ion_auth->in_group('configuration')) { ?>
            <li>
                <a class="tip" href="index.php?module=branches&view=add" title="<?php echo $this->lang->line("new_branch"); ?>">
                    <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/product_add.png" alt="" /></i>
                    <span><span><?php echo $this->lang->line("new_branch"); ?></span></span>
                </a>
            </li>
        <?php } ?>
        <?php if ($this->ion_auth->in_group(array('owner', 'admin'))) { ?>
            <li>
                <a class="tip" href="index.php?module=auth&view=users" title="<?php echo $this->lang->line("list_users"); ?>">
                    <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/users.png" alt="" /></i>
                    <span><span><?php echo $this->lang->line("users"); ?></span></span>
                </a>
            </li>
            <li>
                <a class="tip" href="index.php?module=auth&view=create_user" title="<?php echo $this->lang->line("new_user"); ?>">
                    <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/user_add.png" alt="" /></i>
                    <span><span><?php echo $this->lang->line("new_user"); ?></span></span>
                </a>
            </li>
            <li>
                <a class="tip" href="index.php?module=settings&view=system_setting" title="<?php echo $this->lang->line("settings"); ?>">
                    <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/settings.png" alt="" /></i>
                    <span><span><?php echo $this->lang->line("settings"); ?></span></span>
                </a>
            </li>
        <?php } ?>
        <li>
            <a class="tip" href="index.php?module=auth&view=change_password" title="<?php echo $this->lang->line("change_password"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/user_edit.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("change_password"); ?></span></span>
            </a>
        </li>

        <li>
            <a class="tip" href="index.php?module=customers" title="<?php echo $this->lang->line("list_customers"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/customers.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("customers"); ?></span></span>
            </a>
        </li>

        <?php if (!$this->ion_auth->in_group('configuration')) { ?>
            <li>
                <a class="tip" href="index.php?module=customers&view=add" title="<?php echo $this->lang->line("new_customer"); ?>">
                    <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/customer_add.png" alt="" /></i>
                    <span><span><?php echo $this->lang->line("new_customer"); ?></span></span>
                </a>
            </li>
        <?php } ?>
        <li>
            <a class="tip" href="index.php?module=auth&view=logout" title="<?php echo $this->lang->line("logout"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/logoff.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("logout"); ?></span></span>
            </a>
        </li>
        <?php if (ENABLE_MSG) { ?>
            <li>
                <a class="tip" href="index.php?module=messaging" title="<?php echo $this->lang->line("send_msg"); ?>">
                    <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/results.png" alt="" /></i>
                    <span><span><?php echo $this->lang->line("send_msg"); ?></span></span>
                </a>
            </li>
        <?php } ?>
    </ul>
<?php } ?>


<?php if ($this->ion_auth->in_group('purchaser')) { ?>
    <ul class="dash">

        <li>
            <a class="tip" href="index.php?module=branches" title="<?php echo $this->lang->line("branches"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/products.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("branches"); ?></span></span>
            </a>
        </li>

        <li>
            <a class="tip" href="index.php?module=inventories" title="<?php echo $this->lang->line("inventories"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/puchase.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("purchases"); ?></span></span>
            </a>
        </li>
        <li>
            <a class="tip" href="index.php?module=inventories&view=add" title="<?php echo $this->lang->line("new_purchase"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/puchase_add.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("new_purchase"); ?></span></span>
            </a>
        </li>
        <li style="position:relative;">
            <a class="tip" href="index.php?module=logging&view=branches" title="<?php echo $this->lang->line("product_alerts"); ?>">
    <!--<span style="background:#C00; width: auto; position: absolute; top: -5px; right:5px; color:#FFF; border-radius: 5px; font-weight:bold; padding:2px 5px;"> </span>-->
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/alert.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("product_alerts"); ?></span></span>
            </a>
        </li>
        <li>
            <a class="tip" href="index.php?module=logging&view=purchases" title="<?php echo $this->lang->line("logging"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/report.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("logging"); ?></span></span>
            </a>
        </li>

        <li>
            <a class="tip" href="index.php?module=auth&view=change_password" title="<?php echo $this->lang->line("change_password"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/user_edit.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("change_password"); ?></span></span>
            </a>
        </li>
        <li>
            <a class="tip" href="index.php?module=auth&view=logout" title="<?php echo $this->lang->line("logout"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/logoff.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("logout"); ?></span></span>
            </a>
        </li>

    </ul>

    <div class="clearfix"></div>

<?php } ?>

<?php if ($this->ion_auth->in_group('viewer')) { ?>
    <ul class="dash">

        <li>
            <a class="tip" href="index.php?module=branches" title="<?php echo $this->lang->line("list_branches"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/products.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("branches"); ?></span></span>
            </a>
        </li>
        <li>
            <a class="tip" href="index.php?module=customers" title="<?php echo $this->lang->line("customers"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/customers.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("customers"); ?></span></span>
            </a>
        </li>
        <li>
            <a class="tip" href="index.php?module=auth&view=change_password" title="<?php echo $this->lang->line("change_password"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/user_edit.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("change_password"); ?></span></span>
            </a>
        </li>
        <li>
            <a class="tip" href="index.php?module=auth&view=logout" title="<?php echo $this->lang->line("logout"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/logoff.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("logout"); ?></span></span>
            </a>
        </li>

        <li>
            <a class="tip" href="index.php?module=auth&view=logout" title="<?php echo $this->lang->line("send_msg"); ?>">
                <i><img src="<?php echo $this->config->base_url(); ?>assets/img/icons/results.png" alt="" /></i>
                <span><span><?php echo $this->lang->line("send_msg"); ?></span></span>
            </a>
        </li>
    </ul>

    <div class="clearfix"></div>

<?php } ?>


<!-- End of Big buttons -->
