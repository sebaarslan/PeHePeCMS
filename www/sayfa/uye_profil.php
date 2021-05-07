<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;

try
{
if (UYE_SEVIYE == 0)
{
  $_SESSION['sayfaadi'] = serialize($sayfa);
  header('Location: ?sayfa=giris&hata=15');
	exit;
}
if (UYE_SEVIYE < UYE_GORME_IZIN)
{
  throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],'<b>'.$seviyeler[UYE_GORME_IZIN].'</b>'));
	exit;
}
@ $puyeno    = intval($_GET['uye']);
@ $sayfano   = abs(intval($_REQUEST['s']));
//=================================
// 1. ADIM UYE LISTE
//=================================
if (empty($puyeno))
{
$uvt = new Baglanti();

?>
<table width="100%" align="center">
  <tr>
    <td width="100%" height="20" colspan="7" align="center"><h1><?php echo $dil['Uyeler']; ?></h1></td>
  </tr>
  <tr class="tablobaslik">
    <td align="center" width="5%" height="25"><b>SN</b></td>
		<td align="center"  width="15%" height="25" valign="center"><b><?php echo $dil['Resim']; ?></b></td>
    <td align="center"  width="15%" height="25" valign="center"><b><?php echo $dil['KullaniciAdi']; ?></b></td>
    <td align="center" width="15%" height="25" valign="center"><b><?php echo $dil['Adi']; ?></b></td>
    <td align="center" width="15%" height="25" valign="center"><b><?php echo $dil['Soyadi']; ?></b></td>
    <td align="center" width="15%" height="25"><b><?php echo $dil['DogumTarihi']; ?></b></td>
    <td align="center"  width="20%" height="25" valign="center"><b><?php echo $dil['KayitTarihi']; ?></b></td>
  </tr>
<?php
$toplam_uye = $uvt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE onay='E' AND yonay=5");
$limit = 25;

if(empty($sayfano) || $sayfano>ceil($toplam_uye/$limit)) 
{                
  $sayfano = 1;                
  $baslangic = 0;        
} else {               
  $baslangic = ($sayfano - 1) * $limit;        
}
$uvt->query("SELECT uyeno,uyeadi,adi,soyadi,resim,dogumtarihi,kayittarihi FROM ".TABLO_ONEKI."uyeler WHERE onay='E' AND yonay=5 LIMIT $baslangic,$limit");
$uye_var = $uvt->numRows();
if ($uye_var > 0)
{
 $sayi = 0;
 $u_sira = 0;
  while ($uyeler = $uvt->fetchObject())
  {
    $uye_kuladi = $uyeler->uyeadi;
		$uye_no     = $uyeler->uyeno;
    $uye_adi    = $uyeler->adi;
    $uye_soyadi = $uyeler->soyadi;
		$uye_resim  = $uyeler->resim;
		$uye_dogumtarihi = $uyeler->dogumtarihi;
		$uye_kayittarihi = $uyeler->kayittarihi;

    if (($sayi % 2) == 0)
    {
      $renk = ' class="renk1"';
    } else {
      $renk = ' class="renk2"';
    }
    $resim = UYE_RESIM_DIZIN.'/'.$uye_resim;
    if (!file_exists($resim) || empty($uye_resim))
    $resim = UYE_RESIM_DIZIN.'/bos.gif';
		
		$sayi++;
    $u_sira           = $baslangic+$sayi;
?>
  <tr<?php echo $renk; ?>>
    <td width="5%" height="20" align="center"><?php echo $u_sira; ?></td>
    <td height="80" align="center" valign="middle" style="border:solid 1px #3366ff"><a href="?sayfa=uye&uye=<?php echo $uye_no; ?>&s=<?php echo $sayfano; ?>"><img src="<?php echo $resim; ?>" name="uyeresim" id="uyeresim"  border="0" align="center" width="<?php echo UYE_RESIM_EN; ?>" height="<?php echo UYE_RESIM_BOY; ?>" /></a></td>
    <td align="left" nowrap="nowrap">&nbsp;<a href="?sayfa=uye&uye=<?php echo $uye_no; ?>&s=<?php echo $sayfano; ?>"><?php echo $uye_kuladi; ?></a></td>
    <td align="left" nowrap="nowrap">&nbsp;<?php echo $uye_adi; ?></td>
    <td align="left" nowrap="nowrap">&nbsp;<?php echo $uye_soyadi; ?></td>
    <td align="center"><?php echo $fonk->duzgun_tarih_saat($uye_dogumtarihi); ?></td>
    <td align="center"><?php echo $fonk->duzgun_tarih_saat($uye_kayittarihi,true); ?></td>
  </tr>
  
<?php
}
?>
<tr>
    <td align="center" width="100%" colspan="7">
      <table width="100%" align="center">
        <tr>
          <td width="100%" align="center"><?php echo $fonk->sayfalama($limit,$toplam_uye,$sayfano,'?sayfa=uye&s=[sn]'); ?></td>
        </tr>
      </table>
    </td>
  </tr>
<?php
} else {
  echo '<tr><td align="center">'.$dil['KayitBulunamadi'].'</td></tr>';
}
?>
</table>
<?php
//==================================
} else { // 2. ADIM BASLANGICI
//==================================

$u_vt = new Baglanti();
$u_vt->query("SELECT uyeno,resim,uyeadi,adi,soyadi,eposta,dogumtarihi,seviye,onay,kayittarihi,girissayisi,songiristarihi,guncellemetarihi FROM ".TABLO_ONEKI."uyeler WHERE uyeno=".$puyeno."");
$uye_var                  = $u_vt->numRows();
if ($uye_var > 0)
{
$uye_bilgi                = $u_vt->fetchObject();
$u_kuladi                 = $uye_bilgi->uyeadi;
$u_adi                    = $uye_bilgi->adi;
$u_soyadi                 = $uye_bilgi->soyadi;
$u_eposta                 = $uye_bilgi->eposta;
$u_dogumtarihi            = $uye_bilgi->dogumtarihi;
$u_kayittarihi            = $uye_bilgi->kayittarihi;
$u_resim                  = $uye_bilgi->resim;
list($u_yil,$u_ay,$u_gun) = explode('-',$u_dogumtarihi);
$u_vt->freeResult();

$uye_resim = UYE_RESIM_DIZIN.'/'.$u_resim;
if (!file_exists($uye_resim) || empty($u_resim))
{
  $uye_resim = UYE_RESIM_DIZIN.'/bos.gif';
}

?>
<p>&nbsp;</p>
<table width="85%" align="center">
  <tr>
    <td width="100%" height="20" colspan="2" align="center"><h1><?php echo $dil['UYELIK_BILGILERI']; ?></h1></td>
  </tr>
	<tr>
	  <td width="100%" colspan="2">
	  <table width="130" align="center">
		  <tr>
			  <td width="130" height="80" align="center" valign="middle" style="border:solid 1px #3366ff"><?php echo $dil['Onizleme']; ?><br /><img src="<?php echo $uye_resim; ?>" name="uyeresim" id="uyeresim"  border="0" align="center" width="<?php echo UYE_RESIM_EN; ?>" height="<?php echo UYE_RESIM_BOY; ?>" /></td>
      <td align="left" width="65%" style="padding-left:5px; font-size:9px"></td>
      </tr>
    </table>
		</td>
  <tr>
    <td width="40%" align="right"  height="25" valign="center"><b><?php echo $dil['KullaniciAdi']; ?> : </b></td>
    <td width="60%" align="left" height="25"><?php echo $u_kuladi; ?></td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25" valign="center"><b><?php echo $dil['Adi']; ?> : </b></td>
    <td width="60%" align="left" height="25"><?php echo $u_adi; ?></td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25" valign="center"><b><?php echo $dil['Soyadi']; ?> : </b></td>
    <td width="60%" align="left" height="25" valign="center"><?php echo $u_soyadi; ?></td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25"><b><?php echo $dil['DogumTarihi']; ?> : </b></td>
    <td width="60%" align="left" height="25"><?php echo $u_gun.'.'.$u_ay.'.'.$u_yil; ?></td>
  </tr>
  <tr>
    <td width="40%" align="right"  height="25" valign="center"><b><?php echo $dil['KayitTarihi']; ?> : </b></td>
    <td width="60%" align="left" height="25"><?php echo $fonk->duzgun_tarih_saat($u_kayittarihi,true); ?></td>
  </tr>
	<tr>
    <td width="40%" align="right"  height="25" valign="center"><b><?php echo $dil['EkledigiYaziSayisi']; ?> : </b></td>
    <td width="60%" align="left" height="25">
		<?php 
		$yazi_sayisi = $u_vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE uyeno=".$puyeno." AND onay='E'"); 
		if (UYE_SEVIYE >= UYE_GORME_IZIN)
		echo '<a href="?sayfa=yazi&uyeno='.UYE_NO.'">'.$yazi_sayisi.'</a>';
		?></td>
  </tr>
	<tr>
    <td width="40%" align="right"  height="25" valign="center"><b><?php echo $dil['EkledigiResimSayisi']; ?> : </b></td>
    <td width="60%" align="left" height="25">
		<?php 
		$resim_sayisi = $u_vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE uyeno=".$puyeno." AND onay='E'"); 
		echo $resim_sayisi;
		?></td>
  </tr>
	<tr>
    <td width="40%" align="right"  height="25" valign="center"><b><?php echo $dil['EkledigiYorumSayisi']; ?> : </b></td>
    <td width="60%" align="left" height="25">
		<?php 
		$yorum_sayisi = $u_vt->kayitSay("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE uyeno=".$puyeno." AND onay='E'"); 
		if (UYE_SEVIYE >= UYE_GORME_IZIN)
		echo '<a href="?sayfa=yazi&uyeno='.UYE_NO.'">'.$yorum_sayisi.'</a>';
		?></td>
  </tr>
	<tr>
    <td width="100%" height="20" colspan="2" align="center"><a href="?sayfa=omgonder&uye=<?php echo $u_kuladi; ?>"><?php echo $dil['OzelMesajGonder']; ?></a></td>
  </tr>
	<tr>
    <td width="100%" height="20" colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" colspan="2" width="100%" height="28"><a href="index.php?sayfa=uye&s=<?php echo $sayfano; ?>"><?php echo $dil['GeriDon']; ?></a>&nbsp;&nbsp;||&nbsp;&nbsp;<a href="index.php"><?php echo $dil['AnaSayfa']; ?></a></td>
  </tr>
</table>
<?php
unset($u_vt);
unset($uye_bilgi,$u_kuladi,$u_adi,$u_soyadi,$u_eposta,$u_dogumtarihi,$u_kayittarihi,$u_yil,$u_ay,$u_gun,$u_songiristarihi,$u_guncellemetarihi,$u_girissayisi);

} else {
  throw new Exception($dil['IslemGecersiz']);
}
//===================================
} // 2. ADIM SONU
//===================================
} //try Sonu
catch (Exception $e)
{
?>
  <table align="center" cellpadding="0" cellspacing="0" width="85%">
    <tr>
      <td align="center">
        <?php echo $fonk->hata_mesaj($e->getMessage(),$hata,'<a href="index.php">'.$dil['Tamam'].'</a>'); ?>
      </td>
    </tr>
  </table>
<?php
}
?>