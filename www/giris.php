<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once('genel.php');
$vt = new Baglanti();
global $_SESSION;
global $_COOKIE;
@ $kullaniciadi = $fonk->post_duzen($_POST['kuladi']);
@ $parola       = $fonk->post_duzen($_POST['sifre']);

@ $benitani     = intval($_POST['benitani']);
$yonlendir      = 'Location: index.php?sayfa=giris';
if (UYE_SEVIYE > 0)
{ 
  header('Location: index.php');
  exit;
}
if (empty($kullaniciadi) || empty($parola))
{
  //Kullanici Adi ve Sifre Alani Bos Birakilmamalidir
  header($yonlendir.'&hata=1');
} elseif (!$fonk->kuladi_kontrol($kullaniciadi)) {
  //Kullanici Adinda A-Z Harf, 0-9 Rakam ve Alt Cizgi Kullanilabilir
  header($yonlendir.'&hata=2');
  exit;
} elseif (!$fonk->parola_kontrol($parola)) {
  //Sifrede A-Z Harf, 0-9 Rakam ve Alt Cizgi Kullanilabilir
  header($yonlendir.'&hata=3');
  exit;
} else {
   //Giris Deneme Suresi Asildiysa Sifirlaniyor
  $vt->query("UPDATE ".TABLO_ONEKI."ipkontrol SET denemesayi=0 WHERE tarih<DATE_SUB(NOW(), INTERVAL ".GIRIS_DENEME_SURESI." MINUTE) AND ip='".UYE_IP."'");

  @ $giris_deneme_osure = $_SESSION['giris_deneme']['sure'];
  @ $giris_deneme_csure = $_COOKIE['giris_deneme_suresi'];
  if ($giris_deneme_osure < (time()-(GIRIS_DENEME_SURESI*60)))
  {
    unset($_SESSION['giris_deneme']);
  }
		
  //Giris Deneme IP
  $giris_deneme_isayi   = $vt->kayitSay("SELECT COUNT(ip) FROM ".TABLO_ONEKI."ipkontrol WHERE ip='".UYE_IP."' AND denemesayi >= ".GIRIS_DENEME_SAYISI."");
  //Giris  Deneme Cerez Sayisi 
  @ $giris_deneme_csayi = $_COOKIE['girisdenemesayisi'];

  //Giris Deneme Oturum Sayisi 
  @ $giris_deneme_osayi = $_SESSION['giris_deneme']['sayi'];

  //GIRIS DENEME KONTROL
  if ($giris_deneme_isayi > 0 || $giris_deneme_osayi >= GIRIS_DENEME_SAYISI || $giris_deneme_csayi >= GIRIS_DENEME_SAYISI)
  {
    //Giris Deneme Suresi Kadar Bekleme Gerekiyor
    header($yonlendir.'&hata=4');
    exit;
  } else {

    $vt->query("SELECT `uyeno`,`uyeadi`,`sifre`,`onay`,`yonay`,DATE_ADD(onlinetarih, INTERVAL ".YENILEME_SURESI." SECOND) AS yenileme FROM `".TABLO_ONEKI."uyeler` WHERE uyeadi='".$vt->escapeString($kullaniciadi)."'");

    if ($vt->numRows() > 0)
    {
      $uye_veri          = $vt->fetchObject();
      $uye_no            = $uye_veri->uyeno;
      $uye_adi           = $uye_veri->uyeadi;
      $uye_sifre         = $uye_veri->sifre;
      $uye_onay          = $uye_veri->onay;
      $uye_yoneticionayi = $uye_veri->yonay;
      $uye_onlinetarih   = $uye_veri->yenileme;
      if ($kullaniciadi == $uye_adi && sha1($parola) == trim($uye_sifre))
      {
        if ($uye_onlinetarih > date('Y-m-d H:i:s'))
        {
          header($yonlendir.'&hata=5');
          exit;
        }
        if ($uye_onay == 'H')
        {
          header($yonlendir.'&hata=6');
          exit;
        }
        if ($uye_yoneticionayi == 0)
        {
          header($yonlendir.'&hata=7');
          exit;
        } elseif ($uye_yoneticionayi == 1) {
          header($yonlendir.'&hata=8');
          exit;
        }
        //Giris Basariliysa
        $_SESSION['pehepe_kullanici_adi']   = $uye_adi;
        $_SESSION['pehepe_kullanici_sifre'] = $uye_sifre;
        if ($benitani)
        {
          setcookie("pehepe_kullanici[adi]", $uye_adi, time() +2592000);
          //setcookie("pehepe_kullanici[sifre]", $uye_sifre, time() +2592000);
        } else {
          //Beni Tani Secili Degilse Kullanici Adi Cerezlerden Kaldiriliyor
          setcookie ("pehepe_kullanici[adi]", $deger, time() - 2592000);
        }
        //Oturumdaki Deneme Sayisi Sifirlaniyor   
        unset($_SESSION['giris_deneme'],$_SESSION['giris_deneme']['sure'],$_SESSION['giris_deneme']['sayi']);
        //Cerezdeki Deneme Sayisi Sifirlaniyor
        setcookie("girisdenemesayisi", "", time() -(GIRIS_DENEME_SURESI*60));
				
        //IP Deneme Saysi ve Tarih Sifirlaniyor
        $vt->query("UPDATE ".TABLO_ONEKI."ipkontrol SET denemesayi=0,tarih='0000-00-00 00:00:00' WHERE ip='".UYE_IP."'"); 
				$vt->query("UPDATE ".TABLO_ONEKI."uyeler SET girisdenemesayi=0 WHERE uyeno=$uye_no");
				unset($vt,$kullaniciadi,$parola,$giris_deneme_osayi,$giris_deneme_csayi,$giris_deneme_isayi,$giris_deneme_osure,$giris_deneme_csure);
				@$sayfa_adi = trim(preg_replace('([^0-9a-z\&\;\_\-\=])is','',strtolower((strip_tags(unserialize($_SESSION['sayfaadi']))))));
				unset($_SESSION['sayfaadi']);
				if ($sayfa_adi)
        header('Location:index.php?sayfa='.$sayfa_adi);
				else
				header('Location:index.php');
				
        unset($sayfa_adi,$uye_no,$uye_veri,$uye_adi,$uye_sifre,$uye_onay,$uye_yoneticionayi,$uye_veri);
      } else {
        //Giris Basarili Degilse
				$vt->query("SELECT girisdenemesayi,UNIX_TIMESTAMP(DATE_ADD(girisdenemetarih, INTERVAL ".GIRIS_DENEME_SURESI." MINUTE)) AS girisdenemetarih FROM ".TABLO_ONEKI."uyeler WHERE uyeno=$uye_no");
        $girisdeneme = $vt->fetchObject();
				$girisdeneme_sayi  = intval($girisdeneme->girisdenemesayi+1);
				$girisdeneme_tarih = $girisdeneme->girisdenemetarih;
				if ($girisdeneme_tarih<time())
				{
				  $vt->query("UPDATE ".TABLO_ONEKI."uyeler SET girisdenemesayi=0 WHERE uyeno=$uye_no");
				}
				//Uye Tablosu Giris Deneme Sayisi Artiriliyor
				$vt->query("UPDATE ".TABLO_ONEKI."uyeler SET girisdenemesayi=girisdenemesayi+1, girisdenemetarih=NOW() WHERE uyeno=$uye_no");
        
				if ($girisdeneme_sayi >= GIRIS_DENEME_SAYISI && $girisdeneme_tarih>=time()) 
        {
          //Giris Yasaklandi
          header($yonlendir.'&hata=4');
					exit;
        }
        $fonk->girisDeneme();
        header($yonlendir.'&hata=9');
        exit;
      }
    } else {
      //Giris Basarili Degilse
      $fonk->girisDeneme();
      header($yonlendir.'&hata=9');
    }  
  }
}
?>
