<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font> Prime Time</h1>
	    <div class="clear"></div>
	</div>
<div class="dateRangePicker">
    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
</div>
	<div class="modal-body">
		<div id="content">
			<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
				<li class="active"><a href="#DailyPost" data-toggle="tab">Daily Post</a></li>
				<li><a href="#DailyComment" data-toggle="tab">Daily Comment</a></li>
				<li><a href="#TrendingPost" data-toggle="tab">Trending Post</a></li>
			</ul>
		</div>
		<div id="my-tab-content" class="tab-content">
			<div class="tab-pane active" id="DailyPost">
				<div id="primetime_DailyPost" style="width: 100%"></div>
			</div>
			<div class="tab-pane" id="DailyComment">
				<div id="primetime_DailyComment" style="width: 100%"></div>
			</div>
			<div class="tab-pane" id="TrendingPost">
	  			<?php
	  				$kurangsatu =  date("d-m-Y", strtotime("-1 Month", $unix[0]))." ~ ".date("d-m-Y", strtotime("-1 Month", $unix[1]));
	  				$kurangdua =  date("d-m-Y", strtotime("-2 Month", $unix[0]))." ~ ".date("d-m-Y", strtotime("-2 Month", $unix[1]));
				?>
				<div id="primetime_TrendingPost" style="width: 100%"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$('#primetime_DailyPost').highcharts({
	chart: {
		type: 'heatmap',
		marginTop: 40,
		marginBottom: 80
	},
	title: {
		text: null
	},
	subtitle: {
		text: '<?php echo date('F, d Y', $unix[0]).' ~ '.date('F, d Y', $unix[1]) ?>'
	},
	xAxis: {
		useHTML: true,
		categories: [
		<?php
		for($i=0; $i < 24; $i++)
		$sph[$i] = $this->report_facebook->primetime($account, 'SumPost', array($date, '%H', $i));
		natsort($sph);
		foreach($sph as $num)
		$sp = $num;
		for($i=0; $i < 24; $i++){
			echo $i ? ',' : '';
			if($sp){
				if($sp == $sph[$i]){
					echo '"<b>'.(($i > 9) ? $i : '0'.$i).':00</b><br><b>('.$sph[$i].')</b>"';
				}
				else echo '"'.(($i > 9) ? $i : '0'.$i).':00<br>('.$sph[$i].')"';
			}
			else echo '"'.(($i > 9) ? $i : '0'.$i).':00<br>('.$sph[$i].')"';
		}
		?>
		]
	},
	yAxis: {
		useHTML: true,
		reversed: true,
		gridLineWidth: 0,
		categories: [
		<?php
		$day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		for($ii=0; $ii < count($day); $ii++)
		$spw[$ii] = $this->report_facebook->primetime($account, 'SumPost', array($date, '%w', $ii));
		natsort($spw);
		foreach($spw as $num)
		$sp = $num;
		for($ii=0; $ii < count($day); $ii++){
			echo $ii ? ',' : '';
			if($sp){
				if($sp == $spw[$ii]){
					echo '"<b>'.$day[$ii].' ('.$spw[$ii].')</b>"';
				}
				else echo '"'.$day[$ii].' ('.$spw[$ii].')"';
			}
			else echo '"'.$day[$ii].' ('.$spw[$ii].')"';
		}
		?>
		],
		title: null
	},
	colorAxis: {
		min: 0,
		minColor: '#FFF',
		maxColor: Highcharts.getOptions().colors[0]
	},
	legend: {
		enabled: false
	},
	credits: {
		enabled: false
	},
	tooltip: {
		formatter: function () {
			return '<b>' + this.series.xAxis.categories[this.point.x] + '/59</b><br><b>' +
			this.point.value + '</b> post(s) on <br><b>' + this.series.yAxis.categories[this.point.y] + '</b>';
		}
	},
	plotOptions: {
		series: {
			states: {
				hover: {
					enabled: false
				}
			}
		}
	},
	colors : ['#FFF', '#33B5E5', '#A6C', '#9C0', '#FB3', '#F44', '#09C', '#93C', '#690', '#F80', '#C00', '#C88830', '#D0A848'],
	colorAxis : {
		dataClassColor : 'category',
		dataClasses : [{
				to : 0
			}, {
				from : 1
			}, {
				from : 2
			}, {
				from : 3
			}, {
				from : 4
			}, {
				from : 5
			}, {
				from : 6,
			}, {
				from : 7,
			}, {
				from : 8
			}, {
				from : 9,
				to: 13
			}, {
				from : 14,
				to: 17
			}, {
				from : 18,
				to: 21
			}, {
				from : 22
			}
		]
	},
	series: [{
		name: 'Number of posts',
		color: '#F3F3F3',
		borderWidth: 1,
		data: [
		<?php
		$x=0;
		for($i=0; $i < 24; $i++){
			for($ii=0; $ii < 7; $ii++){
				$pt[$i.'_'.$ii] = $this->report_facebook->primetime($account, 'DailyPost', array($date, (($i > 9) ? $i : '0'.$i).'/'.$ii));
				echo $x ? ',' : '';
				echo '['.$i.', '.$ii.', '.$pt[$i.'_'.$ii].']';
				$x=1;
			}
		}
		?>
		],
		dataLabels: {
			enabled: true,
			color: '#FFF',
			style: {
				textShadow: false 
			}
		}
	}]
});
$('#primetime_DailyComment').highcharts({
	chart: {
		type: 'heatmap',
		marginTop: 40,
		marginBottom: 80
	},
	title: {
		text: null
	},
	subtitle: {
		text: '<?php echo date('F, d Y', $unix[0]).' ~ '.date('F, d Y', $unix[1]) ?>'
	},
	xAxis: {
		useHTML: true,
		categories: [
		<?php
		for($i=0; $i < 24; $i++)
		$sph[$i] = $this->report_facebook->primetime($account, 'SumComment', array($date, '%H', $i));
		
		natsort($sph);
		foreach($sph as $num)
		$sp = $num;
		
		for($i=0; $i < 24; $i++){
			echo $i ? ',' : '';
			if($sp){
				if($sp == $sph[$i]){
					echo '"<b>'.(($i > 9) ? $i : '0'.$i).':00</b><br><b>('.$sph[$i].')</b>"';
				}
				else echo '"'.(($i > 9) ? $i : '0'.$i).':00<br>('.$sph[$i].')"';
			}
			else echo '"'.(($i > 9) ? $i : '0'.$i).':00<br>('.$sph[$i].')"';
		}
		?>
		]
	},
	yAxis: {
		useHTML: true,
		reversed: true,
		gridLineWidth: 0,
		categories: [
		<?php
		$day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		for($ii=0; $ii < count($day); $ii++)
		$spw[$ii] = $this->report_facebook->primetime($account, 'SumComment', array($date, '%w', $ii));
		
		natsort($spw);
		foreach($spw as $num)
		$sp = $num;
		
		for($ii=0; $ii < count($day); $ii++){
			echo $ii ? ',' : '';
			if($sp){
				if($sp == $spw[$ii]){
					echo '"<b>'.$day[$ii].' ('.$spw[$ii].')</b>"';
				}
				else echo '"'.$day[$ii].' ('.$spw[$ii].')"';
			}
			else echo '"'.$day[$ii].' ('.$spw[$ii].')"';
		}
		?>
		],
		title: null
	},
	colorAxis: {
		min: 0,
		minColor: '#FFF',
		maxColor: Highcharts.getOptions().colors[0]
	},
	legend: {
		enabled: false
	},
	credits: {
		enabled: false
	},
	tooltip: {
		formatter: function () {
			return '<b>' + this.series.xAxis.categories[this.point.x] + '/59</b><br><b>' +
			this.point.value + '</b> post(s) on <br><b>' + this.series.yAxis.categories[this.point.y] + '</b>';
		}
	},
	plotOptions: {
		series: {
			states: {
				hover: {
					enabled: false
				}
			}
		}
	},
	colors : ['#FFF', '#33B5E5', '#A6C', '#9C0', '#FB3', '#F44', '#09C', '#93C', '#690', '#F80', '#C00', '#C88830', '#D0A848'],
	colorAxis : {
		dataClassColor : 'category',
		dataClasses : [{
				to : 0
			}, {
				from : 1
			}, {
				from : 2
			}, {
				from : 3
			}, {
				from : 4
			}, {
				from : 5
			}, {
				from : 6,
			}, {
				from : 7,
			}, {
				from : 8
			}, {
				from : 9,
				to: 13
			}, {
				from : 14,
				to: 17
			}, {
				from : 18,
				to: 21
			}, {
				from : 22
			}
		]
	},
	series: [{
		name: 'Number of posts',
		color: '#F3F3F3',
		borderWidth: 1,
		data: [
		<?php
		$x=0;
		for($i=0; $i < 24; $i++){
			for($ii=0; $ii < 7; $ii++){
				$pt[$i.'_'.$ii] = $this->report_facebook->primetime($account, 'DailyComment', array($date, (($i > 9) ? $i : '0'.$i).'/'.$ii));
				echo $x ? ',' : '';
				echo '['.$i.', '.$ii.', '.$pt[$i.'_'.$ii].']';
				$x=1;
			}
		}
		?>
		],
		dataLabels: {
			enabled: true,
			color: '#FFF',
			style: {
				textShadow: false 
			}
		}
	}]
});
$('#primetime_TrendingPost').highcharts({
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Trending Post'
    },
	subtitle: {
		text: '<?php echo date("F", strtotime("-2 Month", $unix[0]))."~".date("F", strtotime("-0 Month", $unix[0])); ?>'
	},
    xAxis: {
        categories: [
 			<?php
				for ($i=0; $i < 24; $i++) { 
					echo "'".$i.":00 - ".$i.":59'";
					if ($i < 23) {
						echo ", ";
					}
				}
			?>
        ]
    },
    yAxis: {
        title: {
            text: 'Num of Post'
        }
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
    series: [{
        name: '<?php echo date("F", strtotime("-0 Month", $unix[0]));?>',
        data:[
 			<?php
				for($i=0; $i < 24; $i++)
				$sph[$i] = $this->report_facebook->primetime($account, 'SumPost', array($date, '%H', $i));
				ksort($sph);
				$k = 0;
				foreach ($sph as $key => $value) {
					echo $value;
					if ($k <23) echo ", ";
				}
			?>
		]
    }, {
        name: '<?php echo date("F", strtotime("-1 Month", $unix[0]));?>',
        data:[
 			<?php
				for($i=0; $i < 24; $i++)
				$sph[$i] = $this->report_facebook->primetime($account, 'SumPost', array($kurangsatu, '%H', $i));
				ksort($sph);
				$k = 0;
				foreach ($sph as $key => $value) {
					echo $value;
					if ($k <23) echo ", ";
				}
			?>
		]
    }, {
        name: '<?php echo date("F", strtotime("-2 Month", $unix[0]));?>',
        data:[
 			<?php
				for($i=0; $i < 24; $i++)
				$sph[$i] = $this->report_facebook->primetime($account, 'SumPost', array($kurangdua, '%H', $i));
				ksort($sph);
				$k = 0;
				foreach ($sph as $key => $value) {
					echo $value;
					if ($k <23) echo ", ";
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
		report_load('facebook', 'primetime', <?php echo $account;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});
</script>