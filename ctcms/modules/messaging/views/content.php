<script src="<?php echo $this->config->base_url(); ?>assets/media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
<style type="text/css">
    .text_filter { width: 100% !important; border: 0 !important; box-shadow: none !important;  border-radius: 0 !important;  padding:0 !important; margin:0 !important; }
    .select_filter { width: 100% !important; padding:0 !important; height: auto !important; margin:0 !important;}
</style>
<script>
    $(document).ready(function() {
        $('#fileData').dataTable({
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "aaSorting": [[0, "desc"]],
            "iDisplayLength": <?php echo ROWS_PER_PAGE; ?>,
            'bProcessing': true,
            'bServerSide': true,
            'sAjaxSource': '<?php echo base_url(); ?>index.php?module=messaging&view=getdatatableajax',
            'fnServerData': function(sSource, aoData, fnCallback)
            {
                aoData.push({"name": "<?php echo $this->security->get_csrf_token_name(); ?>", "value": "<?php echo $this->security->get_csrf_hash() ?>"});
                $.ajax
                        ({
                            'dataType': 'json',
                            'type': 'POST',
                            'url': sSource,
                            'data': aoData,
                            'success': fnCallback
                        });
            },
            "oTableTools": {
                "sSwfPath": "assets/media/swf/copy_csv_xls_pdf.swf",
                "aButtons": [
                    // "copy",
                ]
            },
            "oLanguage": {
                "sSearch": "Filter: "
            },
            "aoColumns": [
                null,
                null,
                null,
                null,
                {"bSortable": false}
            ]

        }).columnFilter({aoColumns: [
                //{ type: "text", bRegex:true },
                //null, null, null, null, null, null, null, null,
                {type: "text", bRegex: true},
                {type: "text", bRegex: true},
                {type: "text", bRegex: true},
                {type: "text", bRegex: true},
                null

            ]});

    });

</script>
<script>
    function checkID(id) {
        if (<?php echo $id ?> == id)
        {
            bootbox.alert("You cannot send a message to yourself.");
            return false;
        }
        return true;
    }
</script>
<?php
if ($success_message) {
    echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $success_message . "</div>";
}
?>
<?php
if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
}
?>


<h3 class="title"><?php echo $page_title; ?></h3>
<p class="introtext"><?php echo $this->lang->line("messaging_intro"); ?></p>

<table id="fileData" cellpadding=0 cellspacing=10 class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th><?php echo $this->lang->line("name"); ?></th>
            <th><?php echo $this->lang->line("department"); ?></th>
            <th><?php echo $this->lang->line("phone"); ?></th>
            <th><?php echo $this->lang->line("email_address"); ?></th>
            <th style="width:45px;"><?php echo $this->lang->line("actions"); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="7" class="dataTables_empty">Loading data from server</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th>[<?php echo $this->lang->line("name"); ?>]</th>
            <th>[<?php echo $this->lang->line("department"); ?>]</th>
            <th>[<?php echo $this->lang->line("phone"); ?>]</th>
            <th>[<?php echo $this->lang->line("email_address"); ?>]</th>
            <th style="width:45px;"><?php echo $this->lang->line("actions"); ?></th>
        </tr>
    </tfoot>
</table>
