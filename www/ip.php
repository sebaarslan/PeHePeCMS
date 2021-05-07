<?php
ob_start();
require_once ("icerik/vt.inc.php");
require_once ("icerik/fonk.inc.php");
require_once ("icerik/dil.inc.php");
dil_belirle();
$fonk = new Fonksiyon();
@ $ip = trim(htmlspecialchars(strip_tags($_GET['ip'])));
$vt->query("SELECT ip,aciklama,tarih FROM ".TABLO_ONEKI."ipengelle WHERE TRIM(ip)='$ip'");
if ($vt->numRows() > 0)
{
  $ipveri     = $vt->fetchObject();
  $ip         = $ipveri->ip;
	$ipaciklama = $ipveri->aciklama;
	$iptarih    = $ipveri->tarih;
  ?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="stil.css" rel="stylesheet" />
  <title>IP</title>
  </head>

  <body>
  <p>&nbsp;</p>

  <table width="350" align="center">
    <tr>
	    <td width="100%" align="center">
			<?php
			$ip_mesaj = $dil['IPEngellendi'].'<br />IP : '.$ip.'<br />'.$ipaciklama.'<br />('.$fonk->duzgun_tarih_saat($iptarih,true).')';
			echo $fonk->hata_mesaj($ip_mesaj,false,'<a href="index.php">'.$dil['Tamam'].'</a>');
			?>
			</td>
	  </tr>
  </table>
  </body>
  </html>
<?php
} else {
  header('Location: index.php');
}
?>
