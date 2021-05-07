<?php
require_once("fonk.inc.php");
$fonk = new Fonksiyon();
$kuladi             = false;
$parola             = false;
$gecerli_uye_seviye = false;
$gecerli_uye_no     = 0;
$gecerli_uye_seviye = 0;
$gecerli_uye_adi    = false;
$gecerli_uye_online = false;

//Alttaki Bolum Sadece Oturum Kullanir
@ $gelen_kuladi = $_SESSION['pehepe_kullanici_adi'];
@ $gelen_parola = $_SESSION['pehepe_kullanici_sifre'];

@ $kuladi = trim(htmlspecialchars(strip_tags($gelen_kuladi)));
@ $parola = trim(htmlspecialchars(strip_tags($gelen_parola)));

if (!$fonk->kuladi_kontrol($kuladi) || !$fonk->parola_kontrol($parola)) 
{
  //Kullanici Adi ve/veya Sifre Gecersiz Karakter Iceriyor
  unset($_SESSION['pehepe_kullanici_adi'],$_SESSION['pehepe_kullanici_sifre'],$_COOKIE['pehepe_kullanici']['adi'],$_COOKIE['pehepe_kullanici']['sifre'],$kuladi,$parola);
}

if (@$kuladi && @$parola) 
{
  $UYESEVIYEALMA["$kuladi"] = "$parola";
  foreach ($UYESEVIYEALMA as $kuladi=> $parola) 
  {
    $vt->query("SELECT `uyeno`,`uyeadi`,`sifre`,`seviye`,DATE_ADD(onlinetarih, INTERVAL ".YENILEME_SURESI." SECOND) AS yenileme FROM ".TABLO_ONEKI."uyeler WHERE TRIM(uyeadi)='$kuladi' AND onay='E' AND yonay>4");
		$uye_kayit_var = $vt->numRows();
		if ($uye_kayit_var > 0)
		{
      $uye_bilgi = $vt->fetchObject();
		  $gecerli_uyesifre  = $uye_bilgi->sifre;
		  $gecerli_uyeadi    = $uye_bilgi->uyeadi;
		  if ($gecerli_uyeadi == $kuladi && $gecerli_uyesifre == $parola)
		  {
		    $gecerli_uye_adi    = $gecerli_uyeadi;
		    $gecerli_uye_seviye = $uye_bilgi->seviye; 
        $gecerli_uye_no     = $uye_bilgi->uyeno;
				$gecerli_uye_online = $uye_bilgi->yenileme;
		  }
			unset($uye_bilgi,$gecerli_uyesifre,$gecerli_uyeadi);
		} 
		$vt->freeResult();
  }
}  

define("UYE_SEVIYE", $gecerli_uye_seviye);
define("UYE_NO", $gecerli_uye_no);
define("UYE_KULLADI", $gecerli_uye_adi);
define("SAYFA_KORUMA", true);

//Son Giris Tarihinin Belirlenmesi
$son_giris_tarihi = false;
if (UYE_SEVIYE > 0)
{
  @ $son_giris_tarihi = $_SESSION['yeni_giris_yapildi'];
  if (empty($son_giris_tarihi))
  {
	  //Baska Bir Sayfadan Giriş Yapıldıysa Cerezler Bosaltiliyor
	  if ($gecerli_uye_online > date('Y-m-d H:i:s'))
		{
		  if (isset($_COOKIE['pehepe_kullanici'])) 
      {
        foreach ($_COOKIE['pehepe_kullanici'] AS $isim => $deger) 
        {
          setcookie ("pehepe_kullanici[$isim]", $deger, time() - 2592000); 
        }
      }
		}
    //IP Adresi Kaydediliyor
    $vt->query("UPDATE ".TABLO_ONEKI."uyeler SET ip='".UYE_IP."' WHERE uyeno=".UYE_NO.""); 
		
		
		//Songiris Tarihi Isleniyor
		$_SESSION['yeni_giris_yapildi'] = true;
    $vt->query("UPDATE ".TABLO_ONEKI."uyeler SET songiris=songiristarihi, songiristarihi=NOW(), girissayisi=girissayisi+1 WHERE uyeno=".UYE_NO."");
  }
	
}
unset($kuladi,$parola,$gecerli_uye_no,$gecerli_uye_seviye,$gecerli_uye_adi,$son_giris_tarihi,$gelen_kuladi,$gelen_parola);
if ($vt->kayitSay("SELECT COUNT(ip) FROM ".TABLO_ONEKI."ipengelle WHERE ip=TRIM('".UYE_IP."')") > 0)
{
  header('Location: '.SITE_ADRESI.'/ip.php?ip='.UYE_IP);
	exit;
}
?>