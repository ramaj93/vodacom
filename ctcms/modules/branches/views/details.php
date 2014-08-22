<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $page_title . " &middot; " . SITE_NAME; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo $this->config->base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
        <script src="<?php echo $this->config->base_url(); ?>assets/js/jquery.js"></script>
        <link href="<?php echo $this->config->base_url(); ?>assets/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="<?php echo $this->config->base_url(); ?>assets/css/sma.css" rel="stylesheet">
        <!--[if lt IE 9]>
              <script src="<?php echo $this->config->base_url(); ?>assets/js/html5shiv.js"></script>
        <![endif]-->

        <style type="text/css">

            #one-column-emphasis
            {
                font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
                font-size: 12px;
                margin: 45px;
                width: 480px;
                text-align: left;
                border-collapse: collapse;
            }
            #one-column-emphasis th
            {
                font-size: 14px;
                font-weight: normal;
                padding: 12px 15px;
                color: #039;
            }
            #one-column-emphasis td
            {
                padding: 10px 15px;
                color: #454545;
                border-bottom: 1px solid #DDD;
            }
            .oce-first
            {
                background: #F6F6F6;
                border-right: 10px solid transparent;
                border-left: 10px solid transparent;
                font-weight:bold;
            }
            #one-column-emphasis tr:hover td
            {
                color: #333;
                background: #EEE;
            }

        </style>
    </head>
    <body>
        <h3 class="title" style="text-align:center;"><?php echo $branch->branch_name; ?></h3>
        <table class="table table-bordered table-hover table-striped table-condensed">
            <tbody>
                <tr>
                    <td><?php echo $this->lang->line("customer"); ?></td>
                    <td><?php echo $customer; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("technology"); ?></td>
                    <td><?php echo $technology; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("service"); ?></td>
                    <td><?php echo $service->name; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("mac_addr"); ?></td>
                    <td><?php echo $branch->mac_addr; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("traffic_pro1"); ?></td>
                    <td><?php echo $branch->traffic_pro_1; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("traffic_pro2"); ?></td>
                    <td><?php echo $branch->traffic_pro_2; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("donor_site"); ?></td>
                    <td><?php echo $branch->donor_site; ?></td>
                </tr>   
                <tr>
                    <td><?php echo $this->lang->line("region"); ?></td>
                    <td><?php echo $branch->region; ?></td>
                </tr> 
                <tr>
                    <td><h4><?php echo $this->lang->line("branch_ips"); ?></h4></td><td>&nbsp;</td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("service_ip"); ?></td><td><?php echo $brips->service_ip; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("wan_ip"); ?></td><td><?php echo $brips->wan_ip; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("gateway_ip"); ?></td><td><?php echo $brips->gateway_ip; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("subnet_mask"); ?></td><td><?php echo $brips->subnet_mask; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("vlan_ip"); ?></td><td><?php echo $brips->vlan_ip; ?></td>
                </tr>
                <tr>
                    <td><h4><?php echo $this->lang->line("man_ips"); ?></h4></td><td>&nbsp;</td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("man_ip"); ?></td><td><?php echo $manips->management_ip; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("gateway_ip"); ?></td><td><?php echo $manips->gateway_ip; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("subnet_mask"); ?></td><td><?php echo $manips->subnet_mask; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("vlan_ip"); ?></td><td><?php echo $manips->vlan_ip; ?></td>
                </tr>

            </tbody>
        </table>
    </body>
</html>