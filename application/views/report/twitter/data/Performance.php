<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font> Performance</h1>
	    <div class="clear"></div>
	</div>
<div class="dateRangePicker">
    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
</div>
	<div class="modal-body">
		<div id="content">
			<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
				<li class="active"><a href="#performance-summary" data-toggle="tab">Summary</a></li>
				<li><a href="#comparison" data-toggle="tab">Comparison</a></li>
				<li><a href="#interaction" data-toggle="tab">Interaction</a></li>
			</ul>
		</div>
		<div id="my-tab-content" class="tab-content">
			<div class="tab-pane active" id="performance-summary">
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-hover">
					<thead>
						<tr>
							<th width="25"></th>
							<th>Account</th>
							<th width="100">Fans<br /><?php echo date('F Y', strtotime("-1 month", $unix)) ?></th>
							<th width="100">Fans<br /><?php echo date('F Y', $unix) ?></th>
							<th width="60">Growth</th>
							<th width="60">% Change</th>
							<th width="100">Feedback Rate<br /><?php echo date('F Y', strtotime("-1 month", $unix)) ?></th>
							<th width="100">Feedback Rate<br /><?php echo date('F Y', $unix) ?></th>
							<th width="60">% Change</th>
							<th width="70">Post</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($data){
								$i = 1;
								$c = count($data);
								$com['name'] = array();
								$com['id'] = array();	
								$e_ratio = array();
								$f_rate = array();
								foreach($data as $list){
									array_push($com['name'], $list->name);
									array_push($com['id'], $list->account_id);
									$rate = $list->feedback / ($list->post ? $list->post : 1);
									$last_rate = $list->last_feedback / ($list->last_post ? $list->last_post : 1);
									echo (($list->account_id == $account->account_id) && ($c > 1)) ? '<tr style="text-align:right; background-color: #cddc39">' : '<tr style="text-align:right">';
									echo '<td>'.$i.'</td>';
									echo '<td style="text-align:left"><a href="https://www.twitter.com/'.($list->username ? $list->username : $list->socmed_id).'" target="_blank">'.$list->name.'</a></td>';
									echo '<td>'.number_format($list->last_fans).'</td>';
									echo '<td>'.number_format($list->fans).'</td>';
									echo '<td>'.number_format($list->fans - $list->last_fans).'</td>';
									echo '<td>'.number_format((($list->fans - $list->last_fans) / ($list->last_fans ? $list->last_fans : 1)) * 100, 2).'%</td>';
									echo '<td title="'.number_format($last_rate, 2).'">'.number_format($last_rate).'</td>';
									echo '<td title="'.number_format($rate, 2).'">'.number_format($rate).'</td>';
									echo '<td>'.number_format((($rate - $last_rate) / ($last_rate ? $last_rate : 1)) * 100, 2).'%</td>';
									echo '<td>'.number_format($list->post).'</td>';
									echo '</tr>';
									$engagement_ratio = ($this->report_twitter->users($list->account_id, 'MostActiveCount', $fdate) / ($list->fans ? $list->fans : 1)) * 100;
									array_push($e_ratio, round($engagement_ratio, 2));
									$feedback_rate = $list->feedback / ($list->post ? $list->post : 1);
									array_push($f_rate, round($feedback_rate, 2));
									$i++;
								}
							} else echo '<tr><td class="nodata" colspan="10">No data.</td></tr>';
						?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" id="comparison">
				<div id="performance_VsCompetitor"></div>
			</div>
			<div class="tab-pane" id="interaction">
				<div class="row">
					<div class="col-md-6">
                		<div id="growth_Bar1"></div>
					</div>
					<div class="col-md-6">
                		<div id="growth_Bar2"></div>
					</div>
				</div>
                <div id="growth_Table2">
                    <table class="table table-striped">
                        <thead>
                            <tr><th></th>
                            </tr>
                        </thead>
                        <tbody class="center">
                        </tbody>
                    </table>
                </div>
			</div>
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
            $engagement_ratio = ($this->report_twitter->users($list->account_id, 'MostActiveCount', $fdate) / ($list->fans ? $list->fans : 1)) * 100;
            $feedback_rate = $list->feedback / ($list->post ? $list->post : 1);
            array_push($row['name'], $list->name);
            array_push($row['id'], $list->account_id);
            array_push($row['e_ratio'], round($engagement_ratio, 2));
            array_push($row['f_rate'], round($feedback_rate));
            $temp = $this->report_twitter->activities($list->account_id, 'TotalFeedback', $fdate);

            if ($temp) {
                foreach ($temp as $t) {
                    $s += $t->total;
                }
                array_push($row['total'], $s);
            }
        }
    }
    $start = date('Y', $unix[0]);
    $end = date('Y', $unix[1]);
?>
<script type="text/javascript">
$('#performance_VsCompetitor').highcharts({
	chart: {
		type: 'column'
	},
	title: {
		text: 'Total Fans per Month'
	},
	subtitle: {
		text: null
	},
	xAxis: {
		categories: 
		<?php
			$x = array();
			for ($i=2; $i>=0 ; $i--){
				$temp = array();
	            $time = strtotime("- $i month", $unix);
	            array_push($x, date('F, Y', $time));	            
	        }
	        echo json_encode($x);
		?>		
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
		for ($i=0; $i < count($com['id']); $i++) {
			echo "{name: '" . $com['name'][$i] . "',";
			echo "data: [";
			for ($j=2; $j >=0; $j--) { 
				echo $this->report_twitter->performance($com['id'][$i], 'VsCompetitor', date('Y-m', strtotime("- $j month", $unix)));
				if ($j>0) echo ",";
			}
			echo "]}";
			if ($i < count($com['id']) - 1) echo ",";
		}
		?>
	]
});
$('#growth_Bar1').highcharts({
    chart: {
        height: 200,
        width: 500,
        type: 'bar'
    },
    title: {
        text: "Engagement Ratio"
    },
    subtitle: {
        text: null
    },
    xAxis: {
        categories: [
            <?php
                for ($i=0; $i < count($row['name']); $i++) {
                    echo "'".$row['name'][$i]."'";
                    if ($i < count($row['name']) - 1) echo ",";
                }
            ?>
        ]
    },
    yAxis: {
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
        bar: {
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '11px'
                }
            },
            color: '#f44336'
        }
    },
    legend: {
        enabled: false
    },
    series: [
        <?php
            echo "{name: 'Engagement Ratio',";
            echo "data: ";
            echo json_encode($row['e_ratio']);
            echo "}";
        ?>
    ]
});
$('#growth_Bar2').highcharts({
    chart: {
        height: 200,
        width: 500,
        type: 'bar'
    },
    title: {
        text: "Feedback Rate"
    },
    subtitle: {
        text: null
    },
    xAxis: {
        categories: [
            <?php
                for ($i=0; $i < count($row['name']); $i++) { 
                    echo "'".$row['name'][$i]."'";
                    if ($i < count($row['name']) - 1) echo ",";
                }
            ?>
        ]
    },
    yAxis: {
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
        bar: {
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
            echo "{name: 'Feedback Rate',";
            echo "data: ";
            echo json_encode($row['f_rate']);
            echo "}";
        ?>
    ]
});

$("#growth_Table2 tr").append(
    <?php 
        for($i=0; $i < count($row['name']); $i++){
            echo '"<th>'.$row['name'][$i].'</th>" ';
            if ($i < count($row['name']) - 1) echo '+';
        }
    ?>
);
$("#growth_Table2 tbody").append('<tr><td>Total Feedback</td>' +
    <?php 
    for($i=0; $i < count($row['total']); $i++){
        echo '"<td>'.$row['total'][$i].'</td>" + ';
    }
    echo "'</tr>'";
    ?>
);
$(document).ready(function(e){
	<?php 
		if($fdate){
			$date = explode(' ~ ', $fdate);
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
		report_load('twitter', 'performance', <?php echo $account->account_id;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});
</script>