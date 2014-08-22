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
<p><?php echo $this->lang->line("enter_branch_info"); ?></p>

<?php $attrib = array('class' => 'form-horizontal');
echo form_open_multipart("module=branches&view=edit&cid=" . $cid . "&brid=" . $brid, $attrib);
?>

<div class="control-group">
            <?php $enable = ($this->ion_auth->in_group("configuration") ? "disabled=\"true\"" : ""); ?>
    <div class="control-group">
        <label class="control-label" for="customer"><?php echo $this->lang->line("customer"); ?></label>
        <div class="controls">  <?php
            $cst = array();
            foreach ($customers as $customer) {
                $cst[$customer->id] = $customer->company;
            }
            echo form_dropdown('customer', $cst, (isset($_POST['customer']) ? $_POST['customer'] : $branch->customer_id), 'id="customer" ' . $enable . ' class="span4 tip chzn-select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("customer") . '" required="required" data-error="' . $this->lang->line("customer") . ' ' . $this->lang->line("is_required") . '"');
            ?> </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="br_name"><?php echo $this->lang->line("branch_name"); ?></label>
        <div class="controls"> <?php echo form_input('br_name', (isset($_POST['br_name']) ? $_POST['br_name'] : $branch->branch_name), 'class="span4 tip" id="br_name"' . $enable . ' title="' . $this->lang->line("branch_name") . '" required="required" data-error="' . $this->lang->line("branch_name") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
    </div>
    <?php if(!$this->ion_auth->in_group('configuration')){?>
    <div class="control-group">
        <label class="control-label" for="technology"><?php echo $this->lang->line("technology"); ?></label>
        <div class="controls"><?php
            $techs = array();
            foreach ($technologies as $technology) {
                $techs[$technology->id] = $technology->name;
            }?>
            </div>
            <div class="controls"> <?php echo form_dropdown('technology', $techs, (isset($_POST['technology']) ? $_POST['technology'] : $techs[$branch->technology]), 'class="span4 tip" id="technology" title="' . $this->lang->line("technology") . '" required="required" data-error="' . $this->lang->line("technology") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>  
        </div>
        <div class="control-group">
            <label class="control-label" for="bandwidth"><?php echo $this->lang->line("bandwidth"); ?></label>
            <div class="controls"><?php
                $bdw = array();
                foreach ($bandwidths as $bd) {
                    $bdw[$bd->id] = $bd->name;
                }
                ?></div>
            <div class="controls"> <?php echo form_dropdown('bandwidth', $bdw, '', 'class="span4 tip" id="bandwidth" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("bandwidth") . '" title="' . $this->lang->line("bandwidth") . '" required="required" data-error="' . $this->lang->line("technology") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>  
        </div>
        <div class="control-group">
            <label class="control-label" for="service"><?php echo $this->lang->line("service"); ?></label>
            <div class="controls"><?php
                $serv = array();
                foreach ($services as $service) {
                    $serv[$service->id] = $service->name;
                }
                ?></div>
            <div class="controls"> <?php echo form_dropdown('service', $serv, '', 'class="span4 tip" id="service" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("service") . '" title="' . $this->lang->line("service") . '" required="required" data-error="' . $this->lang->line("service") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>  
        </div>
        <div class="control-group">
            <label class="control-label" for="mac_addr"><?php echo $this->lang->line("mac_addr"); ?></label>
            <div class="controls"> <?php echo form_input('mac_addr', (isset($_POST['mac_addr']) ? $_POST['mac_addr'] : $branch->mac_addr), 'class="span4 tip" id="mac_addr" title="' . $this->lang->line("mac_addr") . '" required="required" data-error="' . $this->lang->line("mac_addr") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="trpr1"><?php echo $this->lang->line("traffic_pro1"); ?></label>
            <div class="controls"> <?php echo form_input('trpr1', (isset($_POST['trpr1']) ? $_POST['trpr2'] : $branch->traffic_pro_1), 'class="span4 tip" id="trpr1" title="' . $this->lang->line("traffic_pro1") . '" required="required" data-error="' . $this->lang->line("traffic_pro1") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="trpr2"><?php echo $this->lang->line("traffic_pro2"); ?></label>
            <div class="controls"> <?php echo form_input('trpr2', (isset($_POST['trpr2']) ? $_POST['trpr2'] : $branch->traffic_pro_2), 'class="span4 tip" id="trpr2" title="' . $this->lang->line("traffic_pro2") . '" required="required" data-error="' . $this->lang->line("traffic_pro2") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="donor_site"><?php echo $this->lang->line("donor_site"); ?></label>
            <div class="controls"> <?php echo form_input('donor_site', (isset($_POST['donor_site']) ? $_POST['donor_site'] : $branch->donor_site), 'class="span4 tip" id="donor_site" title="' . $this->lang->line("donor_site") . '" required="required"  data-error="' . $this->lang->line("donor_site") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
        </div>


        <div class="control-group">
            <label class="control-label" for="region"><?php echo $this->lang->line("region"); ?></label>
            <div class="controls"> <?php echo form_input('region', (isset($_POST['region']) ? $_POST['region'] : $branch->region), 'class="span4" id="region"' . '" required="required" data-error="' . $this->lang->line("region") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div>
        </div> 
<?php } ?>
<?php if ($this->ion_auth->in_group(array('configuration', 'admin', 'owner'))) { ?>
        <div class="control-group">
            <label class="control-label"><?php echo $this->lang->line("branch_ips"); ?></label>
            <div class="span9">
                <div>
                    <label class="control-label" for="serv_ip"><?php echo $this->lang->line("service_ip"); ?></label>
                    <div class="controls"> <?php echo form_input('serv_ip', (isset($_POST['serv_ip']) ? $_POST['serv_ip'] : $brips->service_ip), 'class="span4" id="serv_ip"' . '" required="required" data-error="' . $this->lang->line("service_ip") . ' ' . $this->lang->line("is_required") . '"'); ?>
                    </div> 
                </div>
                <br>
                <div>
                    <label class="control-label" for="bwan_ip"><?php echo $this->lang->line("wan_ip"); ?></label>
                    <div class="controls"> <?php echo form_input('bwan_ip', (isset($_POST['bwan_ip']) ? $_POST['bwan_ip'] : $brips->wan_ip), 'class="span4" id="bwan_ip"' . ' required="required" data-error="' . $this->lang->line("wan_ip") . ' ' . $this->lang->line("is_required") . '"'); ?>
                    </div>
                </div>
                <br>
                <div>
                    <label class="control-label" for="bgatwy_ip"><?php echo $this->lang->line("gateway_ip"); ?></label>
                    <div class="controls"> <?php echo form_input('bgatwy_ip', (isset($_POST['bgatwy_ip']) ? $_POST['bgatwy_ip'] : $brips->gateway_ip), 'class="span4" id="bgatwy_ip"' . ' required="required" data-error="' . $this->lang->line("gateway_ip") . ' ' . $this->lang->line("is_required") . '"'); ?>
                    </div> 
                </div>
                <br>
                <div>
                    <label class="control-label" for="bsbn_mask"><?php echo $this->lang->line("subnet_mask"); ?></label>
                    <div class="controls"> <?php echo form_input('bsbn_mask', (isset($_POST['bsbn_mask']) ? $_POST['bsbn_mask'] : $brips->subnet_mask), 'class="span4" id="bsbn_mask"' . '" required="required" data-error="' . $this->lang->line("subnet_mask") . ' ' . $this->lang->line("is_required") . '"'); ?>
                    </div>
                </div>
                <br>
                <div>
                    <label class="control-label" for="bvlan_ip"><?php echo $this->lang->line("vlan_ip"); ?></label>
                    <div class="controls"> <?php echo form_input('bvlan_ip', (isset($_POST['bvlan_ip']) ? $_POST['bvlan_ip'] : $brips->vlan_ip), 'class="span4" id="bvlan_ip"' . '" required="required" data-error="' . $this->lang->line("vlan_ip") . ' ' . $this->lang->line("is_required") . '"'); ?>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="control-group">
            <label class="control-label"><?php echo $this->lang->line("man_ips"); ?></label>
            <div class="span9">
                <div>
                    <label class="control-label" for="man_ip"><?php echo $this->lang->line("man_ip"); ?></label>
                    <div class="controls"> <?php echo form_input('man_ip', (isset($_POST['man_ip']) ? $_POST['man_ip'] : $manips->management_ip), 'class="span4" id="man_ip"' . '" required="required" data-error="' . $this->lang->line("man_ip") . ' ' . $this->lang->line("is_required") . '"'); ?>
                    </div> 
                </div>            
                <br>
                <div>
                    <label class="control-label" for="mgatwy_ip"><?php echo $this->lang->line("gateway_ip"); ?></label>
                    <div class="controls"> <?php echo form_input('mgatwy_ip', (isset($_POST['mgatwy_ip']) ? $_POST['mgatwy_ip'] : $manips->gateway_ip), 'class="span4" id="mgatwy_ip"' . ' required="required" data-error="' . $this->lang->line("gateway_ip") . ' ' . $this->lang->line("is_required") . '"'); ?>
                    </div> 
                </div>
                <br>
                <div>
                    <label class="control-label" for="msbn_mask"><?php echo $this->lang->line("subnet_mask"); ?></label>
                    <div class="controls"> <?php echo form_input('msbn_mask', (isset($_POST['donor_site']) ? $_POST['msbn_mask'] : $manips->subnet_mask), 'class="span4" id="msbn_mask"' . '" required="required" data-error="' . $this->lang->line("subnet_mask") . ' ' . $this->lang->line("is_required") . '"'); ?>
                    </div>
                </div>
                <br>
                <div>
                    <label class="control-label" for="mvlan_ip"><?php echo $this->lang->line("vlan_ip"); ?></label>
                    <div class="controls"> <?php echo form_input('mvlan_ip', (isset($_POST['mvlan_ip']) ? $_POST['mvlan_ip'] : $manips->vlan_ip), 'class="span4" id="mvlan_ip"' . '" required="required" data-error="' . $this->lang->line("vlan_ip") . ' ' . $this->lang->line("is_required") . '"'); ?>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>

</div>
<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("update_branch"), 'class="btn btn-primary"'); ?> </div>
</div>
<?php echo form_close(); ?> 
<div id="loading" style="display: none;">
    <div class="blackbg"></div><div class="loader"></div>
</div>
