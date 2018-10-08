<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font> Activities</h1>
	    <div class="clear"></div>
	</div>
<div class="dateRangePicker">
    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;"/>
</div>
	<div class="modal-body">
		<div id="content">
			<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
				<li class="active"><a href="#AccountPost" data-toggle="tab">Account Post</a></li>
				<li><a href="#TotalAmplification" data-toggle="tab">Total Amplification</a></li>
			</ul>
		</div>
		<div id="my-tab-content" class="tab-content">
			<div class="tab-pane active" id="AccountPost">
				<div class="row">
					<div class="col-md-6">
						<div id="piechart"></div>
						<?php
						if(!$AccountPost) echo '<p align="center" style="padding-top:25px">No data.</p>';
						?>	
					</div>
					<div class="col-md-6">
						<div id="piechart1"></div>
						<?php
						if(!$TotalFeedback) echo '<p align="center" style="padding-top:25px">No data.</p>';
						?>				
					</div>
				</div>
			</div>
			<div class="tab-pane" id="TotalAmplification">
				<div id="piechart2"></div>
				<?php
				if(!$TotalAmplification) echo '<p align="center" style="padding-top:25px">No data.</p>';
				?>
			</div>
			<script type="text/javascript">
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
	report_load('facebook', 'activity', <?php echo $account;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
});

Highcharts.setOptions({
    lang: {
        thousandsSep: ','
    }
});

<?php if ($AccountPost): ?>
$('#piechart').highcharts({
	chart: {
		type: 'pie'
	},
	title: {
		text: 'Account post'
	},
	subtitle: {
		text: null
	},
	credits: {
		enabled: false
	},
	tooltip: {
		enabled: false
	},
	plotOptions: {
        pie: {
        	innerSize: '65%',
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y:,.0f}',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            },
            showInLegend: true
        }
    },
	series: [{
		name: 'Account post',
		data: [
		<?php
		$i = 0;

		foreach ($AccountPost as $chart) {
			echo "{name: '" . $chart->type . "',";
			echo "y: " . $chart->total;
			echo "}";
			if($i++ < count($AccountPost) - 1) echo ",";
		}
		?>
		]
	}]
});
<?php endif?>

<?php if ($TotalFeedback): ?>
$('#piechart1').highcharts({
	chart: {
		type: 'pie'
	},
	title: {
		text: 'Total Feedback'
	},
	subtitle: {
		text: null
	},
	credits: {
		enabled: false
	},
	tooltip: {
		enabled: false
	},
	plotOptions: {
        pie: {
        	innerSize: '65%',
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y:,.0f}',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            },
            showInLegend: true
        }
    },
	series: [{
		name: 'Total Feedback',
		data: [
		<?php
		$i = 0;

		foreach ($TotalFeedback as $chart) {
			echo "{name: '" . $chart->type . "',";
			echo "y: " . $chart->total;
			echo "}";
			if($i++ < count($TotalFeedback) - 1) echo ",";
		}
		?>
		]
	}]
});
//<?php echo json_encode($TotalFeedback);?>
<?php endif?>

<?php if ($TotalAmplification): ?>
$('#piechart2').highcharts({
	chart: {
		type: 'pie'
	},
	title: {
		text: 'Total Amplification'
	},
	subtitle: {
		text: null
	},
	credits: {
		enabled: false
	},
	tooltip: {
		enabled: false
	},
	plotOptions: {
        pie: {
        	innerSize: '65%',
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y:,.0f}',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            },
            showInLegend: true
        }
    },
	series: [{
		name: 'Total Amplification',
		data: [
		<?php
		$i = 0;

		foreach ($TotalAmplification as $chart) {
			echo "{name: '" . $chart->type . "',";
			echo "y: " . $chart->total;
			echo "}";
			if($i++ < count($TotalAmplification) - 1) echo ",";
		}
		?>
		]
	}]
});
<?php endif?>

			</script>
		</div>
	</div>
</div>