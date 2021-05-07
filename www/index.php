<?php
/*======================================================================*\
|| #################################################################### ||
|| # PeHePe Uyelik Sistemi                                            # ||
|| # ---------------------------------------------------------------- # ||
|| # Anasayfa                                                         # ||
|| # ---------------------------------------------------------------- # ||
|| #                                                                  # ||
|| #################################################################### ||
\*======================================================================*/
require_once('genel.php');
@ $sayfa = trim(preg_replace('([^a-z0-9])is','',strtolower(htmlspecialchars(strip_tags($_GET['sayfa'])))));
@ $hata  = intval($_GET['hata']);
@ $mesaj = intval($_GET['mesaj']);
@ $islem = intval($_GET['islem']);

include_once("icerik/tem.inc.php");

// Template Sinifi Olusturuluyor
$tema = new template("."); 

$tema->set_file('FileRef', SITE_TEMA.'/index.tpl');
$tema->set_var(array("SITE_TEMA" => SITE_TEMA));


//===========================================================
$tema->set_block("FileRef", "DIL_BLOK", "dil_blok");
//Dil Değiştirme Resimleri
foreach($dil_ayar AS $dilanahtar=>$dildeger)
{
  if ($dilanahtar != $site_dil)
  {
    $tema->set_var(array("DIL_RESIM"=>$dildeger[2],"DIL_ANAHTAR"=>$dilanahtar,"DIL_ISIM"=>$dildeger[1]));
		$tema->parse("dil_blok","DIL_BLOK",true); 
  }
}
unset($dilanahtar,$dildeger);

//============================================================================================
//VERITABANINDAN MENULER CEKILIYOR
//============================================================================================
//MENU -1- BLOK BASLANGICI
//============================================================================================
$tema->set_block("FileRef", "MENU1_BLOK", "menu1"); //Ust Menu Blogu Olusturuluyor
$rsvt = new Baglanti();
$toplam_resim_sayisi = $rsvt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE onay='E'");
unset($rsvt);
//Menu1 Ust Menu
$menu1_vt = new Baglanti();
$sayfa_baslik = '';
$menu_dizi = array();

$menu1_vt->query("SELECT menugrup,menuanahtar,menuresim,menuadi,menudil,menusayfaadi,menusayfadil,menuadres,menuait,menuhedef,menuizin FROM ".TABLO_ONEKI."menuler WHERE menudurum='A' AND (menugrup=1 OR menugrup=0) ORDER BY menu1sira ASC");
if($menu1_vt->numRows()>0)
{
  while($menu1_veri = $menu1_vt->fetchObject())
  {
    $menu1_grup     = $menu1_veri->menugrup;
    $menu1_anahtar  = $menu1_veri->menuanahtar;
		$menu1_resim    = $menu1_veri->menuresim;
	  $menu1_adi      = $menu1_veri->menuadi;
	  $menu1_dil      = $menu1_veri->menudil;
	  if ($menu1_dil=='E')
	  @$menu1_adi      = $dil[$menu1_adi];
    
	  $menu1_sayfaadi = $menu1_veri->menusayfaadi;
	  $menu1_sayfadil = $menu1_veri->menusayfadil;
	  if ($menu1_sayfadil=='E')
	  @$menu1_sayfaadi = $dil[$menu1_sayfaadi];

	
    $menu1_adres    = $menu1_veri->menuadres;
	  $menu1_ait      = $menu1_veri->menuait;
		//menuait Sayfanin index.php Altinda Acilacagini Gosterir
		if ($menu1_ait==1)
		$menu_dizi[$menu1_anahtar] = $menu1_adres;
	  
		if ($menu1_ait==1)
	  $menu1_adres = '?sayfa='.$menu1_anahtar;
	  else
	  $menu1_adres = $menu1_adres;
	
	  $menu1_hedef = $menu1_veri->menuhedef;
		
		$menu1_izin  = $menu1_veri->menuizin;	
		
		//Acilan Sayfa Ile Menu Anahtari Tutuyorsa Sayfa Adini Al
		if ($sayfa==$menu1_anahtar)
		$sayfa_baslik = $menu1_sayfaadi;
		
		

			
    if ($menu1_izin==-1)
		{
		  if (UYE_SEVIYE==0)
			$menu1_goster = 1;
			else
			$menu1_goster = 0;
		} elseif ($menu1_izin==0) {
		  $menu1_goster = 1;
		} else {
		  if (UYE_SEVIYE>=$menu1_izin) 
			{
			  $menu1_goster = 1;
			} else {
			  $menu1_goster = 0;
			}
		}
    $tema->set_block("FileRef", "MENU1_".strtoupper($menu1_anahtar)."_BLOK", "menu1_".strtolower($menu1_anahtar));
		if ($menu1_goster==1)
		{
		  
		  $tema->set_var(array('MENU1_ANAHTAR_'.strtoupper($menu1_anahtar)=>$menu1_anahtar,
		  'MENU1_RESIM_'.strtoupper($menu1_anahtar)=>$menu1_resim,
		  'MENU1_ADRES_'.strtoupper($menu1_anahtar)=>$menu1_adres,
		  'MENU1_ADI_'.strtoupper($menu1_anahtar)=>$menu1_adi,
		  'MENU1_HEDEF_'.strtoupper($menu1_hedef)=>$menu1_hedef));
		  $tema->parse("menu1_".strtolower($menu1_anahtar), "MENU1_".strtoupper($menu1_anahtar)."_BLOK", true);
		
		  $tema->set_var(array("MENU1_ADRES"=>$menu1_adres, "MENU1_ADI"=>$menu1_adi, "MENU1_ANAHTAR"=>$menu1_anahtar, "MENU1_RESIM"=>$menu1_resim, "MENU1_HEDEF"=>$menu1_hedef)); 
			$tema->parse("menu1", "MENU1_BLOK", true);
		} else {
		  $tema->set_var("menu1_".strtolower($menu1_anahtar),"");
		}
  }
	unset($menu1_grup,$menu1_anahtar,$menu1_resim,$menu1_adi,$menu1_dil,$menu1_sayfaadi,$menu1_sayfadil,$menu1_adres,$menu1_ait,$menu1_hedef);
} else {
  $tema->set_var("menu1","");
}
unset($menu1_vt);
//============================================================================================
//MENU -1- BLOK SONU
//========================================================
//MENU -2- BLOK BASLANGICI
//============================================================================================
$tema->set_block("FileRef", "MENU2_BLOK", "menu2"); //Alt Menu Blogu Olusturuluyor
$tema->set_block("FileRef", "SAYFALAR_BLOK", "sayfalar");
$tema->set_var(array("SAYFALAR_BASLIK"=>$dil['Sayfalar']));

//Menu2 Alt Menu (Sayfalar)
$menu2_vt = new Baglanti();
$menu2_vt->query("SELECT menugrup,menuanahtar,menuresim,menuadi,menudil,menusayfaadi,menusayfadil,menuadres,menuait,menuhedef,menuizin FROM ".TABLO_ONEKI."menuler WHERE menudurum='A' AND (menugrup=2 OR menugrup=0) ORDER BY menu2sira ASC");
if($menu2_vt->numRows()>0)
{
  while($menu2_veri = $menu2_vt->fetchObject())
  {
    $menu2_grup     = $menu2_veri->menugrup;
    $menu2_anahtar  = $menu2_veri->menuanahtar;
		$menu2_resim    = $menu2_veri->menuresim;
	  $menu2_adi      = $menu2_veri->menuadi;
	  $menu2_dil      = $menu2_veri->menudil;
	  if ($menu2_dil=='E')
	  $menu2_adi      = $dil[$menu2_adi];
    
	  $menu2_sayfaadi = $menu2_veri->menusayfaadi;
	  $menu2_sayfadil = $menu2_veri->menusayfadil;
	  if ($menu2_sayfadil=='E')
	  $menu2_sayfaadi = $dil[$menu2_sayfaadi];
	  $menu2_ait      = $menu2_veri->menuait;
    
		$menu2_adres    = $menu2_veri->menuadres;
		//menuait Sayfanin index.php Altinda Acilacagini Gosterir
		if ($menu2_ait==1)
		$menu_dizi[$menu2_anahtar] = $menu2_adres;
	  
	  if ($menu2_ait==1)
	  $menu2_adres = '?sayfa='.$menu2_anahtar;
	  else
	  $menu2_adres = $menu2_adres;
	
	  $menu2_hedef = $menu2_veri->menuhedef;	
		$menu2_izin  = $menu2_veri->menuizin;

    if ($sayfa==$menu2_anahtar)
		$sayfa_baslik = $menu2_sayfaadi;
		
		
		
		if ($menu2_izin==-1)
		{
		  if (UYE_SEVIYE==0)
			$menu2_goster = 1;
			else
			$menu2_goster = 0;
		} elseif ($menu2_izin==0) {
		  $menu2_goster = 1;
		} else {
		  if (UYE_SEVIYE>=$menu2_izin) 
			{
			  $menu2_goster = 1;
			} else {
			  $menu2_goster = 0;
			}
		}
		
		if ($menu2_goster==1)
		{
		  $tema->set_block("FileRef", "MENU2_".strtoupper($menu2_anahtar)."_BLOK", "menu2_".strtolower($menu2_anahtar));
		  $tema->set_var(array('MENU2_ANAHTAR_'.strtoupper($menu2_anahtar)=>$menu2_anahtar,
		'MENU2_RESIM_'.strtoupper($menu2_anahtar)=>$menu2_resim,
		'MENU2_ADRES_'.strtoupper($menu2_anahtar)=>$menu2_adres,
		'MENU2_ADI_'.strtoupper($menu2_anahtar)=>$menu2_adi,
		'MENU2_HEDEF_'.strtoupper($menu2_hedef)=>$menu2_hedef));
		$tema->parse("menu2_".strtolower($menu2_anahtar),"MENU2_".strtolower($menu2_anahtar)."BLOK",true);
		
      $tema->set_var(array("MENU2_ADRES"=>$menu2_adres, "MENU2_ADI"=>$menu2_adi, "MENU2_ANAHTAR"=>$menu2_anahtar, "MENU2_RESIM"=>$menu2_resim, "MENU2_HEDEF"=>$menu2_hedef));
      $tema->parse("menu2", "MENU2_BLOK", true);
	  } else {
		  $tema->parse("menu2_".strtolower($menu2_anahtar),"");
		}
  }

	unset($menu2_grup,$menu2_anahtar,$menu2_resim,$menu2_adi,$menu2_dil,$menu2_sayfaadi,$menu2_sayfadil,$menu2_adres,$menu2_ait,$menu2_hedef);
} else {
  $tema->set_var("menu2","");
}
unset($menu2_vt);
$tema->parse("sayfalar","SAYFALAR_BLOK",true);
//=============================================================================================================================
//MENU 2 BLOK SONU
//=============================================================================================================================
//Sayfa TITLE, KEYWORDS, DESCRIPTION Belirleniyor
$sayfa_bilgi = $fonk->sayfa_bilgi($sayfa,$sayfa_baslik,intval(@$_GET['yazino']),intval(@$_GET['kategori']),intval(@$_GET['album']),intval(@$_GET['resimno'])); 
$tema->set_var(array("TITLE" => $sayfa_bilgi['baslik'],"KEYWORDS"=>$sayfa_bilgi['keywords'],"DESCRIPTION"=>$sayfa_bilgi['description']));
//=============================================================================================================================
//Ajax JavaScript Fonksiyon Baslangici
ob_start();
sajax_show_javascript();
$sajax = ob_get_contents();
ob_end_clean();

$tema->set_var(array("SAJAX_SHOW_JAVASCRIPT"=>$sajax)); //AJAX JavaScript
//Ajax JavaScript Fonksiyon Sonu
$tema->set_var(array("RAKAM_KULLANINIZ"=>$dil['RakamKullaniniz']));
$tema->set_var(array("SECENEK_OY_IZIN"=>$fonk->yerine_koy($dil['SecenekOyIzin'],"'+secimizin+'")));
$tema->set_var(array("KARAKTER_SAYI_KONTROL"=>$dil['KarakterSayiKontrol']));
$tema->set_var(array("YENILEME_SURESI"=>YENILEME_SURESI*1000));
$tema->set_var(array("SITE_ADI"=>SITE_ADI,"SITE_ADRES"=>SITE_ADRESI));

//============================================================================================
//UYE ISLEM BLOK BASLANGICI
//============================================================================================
$tema->set_block("FileRef", "UYE_ISLEM_BLOK", "uye_islem");
$tema->set_var(array("UYE_ISLEM_BASLIK"=>$dil['UyeIslemleri']));
require_once('sayfa/giris_form.php');
//Uye Girisi Yapildiysa
if ($sayfa=='giris')
{
//Eger Uye Islem Orta Sayfada Aciliyorsa Yanda Goruntulenmiyor
$tema->parse("uye_islem","");
} else {

if (UYE_SEVIYE > 0)
{
 
  $tema->set_var(array("UYE_ISLEM" => uyeMenu(UYE_KULLADI)));
} else {
  $tema->set_var(array("UYE_ISLEM" => girisForm()));
}
$tema->parse("uye_islem", "UYE_ISLEM_BLOK", true);
}

//===========================================================================================
//UYE ISLEM BLOK SONU
//===========================================================================================
//YAZILAR BLOK BASLANGICI
//===========================================================================================
$tema->set_block("FileRef", "YAZILAR_KONULAR_BLOK", "konular");
$tema->set_block("FileRef", "YAZILAR_BLOK", "yazilar");

//Yazilar
if(KATEGORI_SAYI > 0)
{
  $yazilar_baslik = $dil['Kategoriler'];
} else {
  $yazilar_baslik = $dil['SonYazilar'];
}
$tema->set_var(array("YAZILAR_BASLIK"=>$yazilar_baslik));

//Yazilar
if (KATEGORI_SAYI > 0)
{
  $kategoriler_dizi = $fonk->kategoriListe(0,0,YAZI_KATEGORI_SIRA);
	$tema->set_var(array("KONU_ADRES"=>'?sayfa=yazi&kategori=0',"KONU_ISIM"=>$dil['ButunKonular']));
	$tema->parse("konular", "YAZILAR_KONULAR_BLOK", true);
  for ($i=0; $i<count($kategoriler_dizi); $i++) 
  { 
    $kategorino = $kategoriler_dizi[$i][0];
    $kategoriadi = $kategoriler_dizi[$i][1];
		
    $level = $kategoriler_dizi[$i][2];
    
		$kategori_adi = '';
		for ($j=0;$j<$level;$j++) 
		$kategori_adi = '&nbsp;&nbsp;'; //Alt Kategorileri Iceri Kaydirma Bosluklari 
    $kategori_adi .=  substr($kategoriadi,0,(25-$level)).' ('.$fonk->yazi_sayi($kategorino).'<label id="k'.$kategorino.'"></label>)';
		$tema->set_var(array("KONU_ADRES"=>'?sayfa=yazi&kategori='.$kategorino,"KONU_ISIM"=>$kategori_adi));
		$tema->parse("konular", "YAZILAR_KONULAR_BLOK", true);
  } 

  unset($kategoriler_dizi,$kategorino,$kategoriadi,$level);
} else {
  $vt->query("SELECT yazino,baslik FROM ".TABLO_ONEKI."yazilar WHERE onay='E' ORDER BY eklemetarihi DESC LIMIT 0,10");
  $yazisayi = $vt->numRows();
  if ($yazisayi > 0)
  {
    while ($yaziveri = $vt->fetchArray())
    {
      $yazino = $yaziveri['yazino'];
      $yazibaslik = $yaziveri['baslik'];
			$tema->set_var(array("KONU_ADRES"=>'?sayfa=yazi&yazino='.$yazino,"KONU_ISIM"=>substr($yazibaslik,0,25)));
		  $tema->parse("konular", "YAZILAR_KONULAR_BLOK", true);
    }
    unset($yazino,$yazibaslik);
  } else {
	  $tema->set_var(array("KONU_ADRES"=>'',"KONU_ISIM"=>$dil['KayitBulunamadi']));
    $tema->parse("konular", "YAZILAR_KONULAR_BLOK", true);
  }
}
$tema->parse("yazilar", "YAZILAR_BLOK", true);

//===========================================================================================
//YAZILAR BLOK SONU
//===========================================================================================
//HIZLI MESAJ BLOK BASLANGICI
//===========================================================================================
require_once(SAYFA_KLASOR.'/hizli_mesaj.php');
$tema->set_block("FileRef", "HIZLI_MESAJ_BLOK", "hizli_mesaj");
$tema->set_var(array("HIZLI_MESAJ_BASLIK"=>$dil['HizliMesaj']));
if (UYE_SEVIYE > 0)
{
  if (UYE_SEVIYE < HIZLI_MESAJ_EKLEME_IZIN)
  {
    $tema->set_var(array('HIZLI_MESAJ_EKLEME_IZIN'=>'<span class="fonthata">'.$fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[HIZLI_MESAJ_EKLEME_IZIN]).'</span>'));
  } else {
    $tema->set_var(array('HIZLI_MESAJ_EKLEME_IZIN'=>'<input type="text" id="hmesaj" name="hmesaj" class="input" size="25" maxlength="'.HIZLI_MESAJ_KARAKTER.'" /><br /><input type="submit" name="hmKayit" id="hmKayit"  value="'.$dil['Gonder'].'" />')); 
  }  
} else {
  $tema->set_var(array('HIZLI_MESAJ_EKLEME_IZIN'=>$dil['GirisYapiniz']));
} 
$tema->set_var(array('HIZLI_MESAJ_YAZDIR'=>hizliMesaj()));
$tema->parse('hizli_mesaj',"HIZLI_MESAJ_BLOK",true);
//===========================================================================================
//HIZLI MESAJ BLOK SONU
//===========================================================================================
//EN COK OKUNAN YAZILAR BLOK BASLANGICI
//===========================================================================================
$tema->set_block("FileRef", "COK_OKUNAN_YAZILAR_ALT_BLOK", "coy_alt");
$tema->set_block("FileRef", "COK_OKUNAN_YAZILAR_ANA_BLOK", "coy_ana");

$tema->set_var(array("COK_OKUNAN_YAZILAR"=>$dil['EnCokOkunanlar']));
$co_vt = new Baglanti();
$co_vt->query("SELECT yazino,baslik,okunma FROM ".TABLO_ONEKI."yazilar WHERE onay='E' ORDER BY okunma DESC,eklemetarihi DESC LIMIT 0,10");
$co_yazisayi = $co_vt->numRows();
if ($co_yazisayi > 0)
{
  while ($co_yaziveri = $co_vt->fetchArray())
  {
    $co_yazino     = $co_yaziveri['yazino'];
    $co_yazibaslik = $fonk->yazdir_duzen($co_yaziveri['baslik']);
		$co_okunma     = intval($co_yaziveri['okunma']);
		$tema->set_var(array("CO_YAZILAR_ADRES"=>'?sayfa=yazi&yazino='.$co_yazino.'&islem=2',"CO_YAZILAR_ISIM"=>substr($co_yazibaslik,0,25),"CO_YAZILAR_OKUNMA"=>$co_okunma));
		$tema->parse("coy_alt", "COK_OKUNAN_YAZILAR_ALT_BLOK", true);
  }
  unset($co_yazino,$co_yazibaslik,$co_vt);
	$tema->parse("coy_ana", "COK_OKUNAN_YAZILAR_ANA_BLOK", true);
} else {
	$tema->parse("coy_ana","");
}

//===========================================================================================
//EN COK OKUNAN YAZILAR BLOK SONU
//===========================================================================================
//EKLENEN SON RESIMLER BLOK BASLANGICI
//===========================================================================================
$tema->set_block("FileRef", "SON_EKLENEN_RESIMLER_KAYITYOK_BLOK", "ser_kayityok");
$tema->set_block("FileRef", "SON_EKLENEN_RESIMLER_ALT_BLOK", "ser_alt");
$tema->set_block("FileRef", "SON_EKLENEN_RESIMLER_ANA_BLOK", "ser_ana");

$tema->set_var(array("SON_EKLENEN_RESIMLER_DIL"=>$dil['ResimGalerisinden']));

$srvt = new Baglanti();
$srvt->query("SELECT resimno,albumno,resim,resimadi,aciklama FROM ".TABLO_ONEKI."resim WHERE onay='E' ORDER BY tarih DESC LIMIT 5");
if ($srvt->numRows()>0)
{
while ($r_resimveri = $srvt->fetchObject())
{
  $r_resimno = $r_resimveri->resimno;
  $r_resim   = $r_resimveri->resim;
  $r_resimadi = $r_resimveri->resimadi;
  $r_aciklama = $r_resimveri->aciklama;
  $r_albumno  = $r_resimveri->albumno;
  $res_resim = GALERI_ALBUM_DIZIN.'/album_'.$r_albumno.'/'.$r_resim;
  if (!file_exists($res_resim) || !$r_resim)
  $res_resim = GALERI_ALBUM_DIZIN.'/bos.gif';
	$tema->set_var(array("RESIM_NO"=>$r_resimno,"RESIM"=>$res_resim,"RESIM_ADI"=>$r_resimadi));
	$tema->parse("ser_alt", "SON_EKLENEN_RESIMLER_ALT_BLOK", true);
}
$tema->parse("ser_kayityok","");
} else {
  $tema->parse("ser_alt","");
	$tema->parse("ser_kayityok","SON_EKLENEN_RESIMLER_KAYITYOK_BLOK",true);
  $tema->set_var(array("KAYIT_YOK"=>$dil['KayitBulunamadi']));
}
$tema->parse("ser_ana", "SON_EKLENEN_RESIMLER_ANA_BLOK", true);
//UYELER DOGUM GUNU BLOK BASLANGICI
//===========================================================================================
$tema->set_block("FileRef", "UYELER_DOGUM_GUNU_BLOK", "uyeler_dogum_gunu");
$tema->set_var(array("UYELER_DOGUM_GUNU_BASLIK"=>$dil['UyeDogumGunu']));
$dg_vt = new Baglanti();
$dg_vt->query("SELECT uyeno,uyeadi FROM ".TABLO_ONEKI."uyeler WHERE DAY(dogumtarihi)='".date('d')."' AND MONTH(dogumtarihi)='".date('m')."' AND onay='E' AND yonay=5 ORDER BY uyeadi ASC");
$dogum_gunu = '';
if ($dg_vt->numRows()>0)
{
  while ($dg_veri = $dg_vt->fetchObject())
  {
    $dg_uyeadi = $dg_veri->uyeadi;
    $dg_uyeno  = $dg_veri->uyeno;
    if (UYE_SEVIYE >= UYE_GORME_IZIN)
    $dogum_gunu .= '<a href="?sayfa=uye&uye='.$dg_uyeno.'">'.$dg_uyeadi.'</a>, ';
    else
    $dogum_gunu .= ''.$dg_uyeadi.', ';
  }
  unset($dg_uyeadi,$dg_veri);
} else {
  $dogum_gunu .= $dil['KayitBulunamadi'];
}
$tema->set_var(array('UYELER_DOGUM_GUNU'=>$dogum_gunu));
$tema->parse('uyeler_dogum_gunu','UYELER_DOGUM_GUNU_BLOK',true);
unset($dg_vt);
//===========================================================================================
//UYELER DOGUM GUNU BLOK SONU
//===========================================================================================
//ISTATISTIKLER BLOK BASLANGICI 
//===========================================================================================
$tema->set_block("FileRef", "ISTATISTIK_BLOK", "istatistik");
$tema->set_var(array(
"ISTATISTIKLER"=>$dil['Istatistikler'],
"BUGUN_TEKIL"=>$dil['BugunTekil'],
"BUGUN_TEKIL_YAZDIR"=>BUGUN_TEKIL,
"TOPLAM_TEKIL"=>$dil['ToplamTekil'],
"TOPLAM_TEKIL_YAZDIR"=>TOPLAM_TEKIL,
"BUGUN_COGUL"=>$dil['BugunCogul'],
"BUGUN_COGUL_YAZDIR"=>BUGUN_COGUL,
"TOPLAM_COGUL"=>$dil['ToplamCogul'],
"TOPLAM_COGUL_YAZDIR"=>TOPLAM_COGUL,
"KAYITLI_UYE"=>$dil['KayitliUye'],
"KAYITLI_UYE_YAZDIR"=>TOPLAM_UYE,
"ONLINE_MISAFIR"=>$dil['CevrimiciMisafir'],
"ONLINE_MISAFIR_YAZDIR"=>ONLINE_MISAFIR,
"ONLINE_UYE"=>$dil['CevrimiciUyeler'],
"ONLINE_UYE_YAZDIR"=>ONLINE_UYE,
"AKTIF_UYELER"=>$dil['SuUyelerCevrimici'],
"AKTIF_UYELER_YAZDIR"=>ONLINE_UYELER,
"BUGUN_KAYIT"=>$dil['BugunKayit'],
"BUGUN_KAYIT_YAZDIR"=>BUGUN_KAYIT_UYE,
"BUGUN_KAYIT_OLAN_UYELER"=>$dil['BugunKayitOlanUyeler'],
"BUGUN_KAYIT_OLAN_UYELER_YAZDIR"=>BUGUN_KAYIT_UYELER));
$tema->parse('istatistik','ISTATISTIK_BLOK',true);
//===========================================================================================
//ISTATISTIKLER BLOK SONU
//===========================================================================================
//BAGLANTILAR BLOK BASLANGICI
//===========================================================================================
$tema->set_block("FileRef", "BAGLANTILAR_ALT_BLOK", "bag_alt");
$tema->set_block("FileRef", "BAGLANTILAR_ANA_BLOK", "bag_ana");
$tema->set_var(array("BAGLANTILAR_BASLIK"=>$dil['Baglantilar']));
$b_vt = new Baglanti();
$b_vt->query("SELECT baglantiadres,baglantiadi,baglantihedef FROM ".TABLO_ONEKI."baglantilar WHERE baglantionay='E'");
if ($b_vt->numRows()>0)
{
  while ($b_veri = $b_vt->fetchObject())
	{
	  $baglantiadres = $b_veri->baglantiadres;
	  $baglantiadi = $b_veri->baglantiadi;
		if (empty($baglantiadi))
		$baglantiadi = $baglantiadres;
		$baglantiadi_kisa = substr($baglantiadi,0,50);
		
    $tema->set_var(array("BAGLANTI_ADRES"=>$baglantiadres,"BAGLANTI_ADI_KISA"=>$baglantiadi_kisa,"BAGLANTI_ADI_UZUN"=>$baglantiadi,"BAGLANTI_HEDEF"=>$b_veri->baglantihedef));
		$tema->parse("bag_alt","BAGLANTILAR_ALT_BLOK",true);
	}
	$tema->parse("bag_ana","BAGLANTILAR_ANA_BLOK",true);
	unset($b_veri);
} else {
  $tema->parse("bagana","");
}
unset($b_vt);
//===========================================================================================
//BAGLANTILAR BLOK SONU
//===========================================================================================
//ORTA ALAN BASLANGICI
//===========================================================================================
ob_start();
switch ($sayfa)
{
  case 'giris';
  if (UYE_SEVIYE > 0)
  {
    header('Location: index.php');
  } else {
    echo '<br />';
	  echo girisForm();
		
		if ($hata)
		{
    switch ($hata)
      {
        case 1: $hatamesaj=$dil["BosAlanBirakmayiniz"];break;
        case 2: $hatamesaj=$dil["KullaniciAdiGecersiz"];break;
        case 3: $hatamesaj=$dil["SifreGecersiz"];break;
        case 4: $hatamesaj=$fonk->yerine_koy($dil['GirisDenemeSuresiKadarBekleyiniz'],array(GIRIS_DENEME_SAYISI,GIRIS_DENEME_SURESI));break;
        case 5: $hatamesaj=$dil['BuKullaniciAdiylaGirisYapilmis'];break;
        case 6: $hatamesaj=$dil['UyeliginizOnayBekliyor'];break;
        case 7: $hatamesaj=$dil['UyeliginizYoneticiOnayiBekliyor'];break;
        case 8: $hatamesaj=$dil['UyeliginizOnayli'].' '.$dil['UyeliginizGeciciSureAskiyaAlinmistir'];break;
        case 9: $hatamesaj=$dil['KuladiVeyaSifreYanlis'];break;
        case 15: $hatamesaj=$dil['IslemIcinGirisGerekli'];break;
      }
      echo $fonk->hata_mesaj($hatamesaj);
    }
  }
  break;
	
  case 'hmesaj';
  require_once(SAYFA_KLASOR.'/hizlimesaj_kayit.php');
  break;
	case 'omgelen';
  require_once(SAYFA_KLASOR.'/gelen_mesaj.php');
  break;
	case 'omgiden';
  require_once(SAYFA_KLASOR.'/giden_mesaj.php');
  break;
  case 'omgonder';//omgonder
  require_once(SAYFA_KLASOR.'/mesaj_yaz.php');
  break;
  case 'onay';
  require_once(SAYFA_KLASOR.'/onay.php');
  break;
	case 'sifre';
  require_once(SAYFA_KLASOR.'/sifre_form.php');
  break;
	case 'profil'; //profil
  require_once(SAYFA_KLASOR.'/profil_form.php');
  break;

	case $sayfa;
	if (empty($sayfa) || !array_key_exists($sayfa,$menu_dizi))
	{
	  require_once(SAYFA_KLASOR.'/ana.php');
	} else {
	  if (!is_file($menu_dizi[$sayfa]))
    echo $fonk->hata_mesaj($dil['SayfaBulunamadi']);
    else
    require_once($menu_dizi[$sayfa]);
  }
	break;
  
  default;
  require_once(SAFYA_KLASOR.'/ana.php');
  break;
}

$orta_alan = ob_get_contents();
ob_end_clean();	
ob_end_flush();
$tema->set_var(array("ORTA_ALAN"=>$orta_alan));
unset($orta_alan);
//===========================================================================================
//ORTA ALAN SONU
//===========================================================================================
//YONETIM PANEL LINK BASLANGICI
//===========================================================================================
$tema->set_block("FileRef", "YONETIM_PANEL_BLOK", "yonetim_panel");
if (UYE_SEVIYE > 4)
{
  $tema->set_var(array('YONETIM_PANEL'=>'<br /><a href="yonetim/index.php">'.$dil['YonetimPanel'].'</a>'));
  $tema->parse('yonetim_panel','YONETIM_PANEL_BLOK',true);
} else {
  $tema->set_var("yonetim_panel","");
}
//===========================================================================================
//YONETIM PANEL LINK SONU
//===========================================================================================
$tema->pparse('Sortie', 'FileRef');
//Hafiza Bosaltimi
unset($dil,$vt);
ob_end_flush();
?>