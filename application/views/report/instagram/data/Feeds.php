<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font> Feeds</h1>
	    <div class="clear"></div>
	</div>
	<div class="dateRangePicker">
	    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
	    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
	</div>
	<div class="modal-body feeds-modal">
		<div id="content">
			<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
				<li class="active"><a href="#AllPosting" data-toggle="tab">All Post</a></li>
				<li><a href="#LikePosting" data-toggle="tab">All Liked Post</a></li>
				<li><a href="#CommentPosting" data-toggle="tab">All Comment Post</a></li>
				<li><a href="#VideoPosting" data-toggle="tab">All Video Post</a></li>
				<li><a href="#ViewPosting" data-toggle="tab">All View Video Post</a></li>
			</ul>
		</div>
		<div id="my-tab-content" class="tab-content">
			<div class="tab-pane active" id="AllPosting">
				<?php
					if($AllTopPost){
					$i = 1;
					echo '<h4>Top Post Base on Feedback</h4>';
					echo '<table class="feed_list"><tr>';
					foreach($AllTopPost as $list){
						$view = '';
						if($list->type == 'video'){
							$view = '<br>View: <b>'.number_format($list->view_count).'</b>';
						}
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="'.$list->permalink.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/no-image-available.jpg').'"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b>'.$view.'<br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2"><h2><a href="'.$list->permalink.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
						echo '</table>';
						echo '</td>';
						$i++;
					}
					if($i < 6){
						for($ii=$i; $ii < 6; $ii++){
							echo '<td></td>';
						}
					}
					echo '</tr></table>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
					if($AllLeastPost){
					$i = 1;
					echo '<h4>Least Post Base on Feedback</h4>';
					echo '<table class="feed_list"><tr>';
					foreach($AllLeastPost as $list){
						$view = '';
						if($list->type == 'video'){
							$view = '<br>View: <b>'.number_format($list->view_count).'</b>';
						}
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="'.$list->permalink.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/no-image-available.jpg').'"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b>'.$view.'<br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2"><h2><a href="'.$list->permalink.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
						echo '</table>';
						echo '</td>';
						$i++;
					}
					if($i < 6){
						for($ii=$i; $ii < 6; $ii++){
							echo '<td></td>';
						}
					}
					echo '</tr></table>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
				?>
			</div>
			<div class="tab-pane" id="LikePosting">
				<?php
					if($LikeTopPost){
					$i = 1;
					echo '<h4>Top Post Base on Like</h4>';
					echo '<table class="feed_list"><tr>';
					foreach($LikeTopPost as $list){
						$view = '';
						if($list->type == 'video'){
							$view = '<br>View: <b>'.number_format($list->view_count).'</b>';
						}
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="'.$list->permalink.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/no-image-available.jpg').'"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b>'.$view.'<br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2"><h2><a href="'.$list->permalink.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
						echo '</table>';
						echo '</td>';
						$i++;
					}
					if($i < 6){
						for($ii=$i; $ii < 6; $ii++){
							echo '<td></td>';
						}
					}
					echo '</tr></table>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
					if($LikeLeastPost){
					$i = 1;
					echo '<h4>Least Post Base on Like</h4>';
					echo '<table class="feed_list"><tr>';
					foreach($LikeLeastPost as $list){
						$view = '';
						if($list->type == 'video'){
							$view = '<br>View: <b>'.number_format($list->view_count).'</b>';
						}
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="'.$list->permalink.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/no-image-available.jpg').'"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b>'.$view.'<br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2"><h2><a href="'.$list->permalink.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
						echo '</table>';
						echo '</td>';
						$i++;
					}
					if($i < 6){
						for($ii=$i; $ii < 6; $ii++){
							echo '<td></td>';
						}
					}
					echo '</tr></table>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
				?>
			</div>
			<div class="tab-pane" id="CommentPosting">
				<?php
					if($CommentTopPost){
					$i = 1;
					echo '<h4>Top Post Base on Comment</h4>';
					echo '<table class="feed_list"><tr>';
					foreach($CommentTopPost as $list){
						$view = '';
						if($list->type == 'video'){
							$view = '<br>View: <b>'.number_format($list->view_count).'</b>';
						}
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="'.$list->permalink.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/no-image-available.jpg').'"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b>'.$view.'<br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2"><h2><a href="'.$list->permalink.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
						echo '</table>';
						echo '</td>';
						$i++;
					}
					if($i < 6){
						for($ii=$i; $ii < 6; $ii++){
							echo '<td></td>';
						}
					}
					echo '</tr></table>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
					if($CommentLeastPost){
					$i = 1;
					echo '<h4>Least Post Base on Comment</h4>';
					echo '<table class="feed_list"><tr>';
					foreach($CommentLeastPost as $list){
						$view = '';
						if($list->type == 'video'){
							$view = '<br>View: <b>'.number_format($list->view_count).'</b>';
						}
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="'.$list->permalink.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/no-image-available.jpg').'"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b>'.$view.'<br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2"><h2><a href="'.$list->permalink.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
						echo '</table>';
						echo '</td>';
						$i++;
					}
					if($i < 6){
						for($ii=$i; $ii < 6; $ii++){
							echo '<td></td>';
						}
					}
					echo '</tr></table>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
				?>
			</div>
			<div class="tab-pane" id="VideoPosting">
				<?php
					if($VideoTopPost){
					$i = 1;
					echo '<h4>Top Video Post</h4>';
					echo '<table class="feed_list"><tr>';
					foreach($VideoTopPost as $list){
						$view = '';
						if($list->type == 'video'){
							$view = '<br>View: <b>'.number_format($list->view_count).'</b>';
						}
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="'.$list->permalink.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/no-image-available.jpg').'"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b>'.$view.'<br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2"><h2><a href="'.$list->permalink.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
						echo '</table>';
						echo '</td>';
						$i++;
					}
					if($i < 6){
						for($ii=$i; $ii < 6; $ii++){
							echo '<td></td>';
						}
					}
					echo '</tr></table>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
					if($VideoLeastPost){
					$i = 1;
					echo '<h4>Least Video Post</h4>';
					echo '<table class="feed_list"><tr>';
					foreach($VideoLeastPost as $list){
						$view = '';
						if($list->type == 'video'){
							$view = '<br>View: <b>'.number_format($list->view_count).'</b>';
						}
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="'.$list->permalink.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/no-image-available.jpg').'"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b>'.$view.'<br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2"><h2><a href="'.$list->permalink.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
						echo '</table>';
						echo '</td>';
						$i++;
					}
					if($i < 6){
						for($ii=$i; $ii < 6; $ii++){
							echo '<td></td>';
						}
					}
					echo '</tr></table>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
				?>
			</div>
			<div class="tab-pane" id="ViewPosting">
				<?php
					if($ViewVideoTopPost){
					$i = 1;
					echo '<h4>Top Video Post Base on View</h4>';
					echo '<table class="feed_list"><tr>';
					foreach($ViewVideoTopPost as $list){
						$view = '';
						if($list->type == 'video'){
							$view = '<br>View: <b>'.number_format($list->view_count).'</b>';
						}
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="'.$list->permalink.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/no-image-available.jpg').'"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b>'.$view.'<br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2"><h2><a href="'.$list->permalink.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
						echo '</table>';
						echo '</td>';
						$i++;
					}
					if($i < 6){
						for($ii=$i; $ii < 6; $ii++){
							echo '<td></td>';
						}
					}
					echo '</tr></table>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
					if($ViewVideoLeastPost){
					$i = 1;
					echo '<h4>Least Video Post Base on View</h4>';
					echo '<table class="feed_list"><tr>';
					foreach($ViewVideoLeastPost as $list){
						$view = '';
						if($list->type == 'video'){
							$view = '<br>View: <b>'.number_format($list->view_count).'</b>';
						}
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="'.$list->permalink.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/no-image-available.jpg').'"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b>'.$view.'<br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2"><h2><a href="'.$list->permalink.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
						echo '</table>';
						echo '</td>';
						$i++;
					}
					if($i < 6){
						for($ii=$i; $ii < 6; $ii++){
							echo '<td></td>';
						}
					}
					echo '</tr></table>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
				?>
			</div>
		</div>
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
		report_load('instagram', 'feeds', <?php echo $account;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});

</script>