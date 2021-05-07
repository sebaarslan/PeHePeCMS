<?php
ob_start();
session_start();
require_once ("../icerik/vt.inc.php");
require_once ("../icerik/ayar.inc.php");
require_once ("../icerik/sev.inc.php"); 

if (UYE_SEVIYE < 5)
{
  header('Location: ../index.php');
}
?>
<html>
<head>
<title><?php echo SITE_ADI; ?> : <?php echo $dil['YonetimPaneli']; ?></title>
<meta http-equiv="Content-Language" content="tr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<frameset cols="175,*" frameborder="no" border="0" framespacing="0">
  <frame src="yonet_menu.php" name="menu" scrolling="auto" noresize>
  <frame src="yonet_ana.php" name="ana">
</frameset>
<noframes>
<body>
</body>
</noframes>
</html>
