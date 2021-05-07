<?php 
session_start();
require_once('icerik/vt.inc.php'); 
require_once('icerik/ayar.inc.php'); 
require_once('icerik/sev.inc.php');
require_once('icerik/fonk.inc.php');
$fonk = new Fonksiyon();
@$yazino       = intval($_GET['yazino']);
@$resimno      = intval($_GET['resimno']);
$resim_goster = false;
$aciklama = '';
//===================================================
if ($yazino>0 && UYE_SEVIYE >= YAZI_OKUMA_IZIN) {
//===================================================
$yazi_resim  = '';
$vt->query("SELECT u.uyeno,u.uyeadi,u.resim AS uyeresim,y.yazino,y.kategorino,y.resim,y.baslik,y.yazi,y.eklemetarihi,y.okunma FROM ".TABLO_ONEKI."yazilar AS y, ".TABLO_ONEKI."uyeler AS u WHERE y.onay='E' AND y.yazino=$yazino AND y.uyeno=u.uyeno");

$yazi_sayi   = $vt->numRows();
if ($yazi_sayi > 0)
{
  $yazi_veri       = $vt->fetchObject();
  $yazi_no         = $yazi_veri->yazino;
  $aciklama        = $fonk->yazdir_duzen($yazi_veri->baslik);
  $yazi_resim      = $yazi_veri->resim;
} 
$resim = RESIM_DIZIN.'/'.$yazi_resim;
if (!file_exists($resim) || empty($yazi_resim))
{
  $resim = RESIM_DIZIN.'/bos.gif';
} 
$resim_goster = true;
//========================================================
} elseif ($resimno>0) {
//========================================================
if (UYE_SEVIYE >= GALERI_RESIM_GORME_IZIN)
{
$baslik = '';
$resim_resim  = '';
$vt->query("SELECT u.uyeno,u.uyeadi,r.resimno,r.albumno,r.resim,r.resimadi FROM ".TABLO_ONEKI."resim AS r, ".TABLO_ONEKI."uyeler AS u WHERE r.onay='E' AND r.resimno=$resimno AND r.uyeno=u.uyeno");

$resim_sayi   = $vt->numRows();

if ($resim_sayi > 0)
{
  $resim_veri    = $vt->fetchObject();
	$resimadi      = $resim_veri->resimadi;
  $aciklama      = $fonk->yazdir_duzen($resimadi);
  $resim_resim   = $resim_veri->resim;
	$resim_albumno = $resim_veri->albumno;

} 
$resim = GALERI_ALBUM_DIZIN.'/album_'.$resim_albumno.'/'.$resim_resim;
if (!file_exists($resim) || empty($resim_resim))
{
  $resim = GALERI_ALBUM_DIZIN.'/bos.gif';
} 
$resim_goster = true;
}
//======================================
}
//======================================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_ADI.' - '.$aciklama; ?></title>
</head>

<body style="margin:0">
<table align="center" width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top">
		<?php
		if ($resim_goster)
		{
		?>
    <img alt="<?php echo $aciklama; ?>" title="<?php echo $aciklama; ?>" src="<?php echo $resim; ?>" border="1" class="yaziresim" onclick="window.self.close()" style="cursor:hand" align="texttop" />
		<?php
		} else {
		  echo $dil['IslemGecersiz']; 
		}
		?>
		</td>
	</tr>
</table>
</body>
</html>
