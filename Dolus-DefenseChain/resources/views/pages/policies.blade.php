<?php
use App\Classes\HomeWrapperClass;
$devices = $info['devices'];
$policies = $info['policies'];
$home_wrapper= new HomeWrapperClass();

if ( isset( $_GET['submit'] ) ){
    $whiteListID = $_REQUEST['WLID'];
    $blackListID = $_REQUEST['BLID'];
    if($blackListID != $whiteListID){
        if($blackListID !="none"){
            $device_data = $home_wrapper->getADevices(intval($blackListID));
            if(count($device_data)>0){
                $is_already_blacklisted = $home_wrapper->getABlacklistIPs($device_data[0]['ipv4']);
                $is_whitelisted = $home_wrapper->getAWhiteListIPs($device_data[0]['ipv4']);
                if(count($is_already_blacklisted) <=0 && count($is_whitelisted)<=0) {
                    $ipAddress = $device_data[0]['ipv4'];
                    $macAddress = $device_data[0]['mac'];
                    $home_wrapper->insertIntoBlackLists($ipAddress,$macAddress);
                }

            }
        }
        if($whiteListID != "none"){
            $device_data = $home_wrapper->getADevices(intval($whiteListID));

            if(count($device_data)>0){
                $is_whitelisted = $home_wrapper->getAWhiteListIPs($device_data[0]['ipv4']);
                if(count($is_whitelisted)<=0){
                    $ipv4 = $device_data[0]['ipv4'];
                    $home_wrapper->insertIntoWhiteListIPs($ipv4);
                    $home_wrapper->deleteABlacklistedIP($ipv4);
                }

            }

        }
    }
}
$switch_devices = $home_wrapper->getAllSwtichDevices();
$networkdata=array();
$switches = $home_wrapper->getAllSwitches();
for($i=0;$i<count($switches);$i++){
    $networkdata[$i][0] = 'Controller'.' '.strval($switches[$i]['name']);
}
$len = count($networkdata);
for($i=0;$i<count($switch_devices);$i++){
    $current_device = $home_wrapper->getADevices($switch_devices[$i]['deviceID']);
    $current_switch = $home_wrapper-> getASwitch($switch_devices[$i]['switchID']);
    if(count($current_switch)>0){
        $switch_name = $current_switch[0]['name'];
    }
    else{
        $switch_name = 0;
    }
    if(count($current_device)>0){
        $device_name = $current_device[0]['name'];
    }
    else{
        $device_name = 'Server'.($switch_devices[$i]['deviceID']);
    }
    $networkdata[$len][0] = 'Controller'.' '.strval($switch_name).' '.strval($device_name);
    $len=$len+1;
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
        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {packages:['wordtree']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                // Define the chart to be drawn.
                var data = new google.visualization.arrayToDataTable(<?php echo(json_encode($networkdata)) ?>);
                var options = {
                    wordtree: {
                        format: 'implicit',
                        word: 'Controller'
                    }
                };
                var chart = new google.visualization.WordTree(document.getElementById('wordtree_basic'));
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
        <meta name="csrf-token" content="{{ csrf_token() }}">


    </head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<!-- Content Wrapper. Contains page content -->
        <!-- Main content -->
        <section class="content">
            <!-- POLICY TABLE -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <div class="row">
                                <div class="col-md-6 text-left" style="padding-top:5px">
                                    <form method="get" action={{route('pages.policies')}} >
                                        <br/>
                                        <br/>
                                        <div>
                                            <label for="blaclistedID">Select the device to be Balcklisted:</label>
                                            <select id="deviceBlacklist" class="form-control" name='BLID'>
                                                <?php
                                                // Append Body
                                                if(count($devices) > 0){
                                                    echo "<option device-id='BLID' value='none' selected>None</option>";
                                                    for($i=0; $i<count($devices);$i++){
                                                        $deviceID = $devices[$i]["deviceID"];
                                                        $deviceName = $devices[$i]["name"];
                                                        echo "<option device-id='BLID' value='".$deviceID."'>".$deviceName."</option>";

                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <br/>

                                        <div>
                                            <label for="whiteListedID">Select the device to be Whitelisted:</label>
                                            <select id="deviceWhiteList" class="form-control" name='WLID'>
                                                <?php
                                                // Append Body
                                                if(count($devices) > 0){
                                                    echo "<option device-id='WLID' value='none' selected>None</option>";
                                                    for($i=0; $i<count($devices);$i++){
                                                        $deviceID = $devices[$i]["deviceID"];
                                                        $deviceName = $devices[$i]["name"];
                                                        echo "<option device-id='WLID' value='".$deviceID."'>".$deviceName."</option>";

                                                    }
                                                }
                                                ?>
                                            </select>

                                        </div>
                                        <br/>
                                        <br/>
                                        <input type="submit" name="submit" value="submit">

                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col-md-12 -->
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <i class="fa fa-line-chart"></i>
                            <h3 class="box-title">Network Graph</h3>
                            <div id="wordtree_basic" height="500px"/>

                        </div>

                    </div>
                </div>
            </div>


        </section>

</div>
<!-- ./wrapper -->
</body>
</html>
@endsection
