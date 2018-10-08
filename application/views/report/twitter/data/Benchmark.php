<?php defined('BASEPATH') OR exit('No direct script access allowed');
$arr = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'); ?>
<div class="modal-dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h1><font class="fa fa-star-half-full"></font> Benchmark</h1>
        <div class="clear"></div>
    </div>
<div class="dateRangePicker">
    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
</div>
    <div class="modal-body">
        <div id="growth_Line"></div>
        <div id="growth_Table1">
            <table class="table table-striped">
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
</div>
            <?php
                if($data){
                    $row['name'] = array();
                    $row['id'] = array();
                    $row['e_ratio'] = array();
                    $row['f_rate'] = array();
                    $row['total'] = array();
                    foreach($data as $list){
                        $c = array();
                        $s = 0;
                        $engagement_ratio = ($this->report_twitter->users($list->account_id, 'MostActiveCount', $date) / ($list->fans ? $list->fans : 1)) * 100;
                        $feedback_rate = $list->feedback / ($list->post ? $list->post : 1);
                        array_push($row['name'], $list->name);
                        array_push($row['id'], $list->account_id);
                        array_push($row['e_ratio'], round($engagement_ratio, 2));
                        array_push($row['f_rate'], round($feedback_rate));
                        $temp = $this->report_twitter->activities($list->account_id, 'TotalFeedback', $date);
                        foreach ($temp as $t) {
                            $s += $t->total;
                        }
                        array_push($row['total'], $s);
                    }
                }
                $start = date('Y', $unix[0]);
                $end = date('Y', $unix[1]);
            ?>
            <script type="text/javascript">
                $('#growth_Line').highcharts({
                    chart: {
                        type: 'spline'
                    },
                    title: {
                        text: null
                    },
                    subtitle: {
                        text: null
                    },
                    xAxis: {
                        categories: [
                            <?php
                                for ($i=0; $i < 12; $i++) { 
                                    echo "'".$arr[$i]."'";
                                    if ($i < 11) echo ",";
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
                                $col = array();
                                $cur_month = date('Yn');
                                for ($h=0; $h < count($row['name']); $h++) { 
                                    echo "{name: '" . $row['name'][$h] . "',";
                                    echo "data: ";
                                    $c = array();
                                    $d = array();
                                    $x = 0;
                                    for($i=$start; $i <= $end; $i++){
                                        for($m=1; $m <= count($arr); $m++){
                                            $current = ($i.$m == $cur_month) ? 0 : $this->report_twitter->growth($row['id'][$h], 'MonthlyFans', $i.'-'.(($m > 9) ? $m : '0'.$m));
                                            if($x){
                                                array_push($c, $current ? $current - $last : 0);
                                                array_push($d, $current ? number_format($current - $last) : 0);
                                            }
                                            else{ 
                                                array_push($c, $current - $this->report_twitter->growth($row['id'][$h], 'DailyPrevious', $i.'-01-01'));
                                                array_push($d, number_format($current - $this->report_twitter->growth($row['id'][$h], 'DailyPrevious', $i.'-01-01')));
                                            }
                                            $last = $current;
                                            $x=1;
                                        }
                                    }
                                    array_push($col, $d);
                                    echo json_encode($c);
                                    echo "}";
                                    if ($h < count($row['name']) - 1) echo ",";
                                }
                            ?>
                        ]
                    });
                $("#growth_Table1 tbody").append(
                    <?php 
                        for($i=0; $i < count($row['name']); $i++){
                            echo '"<tr>" + ';
                            echo '"<td>'.$row['name'][$i].'</td>" + ';
                            for($j=0; $j < 12; $j++){
                                echo '"<td>'.$col[$i][$j].'</td>" + ';
                            }
                            echo '"</tr>"';
                            if ($i < count($row['name']) - 1) echo '+';
                        }
                    ?>
                );
            </script>

<script type="text/javascript">
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
        report_load('twitter', 'benchmark', <?php echo $account->account_id;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
    });
});

</script>