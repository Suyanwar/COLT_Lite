<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="modal-dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h1><font class="fa fa-star-half-full"></font> Feeds</h1>
	    <div class="clear"></div>
	</div>
<div class="dateRangePicker">
    <label for="dateRangePicker" title="Select a date range"><span class="fa fa-calendar"></span></label>
    <input type="text" id="dateRangePicker" placeholder="Select a date range:" onkeydown="return false" onfocus="$(this).blur()" style="cursor: pointer;"/>
</div>
	<div class="modal-body feeds-modal">
		<div id="content">
			<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
				<li class="active"><a href="#AllAccount" data-toggle="tab">All Account</a></li>
				<li><a href="#Category" data-toggle="tab">Category</a></li>
				<li><a href="#AllPost" data-toggle="tab">All Posts</a></li>
				<li><a href="#NotLinkPost" data-toggle="tab">Not Link Posts</a></li>
				<li><a href="#LinkPost" data-toggle="tab">Link Posts</a></li>
				<li><a href="#SharePost" data-toggle="tab">Share Posts</a></li>
				<li><a href="#LikePost" data-toggle="tab">Like Posts</a></li>
				<li><a href="#CommentPost" data-toggle="tab">Comment Posts</a></li>
				<li><a href="#OrganicPost" data-toggle="tab">Organic Posts</a></li>
				<li><a href="#BoostPost" data-toggle="tab">Boost Posts</a></li>
				<li><a href="#VideoPost" data-toggle="tab">Video Posts</a></li>
			</ul>
		</div>
		<div id="my-tab-content" class="tab-content">
			<div class="tab-pane active" id="AllAccount">
			<?php
				if($AllAccount){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($AllAccount as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2"><a href="https://www.facebook.com/'.($list->username ? $list->username : $list->socmed_id).'" target="_blank">'.$list->name.'</a></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
					echo '<br>';
				}else echo '<p align="center" style="padding-top:25px">No data.</p>';
				?>
			</div>
			<div class="tab-pane" id="Category">
				<?php
					if($Category){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($Category as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2"><a href="https://www.facebook.com/'.($list->username ? $list->username : $list->socmed_id).'" target="_blank">'.$list->name.'</a></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
					echo '<br>';
				}else  echo '<p align="center" style="padding-top:25px">No data.</p>';
				?>
			</div>
			<div class="tab-pane" id="AllPost">
				<?php
					echo '<h4>Top Post</h4>';
					if($AllTopPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($AllTopPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
					echo '<h4>Least Post</h4>';
					if($AllLeastPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($AllLeastPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
			<div class="tab-pane" id="NotLinkPost">
				<?php
					echo '<h4>Not Link Top Post</h4>';
					if($NotLinkTopPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($NotLinkTopPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
					echo '<h4>Not Link Least Post</h4>';
					if($NotLinkLeastPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($NotLinkLeastPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
			<div class="tab-pane" id="LinkPost">
				<?php
					echo '<h4>Link Top Post</h4>';
					if($LinkTopPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($LinkTopPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
				} else echo '<p align="center" style="padding-top:25px">No data.</p>';
					echo '<h4>Link Least Post</h4>';
					if($LinkLeastPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($LinkLeastPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
			<div class="tab-pane" id="SharePost">
				<?php
					echo '<h4>Share Top Post</h4>';
					if($ShareTopPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($ShareTopPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
					echo '<h4>Share Least Post</h4>';
					if($ShareLeastPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($ShareLeastPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
			<div class="tab-pane" id="LikePost">
				<?php
					echo '<h4>Like Top Post</h4>';
					if($LikeTopPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($LikeTopPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
					echo '<h4>Like Least Post</h4>';
					if($LikeLeastPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($LikeLeastPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
			<div class="tab-pane" id="CommentPost">
				<?php
					echo '<h4>Comment Top Post</h4>';
					if($CommentTopPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($CommentTopPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
					echo '<h4>Comment Least Post</h4>';
					if($CommentLeastPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($CommentLeastPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
			<div class="tab-pane" id="OrganicPost">
				<?php
					echo '<h4>Organic Top Post</h4>';
					if($OrganicTopPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($OrganicTopPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
					echo '<h4>Organic Least Post</h4>';
					if($OrganicLeastPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($OrganicLeastPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
			<div class="tab-pane" id="BoostPost">
				<?php
					echo '<h4>Boost Top Post</h4>';
					if($BoostTopPost) {
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($BoostTopPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
					echo '<h4>Boost Least Post</h4>';
					if($BoostLeastPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($BoostLeastPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
			<div class="tab-pane" id="VideoPost">
				<?php
					echo '<h4>Top Post</h4>';
					if($AllTopVideoPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($AllTopVideoPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
					echo '<h4>Least Post</h4>';
					if($AllLeastVideoPost){
					$i = 1;
					echo '<table class="feed_list" style="color: black"><tr>';
					foreach($AllLeastVideoPost as $list){
						if($i == 6) echo '</tr><tr>';
						echo '<td>';
						echo '<table>';
						echo '<tr><td colspan="2" style="display:none"></td></tr>';
						echo '<tr>';
						echo $list->image_url ? '<td><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank"><img src="'.$list->image_url.'"></a></td>' : ($list->video_url ? '<td><video src="'.$list->video_url.'"></video></td>' : '<td><img src="'.base_url('static/i/spacer.gif').'" style="background-image:url('.base_url('static/i/no-image-available.jpg').')"></td>');
						echo '<td>Likes: <b>'.number_format($list->likes_count).'</b><br>Comments: <b>'.number_format($list->comment_count).'</b><br>Share: <b>'.number_format($list->share_count).'</b><br>Total: <b>'.number_format($list->total).'</b></td>';
						echo '</tr>';
						echo '<tr><td colspan="2">'.($list->est_impression ? '<h2 style="border-bottom:1px solid #DDD">Est. Impression: '.number_format($list->est_impression).'</h2>' : '').'<h2><a href="https://www.facebook.com/'.$list->post_id.'" target="_blank">'.date('d M Y - H:i', strtotime($list->post_time)).'</a></h2><div>'.limit_text($list->text, 20).'</div></td></tr>';
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
		report_load('facebook', 'feeds', <?php echo $account;?>, picker.startDate.format('DD-MMM-YYYY') + " ~ " + picker.endDate.format('DD-MMM-YYYY'));
	});
});

</script>