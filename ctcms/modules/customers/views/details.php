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
        <h3 class="title" style="text-align:center;"><?php echo $customer->company; ?></h3>
        <table class="table table-bordered table-hover table-striped table-condensed">
            <tbody>
                <tr>
                    <td><?php echo $this->lang->line("serial"); ?></td>
                    <td><?php echo $customer->name; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("company"); ?></td>
                    <td><?php echo $customer->company; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("address"); ?></td>
                    <td><?php echo $customer->address; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("postal_code"); ?></td>
                    <td><?php echo $customer->postal_code; ?></td>
                </tr>
                <tr>
                <tr>
                    <td><?php echo $this->lang->line("city"); ?></td>
                    <td><?php echo $customer->city; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("country"); ?></td>
                    <td><?php echo $customer->country; ?></td>
                </tr>
                <tr>
                    <td><h4><?php echo $this->lang->line("contact_details"); ?></h4></td><td>&nbsp;</td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("name"); ?></td><td><?php echo $contacts[0]->name; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("email"); ?></td><td><?php echo $contacts[0]->email; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("phone"); ?></td><td><?php echo $contacts[0]->phone; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("location"); ?></td><td><?php echo $contacts[0]->location; ?></td>
                </tr>
                <tr>
                    <td><h4><?php echo $this->lang->line("contact_details"); ?></h4></td><td>&nbsp;</td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("name"); ?></td><td><?php echo $contacts[1]->name; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("email"); ?></td><td><?php echo $contacts[1]->email; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("phone"); ?></td><td><?php echo $contacts[1]->phone; ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line("location"); ?></td><td><?php echo $contacts[1]->location; ?></td>
                </tr>

            </tbody>
        </table>
    </body>
</html>