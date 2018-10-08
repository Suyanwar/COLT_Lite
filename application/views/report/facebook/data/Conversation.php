<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h1><font class="fa fa-star-half-full"></font> Conversation</h1>
        <div class="clear"></div>
    </div>
<div class="dateRangePicker">
    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
</div>
    <div class="modal-body">
        <div id="content">
            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                <li class="active"><a href="#satu" data-toggle="tab">Chart</a></li>
                <li><a href="#dua" data-toggle="tab">Table</a></li>
            </ul>
        </div>
        <div id="my-tab-content" class="tab-content">
            <div class="tab-pane active" id="satu">
			    <h1><span class="fa fa-circle-o-notch"></span> Admin Conversation <sup><span class="fa fa-info-circle tooltip"><span class="tooltiptext"><b>Admin Conversation</b><hr>Jumlah <i>posting</i> yang di kelompokkan berdasarkan kategori / topik.</span></span></sup></h1>
			    <div class="row">
			    	<div class="col-md-6">
			    		<h4>Conversation Status</h4>
			    		<div id="conversation_Status"></div>
			    	</div>
			    	<div class="col-md-6">
			    		<h4>Conversation Feedback</h4>
			    		<div id="conversation_Feedback"></div>
			    	</div>
			    </div>
            </div>
            <div class="tab-pane" id="dua">
			    <div id="conversation_Feedback">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-hovered">
					<thead>
					<tr>
					    <th>Category</th>
					    <th>Conversation</th>
					    <th>Feedback</th>
					    <th>Feedback Rate</th>
					</tr>
					</thead>
					<tbody>

					<?php
					$i = 0;
					$colors = array("7cb5ec", "434348", "90ed7d", "f7a35c", "8085e9", "f15c80", "e4d354", "2b908f", "f45b5b", "91e8e1");
					for ($i=0; $i < count($data); $i++) { 
						echo "<tr style='background-color: #".$colors[$i]."; color: #ffffff'>";
						echo "<td>" . $data[$i]['category'] . "</td>";
						echo "<td class='center'>" . number_format($data[$i]['conversation']) . "</td>";
						echo "<td class='center'>" . number_format($data[$i]['totalFeedback']) . "</td>";
						echo "<td class='center'>" . number_format(round($data[$i]['totalFeedback'] / ($data[$i]['conversation'] ? $data[$i]['conversation'] : 1))) . "</td>";
						echo "</tr>";
					}	
					?>

					</tbody>
			    </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#conversation_Status').highcharts({
	chart: {
        type: 'pie',
        width: 450,
        options3d: {
            enabled: true,
            alpha: 45,
            beta: 0
        }
	},
	title: {
		text: null
	},
	subtitle: {
		text: null
	},
	credits: {
		enabled: false
	},
	tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	},
	plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            depth: 35,
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
	legend: {
		enabled: true
	},
	series: [{
		type: 'pie',
		name: 'Number of posts',
		data: [
		<?php
		$i = 0;
		for ($i=0; $i < count($data); $i++) { 
			echo "{name: '" . $data[$i]['category'] . "',";
			echo "y: " . $data[$i]['conversation'];
			echo "}";
			if($i < count($data) - 1) echo ",";
		}	
		?>
		]
	}]
});

$('#conversation_Feedback').highcharts({
	chart: {
		width: 450,
		type: 'pie'
	},
	title: {
		text: null
	},
	subtitle: {
		text: null
	},
	credits: {
		enabled: false
	},
	tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	},
	plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
	legend: {
		enabled: true
	},
	series: [{
		name: 'Number of posts',
		data: [
		<?php
		$i = 0;
		for ($i=0; $i < count($data); $i++) { 
			echo "{name: '" . $data[$i]['category'] . "',";
			echo "y: " . $data[$i]['totalFeedback'];
			echo "}";
			if($i < count($data) - 1) echo ",";
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
		report_load('facebook', 'conversation', <?php echo $account;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});
</script>