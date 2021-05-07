<?php
/*======================================================================*\
|| #################################################################### ||
|| # PeHePe Uyelik Sistemi                                            # ||
|| # ---------------------------------------------------------------- # ||
|| #                                                                  # ||
|| #################################################################### ||
\*======================================================================*/
ob_start();
error_reporting(E_ALL | E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_CORE_ERROR | E_COMPILE_ERROR);
@ini_set('display_errors', true);
@ini_set('html_errors', true);

if (!isset($_SESSION))
session_start();
if (version_compare(PHP_VERSION,'5','>'))
date_default_timezone_set('Europe/Istanbul');
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
require_once('icerik/vt.inc.php');
require_once('icerik/ayar.inc.php');
require_once ("icerik/sajax.inc.php");

//SITE DILI AYARLANIYOR
require_once("icerik/dil.inc.php");
@ dil_belirle('tr');
require_once("icerik/fonk.inc.php");
$fonk = new Fonksiyon();
require_once("icerik/sev.inc.php");
require_once("icerik/say.inc.php");

//Sajax Icin Fonksiyonlari Cagirir
function fonksiyonCagir($parametre,$sayfaadi,$fonksiyonadi) 
{
  require_once('sayfa/'.$sayfaadi.'.php');
	return $fonksiyonadi($parametre);
}
	
$sajax_request_type = "POST";
sajax_init();
//$sajax_debug_mode = 1;
sajax_export("fonksiyonCagir");
sajax_handle_client_request();
?>