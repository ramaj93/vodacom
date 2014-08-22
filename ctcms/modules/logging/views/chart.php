<?php
foreach ($monthly_logs as $month_log) {
    $months[] = date('M-Y', strtotime($month_log->month));
    $dAdded[] = $month_log->added;
    $dModified[] = $month_log->modified;
    $dDeleted[] = $month_log->deleted;
    $purchases[] = $month_log->sent;
}
/*
  foreach($monthly_purchases as $month_purchase) {
  $purchases[] = $month_purchase->purchases;
  } */
?>   
<script src="<?php echo base_url(); ?>/assets/js/sl/highcharts.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/sl/modules/exporting.js"></script>
<script type="text/javascript">
    $(function() {
        /*
         // Radialize the colors
         Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
         return {
         radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
         stops: [
         [0, color],
         [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
         ]
         };
         });
         */

        $('#chart').highcharts({
            chart: {
            },
            credits: {
                enabled: false
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: [<?php
foreach ($months as $month) {
    echo "'" . $month . "', ";
}
?>]
            },
            yAxis: {
                min: 0,
                title: ""
            },
            tooltip: {
                shared: true,
                headerFormat: '<span style="font-size:14px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="color:{series.color};padding:0;text-align:right;"><?php echo CURRENCY_PREFIX; ?> <b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                useHTML: true,
                valueDecimals: 2,
                style: {
                    fontSize: '13px',
                    padding: '10px',
                    fontWeight: 'bold',
                    color: '#000000'
                }
            },
            series: [{
                    type: 'column',
                    name: '<?php echo $this->lang->line("dt_upd"); ?>',
                    data: [<?php
echo implode(', ', $dModified);
?>]
                },
                {
                    type: 'column',
                    name: '<?php echo $this->lang->line("dt_del"); ?>',
                    data: [<?php
echo implode(', ', $dDeleted);
?>]
                },
                {
                    type: 'column',
                    name: '<?php echo $this->lang->line("dt_added"); ?>',
                    data: [<?php
echo implode(', ', $dAdded);
?>]
                }, {
                    type: 'spline',
                    name: '<?php echo $this->lang->line("msgs_sent"); ?>',
                    data: [<?php
echo implode(', ', $purchases);
?>],
                    marker: {
                        lineWidth: 2,
                        states: {
                            hover: {
                                lineWidth: 4
                            }
                        },
                        lineColor: Highcharts.getOptions().colors[3],
                        fillColor: 'white'
                    }
                }, {
                    type: 'pie',
                    name: '<?php echo $this->lang->line("gen_overview"); ?>',
                    data: [
                        ['<?php echo $this->lang->line("data_del"); ?>', <?php echo $tdeleted; ?>],
                        ['<?php echo $this->lang->line("data_upd"); ?>', <?php echo $tupdated; ?>],
                        ['<?php echo $this->lang->line("data_add"); ?>', <?php echo $tadded; ?>],
                        ['<?php echo $this->lang->line("data_sent"); ?>', <?php echo $tsent; ?>],
                    ],
                    center: [80, 42],
                    size: 80,
                    showInLegend: false,
                    dataLabels: {
                        enabled: false
                    }
                }]
        });
    });
</script>
<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("overview_chart_heading"); ?></p>
<p>&nbsp;</p>
<div id="chart" style="width:100%; height:450px;"></div>
<p class="text-center"><?php echo $this->lang->line("chart_lable_toggle"); ?></p>