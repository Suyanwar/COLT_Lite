<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font> Summary</h1>
	    <div class="clear"></div>
	</div>
	<div class="dateRangePicker">
	    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
	    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
	</div>
	<div class="modal-body">
		<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="300">
					<?php if(isset($data['post']['post_time'])): ?>
						<table class="table feed_list">
							<tr>
								<td>
									<table>
										<tr><td colspan="2" style="display:none"></td></tr>
										<tr>
											<?php
												if($data['post']['image_url']){
											?>
											<td><a href="<?php echo $data['post']['permalink'] ?>" target="_blank"><img src="<?php echo base_url('static/i/spacer.gif') ?>" style="background-image:url(<?php echo $data['post']['image_url'] ?>)"></a></td>
											<?php 
												}elseif($data['post']['video_url']){
											?>
											<td><video src="<?php echo $data['post']['video_url'] ?>"></video></a></td>
											<?php
												}else{
											?>
											<td><a href="<?php echo $data['post']['permalink'] ?>" target="_blank"><img src="<?php echo base_url('static/i/spacer.gif') ?>" style="background-image:url(<?php echo base_url('static/i/no-image-available.jpg') ?>)"></a></td>
											<?php
												}
											?>
											<td>Likes: <b><?php echo number_format($data['post']['likes_count']) ?></b><br>Comments: <b><?php echo number_format($data['post']['comment_count']) ?></b><?php if($data['post']['type'] == 'video') echo '<br>View: <b>'.number_format($data['post']['view_count']).'</b>' ?><br>Total: <b><?php echo number_format($data['post']['likes_count'] + $data['post']['comment_count']) ?></b></td>
										</tr>
										<tr><td colspan="2"><h2><a href="<?php echo $data['post']['permalink'] ?>" target="_blank"><?php echo date('d M Y - H:i', strtotime($data['post']['post_time'])) ?></a></h2><div><?php echo limit_text(str_replace(array('#'), ' #', $data['post']['text']), 23) ?></div></td></tr>
									</table>
								</td>
							</tr>
						</table>
					<?php endif ?>
				</td>
				<td style="padding-left:10px">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-striped">
						<thead>
							<tr>
								<th width="180">Statistics</th>
								<th width="180"><?php echo $dates[3]; ?></th>
								<th width="180"><?php echo $dates[2]; ?></th>
								<th width="180"><?php echo $dates[1]; ?></th>
								<th width="180"><?php echo $dates[0]; ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td width="180">Photo Post</td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData3['photo_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData2['photo_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData1['photo_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($data['photo_post']) ?></b></td>
							</tr>
							<tr>
								<td width="180">Video Post</td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData3['video_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData2['video_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData1['video_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($data['video_post']) ?></b></td>
							</tr>
							<tr>
								<td width="180">Feedback Rate</td>
								<td width="180" style="text-align: center;"><b title="<?php echo number_format($data['engagement_post'], 2) ?>"><?php echo number_format($prevData3['engagement_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b title="<?php echo number_format($data['engagement_post'], 2) ?>"><?php echo number_format($prevData2['engagement_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b title="<?php echo number_format($data['engagement_post'], 2) ?>"><?php echo number_format($prevData1['engagement_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b title="<?php echo number_format($data['engagement_post'], 2) ?>"><?php echo number_format($data['engagement_post']) ?></b></td>
							</tr>
						</tbody>
					</table>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-striped">
						<thead>
							<tr>
								<th colspan="2" style="text-align:left">Feedback</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td width="180">Likes</td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData3['like_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData2['like_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData1['like_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($data['like_post']) ?></b></td>
							</tr>
							<tr>
								<td width="180">Comments</td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData3['comment_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData2['comment_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData1['comment_post']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($data['comment_post']) ?></b></td>
							</tr>
							<tr>
								<td width="180">Total</td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData3['feedback_total']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData2['feedback_total']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($prevData1['feedback_total']) ?></b></td>
								<td width="180" style="text-align: center;"><b><?php echo number_format($data['feedback_total']) ?></b></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
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
		report_load('instagram', 'summary', <?php echo $account;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});

</script>