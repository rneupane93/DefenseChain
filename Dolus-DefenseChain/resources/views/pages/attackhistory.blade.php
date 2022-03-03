<?php
$attackHistory = $info['attackHistory'];
$attackers_on_months = array(["Jan",0],["Feb",0],["March",0],["Apr",0],["May",0],["Jun",0],[ "Jul",0],["Aug",0],["Sep",0],[ "Oct",0],["Nov",0],["Dec",0]);
if(count($attackHistory) > 0){
    for($i=0; $i<count($attackHistory);$i++){
        $startMon = date("m",strtotime($attackHistory[$i]["attackStartTime"]));
        $endMon = date("m",strtotime($attackHistory[$i]["attackStopTime"]));
        if($startMon == $endMon  ){
            $attackers_on_months[$startMon-1][1] =  $attackers_on_months[$startMon-1][1]+1;
        }
        else if($startMon < $endMon ){
            for($j=$startMon -1; $j<$endMon ;$j++ )
            {
                $attackers_on_months[$j][1] =  $attackers_on_months[$j][1]+1;
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
        <title>MTD | Blacklist</title>

        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                // Define the chart to be drawn.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Month');
                data.addColumn('number', 'No of Attacks');
                data.addRows(<?php echo(json_encode($attackers_on_months)) ?>);
                var options = {'title':'Number of Attackers attaked per Month in the year 2018',
                    'orientation': 'horizontal',
                    'width':'100%',
                    'colors':['red'],
                    'height':500};
                // Instantiate and draw the chart.
                var chart = new google.visualization.AreaChart(document.getElementById('AttackhistoryLineChart'));
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
            <!-- END OF CHART -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Attack History</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="attackHistory" class="table table-bordered table-condensed table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Attack ID</th>
                                        <th class="text-center">Source IP</th>
                                        <th class="text-center">Destination IP</th>
                                        <th class="text-center">Attack Start Time</th>
                                        <th class="text-center">Attack Stop Time</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody" class="text-center">

                                    <?php
                                    // Append Body
                                    if(count($attackHistory) > 0){
                                        for($i=0; $i<count($attackHistory);$i++){
                                            $attacker_id = $attackHistory[$i]["attacker_id"];
                                            $source_IP = $attackHistory[$i]["source_IP"];
                                            $destination_IP = $attackHistory[$i]["destination_IP"];
                                            $attackStartTime = $attackHistory[$i]["attackStartTime"];
                                            $attackStopTime = $attackHistory[$i]["attackStopTime"];

                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$attacker_id."</td>";
                                            echo "<td>".$source_IP."</td>";
                                            echo "<td>".$destination_IP."</td>";
                                            echo "<td>".$attackStartTime."</td>";
                                            echo "<td>".$attackStopTime."</td>";

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
                            <div id="AttackhistoryLineChart" height="500px"/>
                        </div>
                        <!-- /.box-body-->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    <!-- /.content-wrapper -->
</div>
</body>
</html>
@endsection
