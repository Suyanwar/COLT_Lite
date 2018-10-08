<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font> Tag Post</h1>
	    <div class="clear"></div>
	</div>
	<div class="dateRangePicker">
	    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
	    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
	</div>
	<div class="modal-body">
		<h1>Most Used Tag</h1>
		<div id="hashtag_MostEngage"></div>
	</div>
<script type="text/javascript">
$('#hashtag_MostEngage').highcharts({
	chart: {
		type: 'bar',
		width: 500
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
		$i = 0;
		foreach($data as $list){
			echo $i ? ',' : '';
			echo "'$list->text'";
			$i = 1;
		}
		?>
		],
		title: {
			text: null
		},
		labels: {
			style: {
				fontSize: '11px'
			}
		}
	},
	yAxis: {
		min: 0,
		gridLineWidth: 0,
		title: {
			text: null,
			align: 'high'
		},
		labels: {
			enabled: true
		}
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
	credits: {
		enabled: false
	},
	series: [{
		name: 'Total',
		data: [
		<?php
		$i = 0;
		foreach($data as $list){
			echo $i ? ',' : '';
			echo $list->total;
			$i = 1;
		}
		?>
		]
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
		report_load('instagram', 'tagpost', <?php echo $account;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});
</script>
</div>