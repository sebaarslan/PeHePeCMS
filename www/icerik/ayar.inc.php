<?php
//////////////////////
//SITE AYARLARI
//////////////////////
//AYAR VERILERI
$ayar_vt = new Baglanti();
if (!$ayar_vt->tablo_kontrol(TABLO_ONEKI."yonetim"))
{
  echo '<a href="kurulum_install.php">Kurulum - Install</a>';
	exit;
}
$ayar_vt->query("SELECT `siteadi`,`siteadresi`,`siteeposta`,`sitedil`,`sitetema`,`girisdenemesayisi`,`girisdenemesuresi`,`kayitarasisure`,`uyelikonayi`,`uyesilmezamani`,`epostadegisti`,`yazikarakter`,`yazieklemeizin`,`yazikayitarasisure`,`yazionay`,`yaziduzenlemeizin`,`yaziduzenlemesuresi`,`yaziokumaizin`,`yaziayrintiokumaizin`,`yorumkarakter`,`yaziokumaizin`,`yaziayrintiokumaizin`,`yazioylama`,`yazikategorisira`,`yorumeklemeizin`,`yorumonay`,`yorumarasisure`,`ozelmesajgondermeizin`,`ozelmesajarasisure`,`ozelmesajkarakter`,`ozelmesajizin`,`ozelmesajisimizin`,`hizlimesajeklemeizin`,`hizlimesajonay`,`hizlimesajsure`,`hizlimesajkarakter`,`uyekayitkapat`,`hizlimesajonay`,`hizlimesajsure`,`galeriresimgormeizin`,`galeriresimeklemeizin`,`galeriresimkayitsure`,`galeriresimduzenizin`,`galeriresimduzensure`,`galeriresimonay`,`galeriresimoylama`,`galerialbumeklemeizin`,`galerialbumkayitsure`,`galerialbumeklemesayi`,`galerialbumduzenizin`,`galerialbumduzensure`,`galerialbumonay`,`sozcukeklemeizin`,`sozcukduzenlemeizin`,`sozcukonay`,`uyekayitkapat`,`uyegormeizin`,`uye1`,`uye2`,`uye3`,`uye4`,`uye5`,`uye6` FROM ".TABLO_ONEKI."yonetim LIMIT 1");

$siteayar = $ayar_vt->fetchObject(); 

//GENEL VERILER
define("SITE_ADI", $siteayar->siteadi); 
define("SITE_ADRESI", $siteayar->siteadresi);
define("SITE_EPOSTA", $siteayar->siteeposta);
define("YENILEME_SURESI", 60); //Saniye Olarak (Guncel Veriler Icin)
define("ONLINE_SURE", 1); //Dakika Olarak : IP Adreslerinin Online Gorunme Suresi
define("SAYFA_KLASOR","sayfa"); //Sayfalarin Bulundugu Klasor Adi
define("UYE_GORME_IZIN", $siteayar->uyegormeizin); //Uyelerin Baska Uyelerin Bilgilerini Gorme Ayarlari
define("SITE_DIL", $siteayar->sitedil);

//UYE KAYIT/GIRIS
define("UYE_IP", getenv("REMOTE_ADDR"));
define("GIRIS_DENEME_SAYISI", $siteayar->girisdenemesayisi);
define("GIRIS_DENEME_SURESI", $siteayar->girisdenemesuresi);
define("UYE_KAYIT_ARASI_SURE", $siteayar->kayitarasisure);
define("UYELIK_ONAYI", $siteayar->uyelikonayi);
define("UYE_SILME_ZAMANI", $siteayar->uyesilmezamani);
define("UYE_KAYIT_KAPAT", $siteayar->uyekayitkapat);
define("EPOSTA_DEGISTI", $siteayar->epostadegisti);
define("UYE_RESIM_DIZIN","uyeresim");
define("UYE_RESIM_EN",80);
define("UYE_RESIM_BOY",80);


//OZEL MESAJ
define("OZEL_MESAJ_IZIN", $siteayar->ozelmesajizin); //Gelen Klasorunde Kac Mesaj Bulundurabilsin
define("OZEL_MESAJ_KARAKTER", $siteayar->ozelmesajkarakter); //Ozel Mesaj Icin Izin Verilen Karakter
define("OZEL_MESAJ_ARASI_SURE",$siteayar->ozelmesajarasisure); //Iki Ozel Mesaj Arasi Sure
define("OZEL_MESAJ_GONDERME_IZIN",$siteayar->ozelmesajgondermeizin); //Hangi Seviye ve Ustu Uyeler OzelMesaj Gönderebilsin?
define("OZEL_MESAJ_ISIM_IZIN",$siteayar->ozelmesajisimizin); //Hangi Seviye ve Ustu Uyeler OzelMesaj Gönderirken İsimleri Görebilsin

//HIZLI MESAJ
define("HIZLI_MESAJ_ONAY", $siteayar->hizlimesajonay); //Hangi Seviye ve Ustu Uyelerin Hizli Mesajlari Onayli Olsun?
define("HIZLI_MESAJ_SURE", $siteayar->hizlimesajsure); //2 Hizli Mesaj Arasinda Kac Dakika Beklenilsin
define("HIZLI_MESAJ_KARAKTER", $siteayar->hizlimesajkarakter); //Hizli Mesaj Icin Kac Karakter Kullanilsin?
define("HIZLI_MESAJ_EKLEME_IZIN", $siteayar->hizlimesajeklemeizin); //Hangi Seviye ve Ustu Uyeler Hizli Mesaj Ekleyebilsin?

//YAZI-YORUM
define("RESIM_DIZIN", 'yaziresim'); //Yazi Resimlerinin Konulacagi Klasor
define("BOYUT_IZIN", 102400); //102400 bytes = 100KB. - Yazilara Eklenen Resimlerin Maksimum Boyutu
$yazi_resim_uzanti = array('image/gif'=>'gif','image/pjpeg'=>'jpg','image/jpeg'=>'jpg','image/png'=>'png','image/jpg'=>'jpg');
define("YAZI_RESIM_EN", 200); //Yazılara Eklenen Resimlerin Maksimum Gosterme Eni
define("YAZI_RESIM_BOY", 120); //Yazılara Eklenen Resimlerin Maksimum Gosterme Boyu
define("YAZI_RESIM_KAYIT_EN", 600); //Yazilara Eklenen Resimlerin Kayit Eni
define("YAZI_RESIM_KAYIT_BOY", 500); //Yazilara Eklenen Resimlerin Kayit Boyu


define("YAZI_EKLEME_IZIN", $siteayar->yazieklemeizin); //Hangi Seviye ve Uzeri Uyeler Yazi Ekleyebilsin? 1 ve 5 Arasi
define("YAZI_OKUMA_IZIN", $siteayar->yaziokumaizin); //Hangi Seviye ve Uzeri Uyeler Yazi Ozetini Okuyabilsin?
define("YAZI_AYRINTI_OKUMA_IZIN", $siteayar->yaziayrintiokumaizin); //
define("YAZI_BASLIK_KARAKTER", 100); //Yazi Basliklari Icin Izin Verilen Karakter
define("YAZI_KARAKTER", $siteayar->yazikarakter);
define("YAZI_ONAY", $siteayar->yazionay); //Hangi Seviye ve Uzeri Uyelerin Yazilari Hemen Onaylansin?
define("YAZI_DUZENLEME_IZIN", $siteayar->yaziduzenlemeizin); //Hangi Seviye ve Uzeri Uyeler Kendi Yazilarini Her Zaman Duzenleyebilsin
define("YAZI_DUZENLEME_SURESI", $siteayar->yaziduzenlemesuresi); //Kac Saat Icinde Uye Yazdigi Yaziyi Duzenleyebilsin?
define("YAZI_KAYIT_ARASI_SURE", $siteayar->yazikayitarasisure); //Iki Yazi Arasindaki Sure . Dakika Olarak
define("YAZI_OYLAMA",$siteayar->yazioylama); // Yazilara Oy Verme

define("YAZI_KATEGORI_SIRA",$siteayar->yazikategorisira); //Yazi Kategorilerinin Hangi Kritere Göre Siralanacagini Belirler

define("YORUM_EKLEME_IZIN", $siteayar->yorumeklemeizin); //Hangi Seviye ve Uzeri Uyeler Yorum Ekleyebilsin
define("YORUM_ARASI_SURE", $siteayar->yorumarasisure); //Iki Yorum Arasindaki Sure. Dakika Olarak
define("YORUM_ONAY", $siteayar->yorumonay); //Hangi Seviye ve Uzeri Uyelerin Yorumlari Hemen Onayli Olsun
define("YORUM_KARAKTER", $siteayar->yorumkarakter); //Yazilara Eklenen Yorum Karakter Sayisi

//RESIM GALERISI
define("GALERI_ALBUM_DIZIN", 'album'); //Resimlerin Koyulacagi Album Klasoru
define("GALERI_RESIM_GORME_IZIN", $siteayar->galeriresimgormeizin); //Hangi Seviye ve Uzeri Uyeler Resimlere Bakabilsin?
define("GALERI_RESIM_EN", 800);
define("GALERI_RESIM_BOY", 600);
define("GALERI_ALBUM_EN", 200);
define("GALERI_ALBUM_BOY", 110);
define("GALERI_RESIM_OYLAMA", $siteayar->galeriresimoylama);

define("GALERI_RESIM_EKLEME_IZIN",$siteayar->galeriresimeklemeizin); //Hangi Seviye ve Uzeri Uyeler Resim Ekleyebilsin?
define("GALERI_RESIM_DUZEN_IZIN",$siteayar->galeriresimduzenizin); //Hangi Seviye ve Uzeri Uyeler Resimleri Duzenleyebilsin?
define("GALERI_RESIM_KAYIT_SURE",$siteayar->galeriresimkayitsure); //Iki Resim Arasindaki Sure - Dakika Olarak
define("GALERI_RESIM_DUZEN_SURE",$siteayar->galeriresimduzensure); //Kac Saat Icinde Uye Ekledigi Resmi Düzenleyebilsin? 
define("GALERI_RESIM_ONAY", $siteayar->galeriresimonay); //Hangi Seviye ve Uzeri Uyelerin Ekledigi Resimler Onayli Olsun
define("GALERI_RESIM_AD_KARAKTER", 50);
define("GALERI_RESIM_ACIKLAMA_KARAKTER",150);

define("GALERI_ALBUM_EKLEME_IZIN", $siteayar->galerialbumeklemeizin);
define("GALERI_ALBUM_DUZEN_IZIN",$siteayar->galerialbumduzenizin);
define("GALERI_ALBUM_KAYIT_SURE",$siteayar->galerialbumkayitsure);
define("GALERI_ALBUM_DUZEN_SURE",$siteayar->galerialbumduzensure); //Kac Saat Icinde Uye Ekledigi Albumu Duzenleyebilsin?
define("GALERI_ALBUM_EKLEME_SAYI",$siteayar->galerialbumeklemesayi); //Izin Verilen Uye Kac Tane Album Ekleyebilsin?
define("GALERI_ALBUM_ONAY", $siteayar->galerialbumonay); //Hangi Seviye ve Uzeri Uyelerin Ekledigi Albumler Onayli Olsun
define("GALERI_ALBUM_AD_KARAKTER", 50);
define("GALERI_ALBUM_ACIKLAMA_KARAKTER",150);

define("TEMA_KLASOR",'tema'); //Tema Klasoru
@$secilen_tema = trim(htmlspecialchars($_GET['tema']));

if (!$secilen_tema)
{
  @$oturum_tema = trim(htmlspecialchars($_SESSION['tema']));
	if (!$oturum_tema)
	$site_tema = $siteayar->sitetema;
	else
	$site_tema = $oturum_tema;

} else {
  $_SESSION['tema'] = $secilen_tema;
	$site_tema = $secilen_tema;
}

define("SITE_TEMA",TEMA_KLASOR.'/'.$site_tema);

//SOZLUK
define("SOZCUK_EKLEME_IZIN",$siteayar->sozcukeklemeizin);
define("SOZLUK_GORME_IZIN", 0);
define("SOZCUK_DUZENLEME_IZIN", $siteayar->sozcukduzenlemeizin);
define("SOZCUK_ONAY",$siteayar->sozcukonay);

//FTP
//Resim Album Klasoru Olusturmak Icin FTP yi Kullanabilirsiniz
//Kullanmak Istemiyorsaniz Bos Birakabilirsiniz
//Bunun Icin Sunucunuzda ftp fonksiyonlari aktif Olmalidir

define("FTP_SERVER", ''); //Ftp Server ya da Adres
define("FTP_KULLANICI_ADI",''); //Ftp Kullanici Adi
define("FTP_KULLANICI_SIFRE",''); //Ftp Sifresi
define("FTP_YOL","/"); //Uyelik Sistemini Kurdugunuz Klasor. Ana Dizine Kurduysanız / İşareti Yeterli

define("SMTP_SUNUCU", ''); //mail.alanadi.com
define("SMTP_KULLANICI", ''); //mail@alanadi.com
define("SMTP_SIFRE", ''); //sifre

//UYE SEVIYE ADLARI
$seviyeler    = array();
$seviyeler[1] = $siteayar->uye1;
$seviyeler[2] = $siteayar->uye2;
$seviyeler[3] = $siteayar->uye3;
$seviyeler[4] = $siteayar->uye4;
$seviyeler[5] = $siteayar->uye5;
$seviyeler[6] = $siteayar->uye6;

$ayar_vt->freeResult();
unset($site_ayar,$ayar_vt);
?>