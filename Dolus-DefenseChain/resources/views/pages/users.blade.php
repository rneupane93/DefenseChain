<?php
$users = $info['users'];
$servers_used_on_months = array(["Jan",0],["Feb",0],["March",0],["Apr",0],["May",0],["Jun",0],[ "Jul",0],["Aug",0],["Sep",0],[ "Oct",0],["Nov",0],["Dec",0]);
if(count($users) > 0){
    for($i=0; $i<count($users);$i++){
        $startMon = date("m",strtotime($users[$i]["connectionStartTime"]));
        if($users[$i]["connectionStopTime"]!=null)
        {
        $endMon = date("m",strtotime($users[$i]["connectionStopTime"]));
        }
        else{
        $current_mon = date("Y-m-d h:i:s");
         $endMon = date("m",strtotime($current_mon));
        }
        
        if($startMon == $endMon  ){
            $servers_used_on_months[$startMon-1][1] =  $servers_used_on_months[$startMon-1][1]+1;
        }
        else if($startMon < $endMon ){
            for($j=$startMon -1; $j<$endMon ;$j++ )
                {
                    $servers_used_on_months[$j][1] =  $servers_used_on_months[$j][1]+1;
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
                data.addColumn('number', 'No of Users(count)');
                data.addRows(<?php echo(json_encode($servers_used_on_months)) ?>);
                var options = {'title':'Number of Users joined per Month in this year',
                    'orientation': 'horizontal',
                    'width':'100%',
                    'height':500};
                // Instantiate and draw the chart.
                var chart = new google.visualization.BarChart(document.getElementById('UsersBarChart'));
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
        <!-- NAVBAR -->

        <!-- Content Wrapper. Contains page content -->
        <!-- Main content -->
        <section class="content">
            <!-- CURRENT SERVERS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Users</h3>
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
                                        <th class='text-center'>User ID</th>
                                        <th class='text-center'>User Name</th>
                                        <th class='text-center'>User IP</th>
                                        <th class='text-center'>Connection Start Time</th>
                                        <th class='text-center'>Connection Stop Time</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?php
                                    // Append Body
                                    if(count($users) > 0){
                                        for($i=0; $i<count($users);$i++){
                                            $userID = $users[$i]["userID"];
                                            $username = $users[$i]["username"];
                                            $userIP = $users[$i]["ipAddress"];
                                            $connectionStartTime = $users[$i]["connectionStartTime"];
                                            $connectionStopTime = $users[$i]["connectionStopTime"];

                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$userID."</td>";
                                            echo "<td>".$username."</td>";
                                            echo "<td>".$userIP."</td>";
                                            echo "<td>".$connectionStartTime."</td>";
                                            echo "<td>".$connectionStopTime."</td>";
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

                            <h3 class="box-title">Number of Servers Used per Month By All Users</h3>

                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="UsersBarChart" height="500px"/>
                        </div>
                        <!-- /.box-body-->
                    </div>
                </div>
            </div>
            <!-- END OF CHART -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- DATATABLES -->
    <link href="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"/>
    <link href="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap4.min.js">


    </body>
    </html>
@endsection

