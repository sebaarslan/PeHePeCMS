<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;

$svt = new Baglanti();
//IP SAYAC
@ $sayac_artirildi = $_SESSION['sayac_artirildi'];

if ($sayac_artirildi < date('Y-m-d'))
{
  //Yeni Gunde Bugun Tekil ve Bugun Cogul Sifirlaniyor
  $svt->query2("UPDATE ".TABLO_ONEKI."sayac SET buguntekil=0,buguncogul=0,tarih=CURRENT_DATE() WHERE tarih<CURRENT_DATE() LIMIT 1");
  //Bugun Cogul ve Toplam Cogul Artiriliyor
	$svt->query2("UPDATE ".TABLO_ONEKI."sayac SET buguncogul=buguncogul+1,toplamcogul=toplamcogul+1 LIMIT 1"); 
  
	//Gelen Kisinin IP Adresi Kaytli mi Degil mi Kontrol Ediliyor...
  $svt->query("SELECT DATE_FORMAT(tarih,'%Y-%m-%d') AS iptarihi FROM ".TABLO_ONEKI."ipkontrol WHERE ip='".UYE_IP."'");
	$ipkayitli = $svt->numRows();
	
  if ($ipkayitli > 0)
  {
    //IP Kaytli ise Tarihin Bugun Olup Olmadigina Bakiliyor
    $ip_tarihi = $svt->fetchObject();
    $iptarihi  = $ip_tarihi->iptarihi;
		
    if ($iptarihi < date('Y-m-d'))
    {
      $svt->query2("UPDATE ".TABLO_ONEKI."sayac SET buguntekil=buguntekil+1,toplamtekil=toplamtekil+1 LIMIT 1"); 
      //Online IP Tarihi Guncelleniyor
      $svt->query2("UPDATE ".TABLO_ONEKI."ipkontrol SET tarih=NOW() WHERE ip='".UYE_IP."'");
    }
    unset($ip_tarihi,$iptarihi);
  } else {
    //IP Kayitli Degilse Kaydediliyor
    $svt->query2("INSERT INTO ".TABLO_ONEKI."ipkontrol (tarih,ip) VALUES (NOW(),'".UYE_IP."')");
    //Tekil ve Cogul Saya 1 Artiriliyor
    $svt->query2("UPDATE ".TABLO_ONEKI."sayac SET buguntekil=buguntekil+1,toplamtekil=toplamtekil+1 LIMIT 1");
  }
	$svt->freeResult();
  
  //Bu Gune Ait Kayit Yoksa Siliniyor
  $svt->query2("DELETE FROM ".TABLO_ONEKI."ipkontrol WHERE DATE_FORMAT(tarih,'%Y-%m-%d')<CURRENT_DATE()");

  unset($_SESSION['sayac_artirildi']);
	unset($ipkayitli);
  $_SESSION['sayac_artirildi'] = date('Y-m-d');
}

if (UYE_SEVIYE > 0)
{
  //Online IP Tarihi Guncelleniyor
  $svt->query2("UPDATE ".TABLO_ONEKI."ipkontrol SET tarih='".date('Y-m-d')." 00:00:00' WHERE ip='".UYE_IP."'");
	//Online IP Tarihi Guncelleniyor
  $svt->query2("UPDATE ".TABLO_ONEKI."uyeler SET onlinetarih=NOW() WHERE uyeno=".UYE_NO."");
} else {
  //Online IP Tarihi Guncelleniyor
  $svt->query2("UPDATE ".TABLO_ONEKI."ipkontrol SET tarih=NOW() WHERE ip='".UYE_IP."'");
}

//-------------------------------------------------------------------------------------------------------

//Sayac Verileri Aliniyor

//Toplam Gelen
$svt->query("SELECT buguntekil,toplamtekil,buguncogul,toplamcogul FROM ".TABLO_ONEKI."sayac WHERE 1"); 
$toplamgelen       = $svt->fetchObject();
define("BUGUN_TEKIL", $toplamgelen->buguntekil);
define("TOPLAM_TEKIL",$toplamgelen->toplamtekil);
define("BUGUN_COGUL", $toplamgelen->buguncogul);
define("TOPLAM_COGUL",$toplamgelen->toplamcogul);
$svt->freeResult();


//Toplam Uye

define("TOPLAM_UYE", $svt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE onay='E' AND yonay=5"));

//Toplam Online Kisi
define("ONLINE_MISAFIR", $svt->kayitSay("SELECT COUNT(ip) FROM ".TABLO_ONEKI."ipkontrol WHERE tarih>=DATE_SUB(NOW(), INTERVAL ".ONLINE_SURE." MINUTE)"));

//Toplam Online Uye
$svt->query("SELECT uyeno,uyeadi FROM ".TABLO_ONEKI."uyeler WHERE onay='E' AND yonay=5 AND onlinetarih>=DATE_SUB(NOW(), INTERVAL ".ONLINE_SURE." MINUTE)");
$online_uye_sayi = $svt->numRows();
define("ONLINE_UYE", $online_uye_sayi);
$onlineuye = '';
if ($online_uye_sayi > 0)
{
  while($online_uye = $svt->fetchObject())
  {
	  $uyeno  = $online_uye->uyeno;
    $uyeadi = $online_uye->uyeadi;

    if (UYE_SEVIYE >= UYE_GORME_IZIN)
    {
      $onlineuye .= '<a href="?sayfa=uye&uye='.$uyeno.'">'.$uyeadi.'</a>, ';
    } else {
		  if (UYE_SEVIYE >= OZEL_MESAJ_GONDERME_IZIN)
			$onlineuye .= '<a href="?sayfa=omgonder&uye='.$uyeadi.'">'.$uyeadi.'</a>, ';
			else
			$onlineuye .= $uyeadi.', ';
    }
  }
	unset($uyeno,$uyeadi);
} else {
  $onlineuye .= $dil['CevrimiciUyeYok'];
}
$svt->freeResult();
define("ONLINE_UYELER",$onlineuye);
//Bugun Kayit Olan Uyeler
$svt->query("SELECT uyeno,uyeadi FROM ".TABLO_ONEKI."uyeler WHERE onay='E' AND yonay=5 AND DATE(kayittarihi)=CURRENT_DATE()");
$bugunkayit_uye_sayi = $svt->numRows();
define("BUGUN_KAYIT_UYE", $bugunkayit_uye_sayi);
$bugun_kayit_uye = '';
if ($bugunkayit_uye_sayi > 0)
{
  while($bugunkayit_uye = $svt->fetchObject())
  {
	  $uyeno  = $bugunkayit_uye->uyeno;
    $uyeadi = $bugunkayit_uye->uyeadi;

    if (UYE_SEVIYE >= UYE_GORME_IZIN)
    {
      $bugun_kayit_uye .= '<a href="?sayfa=uye&uye='.$uyeno.'">'.$uyeadi.'</a>, ';
    } else {
		  if (UYE_SEVIYE >= OZEL_MESAJ_GONDERME_IZIN)
			$bugun_kayit_uye .= '<a href="?sayfa=omgonder&uye='.$uyeadi.'">'.$uyeadi.'</a>, ';
			else
			$bugun_kayit_uye .= $uyeadi.', ';
    }
  }
	unset($uyeno,$uyeadi);
} else {
  $bugun_kayit_uye .= $dil['KayitBulunamadi'];
}
$svt->freeResult();
define("BUGUN_KAYIT_UYELER",$bugun_kayit_uye);


$gms_vt = new Baglanti();
//OZELMESAJ SAYISI YUKLENIYOR
define("GELEN_MESAJ",$vt->kayitSay("SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."ozelmesaj WHERE kime=".UYE_NO."")); //Gelen Mesaj
  //OKUNMAYAN OZELMESAJ SAYISI YUKLENIYOR
define("OKUNMAYAN_MESAJ",$vt->kayitSay("SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."ozelmesaj WHERE kime=".UYE_NO." AND okundu='H'")); //Okunmayan Mesaj
$gms_vt->freeResult();
unset($gms_vt,$svt,$toplamgelen,$sayac_artirildi,$uyeadi,$online_uye_sayi);
//-------------------------------------------------------------------------------------------------------
?>