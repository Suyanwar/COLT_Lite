<!DOCTYPE html>
<html >
<head>
	<meta charset="UTF-8">
	<title>COLT Lite</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>"> 
</head>
<body>
	<form name="login-form" class="login-form" action="<?php echo base_url('login/aksi_login'); ?>" method="post">
		<div class="header">
			<h1 style="text-align: center;">COLT Management</h1>
			<span>Log in here</span>
		</div>
		<div class="content">
			<input name="username" type="text" class="input username" placeholder="Username" />
			<div class="user-icon"></div>
			<input name="password" type="password" class="input password" placeholder="Password" />
			<div class="pass-icon"></div>
		</div>
		<div class="footer">
			<input type="submit" name="submit" class="button" value="Login">
		</div>
	</form>
</body>
</html>