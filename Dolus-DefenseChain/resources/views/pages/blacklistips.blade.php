<?php
$blacklistips = $info['blacklistIps'];
$backlistips_in_amonth = array(["Jan",0],["Feb",0],["March",0],["Apr",0],["May",0],["Jun",0],[ "Jul",0],["Aug",0],["Sep",0],[ "Oct",0],["Nov",0],["Dec",0]);
if(count($blacklistips) > 0){
    for($i=0; $i<count($blacklistips);$i++){
        $mon = date("m",strtotime($blacklistips[$i]["blacklistedOn"]));
        $backlistips_in_amonth[$mon-1][1] =  $backlistips_in_amonth[$mon-1][1]+1;
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
                data.addColumn('string', 'Blacklisted IPs');
                data.addColumn('number', 'No of Servers');
                data.addRows(<?php echo(json_encode($backlistips_in_amonth)) ?>);
                var options = {'title':'Number of block listed IP\'s Per Month',
                    'orientation': 'horizontal',
                    'width':'100%',
                    'colors':['red'],
                    'height':500};
                // Instantiate and draw the chart.
                var chart = new google.visualization.AreaChart(document.getElementById('BlacklistIpsLineChart'));
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
                            <h3 class="box-title">Blacklisted IPs</h3>
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
                                        <th class="text-center">User IP</th>
                                        <th class="text-center">Mac Address</th>
                                        <th class="text-center">Blacklisted On</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody" class="text-center">

                                    <?php
                                    // Append Body
                                    if(count($blacklistips) > 0){
                                        for($i=0; $i<count($blacklistips);$i++){
                                            $userIP = $blacklistips[$i]["ipAddress"];
                                            $macAddress = $blacklistips[$i]["macAddress"];
                                            $blacklistedOn = $blacklistips[$i]["blacklistedOn"];
                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$userIP."</td>";
                                            echo "<td>".$macAddress."</td>";
                                            echo "<td>".$blacklistedOn."</td>";
                                             echo "</tr>";
                                        }
                                    }
                                    ?> </tbody>
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

                            <h3 class="box-title">Number of Backlisted IP's Per Month</h3>

                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="BlacklistIpsLineChart" style="height:500px;"></div>
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
