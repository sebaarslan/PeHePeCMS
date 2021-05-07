<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;

try
{	
if (UYE_SEVIYE > 0)
{ 
  throw new Exception($dil['CikisYapiniz']);
  exit;
}
if (empty($islem) || $islem==1)
{
$kuladi = false;
$eposta = false;
$gun    = false;
$ay     = false;
$yil    = false;
if (@is_array($_SESSION['yenisifre']))
{
  foreach($_SESSION['yenisifre'] as $anahtar=>$deger)
  {
    ${$anahtar} = unserialize($deger);
    if (empty(${$anahtar}))
    ${$anahtar} = '';
  }
} 
?>
<table width="85%" align="center">
<form name="sifre_form" id="sifre_form"  action="?sayfa=sifre&islem=2" method="post">
  <tr>
    <td width="100%" height="20" colspan="2" align="center"><h1><?php echo $dil['SIFRE_UNUTMA']; ?></h1></td>
  </tr>
  <tr>
    <td width="40%" align="right"  height="25" valign="center">* <?php echo $dil['KullaniciAdiniz']; ?> : </td>
    <td width="60%" align="left" height="25"><input type="text" name="kuladi" id="kuladi" maxlength="20" tabindex="1" value="<?php echo $kuladi; ?>" />&nbsp;&nbsp;Max: 20</td>
  </tr>
	<tr>
    <td width="40%" align="right" height="5">* <?php echo $dil['EpostaAdresiniz']; ?> : </td>
    <td width="60%" align="left" height="25"><input type="text" name="eposta" id="eposta" maxlength="100" tabindex="2" value="<?php echo $eposta; ?>" />&nbsp;&nbsp;Max: 100</td>
  </tr>
	 <tr>
    <td width="40%" align="right" height="25" valign="top">* <?php echo $dil['DogumTarihiniz']; ?> : </td>
    <td width="60%" align="left" height="25">
      <input type="text" name="gun" id="gun" maxlength="2" size="2" style="text-align: center" onkeyup="isNumberic(this)" tabindex="3" value="<?php echo $gun; ?>" /> . 
      <input type="text" name="ay" id="ay" maxlength="2" size="2" style="text-align: center" onkeyup="isNumberic(this)" tabindex="4" value="<?php echo $ay; ?>" /> . 
      <input type="text" name="yil" id="yil" maxlength="4" size="4" style="text-align: center" onkeyup="isNumberic(this)" tabindex="5" value="<?php echo $yil; ?>" />&nbsp;&nbsp;<br />(Örn: 01.01.1978)
    </td>
  </tr>
	<tr>
    <td align="center" colspan="2" width="100%" height="28"><input style="font-size: 10px" type="submit" name="sifreButon" id="sifreButon" value="<?php echo $dil['YENI_SIFRE']; ?>" tabindex="6" /></td>
  </tr>
  <tr>
    <td align="center" colspan="2" width="100%" height="28"><a href="index.php"><?php echo $dil['AnaSayfa']; ?></a></td>
  </tr>
</form>
</table>
<?php
//Hafiza Bosaltiliyor
if (@is_array($_SESSION['yenisifre']))
{
  foreach($_SESSION['yenisifre'] as $anahtar=>$deger)
  {
    unset(${$anahtar});
		unset($_SESSION['yenisifre']);
  }
} 
//====================================
} else { //2. ADIM
//====================================
	$svt = new Baglanti(); //Yeni Veritabanı Baglantisi
	
  @ $kullaniciadi  = $fonk->post_duzen($_POST['kuladi']);
  @ $eposta        = $fonk->post_duzen($_POST['eposta']);
  @ $gun           = intval($_POST['gun']);
	@ $ay            = intval($_POST['ay']);
	@ $yil           = intval($_POST['yil']);

	$_SESSION['yenisifre']['kuladi']  = serialize($kullaniciadi);
	$_SESSION['yenisifre']['eposta']  = serialize($eposta);
	$_SESSION['yenisifre']['gun']     = serialize($gun);
	$_SESSION['yenisifre']['ay']      = serialize($ay);
	$_SESSION['yenisifre']['yil']     = serialize($yil);
	
	if (strlen($gun) == 1) $gun = '0'.$gun;
	if (strlen($ay) == 1) $ay = '0'.$ay;
	$dogumtarihi    = $yil.'-'.$ay.'-'.$gun;


  if (UYE_SEVIYE > 0)
  { 
    throw new Exception($dil['IslemIcinCikisGerekli']);
    exit;
  }
  if (empty($kullaniciadi) || empty($eposta) || empty($gun) || empty($ay) || empty($yil))
  {
    //Kullanici Adi ve Sifre Alani Bos Birakilmamalidir
    throw new Exception($dil["BosAlanBirakmayiniz"],1);
		exit;
  } elseif (!$fonk->kuladi_kontrol($kullaniciadi)) {
    //Kullanici Adinda A-Z Harf, 0-9 Rakam ve Alt Cizgi Kullanilabilir
    throw new Exception($dil["KullaniciAdiGecersiz"],1);
    exit;
  } elseif (!$fonk->eposta_kontrol($eposta)) {
    //E-Posta Adresi Gecersiz
    throw new Exception($dil["EpostaGecersiz"],1);
    exit;
  } else {
    //Giris Deneme Suresi Asildiysa Sifirlaniyor
    $svt->query("UPDATE ".TABLO_ONEKI."ipkontrol SET denemesayi=0 WHERE tarih<DATE_SUB(NOW(), INTERVAL ".GIRIS_DENEME_SURESI." MINUTE) AND ip='".UYE_IP."'");
    @ $giris_deneme_osure = $_SESSION['giris_deneme']['sure'];
    @ $giris_deneme_csure = $_COOKIE['giris_deneme_suresi'];
    if ($giris_deneme_osure < (time()-(GIRIS_DENEME_SURESI*60)))
    {
      unset($_SESSION['giris_deneme']);
    }
		
    //Giris Deneme IP
    $giris_deneme_isayi   = $svt->kayitSay("SELECT COUNT(*) FROM ".TABLO_ONEKI."ipkontrol WHERE ip='".UYE_IP."' AND denemesayi >= ".GIRIS_DENEME_SAYISI."");
    //Giris  Deneme Cerez Sayisi 
    @ $giris_deneme_csayi = $_COOKIE['girisdenemesayisi'];

    //Giris Deneme Oturum Sayisi 
    @ $giris_deneme_osayi = $_SESSION['giris_deneme']['sayi'];

    //GIRIS DENEME KONTROL
    if ($giris_deneme_isayi > 0 || $giris_deneme_osayi >= GIRIS_DENEME_SAYISI || $giris_deneme_csayi >= GIRIS_DENEME_SAYISI)
    {
      //Giris Deneme Suresi Kadar Bekleme Gerekiyor
      throw new Exception($fonk->yerine_koy($dil['GirisDenemeSuresiKadarBekleyiniz'],array(GIRIS_DENEME_SAYISI,GIRIS_DENEME_SURESI)));
      exit;
    } else {
      $svt->query("SELECT `uyeno`,`uyeadi`,`eposta`,`dogumtarihi`,`onay`,`yonay`,DATE_ADD(onlinetarih, INTERVAL ".YENILEME_SURESI." SECOND) AS yenileme FROM `".TABLO_ONEKI."uyeler` WHERE TRIM(uyeadi)='".$vt->escapeString($kullaniciadi)."'");
      if ($svt->numRows() > 0)
      {
        $uye_veri          = $svt->fetchObject();
        $uye_no            = $uye_veri->uyeno;
        $uye_adi           = $uye_veri->uyeadi;
        $uye_eposta        = $uye_veri->eposta;
        $uye_dogumtarihi   = $uye_veri->dogumtarihi;
        $uye_onay          = $uye_veri->onay;
        $uye_onlinetarih   = $uye_veri->yenileme;
        $uye_yonay         = $uye_veri->yonay;

        if ($kullaniciadi == $uye_adi && $eposta == trim($uye_eposta) && $uye_dogumtarihi == $dogumtarihi)
        {
          if ($uye_onlinetarih > date('Y-m-d H:i:s'))
          {
            throw new Exception($dil['BuKullaniciAdiylaGirisYapilmis']);
            exit;
          }
          if ($uye_onay == 'H')
          {
            throw new Exception($dil['UyeliginizOnayBekliyor']);
            exit;
          }
          if ($uye_yonay == 0)
          {
            throw new Exception($dil['UyeliginizYoneticiOnayiBekliyor']);
            exit;
          } elseif ($uye_yonay == 1) {
            throw new Exception($dil['UyeliginizOnayli'].' '.$dil['UyeliginizGeciciSureAskiyaAlinmistir']);
            exit;
          }
          unset($uye_onay,$uye_yonay,$uye_veri,$uye_dogumtarihi,$uye_onlinetarih);
						
          //Giris Basariliysa
          $onay_kodu  = sha1($fonk->kod(10));
          $yenisifre = $fonk->kod(8);
          $yeni_sifre = sha1($yenisifre);
						 
          if ($svt->query("UPDATE ".TABLO_ONEKI."uyeler SET sifre='$yeni_sifre',ip='".UYE_IP."',onaykodu='$onay_kodu' WHERE uyeno=$uye_no"))
          {
            //Üyelik Bilgilerinin E-Posta İle Üyeye Gönderilmesi
            
            $eposta_konu   = $dil['UyelikBilgileriniz'];
            $eposta_mesaj  = SITE_ADI.' '.$dil['UyelikBilgilerinizAsagida']."\r\n";
            $eposta_mesaj .= $dil['KullaniciAdiniz']." : ".$kullaniciadi."\r\n";
            $eposta_mesaj .= $dil['Sifreniz']." : ".$yenisifre."\r\n";
            $eposta_mesaj .= $dil['SifreniziDegistirdiniz']."\r\n";
						$eposta_mesaj .= SITE_ADI."\r\n";
            $eposta_mesaj .= SITE_ADRESI."\r\n";
						
            //==================================================================================
            // E-Posta Gonderiliyor
            $fonk->eposta_gonder(array(trim($uye_eposta)=>''), $eposta_konu, $eposta_mesaj,true,'text');
            //==================================================================================
            unset($eposta_ustbilgi,$eposta_konu,$eposta_mesaj,$uye_eposta);
							
            //Oturumdaki Deneme Sayisi Sifirlaniyor   
            unset($_SESSION['giris_deneme'],$_SESSION['giris_deneme']['sure'],$_SESSION['giris_deneme']['sayi']);
				
            //Cerezdeki Deneme Sayisi Sifirlaniyor
            setcookie("girisdenemesayisi", "", time() -(GIRIS_DENEME_SURESI*60));
				
            //IP Deneme Saysi Sifirlaniyor
            $svt->query("UPDATE ".TABLO_ONEKI."ipkontrol SET denemesayi=0 WHERE ip='".UYE_IP."'"); 
						
            //IP Adresine Ait Tarih Sifirlaniyor
            $svt->query("UPDATE ".TABLO_ONEKI."ipkontrol SET tarih='0000-00-00 00:00:00' WHERE ip='".UYE_IP."'");
            throw new Exception($dil['SifreGonderildi'],2);
            exit;
          } else {
            throw new Exception($dil['IslemBasarisiz']);
            exit;
          }
        } else {
          //Giris Basarili Degilse
          $fonk->girisDeneme();
          throw new Exception($dil['BilgilerGecersiz'],1);
          exit;
        }
      } else {
        //Giris Basarili Degilse
        $fonk->girisDeneme();
        throw new Exception($dil['BilgilerGecersiz'],1);
				exit;
      }  
    }
  }
unset($svt,$kullaniciadi,$eposta,$giris_deneme_osayi,$giris_deneme_csayi,$giris_deneme_isayi,$giris_deneme_osure,$giris_deneme_csure);
//====================================
} //2. ADIM SONU
//====================================
} //try Sonu  
catch (Exception $e)
{
$hatakod = $e->getCode();
if ($hatakod == 1)
{
  $adres = '<a href="index.php?sayfa=sifre">'.$dil['Tamam'].'</a>';
	$hata  = false;
} elseif ($hatakod == 2) {
  unset($_SESSION['yenisifre']);
  $adres = '<a href="index.php">'.$dil['Tamam'].'</a>';
	$hata = true;
} else {
  unset($_SESSION['yenisifre']);
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
}
?>