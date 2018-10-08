<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font>Activity</h1>
	    <div class="clear"></div>
	</div>
	<div class="dateRangePicker">
	    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
	    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
	</div>
	<div class="modal-body">
		<div id="content">
			<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
				<li class="active"><a href="#VsCompetitor" data-toggle="tab">Vs Competitor</a></li>
				<li><a href="#MonthlyActivity" data-toggle="tab">Monthly Activity</a></li>
			</ul>
		</div>
		<div id="my-tab-content" class="tab-content">
			<div class="tab-pane active" id="VsCompetitor">
				<div id="activities_ActivityChart"></div>
				<div id="activities_ActivityTable">
					<table class="table table-striped parallel">
						<tbody></tbody>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="MonthlyActivity">
				<div class="row">
					<div class="col-md-8">
						<div id="activities_ActivitiesChart"></div>
					</div>
					<div class="col-md-4">
						<div id="activities_ActivitiesDetail">
							<table class="table table-striped">
								<thead>
									<tr>
										<th style="text-align: left;">Likes Rate</th>
									    <?php 
									    	$period = getmonth('unix', $date);
									    	for ($i=3; $i >= 0; $i--) {
									    		echo "<th>";
									    		echo date('M-y', strtotime('-' . $i . ' Month', $period[0]));
									    		echo "</th>";
									    	}
									    ?>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td width="100">Per Photo</td>
									    <?php
									    	foreach ($detail['like_photo'] as $d) {
									    		echo "<td><b>" . $d . "</td></b>";
									    	}
									    ?>
									</tr>
									<tr>
										<td>Per Video</td>
									    <?php
									    	foreach ($detail['like_video'] as $d) {
									    		echo "<td><b>" . $d . "</td></b>";
									    	}
									    ?>
									</tr>
								</tbody>
							</table>
							<table class="table table-striped">
								<thead>
									<tr>
										<th style="text-align: left;">Comments Rate</th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td width="100">Per Photo</td>
									    <?php
									    	foreach ($detail['comment_photo'] as $d) {
									    		echo "<td><b>" . $d . "</td></b>";
									    	}
									    ?>
									</tr>
									<tr>
										<td>Per Video</td>
									    <?php
									    	foreach ($detail['comment_video'] as $d) {
									    		echo "<td><b>" . $d . "</td></b>";
									    	}
									    ?>
									</tr>
								</tbody>
							</table>
							<table class="table table-striped">
								<thead>
									<tr>
										<th style="text-align: left;">Views Rate</th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td width="100">Per Photo</td>
									    <?php
									    	foreach ($detail['view_photo'] as $d) {
									    		echo "<td><b>" . $d . "</td></b>";
									    	}
									    ?>
									</tr>
									<tr>
										<td>Per Video</td>
									    <?php
									    	foreach ($detail['view_video'] as $d) {
									    		echo "<td><b>" . $d . "</td></b>";
									    	}
									    ?>
									</tr>									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
<?php

if($competitor){
$year = date('Y', strtotime(date('Y-m', $unix[0]).'-01'));
?>

$('#activities_ActivityChart').highcharts({
	chart: {
		zoomType: 'xy'
	},
	title: {
		text: null
	},
	subtitle: {
		text: '<?php echo date('F, d Y', $unix[0]).' ~ '.date('F, d Y', $unix[1]) ?>'
	},
	xAxis: [{
		labels: {
			formatter: function(){
	            if(this.value === "<?php echo $account['name']; ?>"){
	                return "<b>" + this.value + "</b>";
	            }
	            else{
	            	return this.value;
	            }
	        }, 
            style: {
                fontSize: '12px'
            }
        },
		categories: [
		<?php
			$i = 1;
			foreach($competitor as $list){
				$chart[$list->name] = $this->report_instagram->activities($list->account_id, 'Summary', $date);
				echo "'" . $list->name . "'";
				if ($i < count($competitor)) echo ",";
				$i++;
			}
		?>
		]
	}],
	yAxis: [{
		gridLineWidth: 0,
		title: {
			text: 'Engagement',
			style: {
				color: '#89A54E'
			}
		},
		labels: {
			format: '{value}',
			formatter: function () {
                return Highcharts.numberFormat(this.value, 0, '.', ',');
            },
			style: {
				color: '#89A54E'
			}
		}
	}, {
		gridLineWidth: 0,
		title: {
			text: 'Post',
			style: {
				color: '#4572A7'
			}
		},
		labels: {
			format: '{value}',
			formatter: function () {
                return Highcharts.numberFormat(this.value, 0, '.', ',');
            },
			style: {
				color: '#4572A7'
			}
		},
		opposite: true
	}],
	tooltip: {
		headerFormat: '<span style="font-size: 12px; font-weight: bold"> {point.key}s </span><br/>',
		shared: true
	},
	legend: {
		enabled: true,
		borderColor: '#DDD',
		itemStyle: {
			color: '#666'
		}
	},
	credits: {
		enabled: false
	},
	plotOptions: {
		column: {
			stacking: 'normal',
			dataLabels: {
				enabled: true,
				formatter: function () {
                    return Highcharts.numberFormat(this.y, 0, '.', ',');
                },
				color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || '#000',
				style: {
					color: '#000',
					textShadow: false
				}
			}
		},
		spline: {
			dataLabels: {
				enabled: true,
				formatter: function () {
                    return Highcharts.numberFormat(this.y, 0, '.', ',');
                },
				style: {
					color: '#000'
				}
			}
		}
	},
	series: [{
		type: 'column',
		name: 'Video post',
		color: '#D7CCC8',
		yAxis: 1,
		data: [
		<?php
			$i = 1;		
			foreach($chart as $list){
				echo $list['video_post'];
				if ($i < count($chart)) echo ",";
				$i++;
			}
		?>
		],
		tooltip: {
			valueSuffix: null
			}
	}, {
		type: 'column',
		name: 'Photo post',
		yAxis: 1,
		data: [
		<?php
			$i = 1;
			foreach($chart as $list){
				echo $list['photo_post'];
				if ($i < count($chart)) echo ",";
				$i++;
			}
		?>
		],
		tooltip: {
			valueSuffix: null
		}
	}, {
		name: 'Engagement',
		color: '#89A54E',
		type: 'spline',
		data: [
		<?php
			$i = 1;		
			foreach($chart as $list){
				echo $list['engagement_total'];
				if ($i < count($chart)) echo ",";
				$i++;
			}
		?>
		],
		tooltip: {
			valueSuffix: null
		}
	}]
});
$("#activities_ActivityTable tbody").append(
	"'<tr>" +
	"<td>Feedback Rate</td>'" +
	<?php
		foreach($chart as $list){
			$div = $list['video_post'] + $list['photo_post'];
			echo "'<td>'+"; 
			echo ($div) ? round($list['engagement_total'] / $div) : 0;
			echo "+'</td>' +"; 
		}
	?>
	"</tr>"
);
<?php
}else echo '<p align="center" style="padding-top:25px">No data.</p>';
?>

$('#activities_ActivitiesChart').highcharts({
	chart: {
		zoomType: 'xy'
	},
	title: {
		text: null
	},
	subtitle: {
		text: '<?php echo date('F, d Y', $unix[0]).' ~ '.date('F, d Y', $unix[1]) ?>'
	},
	xAxis: [{
		labels: {
			rotation: 320
		},
		categories: [
		<?php
			$i = 1;
			foreach($activity as $list){
				echo ($i > 1) ? ',' : '';
				if($list[0] == $list[1]){
					echo "'<b>Week $i</b> (".date('j M', strtotime($list[1])).")'";
				}
				else echo "'<b>Week $i</b> (".date('j', strtotime($list[0]))." - ".date('j M', strtotime($list[1])).")'";
				$i++;
			}
		?>
		]
	}],
	yAxis: [{
		gridLineWidth: 0,
		title: {
			text: 'Feedback',
			style: {
				color: '#89A54E'
			}
		},
		labels: {
			format: '{value}',
			formatter: function () {
                return Highcharts.numberFormat(this.value, 0, '.', ',');
            },
			style: {
				color: '#89A54E'
			}
		}
	}, {
		gridLineWidth: 0,
		title: {
			text: 'Post',
			style: {
				color: '#4572A7'
			}
		},
		labels: {
			format: '{value}',
			formatter: function () {
                return Highcharts.numberFormat(this.value, 0, '.', ',');
            },
			style: {
				color: '#4572A7'
			}
		},
		opposite: true
	}],
	tooltip: {
		shared: true
	},
	legend: {
		enabled: true,
		borderColor: '#DDD',
		itemStyle: {
			color: '#666'
		}
	},
	credits: {
		enabled: false
	},
	plotOptions: {
		column: {
			stacking: 'normal',
			dataLabels: {
				enabled: true,
				formatter: function () {
                    return Highcharts.numberFormat(this.y, 0, '.', ',');
                },
				color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || '#000',
				style: {
					color: '#000',
					textShadow: false
				}
			}
		},
		spline: {
			dataLabels: {
				enabled: true,
				formatter: function () {
                    return Highcharts.numberFormat(this.y, 0, '.', ',');
                },
				style: {
					color: '#000'
				}
			}
		}
	},
	series: [{
		type: 'column',
		name: 'Photo post',
		yAxis: 1,
		data: [
		<?php
			$i = 1;
			foreach($activity as $list){
				echo ($i > 1) ? ',' : '';
				echo $this->report_instagram->activities($account['account_id'], 'post', array('type' => 'image', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '".$year.date('-m-d', strtotime($list[0]))."' AND '".$year.date('-m-d', strtotime($list[1]))."'" => NULL));
				$i++;
			}
		?>
		],
		tooltip: {
			valueSuffix: null
		}
	}, {
		type: 'column',
		name: 'Video post',
		color: '#D7CCC8',
		yAxis: 1,
		data: [
		<?php
			$i = 1;
			foreach($activity as $list){
				echo ($i > 1) ? ',' : '';
				echo $this->report_instagram->activities($account['account_id'], 'post', array('type' => 'video', "DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '".$year.date('-m-d', strtotime($list[0]))."' AND '".$year.date('-m-d', strtotime($list[1]))."'" => NULL));
				$i++;
			}
		?>
		],
		tooltip: {
			valueSuffix: null
		}
	}, {
		name: 'Feedback',
		color: '#89A54E',
		type: 'spline',
		data: [
		<?php
			$i = 1;
			foreach($activity as $list){
				echo ($i > 1) ? ',' : '';
				echo $this->report_instagram->activities($account['account_id'], 'engagement', array("DATE_FORMAT(post_time, '%Y-%m-%d') BETWEEN '".$year.date('-m-d', strtotime($list[0]))."' AND '".$year.date('-m-d', strtotime($list[1]))."'" => NULL));
				$i++;
			}
		?>
		],
		tooltip: {
			valueSuffix: null
		}
	}]
});
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
		report_load('instagram', 'activity', <?php echo $account['account_id'];?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});

Highcharts.setOptions({
    lang: {
        thousandsSep: ','
    }
});
</script>