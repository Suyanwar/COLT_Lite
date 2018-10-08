<?php defined('BASEPATH') OR exit('No direct script access allowed');
$unix = getmonth('unix', $date); ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font> Highlight</h1>
	    <div class="clear"></div>
	</div>
	<div class="dateRangePicker">
	    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
	    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;"/>
    </div>
	<div class="modal-body">
	    <h1><span class="fa fa-circle-o-notch"></span> Summary <sup><span class="fa fa-info-circle tooltip"><span class="tooltiptext"><b>Summary</b><hr>Ringkasan informasi <i>account</i> pada periode saat ini.</span></span></sup></h1>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table">
<thead>
<tr>
    <th width="130">Month</th>
    <th width="125">Feedback Rate</th>
    <th width="125">Engagement Ratio</th>
    <th width="125">Total Interaction</th>
    <th width="125">Estimated Impression</th>
    <th width="120">Fans</th>
    <th>Prime Time</th>
</tr>
</thead>
<tbody>
<?php
if ($highlight) {
	foreach($highlight as $list){
		echo "<tr>";
		$date = '01-'.date('M Y', strtotime($list['month'])).' ~ '.date('t M Y', strtotime($list['month']));
		$time = '-';
		if(isset($list['primetime']->created_time)){
			$hh = date('H', strtotime($list['primetime']->created_time));
			$time = "{$hh}:00 - {$hh}:59";
		}
		$status_ft = '';
		if(isset($ft)){
			$ft_old = $ft;
			$ft = $list['feedback'] / ($list['posts'] ? $list['posts'] : 1);
			if($ft > $ft_old){
				$status_ft = '<font class="fa fa-caret-up" style="color:#00F"></font> ';
			}else{
				if($ft < $ft_old){
					$status_ft = '<font class="fa fa-caret-down" style="color:#F00"></font> ';
				}
			}
		}
		else $ft = $list['feedback'] / ($list['posts'] ? $list['posts'] : 1);
		$status_er = '';
		if(isset($er)){
			$er_old = $er;
			$er = ($this->report_twitter->users($account->account_id, 'MostActiveCount', $date) / ($list['fans'] ? $list['fans'] : 1)) * 100;
			if($er > $er_old){
				$status_er = '<font class="fa fa-caret-up" style="color:#00F"></font> ';
			}else{
				if($er < $er_old){
					$status_er = '<font class="fa fa-caret-down" style="color:#F00"></font> ';
				}
			}
		}
		else $er = ($this->report_twitter->users($account->account_id, 'MostActiveCount', $date) / ($list['fans'] ? $list['fans'] : 1)) * 100;
		
		$status_mp = '';
		if(isset($mp)){
			$mp_old = $mp;
			$mp = $list['impression'] ? ($list['growth'] / $list['impression']  * 100) : 0;
			if($mp > $mp_old){
				$status_mp = '<font class="fa fa-caret-up" style="color:#00F"></font> ';
			}else{
				if($mp < $mp_old){
					$status_mp = '<font class="fa fa-caret-down" style="color:#F00"></font> ';
				}
			}
		}
		else $mp = $list['impression'] ? ($list['growth'] / $list['impression']  * 100) : 0;
		$status_im = '';
		if(isset($im)){
			$im_old = $im;
			$im = $list['impression'];
			if($im > $im_old){
				$status_im = '<font class="fa fa-caret-up" style="color:#00F"></font> ';
			}else{
				if($im < $im_old){
					$status_im = '<font class="fa fa-caret-down" style="color:#F00"></font> ';
				}
			}
		}
		else $im = $list['impression'];
		$status_fn = '';
		if(isset($fn)){
			$fn_old = $fn;
			$fn = $list['fans'];
			if($fn > $fn_old){
				$status_fn = '<font class="fa fa-caret-up" style="color:#00F"></font> ';
			}else{
				if($fn < $fn_old){
					$status_fn = '<font class="fa fa-caret-down" style="color:#F00"></font> ';
				}
			}
		}
		else $fn = $list['fans'];
		echo '<td align="center">'.date('F', strtotime($list['month'])).'</td>';
		echo '<td align="right" title="'.number_format($ft, 2).'">'.$status_ft.number_format($ft).'</td>';
		echo '<td align="right">'.$status_er.number_format($er, 2).'%</td>';
		echo '<td align="right">'.$status_mp.number_format($mp, 2).'%</td>';
		echo '<td align="right">'.$status_im.number_format($im).'</td>';
		echo '<td align="right">'.$status_fn.number_format($fn).'</td>';
		echo '<td align="center">'.$time.'</td>';
		echo '</tr>';
	}
}
?>

</tbody>

</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table">
<tbody>
<?php if($highlight): ?>
<tr>
	<td rowspan="3"><h1><span class="fa fa-circle-o-notch"></span> GROWTH <sup><span class="fa fa-info-circle tooltip"><span class="tooltiptext"><b>GROWTH</b><hr>Ringkasan informasi <i>account</i> pada periode saat ini.</span></span></sup></h1></td>
	<td style="font-size: 18px;"><b><?php echo date('F', strtotime('-2 Month', $unix[0])) ?></b><div><?php echo number_format($highlight[0]['growth'])?></div></td>
	<td rowspan="3" class="highlight">The greatest growth<div><h1 style="border-bottom: none;"><?php echo $growth ?></h1></div></td>
<?php
	if($AllAccount){ 
		foreach($AllAccount as $list){
			echo '<td rowspan="3" class="col-lg-3">';
			echo '<table>';
			echo '<tr>';
			echo $list->image_url ? '<td><a href="https://www.twitter.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
			echo '<td>Total Feedback: <b>'.number_format($list->total).'</b></td>';
			echo '</tr>';
			echo '<tr><td colspan="2"><div>'.limit_text($list->text, 20).'</div></td></tr>';
			echo '</table>';
			echo '</td>';
			break;
		}	
	}
?>
</tr>
<tr>
	<td style="font-size: 18px;"><b><?php echo date('F', strtotime('-1 Month', $unix[0])) ?></b><div><?php echo number_format($highlight[1]['growth'])?></div></td>
</tr>
<tr>
	<td style="font-size: 18px;"><b><?php echo date('F', strtotime('-0 Month', $unix[0])) ?></b><div><?php echo number_format($highlight[2]['growth'])?></div></td>
</tr>
<?php else: ?>
	<tr><td align="center" colspan="6">No data.</td></tr>
<?php endif; ?>
</tbody>
</table>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table">
	<thead>
	<tr>
		<th></th>
		<th>Growth</th>
		<th>Engagement Ratio</th>
	</tr>
	</thead>
	<tbody class="center">
<?php if($benchmark):
	$status_gwt = '';
	if($benchmark['growth'] < ($highlight[2]['fans'] - $highlight[1]['fans']) / ($highlight[1]['fans'] ? $highlight[1]['fans'] : 1) * 100){
		$status_gwt = '<font class="fa fa-caret-up" style="color:#00F"></font> ';
	}else{
		if($benchmark['growth'] > ($highlight[2]['fans'] - $highlight[1]['fans']) / ($highlight[1]['fans'] ? $highlight[1]['fans'] : 1) * 100){
			$status_gwt = '<font class="fa fa-caret-down" style="color:#F00"></font> ';
		}
	}
	$status_egr = '';
	if($benchmark['engagement_ratio'] < $er){
		$status_egr = '<font class="fa fa-caret-up" style="color:#00F"></font> ';
	}else{
		if($benchmark['engagement_ratio'] > $er){
			$status_egr = '<font class="fa fa-caret-down" style="color:#F00"></font> ';
		}
	}
?>
		<tr>
	        <td>Benchmark Industry</td>
	        <td><?php echo $benchmark['growth'] ?></td>
	        <td><?php echo $benchmark['engagement_ratio'] ?></td>
	    </tr>
		<tr>
			<td><?php echo $account->name?></td>
	        <td><?php echo $status_gwt.number_format(($highlight[2]['fans'] - $highlight[1]['fans']) / ($highlight[1]['fans'] ? $highlight[1]['fans'] : 1) * 100, 2) . '%' ?></td>
	        <td><?php echo $status_egr.number_format($er, 2) . "%" ?></td>
		</tr>
<?php else: ?> 
	<tr><td align="center" colspan="6">No data.</td></tr>

<?php endif; ?>
	</tbody>
	</table>
	</div>
</div>
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
		report_load('twitter', 'highlight', <?php echo $account->account_id;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});

</script>
