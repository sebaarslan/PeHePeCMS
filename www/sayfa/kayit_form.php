<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
{
  echo 'Tek Kullanilmaz';
  exit;
}

try
{
if (UYE_SEVIYE > 0)
{ 
  throw new Exception($dil['IslemIcinCikisGerekli']);
  exit;
}

if (UYE_KAYIT_KAPAT == 'E')
{
  throw new Exception($dil['UyeKaydiKapali']);
	exit;
}
$kullaniciadi = '';
$eposta = '';
$adi = '';
$soyadi = '';
$gun = '';
$ay='';
$yil='';
if (@is_array($_SESSION['uyekayit']))
{
  foreach($_SESSION['uyekayit'] as $anahtar=>$deger)
  {
    ${$anahtar} = unserialize($deger);
    if (empty(${$anahtar}))
    ${$anahtar} = '';
  }
} 
if (empty($islem))
{
$hata_mesaj = false;
switch ($hata)
{
  case 1: $hata_mesaj = $dil['IslemIcinCikisGerekli']; break;
	case 2: $hata_mesaj = $dil['UyeKaydiKapali']; break;
  case 3: $hata_mesaj = $dil['IsaretliAlanlariBosBirakmayiniz']; break;
	case 5: $hata_mesaj = $dil['ResimAdiGecersiz']; break;
	case 6: $hata_mesaj = $dil["KullaniciAdiGecersiz"]; break;
	case 7: $hata_mesaj = $dil["EpostaGecersiz"]; break;
	case 8: $hata_mesaj = $dil["SifreGecersiz"]; break;
	case 9: $hata_mesaj = $dil["IsimSoyisimGecersiz"]; break;
	case 10: $hata_mesaj = $dil["KullaniciAdiKarakterIzin"]; break;
	case 11: $hata_mesaj = $dil["ParolaKarakterIzin"]; break;
	case 12: $hata_mesaj = $dil["SifrelerUyusmuyor"]; break;
	case 13: $hata_mesaj = $dil["EpostaKarakterIzin"]; break;
	case 14: $hata_mesaj = $dil["IsimKarakterIzin"]; break;
	case 15: $hata_mesaj = $dil["SoyisimKarakterIzin"]; break;
	case 16: $hata_mesaj = $dil['KullaniciAdiSistemdeKayitli']; break;
	case 17: $hata_mesaj = $dil['EpostaSistemdeKayitli']; break;
	case 18: $hata_mesaj = $dil['YilIcinGecerliSayiGiriniz']; break;
	case 19: $hata_mesaj = $dil['DogumTarihiGecersiz']; break;
}
?>
<p>&nbsp;</p>
<table width="85%" align="center">
<form name="kayitForm" id="kayitForm"  action="?sayfa=kayit&islem=2" method="post">
  <?php
  if ($hata_mesaj)
  {
  ?>
  <tr>
    <td width="100%" colspan="7" align="center"><?php echo $fonk->hatamesaj($hata_mesaj); ?></td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td width="100%" height="20" colspan="2" align="center"><h1><?php echo $dil['YENI_UYE_KAYDI']; ?></h1></td>
  </tr>
  <tr>
    <td width="40%" align="right"  height="25" valign="center"><b>* <?php echo $dil['KullaniciAdiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><input type="text" name="kullaniciadi" id="kullaniciadi" maxlength="20" tabindex="1"  autocomplete="off" value="<?php echo $kullaniciadi; ?>" />&nbsp;&nbsp;Max: 20</td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25" valign="center"><b>* <?php echo $dil['Sifreniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><input type="password" name="parola" id="parola" maxlength="25" tabindex="2" autocomplete="off" />&nbsp;&nbsp;Max: 25</td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25"><b>* <?php echo $dil['SifrenizTekrar']; ?> : </b></td>
    <td width="60%" align="left" height="25"><input type="password" name="parolatekrar" id="parolatekrar" maxlength="25" tabindex="3" autocomplete="off" />&nbsp;&nbsp;Max: 25</td>
  </tr>
  <tr>
    <td width="40%" align="right" height="5"><b>* <?php echo $dil['EpostaAdresiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><input type="text" name="eposta" id="eposta" maxlength="100" tabindex="4" autocomplete="off" value="<?php echo $eposta; ?>"/>&nbsp;&nbsp;Max: 100</td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25" valign="center"><b>* <?php echo $dil['Adiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><input type="text" name="adi" id="adi" maxlength="50" tabindex="5" autocomplete="off" value="<?php echo $adi; ?>" />&nbsp;&nbsp;Max: 50</td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25" valign="center"><b>* <?php echo $dil['Soyadiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25" valign="center"><input type="text" name="soyadi" id="soyadi" maxlength="50" tabindex="6" autocomplete="off" value="<?php echo $soyadi; ?>" />&nbsp;&nbsp;Max: 50</td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25" valign="top"><b>* <?php echo $dil['DogumTarihiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25">
      <input type="text" name="gun" id="gun" maxlength="2" size="2" style="text-align: center" onkeyup="isNumberic(this)" tabindex="7" autocomplete="off" value="<?php echo $gun; ?>" /> . 
      <input type="text" name="ay" id="ay" maxlength="2" size="2" style="text-align: center" onkeyup="isNumberic(this)" tabindex="8" autocomplete="off" value="<?php echo $ay; ?>" /> . 
      <input type="text" name="yil" id="yil" maxlength="4" size="4" style="text-align: center" onkeyup="isNumberic(this)" tabindex="9" autocomplete="off" value="<?php echo $yil; ?>" />&nbsp;&nbsp;<br />(Örn: 01.01.1978)
    </td>
  </tr>
	 <tr>
    <td width="40%" align="right" height="25" valign="center">&nbsp;</td>
    <td width="60%" align="left" height="25" valign="center"><input type="checkbox" name="yorummesaj" id="yorummesaj" maxlength="50" value="1" checked="checked" tabindex="10" />&nbsp;<?php echo $dil['YorumMesajGonder']; ?></td>
  </tr>
	 <tr>
    <td width="100%" colspan="2" align="right" height="30" valign="center">&nbsp;</td>
	</tr>
  <tr>
    <td align="center" colspan="2" width="100%" height="28"><input style="font-size: 10px" type="submit" name="kayitButon" id="kayitButon" value="<?php echo $dil['KAYIT_OL']; ?>" tabindex="11" /></td>
  </tr>
  <tr>
    <td align="center" colspan="2" width="100%" height="28"><a href="index.php"><?php echo $dil['AnaSayfa']; ?></a></td>
  </tr>
</form>
</table>
<?php
//Hafiza Bosaltiliyor
if (@is_array($_SESSION['uyekayit']))
{
  foreach($_SESSION['uyekayit'] as $anahtar=>$deger)
  {
    unset(${$anahtar});
		unset($_SESSION['uyekayit']);
  }
} 
//===========================================
} elseif ($islem == 2) { // 2. ADIM BASLANGICI - UYE KAYIT
//===========================================
  //POST Degerleri Oturuma Yukleniyor
  
  $vt = new Baglanti();
	
	@ $kullaniciadi = $fonk->post_duzen($_POST['kullaniciadi']);
	@ $parola       = $fonk->post_duzen($_POST['parola']);
	@ $parolatekrar = $fonk->post_duzen($_POST['parolatekrar']);    
  @ $eposta       = $fonk->post_duzen($_POST['eposta']);
  @ $adi          = $fonk->post_duzen($_POST['adi']);
  @ $soyadi       = $fonk->post_duzen($_POST['soyadi']);
	@ $gun          = intval($_POST['gun']);
	@ $ay           = intval($_POST['ay']);
	@ $yil          = intval($_POST['yil']);
  @ $dogumtarihi  = $gun.'.'.$ay.'.'.$yil;
	@ $resim     = trim(strip_tags(htmlspecialchars($_POST['resim'])));
	foreach($_POST as $anahtar=>$deger)
  {
    $_SESSION['uyekayit'][$anahtar] = serialize($deger);
  }
	
	$hatayonlendir = 'Location: ?sayfa=kayit&hata';
	
  if (UYE_SEVIYE > 0)
  { 
    header($hatayonlendir.'=1');
    exit;
  }
		
  if (UYE_KAYIT_KAPAT == 'E')
  {
    //Uye Kaydi Kapali Hatasi
    header($hatayonlendir.'=2');
    exit;
  }
  if ($vt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE kayittarihi>=DATE_SUB(NOW(), INTERVAL ".UYE_KAYIT_ARASI_SURE." MINUTE) AND ip='".UYE_IP."'") > 0)
  {
    throw new Exception($fonk->yerine_koy($dil['YeniKayitIcinBekleyiniz'],UYE_KAYIT_ARASI_SURE));
    exit;
  }
  if (empty($kullaniciadi) || empty($parola) || empty($parolatekrar) || empty($eposta) || empty($adi) || empty($soyadi) || empty($gun) || empty($ay) || empty($yil))
  {
    header($hatayonlendir.'=3');
    exit;
  }
		
  if ($resim != '') 
	{
    if (!$fonk->resim_adi_kontrol($resim))
    {
      header($hatayonlendir.'=5');
      exit;
    }
  }
  if (!$fonk->kuladi_kontrol($kullaniciadi)) 
	{
    //Kullanici Adinda A-Z Harf, 0-9 Rakam ve Alt Cizgi Kullanilabilir
    header($hatayonlendir.'=6');
    exit;
		
  } elseif (!$fonk->eposta_kontrol($eposta)) {
    //E-Posta Adresi Gecersiz
    header($hatayonlendir.'=7');
    exit;
    
  } elseif (!$fonk->parola_kontrol($parola)) {
      //Sifrede A-Z Harf, 0-9 Rakam ve Alt Cizgi Kullanilabilir
      header($hatayonlendir.'=8');
      exit;
		
		} elseif (!$fonk->turkceharf_kontrol($adi) || !$fonk->turkceharf_kontrol($soyadi)) {
      //Isim ve Soyisimde a-zAZ Harf Kullanilabilir
      header($hatayonlendir.'=9');
      exit;
		}
			
		if (strlen($kullaniciadi) > 20 || strlen($kullaniciadi) < 4)
		{
		  //Kullanici Adi En Fazla 20 Karakter Olabilir
			header($hatayonlendir.'=10');
      exit;
		}
		if (strlen($parola) > 25 || strlen($parola) < 6)
		{
		  //Şifre En Fazla 25 Karakter Olabilir
			header($hatayonlendir.'=11');
      exit;
		}
		
		//Sifreler Uyusmuyor
		if ($parola != $parolatekrar)
		{
		  header($hatayonlendir.'=12');
      exit;
		} 
		
		if (strlen($eposta) > 100 || strlen($eposta) < 7)
		{
		  //Eposta En Fazla 100 En Az 5 Karakter Olabilir
			header($hatayonlendir.'=13');
      exit;
		}
		
		if (strlen($adi) > 50 || strlen($adi) < 2)
		{
		  //Isim En Fazla 50 En Az 2 Karakter Olabilir
			header($hatayonlendir.'=14');
      exit;
		}
		
		if (strlen($soyadi) > 50 || strlen($soyadi) < 2)
		{
		  //Soyisim En Fazla 50 En Az 2 Karakter Olabilir
			header($hatayonlendir.'=15');
      exit;
		}
		
		//Kullanici Adi Kontrolu
		$uye_adi_var = $vt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE uyeadi='$kullaniciadi'"); 
		if ($uye_adi_var > 0)
		{
		  //Kullanici Adi Daha Onceden Alinmis
      header($hatayonlendir.'=16');
      exit;
		}
		//E-Posta Adresi Kontrolu
		$eposta_var = $vt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE eposta='$eposta'");  
    if ($eposta_var > 0)
		{
      header($hatayonlendir.'=17');
      exit;
		}
		
		//Dogum Tarihi Kontrolu
		$yil_izin = intval(date('Y'))-100;
		if ($yil > date('Y',time()-378432000) || $yil < $yil_izin)
		{
		  header($hatayonlendir.'=18');
			exit;
		}
		if (!$dogumtarihi = $fonk->mysql_tarih_kontrol($dogumtarihi))
		{
		  header($hatayonlendir.'=19');
		  exit;
		}
		//Onay Ayarları
    if (UYELIK_ONAYI == 1)
    {
      $onay = 'E';
      $yoneticionayi = 5;
    } elseif (UYELIK_ONAYI == 2) {
      $onay = 'E';
      $yoneticionayi = 0;
    } elseif (UYELIK_ONAYI == 3) {
      $onay = 'H';
      $yoneticionayi = 5;
    } elseif (UYELIK_ONAYI == 4) {
      $onay = 'H';
      $yoneticionayi = 0;
    }
		$onay_kodu  = sha1($fonk->kod(10));
		$yeni_sifre = sha1($parola); 
		 
		if ($vt->query("INSERT INTO ".TABLO_ONEKI."uyeler (`uyeadi`,`sifre`,`adi`,`soyadi`,`eposta`,`dogumtarihi`,`onay`,`yonay`,`kayittarihi`,`ip`,`onaykodu`) VALUES('".$vt->escapeString($kullaniciadi)."','".$vt->escapeString($yeni_sifre)."','".$vt->escapeString($adi)."','".$vt->escapeString($soyadi)."','".$vt->escapeString($eposta)."','".$vt->escapeString($dogumtarihi)."','".$vt->escapeString($onay)."','$yoneticionayi',NOW(),'".UYE_IP."','$onay_kodu')"))
		{
		  
			//Üyelik Bilgilerinin E-Posta İle Üyeye Gönderilmesi
			$eposta_konu      = $dil['UyelikBilgileriniz'];
      $eposta_mesaj     = SITE_ADI.' '.$dil['UyelikBilgilerinizAsagida']."\r\n";
      $eposta_mesaj    .= $dil['KullaniciAdiniz'].' : '.$kullaniciadi."\r\n";
      $eposta_mesaj    .= $dil['Sifreniz'].' : '.$parola."\r\n";

      if (UYELIK_ONAYI == 1)
      { 
        $kayit_mesaji   = $dil['UyelikTamamlandi'];
  
      } elseif (UYELIK_ONAYI == 2) {
        $kayit_mesaji  = $dil['KayitYoneticiOnayi'];
  
      } elseif(UYELIK_ONAYI == 3) {

				$eposta_mesaj .= $dil['EpostaMesaj3']."\r\n";
				$eposta_mesaj .= $dil['OnayIsleminiBaslat']."\r\n";
				$eposta_mesaj .= SITE_ADRESI."/?sayfa=onay&kadi=".$kullaniciadi."&kod=".$onay_kodu."\r\n";
        $eposta_mesaj .= $dil['EpostaMesaj4']."\r\n";
        $eposta_mesaj .= SITE_ADRESI."/?sayfa=onay&kadi=".$kullaniciadi."&kod=".$onay_kodu."\r\n";

        $kayit_mesaji  = $dil['KayitEpostaOnayi'];

      } elseif (UYELIK_ONAYI == 4) {
        $eposta_mesaj .= $dil['EpostaMesaj3']."\r\n";
				$eposta_mesaj .= $dil['OnayIsleminiBaslat']."\r\n";
        $eposta_mesaj .= SITE_ADRESI."/?sayfa=onay&kadi=".$kullaniciadi."&kod=".$onay_kodu."\r\n";
        $eposta_mesaj .= $dil['EpostaMesaj5']."\r\n";
        $eposta_mesaj .= $dil['EpostaMesaj4']."\r\n";
        $eposta_mesaj .= SITE_ADRESI."/?sayfa=onay&kadi=".$kullaniciadi."&kod=".$onay_kodu."\r\n";
				
        $kayit_mesaji  = $dil['KayitEpostaVeYoneticiOnayi'];
      } else {
        $kayit_mesaji  = $dil['KayitIslemiTamamlandi'];
      }
      $eposta_mesaj .= $dil['SitemiziSectiginizIcinTesekkur']."\r\n";
			$eposta_mesaj .= SITE_ADI."\r\n";
      $eposta_mesaj .= SITE_ADRESI."\r\n";

      //==================================================================================
			// E-Posta Gonderiliyor
      $fonk->eposta_gonder( array(trim($eposta)=>$adi.' '.$soyadi), $eposta_konu, $eposta_mesaj, true, 'html' );
			//==================================================================================
     
			unset($_SESSION['uyekayit'],$eposta_mesaj,$eposta_konu,$eposta_mesaj,$eposta_ustbilgi);
      //Kayit Islemi Tamamlandi
      throw new Exception($kayit_mesaji,2);
		} else {
		  throw new Exception($dil['IslemBasarisiz']);
		}
//=====================================================
} //2. ADIM SONU
//=====================================================
} //try Sonu
catch (Exception $e)
{
  $hatakod = $e->getCode();
  if ($hatakod == 1)
  {
    $hata = false;
    $adres = '<a href="?sayfa=kayit">'.$dil['Tamam'].'</a>';
  } elseif ($hatakod == 2) {
    $hata = true;
    $adres = '<a href="?">'.$dil['Tamam'].'</a>';
  } else {
    $hata = false;
    $adres = '<a href="?">'.$dil['Tamam'].'</a>';
  }
  echo $fonk->hata_mesaj($e->getMessage(),$hata,$adres);
} 
unset($vt,$kullaniciadi,$parola,$parolatekrar,$eposta,$adi,$soyadi,$gun,$ay,$yil,$dogumtarihi);
?>