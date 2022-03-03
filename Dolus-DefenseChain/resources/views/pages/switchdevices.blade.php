<?php
$switches = $info['switches'];
$devices = $info['devices'];
$switchdevices = $info['switchdevices'];
$switch_to_device = array();
if(count($switchdevices)>0){
    for($i=0;$i<count($switchdevices);$i++){
        if(array_search($switchdevices[$i]["switchID"], array_column($switch_to_device, '0')) !== False){
			for($j =0; $j<count($switch_to_device);$j++){
				if(strval($switchdevices[$i]["switchID"]) == $switch_to_device[$j][0]){
					$switch_to_device[$j][1] = $switch_to_device[$j][1]+1;}
				}
			}      
		else{

			$k= count($switch_to_device);
			$switch_to_device[$k][0]= strval($switchdevices[$i]["switchID"]);
			$switch_to_device[$k][1]=1;
		}
	}
}
?>


@extends('layouts.master')

@section('content')
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MTD | Users</title>

        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart']});
            google.charts.setOnLoadCallback(drawChart);
			function drawChart() {
                // Define the chart to be drawn.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'switchID');
                data.addColumn('number', 'deviceIDs associated');
                data.addRows(<?php echo(json_encode($switch_to_device)) ?>);
                var options = {'title':'Devices and Switches association',
                    'orientation': 'horizontal',
                    'width':'100%',
                    'height':500};
                // Instantiate and draw the chart.
                var chart = new google.visualization.BarChart(document.getElementById('SwitchDevicesScatterPlot'));
                chart.draw(data, options);
            }
        </script>
        <style>
            .info-box-number{
                font-size:32px;
            }
            span.label{
                font-size:12px;
            }
        </style>


    </head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<!-- Content Wrapper. Contains page content -->
        <!-- Main content -->
        <section class="content">
            <!-- CURRENT SWITCHES -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Switches</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="switchTable" class="table table-striped table-bordered table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>Switch ID</th>
                                        <th class='text-center'>Switch Name</th>
                                        <th class='text-center'>Total Ports</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?php

                                    if(count($switches) > 0){
                                        for($i=0; $i<count($switches);$i++){
                                            $switchID = $switches[$i]["switchID"];
                                            $name = $switches[$i]["name"];
                                            $totalPorts = $switches[$i]["totalPorts"];

                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$switchID."</td>";
                                            echo "<td>".$name."</td>";
                                            echo "<td>".$totalPorts."</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- CURRENT DEVICES -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Devices</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="deviceTable" class="table table-striped table-bordered table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>Device ID</th>
                                        <th class='text-center'>Device Name</th>
                                        <th class='text-center'>IPv4</th>
                                        <th class='text-center'>IPv6</th>
                                        <th class='text-center'>MAC</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?php
                                   if(count($devices) > 0){
                                        for($i=0; $i<count($devices);$i++){
                                            $deviceID = $devices[$i]["deviceID"];
                                            $deviceName = $devices[$i]["name"];
                                            $ipv4 = $devices[$i]["ipv4"];
                                            $ipv6 = $devices[$i]["ipv6"];
                                            $mac = $devices[$i]["mac"];

                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$deviceID."</td>";
                                            echo "<td>".$deviceName."</td>";
                                            echo "<td>".$ipv4."</td>";
                                            echo "<td>".$ipv6."</td>";
                                            echo "<td>".$mac."</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- CURRENT SWITCH-DEVICE RELATIONSHIPS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Switch-to-Device Association</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="switchDeviceTable" class="table table-striped table-bordered table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>Switch ID</th>
                                        <th class='text-center'>Port</th>
                                        <th class='text-center'>Device ID</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?php
                                     if(count($switchdevices) > 0){
                                        for($i=0; $i<count($switchdevices);$i++){
                                            $switchID = $switchdevices[$i]["switchID"];
                                            $deviceID = $switchdevices[$i]["deviceID"];
                                            $port = $switchdevices[$i]["port"];

                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$switchID."</td>";
                                            echo "<td>".$port."</td>";
                                            echo "<td>".$deviceID."</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart-o"></i>

                            <h3 class="box-title">Number of Devices connected to Switch</h3>

                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="SwitchDevicesScatterPlot" height="500px"/>
                        </div>
                        <!-- /.box-body-->
                    </div>
                </div>
            </div>
            <!-- END OF CHART -->
        </section>
        <!-- /.content -->
    <!-- /.content-wrapper -->
</div>

</body>
</html>
    @endsection
