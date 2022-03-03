<?php
$qvms = $info['qvms'];
$servers = $info['servers'];
$users = $info['users'];
$rootswitch_logs = $info['rootswitch_logs'];
$slaveswitch_logs = $info['slaveswitch_logs'];
$totalnetwork_bandwidth = array();
$root_switchdata = array();
$slave_switchdata = array();
if(count($rootswitch_logs)>0)
{

        for($i=0;$i<count($rootswitch_logs);$i++)
        {
                if(array_search($rootswitch_logs[$i]['unixtimestamp'], array_column($root_switchdata, '0')) !== False)
                {
                        for($j =0; $j<count($root_switchdata);$j++){
                                if(strval($rootswitch_logs[$i]['unixtimestamp']) == $root_switchdata[$j][0]){
                                        $root_switchdata[$j][1] = $root_switchdata[$j][1] + ($rootswitch_logs[$i]['tx_bytes'] + $rootswitch_logs[$i]['rx_bytes'])*8;
                                        $totalnetwork_bandwidth[$j][1] = $totalnetwork_bandwidth[$j][1]+($rootswitch_logs[$i]['tx_bytes'] + $rootswitch_logs[$i]['rx_bytes'])*8;

                                }
                        }
                }
                else{
                        $k= count($root_switchdata);

                        $root_switchdata[$k][0] = strval(gmdate("Y-m-d H:i:s", $rootswitch_logs[$i]['unixtimestamp']));
                        $root_switchdata[$k][1] = ($rootswitch_logs[$i]['tx_bytes'] + $rootswitch_logs[$i]['rx_bytes'])*8;
                        $totalnetwork_bandwidth[$k][0] = strval(gmdate("Y-m-d H:i:s",$rootswitch_logs[$i]['unixtimestamp']));
                        $totalnetwork_bandwidth[$k][1] = ($rootswitch_logs[$i]['total_bytes'])*8;
                }


        }
}

if(count($slaveswitch_logs)>0)
{


        for($i=0;$i<count($slaveswitch_logs);$i++)
        {
			if(array_search($slaveswitch_logs[$i]['unixtimestamp'], array_column($slave_switchdata, '0')) !== False)
                {
                    for($j =0; $j<count($slave_switchdata);$j++){
                        if(strval($slaveswitch_logs[$i]['unixtimestamp']) == $slave_switchdata[$j][0]){
                             $slave_switchdata[$j][1] = $slave_switchdata[$j][1] + ($slaveswitch_logs[$i]['tx_bytes'] + $slaveswitch_logs[$i]['rx_bytes'])*8;
                                }
                        }
                }
                else{
                        $k= count($slave_switchdata);
                        $slave_switchdata[$k][0] = strval(gmdate("Y-m-d H:i:s",$slaveswitch_logs[$i]['unixtimestamp']));
                        $slave_switchdata[$k][1] = ($slaveswitch_logs[$i]['tx_bytes'] + $slaveswitch_logs[$i]['rx_bytes'])*8;
                }

                if(array_search(strval(gmdate("Y-m-d H:i:s",$slaveswitch_logs[$i]['unixtimestamp'])), array_column($totalnetwork_bandwidth, '0')) !== False){
                        for($j=0; $j<count($totalnetwork_bandwidth);$j++){
                                if(strval(gmdate("Y-m-d H:i:s",$slaveswitch_logs[$i]['unixtimestamp'])) == $totalnetwork_bandwidth[$j][0]){
                                        $totalnetwork_bandwidth[$j][1] = $totalnetwork_bandwidth[$j][1] + ($slaveswitch_logs[$i]['tx_bytes'] + $slaveswitch_logs[$i]['rx_bytes'])*8;
                                }
                        }
                }
                else{
                        $m= count($totalnetwork_bandwidth);
                       
                        $totalnetwork_bandwidth[$m][0] = strval(gmdate("Y-m-d H:i:s",$slaveswitch_logs[$i]['unixtimestamp']));
						 $totalnetwork_bandwidth[$m][1] = ($slaveswitch_logs[$i]['tx_bytes'] + $slaveswitch_logs[$i]['rx_bytes'])*8;
                }
        }
}
#dd($totalnetwork_bandwidth);
?>

@extends('layouts.master')

<title>MTD | Dashboard</title>
@section('content')
 <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MTD | Blacklist</title>

        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
                                      
									   <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart']});
            google.charts.setOnLoadCallback(drawChart);
			//var totalnetwork = <?php echo(json_encode($totalnetwork_bandwidth)) ?>;
			//totalnetwork.unshift(['DateTime','Total bandwidth utilization']);
            function drawChart() {
                // Define the chart to be drawn.
                                var today = <?php echo(json_encode(date("F j, Y"))); ?>;
                                var data1 = new google.visualization.DataTable();
                data1.addColumn('string', 'Date - Time');
                data1.addColumn('number', 'Bandwidth Utilization');
				data1.addRows(<?php echo(json_encode($totalnetwork_bandwidth)) ?>);
                var options1 = {'title':'Newtork bandwidth utilization  ',
                               'orientation': 'horizontal',
                               'width':'100%',
                               'height':500};
                var data2 = new google.visualization.DataTable();
                data2.addColumn('string', 'Date - Time');
                data2.addColumn('number', 'Bandwidth Utilization');
                data2.addRows(<?php echo(json_encode($root_switchdata)) ?>);
                var options2 = {'title':'root switch bandwidth utilization on ',
                               'orientation': 'horizontal',
                               'width':'100%',
                               'height':500};
                            var data3 = new google.visualization.DataTable();
                data3.addColumn('string', 'Date - Time');
                data3.addColumn('number', 'Bandwidth Utilization');
                data3.addRows(<?php echo(json_encode($slave_switchdata)) ?>);
                var options3 = {'title':'slave switch bandwidth utilization on ',
                               'orientation': 'horizontal',
                               'width':'100%',
                               'height':500};
                // Instantiate and draw the chart.
                                var chart1 = new google.visualization.AreaChart(document.getElementById('totalNewtorkGraph'));
                chart1.draw(data1, options1);
                var chart2 = new google.visualization.AreaChart(document.getElementById('rootSwitchGraph'));
                chart2.draw(data2, options2);
                                var chart3 = new google.visualization.AreaChart(document.getElementById('slaveSwitchGraph'));
                chart3.draw(data3, options3);
            }
        </script>

        <style>
            .info-box-number{
                            
							
							 font-size:32px;
            }
            span.label{
                font-size:12px;
            }
            .flot-x-axis .flot-tick-label {
                white-space: nowrap;
                transform: translate(-9px, 0) rotate(-70deg);
                text-indent: -100%;
                transform-origin: top right;
                text-align: right !important;
            }

        </style>


    </head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <div class="hold-transition skin-blue sidebar-mini">

        <div class="wrapper">

            <!-- Content Wrapper. Contains page content -->
        <!-- Main content -->
            <section class="content">

                <!-- Info boxes -->
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <a href={{ route('pages.servers')}}>
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-sitemap"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Currently Available Servers</span>
                                    <!-- VALUE OBTAINED FROM SIDEBAR.PHP -->
                                    <span class="info-box-number"><?php echo($servers); ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                                   <div class="col-md-4 col-sm-6 col-xs-12">
                        <a href={{ route('pages.users')}}>
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Current Users</span>
                                    <!-- VALUE OBTAINED FROM SIDEBAR.PHP -->
                                    <span class="info-box-number"><?php echo($users); ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <!-- /.col -->
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <a href={{ route('pages.qvms')}}>
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">TOTAL QVMs</span>
                                    <!-- VALUE OBTAINED FROM SIDEBAR.PHP -->
                                    <span class="info-box-number"><?php echo($qvms); ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>

                <!-- BANDWIDTH UTILIZATION -->
                <div class="row">
                    <div class="col-xs-12">
                        <!-- interactive chart -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <i class="fa fa-line-chart"></i>

                                <h3 class="box-title">Bandwidth Utilization</h3>
                                <div class="box-tools pull-right">
                                                        <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                    </button>

                            </div>
                            <div class="box-body">
                                                                <div id="totalNewtorkGraph" style="height: 100%; width:100%"></div>
                             </div>
                                                         </div>
                            <!-- /.box-body-->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- CURRENT NETWORK -->
                <div class="row">
                    <div class="col-xs-6">
                        <!-- interactive chart -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <i class="fa fa-line-chart"></i>

                                <h3 class="box-title">Root Switch</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                    </button>
                                </div>


                            </div>
                            <div class="box-body">
                                                                <div id="rootSwitchGraph" style="height: 100%; width:100%"></div>
                                                        </div>
                            <!-- /.box-body-->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->

                    <div class="col-xs-6">
                        <!-- interactive chart -->
                                                     
													  <div class="box box-primary">
                            <div class="box-header with-border">
                                <i class="fa fa-line-chart"></i>

                                <h3 class="box-title">Slave Switch</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                    </button>
                                </div>


                            </div>
                            <div class="box-body">
                                                        <div id="slaveSwitchGraph" style="height: 100%; width:100%"></div>
                            </div>
                        </div>
                            <!-- /.box-body-->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
        <!-- /.content-wrapper -->
        </div>

    </div>
    <!-- ./wrapper -->
        <div>
</body>
</html>

@endsection
                               
									  
