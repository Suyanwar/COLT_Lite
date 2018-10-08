<div class="container">
	<div class="row">
		<?php
			if ($list_data) {
				foreach ($list_data as $list) {
		?>
					<a href="<?php echo site_url("report/{$list->socmed}/main/index/{$list->account_id}") ?>">
						<div class="col-sm-3">
							<div class="mini-stat clearfix bx-shadow" style="background-image: url(<?php echo $list->photo_cover ?>);">
								<span class="mini-stat-icon bg-success" style="background-image: url('<?php echo $list->photo_profile ?>');"></span>
							</div>
							<div class="tiles-progress">
								<div class="m-t-20" style="padding: 10px;">
									<h5 class="text-uppercase"><?php echo $list->name ?><span class="pull-right" style="font-size: 14px;"><?php echo number_format($list->follower) ?> Fans</span></h5>
								</div>
							</div>
						</div>
					</a>
		<?php
				}
			}else echo '<p align="center">No data!</p>';
		?>
	</div>
</div>