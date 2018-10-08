<?php defined('BASEPATH') OR exit('No direct script access allowed');
$nameList = array($account['name']);
$arr = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'); ?>
<div class="modal-dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h1><font class="fa fa-star-half-full"></font> Growth</h1>
        <div class="clear"></div>
    </div>
    <div class="dateRangePicker">
        <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
        <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
    </div>
    <div class="modal-body">
        <div id="content">
            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                <li class="active"><a href="#DailyFans" data-toggle="tab">Daily Fans</a></li>
                <li><a href="#DailyGrowth" data-toggle="tab">Daily Growth</a></li>
                <li><a href="#MonthlyFans" data-toggle="tab">Monthly Fans</a></li>
                <li><a href="#MonthlyGrowth" data-toggle="tab">Monthly Growth</a></li>
                <?php if(count($data) > 1):?>
                <li><a href="#CompetitorMonthlyGrowth" data-toggle="tab"> Competitor Monthly Growth</a></li>
                <?php endif;?>
                <li><a href="#CompareMonthlyGrowth" data-toggle="tab"> Compare Monthly Growth</a></li>
            </ul>
        </div>
        <div id="my-tab-content" class="tab-content">
            <div class="tab-pane active" id="DailyFans">
                <div id="growth_DailyFans"></div>
            </div>
            <div class="tab-pane" id="DailyGrowth">
                <div id="growth_DailyGrowth"></div>
            </div>
            <div class="tab-pane" id="MonthlyFans">
                <div id="growth_MonthlyFans"></div>
            </div>
            <div class="tab-pane" id="MonthlyGrowth">
                <div id="growth_MonthlyGrowth"></div>
                <div id="growth_MonthlyGrowthTable">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <?php foreach ($arr as $m) { echo "<th>".$m."</th>";}?>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="CompetitorMonthlyGrowth">
            <?php
                if(count($data) > 1){
                    $i = 0;
                    echo '<h2 style="background:#F8F8F8;text-align:center;padding:10px 0 5px;border:1px solid #EEE">TOP 3 Competitors Growth</h2>';
                    foreach($data as $list){
                        if ($account['name'] == $list->name){
                            continue;
                        }
                        array_push($nameList, $list->name);
                        if($i < 3){
                            echo '<h1><span class="fa fa-circle-o-notch"></span> '.$list->name.' <sup><span class="fa fa-info-circle tooltip"><span class="tooltiptext"><b>Monthly Growth</b><hr>Jumlah perkembangan <i>fans</i> per bulan pada periode saat ini.</span></span></sup></h1>';
                        echo '<div id="growth_MonthlyGrowthCompetitor_'.$list->account_id.'"></div>';
                        }
                        $i++;
                    }
                }
            ?>
            </div>
            <div class="tab-pane" id="CompareMonthlyGrowth">
                <h1><span class="fa fa-circle-o-notch"></span>Compare Growth<sup><span class="fa fa-info-circle tooltip"><span class="tooltiptext"><b>Compare Growth</b><hr>Jumlah perkembangan <i>fans</i> per bulan pada periode saat ini.</span></span></sup></h1>
                <div class="row">
                    <div class="col-md-6">
                        <div id="growth_CompareMonthlyGrowthTier1"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="growth_CompareMonthlyGrowthTier2"></div>
                    </div>
                </div>
                <div id="growth1_MonthlyGrowthTable">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table">
                        <thead>
                            <tr>
                                <th>Account Name</th>
                                <?php 
                                    $colHead = array(date('M', strtotime('-2 Month', $unix[0])), date('M', strtotime('-1 Month', $unix[0])), date('M', $unix[0]));
                                    $x = 0;
                                    foreach ($colHead as $c) {
                                        echo "<th>" . $c . "</th>";
                                        if ($x) echo "<th></th>";
                                        $x = 1;
                                    }
                                 ?>
                            </tr>
                        </thead>
                        <tbody id="Customdata">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
Highcharts.setOptions({
    lang: {
        thousandsSep: ','
    }
});
$('#growth_DailyFans').highcharts({
    chart: {
        type: 'spline'
    },
    title: {
        text: '<?php echo date('F, d Y', $unix[0]).' ~ '.date('F, d Y', $unix[1]) ?>'
    },
    subtitle: {
        text: null
    },
    xAxis: {
        categories: [
        <?php
            $i=0;
            foreach($dailyFans as $list){
                echo $i ? ',' : '';
                echo '"'.date('d', strtotime($list->created_time)).'"';
                $i=1;
            }
        ?>
        ]
    },
    yAxis: {
        gridLineWidth: 0,
        title: {
            text: null
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        valueSuffix: null
    },
    plotOptions: {
        spline: {
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '11px'
                }
            }
        }
    },
    legend: {
        enabled: false
    },
    series: [{
        name: 'Fans',
        data: [
        <?php
                $i=0;
                foreach($dailyFans as $list){
                    echo $i ? ',' : '';
                    echo $list->count;
                    $i=1;
                }
            ?>
        ]
    }]
});
$('#growth_DailyGrowth').highcharts({
    chart: {
        type: 'spline'
    },
    title: {
        text: '<?php echo date('F, d Y', $unix[0]).' ~ '.date('F, d Y', $unix[1]) ?>'
    },
    subtitle: {
        text: null
    },
    xAxis: {
        categories: [
            <?php
                $i=0;
                foreach($dailyGrowth as $list){
                    echo $i ? ',' : '';
                    echo '"'.date('d', strtotime($list->created_time)).'"';
                    $i=1;
                }
            ?>
        ]
    },
    yAxis: {
        gridLineWidth: 0,
        title: {
            text: null
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        valueSuffix: null
    },
    plotOptions: {
        spline: {
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '11px'
                }
            }
        }
    },
    legend: {
        enabled: false
    },
    series: [{
        name: 'Fans',
        data: [
            <?php
                $i=0;
                foreach($dailyGrowth as $list){
                    if($i){
                        echo ','.($list->count - $last);
                    }
                    else echo $list->count - $this->report_instagram->growth($account['account_id'], 'DailyPrevious', date('Y-m-d', $unix[0]));
                    $last = $list->count;
                    $i=1;
                }
            ?>
        ]
    }]
});
<?php
$start = date('Y', $unix[0]);
$end = date('Y', $unix[1]);
?>
$('#growth_MonthlyFans').highcharts({
    chart: {
        type: 'spline'
    },
    title: {
        text: '<?php echo ($start == $end) ? $start : $start.' ~ '.$end ?>'
    },
    subtitle: {
        text: null
    },
    xAxis: {
        categories: [
            <?php
                $x=0;
                for($i=$start; $i <= $end; $i++){
                    for($m=1; $m <= count($arr); $m++){
                        echo $x ? ',' : '';
                        echo '"'.$arr[$m].'"';
                        $x=1;
                    }
                }
            ?>
        ]
    },
    yAxis: {
        gridLineWidth: 0,
        title: {
            text: null
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        valueSuffix: null
    },
    plotOptions: {
        spline: {
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '11px'
                }
            }
        }
    },
    legend: {
        enabled: false
    },
    series: [{
        name: 'Fans',
        data: [
            <?php
                $x=0;
                $cur_month = date('Yn');
                for($i=$start; $i <= $end; $i++){
                    for($m=1; $m <= count($arr); $m++){
                        echo $x ? ',' : '';
                        echo ($i.$m == $cur_month) ? 0 : $this->report_instagram->growth($account['account_id'], 'MonthlyFans', $i.'-'.(($m > 9) ? $m : '0'.$m));
                        $x=1;
                    }
                }
            ?>
        ]
    }]
});
$('#growth_MonthlyGrowth').highcharts({
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Growth Performance <?php echo ($start-1).' ~ '.$end ?>'
    },
    subtitle: {
        text: null
    },
    xAxis: {
        categories: [
            <?php
                $x=0;           
                for($m=1; $m <= count($arr); $m++){
                    echo $x ? ',' : '';
                    echo '"'.$arr[$m].'"';
                    $x=1;
                }
            ?>
        ]
    },
    yAxis: {
        gridLineWidth: 0,
        title: {
            text: null
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        valueSuffix: null
    },
    plotOptions: {
        spline: {
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '11px'
                }
            }
        }
    },
    legend: {
        enabled: false
    },
    series: [
            <?php
                $rowSelf = array();
                $x=0;
                for($i=$start-1; $i <= $start; $i++){
                    $temp['total'] = array();
                    $temp['growth'] = array();
                    echo "{name: " . $i . ",";
                    echo "data: ";
                    for($m=1; $m <= count($arr); $m++){
                        $current = ($i.$m == $cur_month) ? 0 : $this->report_instagram->growth($account['account_id'], 'MonthlyFans', $i.'-'.(($m > 9) ? $m : '0'.$m));
                        array_push($temp['total'], intval($current));
                        if($x){
                            array_push($temp['growth'], $current ? $current - $last : 0);
                        }
                        else{
                            array_push($temp['growth'], $current - $this->report_instagram->growth($account['account_id'], 'DailyPrevious', $i.'-01-01'));
                        }
                        $last = $current;
                        $x=1;
                    }
                    echo json_encode($temp['growth']);
                    echo "}";
                    array_push($rowSelf, $temp['total'], $temp['growth']);
                    if ($i < $start) echo ",";
                }
                $rowCom = array($rowSelf[count($rowSelf) - 2], $rowSelf[count($rowSelf) - 1]);
                $mList = array(
                    intval(date("m", strtotime("-2 Month", $unix[1]))), 
                    intval(date("m", strtotime("-1 Month", $unix[1]))), 
                    intval(date("m", strtotime($unix[1]))));
                $rowSum = array();
                $rowData = array();
                $temp = array();
                
                foreach ($mList as $l) {
                    array_push($temp, $rowCom[0][$l-1]);
                }
                array_push($rowData, $temp);
                array_push($rowSum, array_sum($temp));
            ?>
        ]
});
<?php
if($data):
    $h = 0;
    foreach($data as $list):
        if ($account['name'] == $list->name) {
            continue;
        }
        $temp['total'] = array();
        $temp['growth'] = array();
        $x=0;
        $cur_month = date('Yn');
        for($i=$start; $i <= $end; $i++){
            for($m=1; $m <= count($arr); $m++){
                $current = ($i.$m == $cur_month) ? 0 : $this->report_instagram->growth($list->account_id, 'MonthlyFans', $i.'-'.(($m > 9) ? $m : '0'.$m));
                array_push($temp['total'], intval($current));
                if($x){
                    array_push($temp['growth'], $current ? $current - $last : 0);
                    
                }
                else array_push($temp['growth'], $current - $this->report_instagram->growth($list->account_id, 'DailyPrevious', $i.'-01-01')); 
                $last = $current;
                $x=1;
            }
        }
        array_push($rowCom, $temp['total'], $temp['growth']);

        $t = array();
        
        foreach ($mList as $l) {
            array_push($t, $temp['total'][$l-1]);
        }
        array_push($rowData, $t);
        array_push($rowSum, array_sum($t));
        if($h < 3):
?>
$('#growth_MonthlyGrowthCompetitor_<?php echo $list->account_id ?>').highcharts({
    chart: {
        type: 'spline'
    },
    title: {
        text: '<?php echo ($start == $end) ? $start : $start.' ~ '.$end ?>'
    },
    subtitle: {
        text: null
    },
    xAxis: {
        categories: [
            <?php
                $x=0;
                for($i=$start; $i <= $end; $i++){
                    for($m=1; $m <= count($arr); $m++){
                        echo $x ? ',' : '';
                        echo '"'.$arr[$m].'"';
                        $x=1;
                    }
                }
            ?>
        ]
    },
    yAxis: {
        gridLineWidth: 0,
        title: {
            text: null
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        valueSuffix: null
    },
    plotOptions: {
        spline: {
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '11px'
                }
            }
        }
    },
    legend: {
        enabled: false
    },
    series: [{
        name: 'Fans',
        data: 
        <?php echo json_encode($temp['growth']); ?>
    }]
});
<?php
        endif;
        $h++;
    endforeach;
endif;
array_multisort($rowSum, SORT_DESC, $nameList, SORT_ASC, $rowData);
if ($data):
?>
$('#growth_CompareMonthlyGrowthTier1').highcharts({
    chart: {
        type: 'column'
    },
    title: {
        text: null
    },
    subtitle: {
        text: '<?php echo date("F", strtotime("-2 Month", $unix[0])) . " ~ " . date("F", $unix[0]); ?>'
    },
    xAxis: {
        categories: [
        <?php
        $x=0;
        for($m=2; $m >= 0; $m--){
            $n = date("F, Y", strtotime("-" . $m . " Month", $unix[0]));
            echo $x ? ',' : '';
            echo '"'.$n.'"';
            $x=1;
        }
        ?>
        ]
    },
    yAxis: {
        gridLineWidth: 0,
        title: {
            text: null
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        valueSuffix: null
    },
    plotOptions: {
        spline: {
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '11px'
                }
            }
        }
    },
    legend: {
        enabled: true
    },
    series: [
        <?php
        $i = 0;
        foreach ($nameList as $name){
            if ($rowSum[$i] >= 240000){
                echo "{name: '" . $name . "',";
                echo "data: ";
                echo json_encode($rowData[$i]);
                echo "}";
                if ($i < count($nameList) - 1) echo ",";
            }
            $i++;
        }
        ?>
    ]
});

$('#growth_CompareMonthlyGrowthTier2').highcharts({
    chart: {
        type: 'column'
    },
    title: {
        text: null
    },
    subtitle: {
        text: '<?php echo date("F", strtotime("-2 Month", $unix[0])) . " ~ " . date("F", $unix[0]); ?>'
    },
    xAxis: {
        categories: [
        <?php
        $x=0;
        for($m=2; $m >= 0; $m--){
            $n = date("F, Y", strtotime("-" . $m . " Month", $unix[0]));
            echo $x ? ',' : '';
            echo '"'.$n.'"';
            $x=1;
        }
        ?>
        ]
    },
    yAxis: {
        gridLineWidth: 0,
        title: {
            text: null
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        valueSuffix: null
    },
    plotOptions: {
        spline: {
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '11px'
                }
            }
        }
    },
    legend: {
        enabled: true
    },
    series: [
        <?php
        $i = 0;
        foreach ($nameList as $name){
            if ($rowSum[$i] < 240000){
                echo "{name: '" . $name . "',";
                echo "data: ";
                echo json_encode($rowData[$i]);
                echo "}";
                if ($i < count($nameList) - 1) echo ",";
            }
            $i++;
        }
        ?>
    ]
});

<?php
endif;
?>


$("#growth_MonthlyGrowthTable tbody").append(
    <?php
        $cell = array('No of fans ', 'Growth of fans ');
        $x = 0;
        for ($i=$start-1; $i <= $start; $i++) { 
            echo "'<tr>' +";
            echo "'<td>" . $cell[$x % 2] . $i . "</td>' + ";
            for($j=0; $j < 12; $j++){
                echo '"<td>'.number_format($rowSelf[$x][$j]).'</td>" + ';
            }
            if($x % 2 == 0) $i--;
            echo "'</tr>'";
            if($x++ < count($rowSelf) - 1) echo "+";
        }
    ?>
);  
    var mybody =
    <?php
        $i = 0;
        foreach ($nameList as $name) {
            echo "'<tr>' + ";
            echo "'<td>" . $name . "</td>' + ";

            for ($j = 0; $j < 3; $j++) { 
                echo "'<td>" . number_format($rowData[$i][$j]) . "</td>' + ";

                if ($j > 0) {
                    $per = ($rowData[$i][$j-1]) ? ($rowData[$i][$j] - $rowData[$i][$j-1]) / $rowData[$i][$j-1]: 0;
                    echo "'<td>" . number_format($per * 100, 2) . "%</td>' + ";                 
                }
            }
            echo "'</tr>'";
            if($i++ < count($nameList) - 1) echo "+";
        }
    ?>;
    document.getElementById("Customdata").innerHTML = mybody;
$(document).ready(function(e){
    <?php 
        if($date){
            $date = explode(' ~ ', $date);
            $periods = array(date('01-M-Y', strtotime($date[0])), date('t-M-Y', strtotime($date[1])));
        }
        else $periods = array(date("01-M-Y"), date("t-M-Y"));
    ?>
    $("#dateRangePicker").daterangepicker({
        'locale': {
          'format': 'DD-MMM-YYYY',
          "separator": " ~ ",
        },
        'startDate': '<?php echo $periods[0];?>',
        'endDate': '<?php echo $periods[1];?>',
        'opens': 'center',
        'autoApply': true,
        'linkedCalendars': false
    });
    $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
        report_load('instagram', 'growth', <?php echo $account['account_id'];?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
    });
});
</script>