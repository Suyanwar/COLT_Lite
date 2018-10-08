<html>
<head>
	<title>Nabata</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
	<meta name="author" content="Coderthemes">
	<link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/style-admin.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/material-design-iconic-font.min.css') ?>" rel="stylesheet" />
</head>
<body>
<?php $this->load->view('header');?>
	<div class="left side-menu">
		<div class="sidebar-inner slimscrollleft">
			<div id="sidebar-menu">
				<ul>
					<li id="facebook"><a href="#"><i class="fa fa-facebook-official"></i>Facebook</a></li>
					<li id="twitter"><a href="#"><i class="fa fa-twitter-square"></i>Twitter</a></li>
					<li id="instagram"><a href="#"><i class="fa fa-instagram"></i>Instagram</a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="content-page">
		<div class="content">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="pull-left page-title">Welcome !</h4>
					</div>
				</div>
				<div id="content"></div>
			</div>
		</div>
		<footer class="footer text-right">
                    2017 © Nabata.
        </footer>
    </div>
</body>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#twitter").click(function() {
		$.ajax({
			url : "<?php echo site_url('admin/load/twitter') ?>",
			data:{"id":"twitter"},
			type: 'POST',
			success: function(data){
				$("#content").html('');
				$("#content").append(data);
			}
		});
	});
	$("#facebook").click(function() {
		$.ajax({
			url : "<?php echo site_url('admin/load/facebook') ?>",
			data:{"id":"facebook"},
			type: 'POST',
			success: function(data){
				$("#content").html('');
				$("#content").append(data);
			}
		});
	});
	$("#instagram").click(function() {
		$.ajax({
			url : "<?php echo site_url('admin/load/instagram') ?>",
			data:{"id":"instagram"},
			type: 'POST',
			success: function(data){
				$("#content").html('');
				$("#content").append(data);
			}
		});
	});
});
</script>
</html>