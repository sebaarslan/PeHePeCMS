<?php
function dil_belirle($gelen_dil='tr',$klasor='dil')
{
global $_SESSION;
global $dil_ayar,$site_dil;

//===========================================================================
//HER DIL ICIN BURAYA EKLEME YAPINIZ
//Turkce Dil Dosyasi
$dil_ayar['tr'] = array('tr.php','Türkçe','tr.gif');

//Ingilizce Dil Dosyasi Yuklerseniz Alt Bolumu Aktiflestirin
$dil_ayar['en'] = array('en.php','English','en.gif'); 

//Almanca Dil Dosyasi Yuklerseniz Alt Bolumu Aktiflestirin
$dil_ayar['de'] = array('de.php','Deutsch','de.gif');

//Fransizca Dil Dosyasi Yuklerseniz Alt Bolumu Aktiflestirin
$dil_ayar['fr'] = array('fr.php','French','fr.gif');

//Yeni Dil Dosyalari Icin Yukarıdaki Ornekler Gibi Ekleme Yapabilirsiniz

//=============================================================================

//DIL AYARLARI
@ $oturum_dil = $_SESSION['oturum_dil'];

$site_dil = 'tr';  //Varsayilan Dil
$kullanici_dil = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
if (SITE_DIL == '0')
{
  if (is_array($dil_ayar[$kullanici_dil]))
  $site_dil = $kullanici_dil;
} else {
  if (is_array($dil_ayar[SITE_DIL]))
	$site_dil = SITE_DIL;
}
if (empty($gelen_dil))
{
  if (!empty($oturum_dil))
  {
    $site_dil = $oturum_dil;
  }
} else {
  if (is_array($dil_ayar[$gelen_dil]))
  {
    $site_dil = $gelen_dil;
    $_SESSION['oturum_dil'] = $gelen_dil;
  }
}

//DIL DOSYASININ SAYFAYA EKLENMESI
unset($gelen_dil,$varsayilan_dil,$oturum_dil,$dil_dosyasi);
global $dil;
$dil_dosyasi = $klasor.'/'.$dil_ayar[$site_dil][0];
if (file_exists($dil_dosyasi))
{
  $return_dil = $dil_ayar[$site_dil][0];
} else {
  $return_dil = $dil_ayar['tr'][0];
}

if (!file_exists($klasor.'/'.$return_dil))
{
  echo 'Dil Dosyasi Bulunamadi';
  exit;
}
return require_once( $klasor.'/'.$return_dil);
}

?>