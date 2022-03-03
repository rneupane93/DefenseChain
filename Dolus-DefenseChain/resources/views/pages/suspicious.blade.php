<?php
use App\Classes\HomeWrapperClass;
$devices = $info['devices'];

if(isset($_COOKIE['selectedDeviceID'])) {
    $selectedDeviceID = intval($_COOKIE['selectedDeviceID']);
}
else{
    $selectedDeviceID=1;
}
if(isset($_COOKIE['distinctIPDevice'])){
    $distinctIPDevice = $_COOKIE['distinctIPDevice'];
}
else{
    $distinctIPDevice='10.0.0.107';
}

if(isset($_COOKIE['flowsIPDevice'])){
    $flowsIPDevice = $_COOKIE['flowsIPDevice'];
}
else{
    $flowsIPDevice='10.0.0.107';
}

if(isset($_COOKIE['bytesIPDevice'])){
    $bytesIPDevice = $_COOKIE['bytesIPDevice'];
}
else{
    $bytesIPDevice='10.0.0.107';
}

$suspicious_on_months = array(["Jan",0],["Feb",0],["March",0],["Apr",0],["May",0],["Jun",0],[ "Jul",0],["Aug",0],["Sep",0],[ "Oct",0],["Nov",0],["Dec",0]);

?>


@extends('layouts.master')

@section('content')
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MTD | Blacklist</title>

        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />

        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

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
    <!-- NAVBAR -->


<!-- Content Wrapper. Contains page content -->
        <!-- Main content -->
        <section class="content">
            <!-- SUSPECT SCORES BAR GRAPH -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Suspiciousness Score Trend by Device</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                                <div class="col-md-2 col-xs-12" style="margin-bottom:5px">
                                    <select id="device" class="form-control" onchange="run()">
                                        <?php
                                        // Append Body
                                        if(count($devices) > 0){
                                            for($i=0; $i<count($devices);$i++){
                                                $deviceID = $devices[$i]["deviceID"];
                                                $deviceName = $devices[$i]["name"];
                                                if($selectedDeviceID == $deviceID)
                                                    echo "<option device-id='".$deviceID."' value='".$deviceID."' selected>".$deviceName."</option>";
                                                else
                                                    echo "<option device-id='".$deviceID."' value='".$deviceID."'>".$deviceName."</option>";

                                            }
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        function run() {
                                            var selectedDeviceID = document.getElementById("device").value;
                                            document.cookie = "selectedDeviceID = " + selectedDeviceID
                                            location.reload();
                                        }
                                    </script>
                                    <br/>
                                    <br/>
                                </div>
                                <div class="table-responsive">
                                        <table id="deviceSuspectTable" class="table table-striped table-bordered table-condensed no-margin">
                                            <thead>
                                            <tr>
                                                <th class='text-center'>Device ID</th>
                                                <th class='text-center'>Device Name</th>
                                                <th class='text-center'>Device Suspect Scores</th>
                                                <th class='text-center'>Calculated Time</th>
                                            </tr>
                                            </thead>
                                            <tbody class="text-center">
                                            <?php
                                            $home_wrapper= new HomeWrapperClass();

                                            $suspiciousScoresByDevice = $home_wrapper->getSuspiciousnessScoreByDevice($selectedDeviceID);
                                            if(count($suspiciousScoresByDevice) > 0){
                                                for($i=0; $i<count($suspiciousScoresByDevice);$i++){
                                                    $device = $suspiciousScoresByDevice[$i];
                                                    $deviceID = $suspiciousScoresByDevice[$i]["device_id"];
                                                    $name = $suspiciousScoresByDevice[$i]["name"];
                                                    $score = $suspiciousScoresByDevice[$i]["score"];
                                                    $calculatedTime = $suspiciousScoresByDevice[$i]["ssscore_caluculated_time"];
                                                    $mon = date("m",strtotime($suspiciousScoresByDevice[$i]["ssscore_caluculated_time"]));
                                                    $suspicious_on_months[$mon-1][1] =  $suspicious_on_months[$mon-1][1]+$score;
                                                    // Create Table Row
                                                    echo "<tr>";
                                                    echo "<td>".$deviceID."</td>";
                                                    echo "<td>".$name."</td>";
                                                    echo "<td>".$score."</td>";
                                                    echo "<td>".$calculatedTime."</td>";
                                                    echo "</tr>";
                                                }
                                            }

                                            ?>
                                            </tbody>
                                        </table>
                                </div>
                            <script type="text/javascript">
                                google.charts.load('current', {packages: ['corechart']});
                                google.charts.setOnLoadCallback(drawChart);
                                function drawChart() {
                                    // Define the chart to be drawn.
                                    var data = new google.visualization.DataTable();
                                    data.addColumn('string', 'Month');
                                    data.addColumn('number', 'Suspicious score of the device');
                                    data.addRows(<?php echo(json_encode($suspicious_on_months)) ?>);
                                    var options = {'title':'History of Suspicious scores per Month in the year 2018',
                                        'orientation': 'horizontal',
                                        'width':'100%',
                                        'height':500};
                                    // Instantiate and draw the chart.
                                    var chart = new google.visualization.LineChart(document.getElementById('SuspiciousLineChart'));
                                    chart.draw(data, options);
                                }
                            </script>
                            <div class="row" style="padding:15px; padding-bottom:0">
                                <div id="SuspiciousLineChart" style="height: 100%; width:100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <!-- SUSPECT SCORES GRAPH -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Suspiciousness Graph</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">

                            <?php
                                $home_wrapper= new HomeWrapperClass();
                                $allSuspiciousScores = $home_wrapper->getAllSuspiciousnessScores();
                                $suspicious_by_devices=array();

                                if(count($allSuspiciousScores) > 0){
                                    $idx=0;
                                    for($i=0; $i<count($allSuspiciousScores);$i++){
                                        $device = $allSuspiciousScores[$i];
                                        $deviceID = $allSuspiciousScores[$i]["device_id"];
                                        $name = $allSuspiciousScores[$i]["name"];
                                        $score = $allSuspiciousScores[$i]["score"];
                                        $calculatedTime = $allSuspiciousScores[$i]["ssscore_caluculated_time"];
                                        $deviceAlreadyThere = false;
                                        for($j=0; $j<count($suspicious_by_devices);$j++)
                                        {
                                            if($name==$suspicious_by_devices[$j][0]) {
                                                $deviceAlreadyThere = true;
                                                break;
                                            }
                                        }
                                        if($deviceAlreadyThere){
                                            $suspicious_by_devices[$j][1]= intval($suspicious_by_devices[$j][1]+ intval($score));
                                        }
                                        else {
                                            $suspicious_by_devices[$idx][0]= $name;
                                            $suspicious_by_devices[$idx][1]= intval($score);
                                            $idx=$idx+1;
                                        }



                                    }
                                }
                                ?>
                                <div class="row" style="padding:15px">
                                    <script type="text/javascript">
                                        google.charts.load('current', {packages: ['corechart']});
                                        google.charts.setOnLoadCallback(drawChart);
                                        function drawChart() {
                                            // Define the chart to be drawn.
                                            var data = new google.visualization.DataTable();
                                            data.addColumn('string', 'Device');
                                            data.addColumn('number', 'Suspicious score of the device');
                                            data.addRows(<?php echo(json_encode($suspicious_by_devices)) ?>);
                                            var options = {'title':'History of Suspicious scores of devices',
                                                'orientation': 'horizontal',
                                                'width':'100%',
                                                'height':500};
                                            // Instantiate and draw the chart.
                                            var chart = new google.visualization.BarChart(document.getElementById('SuspiciousGraph'));
                                            chart.draw(data, options);
                                        }
                                    </script>
                                    <div id="SuspiciousGraph" style="height: 100%; width:100%"></div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Distinct IP connections </h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2 col-xs-12" style="margin-bottom:5px">
                                    <select id="IPdevice" class="form-control" onchange="runIP()">
                                        <?php
                                        // Append Body
                                        if(count($devices) > 0){
                                            for($i=0; $i<count($devices);$i++){
                                                $deviceID = $devices[$i]["deviceID"];
                                                $deviceName = $devices[$i]["name"];
                                                $srcIP = $devices[$i]["ipv4"];
                                                if($distinctIPDevice == $srcIP)
                                                    echo "<option device-id='".$deviceID."' value='".$srcIP."' selected>".$deviceName."</option>";
                                                else
                                                    echo "<option device-id='".$deviceID."' value='".$srcIP."'>".$deviceName."</option>";

                                            }
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        function runIP() {
                                            var distinctIPDevice = document.getElementById("IPdevice").value;
                                            document.cookie = "distinctIPDevice = " + distinctIPDevice

                                            location.reload();
                                        }
                                    </script>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="deviceSuspectTable" class="table table-striped table-bordered table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>Switch ID</th>
                                        <th class='text-center'>Source</th>
                                        <th class='text-center'>Destination</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?php
                                    $home_wrapper= new HomeWrapperClass();
                                    $distinctIPdeviceConnec = $home_wrapper->getDistinctDestinationsOfDevice($distinctIPDevice);
                                    if(count($distinctIPdeviceConnec) > 0){
                                        for($i=0; $i<count($distinctIPdeviceConnec);$i++){
                                            $switchID = $distinctIPdeviceConnec[$i]["switch_id"];
                                            $source = $distinctIPdeviceConnec[$i]["ip_src"];
                                            $destination = $distinctIPdeviceConnec[$i]["ip_dst"];
                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$switchID."</td>";
                                            echo "<td>".$source."</td>";
                                            echo "<td>".$destination."</td>";
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

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Total Flows</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2 col-xs-12" style="margin-bottom:5px">
                                    <select id="deviceFlows" class="form-control" onchange="runFlows()">
                                        <?php
                                        // Append Body
                                        if(count($devices) > 0){
                                            for($i=0; $i<count($devices);$i++){
                                                $deviceID = $devices[$i]["deviceID"];
                                                $deviceName = $devices[$i]["name"];
                                                $srcIP = $devices[$i]["ipv4"];
                                                if($flowsIPDevice == $srcIP)
                                                    echo "<option device-id='".$deviceID."' value='".$srcIP."' selected>".$deviceName."</option>";
                                                else
                                                    echo "<option device-id='".$deviceID."' value='".$srcIP."'>".$deviceName."</option>";

                                            }
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        function runFlows() {
                                            var flowsIPDevice = document.getElementById("deviceFlows").value;
                                            document.cookie = "flowsIPDevice = " + flowsIPDevice

                                            location.reload();
                                        }
                                    </script>

                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="deviceSuspectTable" class="table table-striped table-bordered table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>Switch ID</th>
                                        <th class='text-center'>Source</th>
                                        <th class='text-center'>Total Flows</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?php
                                    $home_wrapper= new HomeWrapperClass();
                                    $flowsDevice = $home_wrapper->getAllPacketLogsByDevice($flowsIPDevice);
                                    if(count($flowsDevice) > 0){
                                        $switchID = $flowsDevice[0]["switch_id"];
                                        $source = $flowsDevice[0]["ip_src"];
                                        $flowsTotal = count($flowsDevice);
                                        // Create Table Row
                                        echo "<tr>";
                                        echo "<td>".$switchID."</td>";
                                        echo "<td>".$source."</td>";
                                        echo "<td>".$flowsTotal."</td>";
                                        echo "</tr>";
                                    }

                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Total Bytes transfered per 15 seconds</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2 col-xs-12" style="margin-bottom:5px">
                                    <select id="deviceBytes" class="form-control" onchange="runBytes()">
                                        <?php
                                        // Append Body
                                        if(count($devices) > 0){
                                            for($i=0; $i<count($devices);$i++){
                                                $deviceID = $devices[$i]["deviceID"];
                                                $deviceName = $devices[$i]["name"];
                                                $srcIP = $devices[$i]["ipv4"];
                                                if($bytesIPDevice == $srcIP)
                                                    echo "<option device-id='".$deviceID."' value='".$srcIP."' selected>".$deviceName."</option>";
                                                else
                                                    echo "<option device-id='".$deviceID."' value='".$srcIP."'>".$deviceName."</option>";

                                            }
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        function runBytes() {
                                            var bytesIPDevice = document.getElementById("deviceBytes").value;
                                            document.cookie = "bytesIPDevice = " + bytesIPDevice

                                            location.reload();
                                        }
                                    </script>

                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="deviceSuspectTable" class="table table-striped table-bordered table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>Switch ID</th>
                                        <th class='text-center'>Source</th>
                                        <th class='text-center'>Bytes Flows</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?php
                                    $home_wrapper= new HomeWrapperClass();
                                    $bytesDevice = $home_wrapper->getAllPacketLogsByDevice($bytesIPDevice);
                                    if(count($bytesDevice) > 0){
                                        $switchID = $bytesDevice[0]["switch_id"];
                                        $source = $bytesDevice[0]["ip_src"];
                                        $bytesTotal=0;
                                        for($i=0; $i<count($bytesDevice);$i++)
                                            {
                                                $bytesTotal = $bytesTotal+$bytesDevice[$i]["frame_len"];
                                            }
                                        // Create Table Row
                                        echo "<tr>";
                                        echo "<td>".$switchID."</td>";
                                        echo "<td>".$source."</td>";
                                        echo "<td>".$bytesTotal."</td>";
                                        echo "</tr>";
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
            <!-- END OF CHART -->
        </section>
        <!-- /.content -->
    <!-- /.content-wrapper -->


</div>
<!-- ./wrapper -->
</body>
</html>
@endsection
