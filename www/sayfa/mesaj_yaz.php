<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;
//==============================
try { // try Başlangıcı
//==============================

if (UYE_SEVIYE == 0)
{
  $_SESSION['sayfaadi'] = serialize($sayfa);
  header('Location: ?sayfa=giris&hata=15');
	exit;
}

//=========================================================
if (empty($islem) || $islem == 1) { // 1. ADIM BAŞLANGICI
//=========================================================
require_once(SAYFA_KLASOR."/mesaj_menu.php");

@ $uyelik_adi = $fonk->post_duzen($_GET['uye']);

if (UYE_SEVIYE < OZEL_MESAJ_GONDERME_IZIN)
{
  throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[OZEL_MESAJ_GONDERME_IZIN]));
	exit;
}
if (!empty($uyelik_adi))
{
  if (!$fonk->kuladi_kontrol($uyelik_adi)) {
    //Kullanici Adinda A-Z Harf, 0-9 Rakam ve Alt Cizgi Kullanilabilir
    throw new Exception($dil["KullaniciAdiGecersiz"]);
    exit;
  } elseif (UYE_KULLADI == $uyelik_adi) {
	  throw new Exception($dil["KendinizeOzelMesajIzniYok"]);
    exit;
  }
}

//MESAJ CEVAPLAMA BILGILERI
@ $mesajno    = intval($_REQUEST['mesajno']);
$cevap_kime   = $uyelik_adi;
$cevap_baslik = '';
$cevap_mesaj  = '';

if (empty($mesajno))
{
  $mesajsayfaadi = $dil['OZEL_MESAJ_GONDER']; 
} else {
  $mesajsayfaadi = $dil['OZEL_MESAJ_CEVAPLA'];
  
  $vt->query("SELECT u.uyeadi,m.kimden,m.baslik,m.mesaj,m.tarih FROM ".TABLO_ONEKI."ozelmesaj AS m,".TABLO_ONEKI."uyeler AS u WHERE m.kimden=u.uyeno AND m.kime=".UYE_NO." AND m.mesajno=$mesajno");
  $cevap_izin            = $vt->numRows();
  $cevapkime_ayrinti     = $vt->fetchObject();
  $cevap_kime            = $cevapkime_ayrinti->uyeadi;
  $cevap_baslik          = $fonk->yazdir_duzen($cevapkime_ayrinti->baslik);
  $cevap_mesaj           = $fonk->yazdir_duzen($cevapkime_ayrinti->mesaj);
  if ($cevap_izin == 0)
  {
	  echo $fonk->hata_mesaj($dil['MesajiCevaplamaYetkinizYok']);
    exit;
  }
	$vt->freeResult();
}
if (@is_array($_SESSION['mesaj_kayit']))
{
  foreach($_SESSION['mesaj_kayit'] as $anahtar=>$deger)
  {
    ${$anahtar} = unserialize($deger);
    if (empty(${$anahtar}))
    ${$anahtar} = '';
  }
	$cevap_baslik = $baslik;
	$cevap_mesaj  = $mesaj;
  $cevap_kime = $uyeadi;
} 
?>
<p>&nbsp;</p>
<form name="mesaj_yaz" id="mesaj_yaz"  action="?sayfa=omgonder&islem=2" method="post" autocomplete="off">
<input type="hidden" name="mesajno" value="<?php echo $mesajno;?>">
<table cellspacing="0" cellpadding="0" width="98%" align="center" valign="top">
  <tr>
    <td width="100%" height="20" align="center" nowrap="nowrap"><h1><?php echo $mesajsayfaadi; ?></h1></td>
  </tr>
  <tr>
    <td align="center" width="100%" valign="top">
      <table width="100%">
        <tr>
          <td width="35%" align="right" valign="top" nowrap="nowrap">* <?php echo $dil['Kime']; ?> : </td>
          <td width="65%" align="left" valign="top" nowrap="nowrap"> 
          <?php	
          if (empty($mesajno))
          {
          ?>
					<input type="text" name="uyeadi" id="uyeadi" size="35" value="<?php echo $cevap_kime; ?>" autocomplete="off" onkeyup="if(document.getElementById('uyeadi').value.length>1){ajaxSorgu(document.getElementById('uyeadi').value+':'+document.getElementById('adi').value+':'+document.getElementById('soyadi').value+':1','uyeliste','uyeListe','ajaxSonuc');}" tabindex="1" />
					
					<br /><div id="ana_div" class="ana_div" style="width:300px">
          <table width="100%" cellspacing="0" cellpadding="0">
            <tr bgcolor="#999999">
              <td align="left" nowrap="nowrap" height="20" style="padding-left:3px;"><b><?php echo $dil['ARAMA_SONUCLARI']; ?></b></td>
              <td align="right" nowrap="nowrap" style="padding-right:3px;font-size:10px;" height="20"><a href="javascript:void(null)" onclick="document.getElementById('ana_div').style.display='none'"><?php echo $dil['Kapat']; ?>X</a></td>
            </tr>
            <tr>
              <td style="padding-left:5px;font-size:10px" colspan="2" nowrap="nowrap"><div id="icerik_div" class="icerik_div"></div></td>
            </tr>
          </table>
          </div>
          <img src="resim/bosluk.gif" id="bekle" width="16" height="16" />
					<?php
            if (UYE_SEVIYE >= OZEL_MESAJ_ISIM_IZIN)
            {
            ?>
						<div><b><?php echo $dil['ARA']; ?> :</b> <?php echo $dil['UyeAdi']; ?> : <input type="checkbox" name="uyelik" id="uyelik" value="1" checked="checked"  onkeyup="if(document.getElementById('uyeadi').value.length>1){ajaxSorgu(document.getElementById('uyeadi').value+':'+document.getElementById('adi').value+':'+document.getElementById('soyadi').value,'uyeliste','uyeListe','ajaxSonuc');}" />&nbsp;&nbsp;
						<?php echo $dil['Adi']; ?> : <input type="checkbox" name="adi" id="adi" value="1"  onkeyup="if(document.getElementById('uyeadi').value.length>1){ajaxSorgu(document.getElementById('uyeadi').value+':'+document.getElementById('adi').value+':'+document.getElementById('soyadi').value,'uyeliste','uyeListe','ajaxSonuc');}" />&nbsp;&nbsp;<?php echo $dil['Soyadi']; ?> : <input type="checkbox" name="soyadi" id="soyadi" value="1"  onkeyup="if(document.getElementById('uyeadi').value.length>1){ajaxSorgu(document.getElementById('uyeadi').value+':'+document.getElementById('adi').value+':'+document.getElementById('soyadi').value,'uyeliste','uyeListe','ajaxSonuc');}" /></div>
						<?php
            } else {
						  echo '<input type="hidden" name="adi" id="adi" value="0" />
							<input type="hidden" name="soyadi" id="soyadi" value="0" />';
						}
          } else {
            echo '<input type="hidden" name="uyeadi" value="'.$cevap_kime.'" /><b>'.$cevap_kime.'</b>';
          }
          ?>
          </td>
        </tr>
        <tr>
          <td width="35%" align="right" height="25">* <?php echo $dil['MesajBasligi']; ?> : </td>
          <td width="65%" height="25" align="left"><input type="text" name="baslik" id="baslik" maxlength="100" size="35" value="<?php echo $cevap_baslik?>" tabindex="2" /></td>
        </tr>
        <tr>
          <td width="35%" align="right" valign="top">* <?php echo $dil['Mesaj']; ?> : </td>
          <td width="65%" height="25" align="left"><textarea name="mesaj" id="mesaj" cols="40" rows="7" style="width:250px" onkeyup="karakter_sayi_kontrol('mesaj',<?php echo OZEL_MESAJ_KARAKTER; ?>);" tabindex="3"><?php echo $cevap_mesaj;?></textarea></td>
        </tr>
        <tr>
          <td width="35%" align="right" valign="top">&nbsp;</td>
          <td width="65%" height="25" align="left"><input type="text" name="mesaj_sayac" id="mesaj_sayac" size="5" value="<?php echo OZEL_MESAJ_KARAKTER; ?>" disabled="disabled" />
        </tr>
      </table>
    </td>
  </tr>
	<tr>
    <td align="center" width="100%" height="20"><input style="font-size: 10px" type="submit" value="<?php echo $dil['GONDER']; ?>" tabindex="4" id="mesajGonder" name="mesajGonder" /></td>
  </tr>
  <tr>
    <td align="center" width="100%"><br /><a href="index.php"><?php echo $dil['AnaSayfa']; ?></a></td>
  </tr>
</table> 
</form>
<?php
//Hafiza Bosaltiliyor
if (@is_array($_SESSION['mesaj_kayit']))
{
  foreach($_SESSION['mesaj_kayit'] as $anahtar=>$deger)
  {
    unset(${$anahtar});
  }
	unset($_SESSION['mesaj_kayit']);
} 
unset($mesajno,$cevapkime,$cevap_baslik,$cevap_mesaj,$mesajsayfaadi,$cevap_izin,$cevapkime_ayrinti,$cevap_uyeadi);
//=====================================
} else { // 2. ADIM BASLANGICI
//=====================================
if (UYE_SEVIYE == 0) 
{
  $_SESSION['sayfaadi'] = serialize($sayfa);
  header('Location: ?sayfa=giris&hata=15'); 
  exit;
  }
  if (UYE_SEVIYE < OZEL_MESAJ_GONDERME_IZIN)
  {
    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler['OZEL_MESAJ_GONDERME_IZIN']));
    exit;
  }
    
  @ $baslik  = $fonk->post_duzen($_POST['baslik']);
  @ $mesaj   = $fonk->post_duzen($_POST['mesaj']);
  @ $mesajno = trim(intval($_POST['mesajno']));
	@ $uyeadi  = $fonk->post_duzen($_POST['uyeadi']);
	$_SESSION['mesaj_kayit']['baslik']  = serialize($baslik);
	$_SESSION['mesaj_kayit']['mesaj']   = serialize($mesaj);
	$_SESSION['mesaj_kayit']['mesajno'] = serialize($mesajno);
	$_SESSION['mesaj_kayit']['uyeadi']  = serialize($uyeadi);
	
		
  if (empty($uyeadi))
  {
    //Bos Alan Birakildi
    throw new Exception($dil["BosAlanBirakmayiniz"],1);
    exit;
  } elseif (!$fonk->kuladi_kontrol($uyeadi)) {
    //Kullanici Adinda A-Z Harf, 0-9 Rakam ve Alt Cizgi Kullanilabilir
    throw new Exception($dil["KullaniciAdiGecersiz"],1);
    exit;
  }

	$uyeno = 0;
  $vt->query("SELECT uyeno,uyeadi FROM ".TABLO_ONEKI."uyeler WHERE uyeno<>".UYE_NO." AND uyeadi='$uyeadi' AND onay='E' AND yonay=5");
  $bulunan = $vt->numRows();
  if ($bulunan > 0)
  {
    $kisi_uyeno = $vt->fetchObject();
    $uyeno      = $kisi_uyeno->uyeno;
  } else {
    throw new Exception($dil['KullaniciAdiSistemdeKayitliDegil'],1);
    exit;
  } 
	
  // VERILER KONTROL EDILIYOR
  if (empty($uyeno) || empty($baslik) || empty($mesaj))
  {
    //Bos Alan Birakildi
    throw new Exception($dil["BosAlanBirakmayiniz"],1);
    exit;
  } elseif (strlen($fonk->yazdir_duzen($baslik)) > 100) {
    throw new Exception($fonk->yerine_koy($dil["BaslikKarakterIzin"],100),1);
    exit;
  } elseif (strlen($fonk->yazdir_duzen($mesaj)) > OZEL_MESAJ_KARAKTER) {
    throw new Exception($fonk->yerine_koy($dil["YaziKarakterIzin"],OZEL_MESAJ_KARAKTER),1);
    exit;
  } elseif ($vt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE uyeno=$uyeno")==0) {
    throw new Exception($dil["GecersizKullaniciAdi"],1);
    exit;
  } elseif ($uyeno == UYE_NO){
    //Mesajin Kendisine Gonderilip Gonderilmedigi Kontrol Ediliyor
    throw new Exception($dil["KendinizeOzelMesajIzniYok"],1);
    exit;
  } elseif ($vt->kayitSay("SELECT COUNT(*) FROM ".TABLO_ONEKI."ozelmesaj WHERE kime=$uyeno") >= OZEL_MESAJ_IZIN && $vt->kayitSay("SELECT COUNT(*) FROM ".TABLO_ONEKI."uyeler WHERE seviye>5")==0) {
    //Gonderilen Kisinin Klasorunde Yer Olup Olmadigi Kontrol Ediliyor
    throw new Exception($dil["GonderilenKlasorDolu"],1);
    exit;
  } else {

    if (empty($mesajno))
    {
      $cevaplandi = 0;
    } else { 
      //Mesaj Cevaplandý Bölümü Güncelleniyor
      $cevaplandi = 2; 
      $vt->query("SELECT cevaplandi FROM ".TABLO_ONEKI."ozelmesaj WHERE mesajno=$mesajno");
      $cevapnoveri   = $vt->fetchObject();
      $cevapno       = $cevapnoveri->cevaplandi;
      if ($cevapno == 2)
      {
        $cevap = 3;
      } else {
        $cevap = 1;
      }
      $vt->query2("UPDATE ".TABLO_ONEKI."ozelmesaj SET cevaplandi=$cevap WHERE mesajno=$mesajno AND kime=".UYE_NO."");
    }
    //Mesaj Gonderme Arasi Sure 
    $gonderilen_mesaj = $vt->kayitSay("SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."ozelmesaj WHERE tarih > DATE_SUB(NOW(), INTERVAL ".OZEL_MESAJ_ARASI_SURE." MINUTE) AND kimden=".UYE_NO."");
    if ($gonderilen_mesaj > 0)
    {
      throw new Exception($fonk->yerine_koy($dil['IslemIcinBeklemenizGerekiyor'],OZEL_MESAJ_ARASI_SURE));
      exit;
  }

  //MESAJ KAYIT
 $vt->query2("INSERT INTO ".TABLO_ONEKI."ozelmesaj (kimden,kime,baslik,mesaj,tarih,cevaplandi) VALUES (".UYE_NO.",$uyeno,'".$vt->escapeString($baslik)."','".$vt->escapeString($mesaj)."',NOW(),'$cevaplandi')");
  unset($baslik,$mesaj,$mesajno,$uyeadi,$bulunan,$cevaplanma_no,$cevapnoveri,$cevapno,$cevap,$cevap_sql,$gonderilen_mesaj);
  throw new Exception($dil['OzelMesajinizGonderildi'],2);
}
unset($vt,$baslik,$mesaj,$mesajno);

//=====================================
} // 2. ADIM SONU
//=====================================
} //try Sonu  
catch (Exception $e)
{
$hatakod = $e->getCode();
if ($hatakod == 1)
{
  $adres = '<a href="index.php?sayfa=omgonder">'.$dil['Tamam'].'</a>';
	$hata  = false;
} elseif ($hatakod == 2) {
  unset($_SESSION['mesaj_kayit']);
  $adres = '<a href="index.php?sayfa=omgiden">'.$dil['Tamam'].'</a>';
	$hata = true;
} else {
  unset($_SESSION['mesaj_kayit']);
  $adres = '<a href="index.php">'.$dil['Tamam'].'</a>';
	$hata = false;
}
?>
<table align="center" cellpadding="0" cellspacing="0" width="85%">
  <tr>
	  <td align="center">
    <?php echo $fonk->hata_mesaj($e->getMessage(),$hata,$adres); ?>
		</td>
	</tr>
</table>
<?php
} //catch Sonu
?>