<?php
$userMigrations = $info['usermigrations'];
$servers_migrated = array(["Jan",0],["Feb",0],["March",0],["Apr",0],["May",0],["Jun",0],[ "Jul",0],["Aug",0],["Sep",0],[ "Oct",0],["Nov",0],["Dec",0]);
if(count($userMigrations) > 0){
    for($i=0; $i<count($userMigrations);$i++){
        $startMon = date("m",strtotime($userMigrations[$i]["migrationStartTime"]));
        $endMon = date("m",strtotime($userMigrations[$i]["migrationStopTime"]));
        if($startMon == $endMon  ){
            $servers_migrated[$startMon-1][1] =  $servers_migrated[$startMon-1][1]+1;
        }
        else if($startMon < $endMon ){
            for($j=$startMon -1; $j<$endMon ;$j++ )
            {
                $servers_migrated[$j][1] =  $servers_migrated[$j][1]+1;
            }
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
                data.addColumn('string', 'Month');
                data.addColumn('number', 'No of User Migrations(count)');
                data.addRows(<?php echo(json_encode($servers_migrated)) ?>);
                var options = {'title':'Number of User Migrated per Month',
                    'orientation': 'horizontal',
                    'width':'100%',
                    'colors':['green'],
                    'height':500};
                // Instantiate and draw the chart.
                var chart = new google.visualization.AreaChart(document.getElementById('UserMigrationsLineChart'));
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
            <!-- CURRENT SERVERS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">User Migration History</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="userTable" class="table table-striped table-bordered table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>User Migration ID</th>
                                        <th class='text-center'>User IP</th>
                                        <th class='text-center'>Orginal Server IP</th>
                                        <th class='text-center'>Migrated Server IP</th>
                                        <th class='text-center'>Migration Start Time</th>
                                        <th class='text-center'>Migration Stop Time</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody" class="text-center">
                                    <?php

                                    // Append Body
                                    if(count($userMigrations) > 0){
                                        for($i=0; $i<count($userMigrations);$i++){
                                            $userMigrationUID = $userMigrations[$i]["userMigrationUID"];
                                            $userIP = $userMigrations[$i]["userIP"];
                                            $originalServerIP = $userMigrations[$i]["originalServerIP"];
                                            $migratedServerIP = $userMigrations[$i]["migratedServerIP"];
                                            $migrationStartTime = $userMigrations[$i]["migrationStartTime"];
                                            $migrationStopTime = $userMigrations[$i]["migrationStopTime"];

                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$userMigrationUID."</td>";
                                            echo "<td>".$userIP."</td>";
                                            echo "<td>".$originalServerIP."</td>";
                                            echo "<td>".$migratedServerIP."</td>";
                                            echo "<td>".$migrationStartTime."</td>";
                                            echo "<td>".$migrationStopTime."</td>";
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

                            <h3 class="box-title">Number of User Migrations per Month</h3>

                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="UserMigrationsLineChart" height="500px"/>
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

<!-- ./wrapper -->

</body>
</html>
@endsection

