<?php
$qvms = $info['qvms'];

$blacklistIps = $info['blacklistIps'];
$devices = $info['devices'];

$qvms_used_on_months = array(["Jan",0],["Feb",0],["March",0],["Apr",0],["May",0],["Jun",0],[ "Jul",0],["Aug",0],["Sep",0],[ "Oct",0],["Nov",0],["Dec",0]);

$attackers_types=array(["Benign User's",0],["Active/InActive Attacker's",0]);

if(count($qvms) > 0){
    for($i=0; $i<count($qvms);$i++){
        $mon = date("m",strtotime($qvms[$i]["qvmStartTime"]));
        $qvms_used_on_months[$mon-1][1] =  $qvms_used_on_months[$mon-1][1]+1;
         if(count($devices)>0){
            $attackers_types[0][1]=count($devices);;
        }
        if(count($blacklistIps)>0){
            $attackers_types[1][1]=count($blacklistIps);
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
        <title>MTD | QVMs</title>

        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                // Define the chart to be drawn.
                var data1 = new google.visualization.DataTable();
                data1.addColumn('string', 'type of users');
                data1.addColumn('number', 'No of Attackers');
                data1.addRows(<?php echo(json_encode($attackers_types))?>);
                var options1 = {'title':'Active/Inactive attackers and benign users per Month in the year 2018',
                    'orientation': 'horizontal',
                    'width':'100%',
                    'height':500};
                // Instantiate and draw the chart.
                var chart1 = new google.visualization.PieChart(document.getElementById('ActiveAttackersPieChart'));
                chart1.draw(data1, options1);
                //active qvms
                var data2 = new google.visualization.DataTable();
                data2.addColumn('string', 'Month');
                data2.addColumn('number', 'No of QVMS');
                data2.addRows(<?php echo(json_encode($qvms_used_on_months)) ?>);
                var options2 = {'title':'Number of QVMS Active per Month in the year 2018',
                    'orientation': 'horizontal',
                    'width':'100%',
                    'height':500};
                // Instantiate and draw the chart.
                var chart2 = new google.visualization.BarChart(document.getElementById('ActiveQVMSBarChart'));
                chart2.draw(data2, options2);

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
        <!-- NAVBAR -->

        <!-- Content Wrapper. Contains page content -->
        <!-- Main content -->
        <section class="content">
            <!-- CURRENT SERVERS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Quarantine Virtual Machines</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="serverTable" class="table table-striped table-bordered table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>QVM UID</th>
                                        <th class='text-center'>QVM Name</th>
                                        <th class='text-center'>QVM IP Address</th>
                                        <th class='text-center'>Start Time</th>
                                        <th class='text-center'>Number of Attackers</th>
                                        <th class='text-center'>Currently Active?</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?php
                                    // Append Body
                                    if(count($qvms) > 0){
                                        for($i=0; $i<count($qvms);$i++){
                                            $uid = $qvms[$i]["qvmUID"];
                                            $name = $qvms[$i]["qvmName"];
                                            $ip = $qvms[$i]["qvmIP"];
                                            $startTime = $qvms[$i]["qvmStartTime"];
                                            $attackers = $qvms[$i]["numberOfAttackers"];
                                            $active = $qvms[$i]["currentlyActive"];

                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$uid."</td>";
                                            echo "<td>".$name."</td>";
                                            echo "<td>".$ip."</td>";
                                            echo "<td>".$startTime."</td>";
                                            echo "<td>".$attackers."</td>";
                                            if($active == 0){
                                                echo "<td><span class='fa fa-circle' style='color:red'></span></td>";
                                            } else {
                                                echo "<td><span class='fa fa-circle' style='color:green'></span></td>";
                                            }
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

                            <h3 class="box-title">Number of Benign users and Attackers Active/Inactive Per Month</h3>

                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="ActiveAttackersPieChart" style="height:500px;"></div>
                        </div>
                        <div class="box-body">
                            <div id="ActiveQVMSBarChart" style="height:500px;"></div>
                        </div>
                        <div class="box-body">
                            <div id="bar-chart" style="height: 300px;"></div>
                        </div>

                        <!-- /.box-body-->
                    </div>
                </div>
            </div>
            <!-- END OF CHART -->
        </section>
        <!-- /.content -->
    </div>


    <!-- DATATABLES -->
    <link href="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"/>
    <link href="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap4.min.js">


    </body>
    </html>
@endsection

