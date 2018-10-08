<!DOCTYPE html>
<html>
<head>
	<title>Nabata</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
	<meta name="author" content="Coderthemes">
	<script type="text/javascript">var base_urls = "<?php echo base_url() ?>", site_urls = "<?php echo site_url() ?>";</script>
	<link href="<?php echo base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/style-admin.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/material-design-iconic-font.min.css') ?>" rel="stylesheet" />
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<?php $this->load->view('header');?>
    <div style="margin-top: 70px; padding: 30px;">
        <div class="row"><h1 class="center" style="margin-bottom: 20px;"><?php echo $nama ?></h1></div>
        <div class="row">
            <div class="col-lg-4" id="summary" style="cursor: pointer;">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Summary</h3>
                    </div>
                    <div class="panel-body">
                        <p>Berisikan daftar <i>account</i> dan kompetitor yang di urutkan berdasarkan , <i>posting</i>, <i>fans</i>, <i>feedback</i>, dan <i>komunikasi</i> terbanyak.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" id="activity" style="cursor: pointer;">
                <div class="panel panel-color panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">Activity</h3>
                    </div>
                    <div class="panel-body">
                        <p>Berisikan jumlah <i>post</i>, <i>feedback</i>, dan <i>amplification</i> yang di kelompokkan berdasarkan kategori / jenis.</span></span></sup></h1>.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" id="feeds" style="cursor: pointer;">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Feeds</h3>
                    </div>
                    <div class="panel-body">
                        <p>Berisikan daftar <i>posting account</i> dan kompetitor yang di urutkan berdasarkan jumlah <i>feedback</i> terbanyak.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4" id="users" style="cursor: pointer;">
                <div class="panel panel-color panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Users</h3>
                    </div>
                    <div class="panel-body">
                        <p>Berisikan perkiraan daftar <i>user</i> yang dapat memberikan <i>amplification</i>, <i>feedback</i> lebih banyak.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" id="tagpost" style="cursor: pointer;">
                <div class="panel panel-color panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tag Post</h3>
                    </div>
                    <div class="panel-body">
                        <p>Berisikan hastag terpopuler yang digunakan account.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" id="primetime" style="cursor: pointer;">
                <div class="panel panel-color panel-inverse">
                    <div class="panel-heading">
                        <h3 class="panel-title">Prime Time</h3>
                    </div>
                    <div class="panel-body">
                        <p>Berisikan jumlah <i>posting fanpage</i>, <i>komentar user</i>,  yang di kelompokkan berdasarkan waktu / jam.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4" id="growth" style="cursor: pointer;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Growth</h3>
                    </div>
                    <div class="panel-body">
                        <p>Berisikan jumlah <i>fans</i>, perkembangan <i>fans</i>, dan komparasi dengan kompetitor lain per hari pada periode saat ini.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" id="conversation" style="cursor: pointer;">
                <div class="panel panel-color panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Conversation</h3>
                    </div>
                    <div class="panel-body">
                        <p>Berisikan jumlah <i>posting</i> yang di kelompokkan berdasarkan kategori / topik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="modal fade" id="load_content" role="dialog"></div>
</body>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo base_url('assets/js/highcharts.js') ?>"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<script type="text/javascript" src="<?php echo base_url('assets/js/heatmap.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/script.js') ?>"></script>
<script type="text/javascript">
$(function() {
    $(".col-lg-4").click(function(event){ 
        var $parent = $(event.target).closest('.col-lg-4'); 
        $('#load_content').modal('toggle'); 
        report_load('instagram', $parent.prop('id'), <?php echo $account_id;?>, prev_month());
    });
    $('#load_content').on('hidden.bs.modal', function () {
        $('#load_content').html('');
    });

});
function current_month(){
    return '<?php echo date("01-M-Y ~ t-M-Y")?>';
}
function prev_month(){
    return '<?php echo date("01-M-Y ~ t-M-Y", strtotime("first day of last month"))?>';
}

function base_url(id){
	return base_urls + id;
}
function site_url(id){
	return site_urls + id;
}
</script>
</html>