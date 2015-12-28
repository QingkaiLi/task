<?php
$_accept = $_SERVER["HTTP_ACCEPT"];

if (strstr($_accept, "application/vnd.wap.xhtml+xml") !== false) {
	header("Content-Type: application/vnd.wap.xhtml+xml; charset=utf-8");
	echo '<?xml version="1.0"?>'; 
	echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">'; 
} else if (strstr($_accept, "application/xhtml+xml") !== false) {
//	header("Content-Type: application/xhtml+xml; charset=utf-8");
	header("Content-Type: text/html; charset=utf-8");
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
} else {
	header("Content-Type: text/html; charset=utf-8");
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
}

function clear_reload($field) {
	$redirect = preg_replace('/'.$field.'=.*?&/', "&", $_SERVER["QUERY_STRING"]);
	$redirect = preg_replace('/'.$field.'=.*?$/', "", $redirect);
	header("Location: ?$redirect");
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>筋斗云</title>

    <!-- Bootstrap -->
    <link href="../static/css/bootstrap.min.css" rel="stylesheet"/>
    <!--<link href="../static/css/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>-->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />
    <!--<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css">-->
    <!--<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">-->
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  <!--<link href="../static/css/font-awesome.min.css" rel="stylesheet">-->
	<!--link href="../plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
	<link href="../plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
	<link href="../plugins/xcharts/xcharts.min.css" rel="stylesheet">
	<link href="../plugins/select2/select2.css" rel="stylesheet"-->
	<!--<link href="../static/css/style.css" rel="stylesheet">-->
	<link rel="stylesheet" href="../static/css/uploadify.css" /><!--
	<link rel="stylesheet" href="../static/css/Tstyle.css" />-->
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
			<script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
			<script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
	<![endif]-->
  <!--<link rel="stylesheet" href="../static/css/themes/default/easyui.css" />-->
  <link href="../static/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
  <link href="../static/css/bootstrap-slider.css" rel="stylesheet">
  <link href="../static/css/custom.css" rel="stylesheet">

  <script src="../static/js/jquery/jquery-2.1.0.min.js"></script>
	<!--<script src="../static/js/jquery-ui/jquery-ui.min.js"></script>-->
  <script src="../static/js/bootstrap.min.js"></script>
  <!--<script src='../static/js/bootstrap-slider.js'></script>-->
  <script src="../static/js/bootstrap-validator.js"></script>
  <script src="../static/js/datetimepicker/bootstrap-datetimepicker.min.js"></script>
  <script src="../static/js/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>
  <script src="../static/js/moment.js"></script>
  </head>
  <body>

