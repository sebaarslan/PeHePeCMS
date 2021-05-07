<?php
ob_start();
session_start();
require_once ("../icerik/vt.inc.php");
require_once ("../icerik/ayar.inc.php");
require_once ("../icerik/sev.inc.php");
require_once ("../icerik/dil.inc.php");
dil_belirle('','yonetimdil');
if (UYE_SEVIYE > 4)
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_ADI; ?> : <?php echo $dil['YonetimPaneli']; ?></title>
<link rel="stylesheet" href="ystil.css" />
</head>
<body background="yonetimresim/bg.gif">
<table border="0" bgcolor="#b6c5f2" align="center" cellpadding="1" cellspacing="0" width="100%">
  <tr>
    <td>
      <table width="100%" border="0" cellpadding="2" cellspacing="0" bgcolor="#f0f0f0" align="center">
				<tr>
	      <td height="20" bgcolor="#b6c5f2" align="center"><font color=#000000><b><?php echo $dil['UYE_ISLEMLERI']; ?></b></font></td>
        </tr>
        <tr>
          <td align="left" onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a target="ana" href="uye_yonet.php">+ <?php echo $dil['UyeListesi']; ?></a></td>
        </tr>
				<?php
				if (UYE_SEVIYE > 5)
				{
				?>
				<tr>
	      <td height="20" bgcolor="#b6c5f2" align="center"><font color=#000000><b><?php echo $dil['AYARLAR']; ?></b></font></td>
        </tr>
        <tr>
          <td align="left" height="20" onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="ayarlar.php" target="ana">+ <?php echo $dil['SiteAyarlari']; ?></a></td>
        </tr>
        <?php
				}
				?>
				<tr>
	      <td height="20" bgcolor="#b6c5f2" align="center"><font color=#000000><b><?php echo $dil['YAZI_YORUM_YONETIMI']; ?></b></font></td>
        </tr>
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="yazi_yonet.php" target="ana">+ <?php echo $dil['YaziYonet']; ?></a></td>
        </tr>
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="yaziyorum_yonet.php" target="ana">+ <?php echo $dil['YaziYorumYonet']; ?></a></td>
        </tr>  
        <tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="hmesaj_yonet.php" target="ana">+ <?php echo $dil['HizliMesajYonet']; ?></a></td>
        </tr>  
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="kategori_yonet.php" target="ana">+ <?php echo $dil['KategoriYonet']; ?></a></td>
        </tr> 
				<tr>
	        <td height="20" bgcolor="#b6c5f2" align="center"><font color=#000000><b><?php echo $dil['ANKET_EKLE_DUZENLE']; ?></b></font></td>
        </tr>
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="anket_yonet.php" target="ana">+ <?php echo $dil['AnketEkleDuzenle']; ?></a></td>
        </tr> 
				<tr>
	        <td height="20" bgcolor="#b6c5f2" align="center"><font color=#000000><b><?php echo $dil['RESIM_GALERISI']; ?></b></font></td>
        </tr>
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="album_yonet.php" target="ana">+ <?php echo $dil['AlbumYonet']; ?></a></td>
        </tr> 
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="resim_yonet.php" target="ana">+ <?php echo $dil['ResimYonet']; ?></a></td>
        </tr> 
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="resimyorum_yonet.php" target="ana">+ <?php echo $dil['ResimYorumYonet']; ?></a></td>
        </tr> 
        <tr>
	        <td height="20" bgcolor="#b6c5f2" align="center"><font color=#000000><b><?php echo $dil['DOSYA_EKLE_DUZENLE']; ?></b></font></td>
        </tr>
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="dosya_yonet.php" target="ana">+ <?php echo $dil['DosyaEkleDuzenle']; ?></a></td>
        </tr> 
				<tr>
	        <td height="20" bgcolor="#b6c5f2" align="center"><font color=#000000><b><?php echo $dil['MENU_SAYFA_YONETIMI']; ?></b></font></td>
        </tr>
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="menu_sayfa_yonet.php" target="ana">+ <?php echo $dil['MenuSayfaDuzenle']; ?></a></td>
        </tr> 
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="baglanti_yonet.php" target="ana">+ <?php echo $dil['BaglantiYonet']; ?></a></td>
        </tr> 
				<tr>
	        <td height="20" bgcolor="#b6c5f2" align="center"><font color=#000000><b><?php echo $dil['DIGER_ISLEMLER']; ?></b></font></td>
        </tr>
				<?php
				if (UYE_SEVIYE>5)
				{
				?>
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="ip_engelle.php" target="ana">+ <?php echo $dil['IpEngelle']; ?></a></td>
        </tr> 
				<?php
				}
				?>
				<tr>
          <td align="left" height="20" valign="center"onMouseOver="this.style.background = '#F7E9FE';" onmouseout="this.style.background = 'none';">&nbsp;<a href="omesaj_eposta.php" target="ana">+ <?php echo $dil['OzelMesajEpostaGonder']; ?></a></td>
        </tr> 
      </table>
    </td>
  </tr>
</table>
<center>
<br />|| <a href="yonet_ana.php" target="ana"><?php echo $dil['YonetimAnasayfa']; ?></a> ||
<br />|| <a href="../index.php" target="_parent"><?php echo $dil['SiteAnasayfa']; ?></a> || 
</center>
</body>
</html>
<?php
}
?>