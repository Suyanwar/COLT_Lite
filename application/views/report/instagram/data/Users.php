<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font> Users</h1>
	    <div class="clear"></div>
	</div>
	<div class="dateRangePicker">
	    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
	    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;" />
	</div>
	<div class="modal-body">
		<div id="content">
			<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
				<li class="active"><a href="#MostActive" data-toggle="tab">Most Active</a></li>
				<li><a href="#MostCommenters" data-toggle="tab">Most Commenters</a></li>
				<li><a href="#MostLikers" data-toggle="tab">Most Likers</a></li>
			</ul>
		</div>
		<div id="my-tab-content" class="tab-content">
			<div class="tab-pane active" id="MostActive">
				<?php
					if($mostActive){
					echo '<ul class="user_list">';
					foreach($mostActive as $list){
						echo '<li><table><tr><td><a href="https://www.instagram.com/'.($list->u ? $list->u : $list->s).'" target="_blank"><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.$list->p.')"></a></td><td>Activity: <b>'.number_format($list->t).'</b><br>Followers: <b>'.(($list->f > 1) ? number_format($list->f) : '').'</b><!--br>Loc: <b>'.$list->l.'</b--></td></tr><tr><td colspan="2"><div><a href="https://www.instagram.com/'.($list->u ? $list->u : $list->s).'" target="_blank">'.($list->u ? '@'.$list->u : $list->n).'</a></div></td></tr></table></li>';
					}
					echo '</ul>';
					echo '<div class="clear"></div>';
					echo '<br>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
				?>
			</div>
			<div class="tab-pane" id="MostCommenters">
				<?php
					if($mostCommenters){

					echo '<ul class="user_list">';
					foreach($mostCommenters as $list){
						echo '<li><table><tr><td><a href="https://www.instagram.com/'.($list->u ? $list->u : $list->s).'" target="_blank"><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.$list->p.')"></a></td><td>Activity: <b>'.number_format($list->t).'</b><br>Followers: <b>'.(($list->f > 1) ? number_format($list->f) : '').'</b><!--br>Loc: <b>'.$list->l.'</b--></td></tr><tr><td colspan="2"><div><a href="https://www.instagram.com/'.($list->u ? $list->u : $list->s).'" target="_blank">'.($list->u ? '@'.$list->u : $list->n).'</a></div></td></tr></table></li>';
					}
					echo '</ul>';
					echo '<div class="clear"></div>';
					echo '<br>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
				?>
			</div>
			<div class="tab-pane" id="MostLikers">
				<?php
					if($mostLikers){

					echo '<ul class="user_list">';
					foreach($mostLikers as $list){
						echo '<li><table><tr><td><a href="https://www.instagram.com/'.($list->u ? $list->u : $list->s).'" target="_blank"><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.$list->p.')"></a></td><td>Activity: <b>'.number_format($list->t).'</b><br>Followers: <b>'.(($list->f > 1) ? number_format($list->f) : '').'</b><!--br>Loc: <b>'.$list->l.'</b--></td></tr><tr><td colspan="2"><div><a href="https://www.instagram.com/'.($list->u ? $list->u : $list->s).'" target="_blank">'.($list->u ? '@'.$list->u : $list->n).'</a></div></td></tr></table></li>';
					}
					echo '</ul>';
					echo '<div class="clear"></div>';
					echo '<br>';
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
		report_load('instagram', 'users', <?php echo $account;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});

</script>