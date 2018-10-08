<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font> Effective<br>Communication</h1>
	    <div class="clear"></div>
	</div>
<div class="dateRangePicker">
    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
</div>
	<div class="modal-body">
		<h1><span class="fa fa-circle-o-notch"></span> Summary <sup><span class="fa fa-info-circle tooltip"><span class="tooltiptext"><b>Summary</b><hr>Ringkasan informasi <i>account</i> pada periode saat ini.</span></span></sup></h1>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-hover">
			<thead>
				<tr>
					<th>Account</th>
					<th>Total tweet</th>
					<th>Total feedback</th>
					<th>Total followers</th>
					<th>Feedback rate</th>
					<th>Effective communication</th>
					<th>Sentiment (-)</th>
					<th>Sentiment (+)</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($data as $list){
						echo ($list['account_id'] == $account->account_id) ? '<tr style="background-color: #cddc39">' : '<tr>';
							echo "<td>" . $list['name'] . "</td>";
							echo "<td>" . $list['posts'] . "</td>";
							echo "<td>" . $list['feedback'] . "</td>";
							echo "<td>" . $list['fans'] . "</td>";
							echo "<td>" . $list['feedback_rate'] . "</td>";
							echo "<td>" . $list['effective_communication'] . "</td>";
							echo "<td></td>";
							echo "<td></td>";
						echo "</tr>";
					}
				?>
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
		report_load('twitter', 'effective_communication', <?php echo $account->account_id;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});

</script>