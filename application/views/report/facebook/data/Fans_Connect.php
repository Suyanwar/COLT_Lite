<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font> Fans <br>Connect</h1>
	    <div class="clear"></div>
	</div>
<div class="dateRangePicker">
    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
</div>
	<div class="modal-body">
		<h1><span class="fa fa-circle-o-notch"></span> Top Fanpage <sup><span class="fa fa-info-circle tooltip"><span class="tooltiptext"><b>Top Fanpage</b><hr>Perkiraan jumlah <i>fanpage</i> terbanyak yang di ikuti oleh <i>user</i> yang aktif.</span></span></sup></h1>

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="list">
<thead>
<tr>
    <th width="25"></th>
    <th>Fanpage</th>
    <th width="250">Category</th>
</tr>
</thead>
<tbody>
<?php
if($data){
	$i = 1;
	foreach($data as $list){
		echo '<tr style="text-align:right">';
		echo '<td>'.$i.'</td>';
		echo '<td style="text-align:left"><a href="https://www.facebook.com/'.$list->socmed_id.'" target="_blank" title="'.$list->total.'">'.$list->name.'</a></td>';
		echo '<td>'.$list->category.'</td>';
		echo '</tr>';
		$i++;
	}
}
else echo '<tr><td class="nodata" colspan="4">No data.</td></tr>';
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
		report_load('facebook', 'fans_connect', <?php echo $account?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});
</script>