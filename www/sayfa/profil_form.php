<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;

function resimKayit($uyeno,$uzanti='')
{
//YENİ KAYITTA RESİM YÜKLEME İŞLEMLERİ
global $fonk,$dil;
$resim_dizin  = UYE_RESIM_DIZIN.'/r_'.$uyeno.'.'.$uzanti;

if (is_uploaded_file($_FILES['resim']['tmp_name']))
{
  if (!@move_uploaded_file($_FILES['resim']['tmp_name'], $resim_dizin))
  {
    return '<br />'.$dil['ResimYuklemeBasarisiz'];
  } else {
    // Yeniden Boyutlandırılmış Resim Çekiliyor ve Üzerine Yazılıyor 
    $icerik = $fonk->boyutlandir($resim_dizin,UYE_RESIM_EN,UYE_RESIM_BOY,false); 
    if ($dosya  = fopen ($resim_dizin,"w+"))
    {
      if (fwrite($dosya,$icerik))
      {
        fclose($dosya);
      }
    }
  }
} else {
  return '<br />'.$dil['ResimYuklemeBasarisiz'];
}
//Eski Resmin Adi Aliniyor
$rvt = new Baglanti();
$rvt->query("SELECT resim FROM ".TABLO_ONEKI."uyeler WHERE uyeno=$uyeno");
$eski = $rvt->fetchObject();
$eski_resim = $eski->resim;
$yeni_resim = 'r_'.$uyeno.'.'.$uzanti;
if ($rvt->query2("UPDATE ".TABLO_ONEKI."uyeler SET resim='$yeni_resim' WHERE uyeno=$uyeno"))
{
  if ($eski_resim !== $yeni_resim)
  {
    $sil = UYE_RESIM_DIZIN.'/'.$eski_resim;
    @ unlink($sil);
  }
} else {
  return '<br />'.$dil['ResimYuklemeBasarisiz'];
}
unset($rvt);
return '<br />'.$dil['ResimYuklendi'];
//RESİM YÜKLEME İŞLEMLERİ BİTTİ
}//Resim Yükleme Fonksiyon Sonu
	
	
//=============================
try { // try Baslangici
//=============================

if (UYE_SEVIYE == 0)
{ 
  $_SESSION['sayfaadi'] = serialize($sayfa);
  header('Location: ?sayfa=giris&hata=15');
	exit;
}
//=========================================
if (empty($islem) || $islem==1)
{  // 1. ADIM BASLANGICI
//=========================================
//Uye Bilgileri Aliniyor
$vt->query("SELECT uyeno,resim,uyeadi,adi,soyadi,eposta,dogumtarihi,seviye,onay,kayittarihi,girissayisi,songiristarihi,guncellemetarihi FROM ".TABLO_ONEKI."uyeler WHERE uyeno=".UYE_NO."");
$uye_bilgi              = $vt->fetchObject();
$resim                  = $uye_bilgi->resim;
$kuladi                 = $uye_bilgi->uyeadi;
$adi                    = $uye_bilgi->adi;
$soyadi                 = $uye_bilgi->soyadi;
$eposta                 = $uye_bilgi->eposta;
$dogumtarihi            = $uye_bilgi->dogumtarihi;
$kayittarihi            = $uye_bilgi->kayittarihi;
$songiristarihi         = $uye_bilgi->songiristarihi;
$girissayisi            = $uye_bilgi->girissayisi;
$guncellemetarihi       = $uye_bilgi->guncellemetarihi;
list($yil,$ay,$gun) = explode('-',$dogumtarihi);

$vt->freeResult();

if (@is_array($_SESSION['profilduzenleme']))
{
  foreach($_SESSION['profilduzenleme'] as $anahtar=>$deger)
  {
    ${$anahtar} = unserialize($deger);
    if (empty(${$anahtar}))
    ${$anahtar} = '';
  }
} 


$uye_resim = UYE_RESIM_DIZIN.'/'.$resim;
if (!file_exists($uye_resim) || empty($resim))
{
  $uye_resim = UYE_RESIM_DIZIN.'/bos.gif';
}


?>
<p>&nbsp;</p>
<table width="85%" align="center">
<form name="kayit" id="kayit"  action="?sayfa=profil&islem=2" method="post" enctype="multipart/form-data" autocomplete="off">
  <input type="hidden" name="uyeno" id="uyeno" value="<?php echo UYE_NO; ?>"  />
  <tr>
    <td width="100%" height="20" colspan="2" align="center"><h1><?php echo $dil['UYELIK_BILGILERINIZ']; ?></h1></td>
  </tr>
	<tr>
	<td width="100%" colspan="2"><table width="130" align="center"><tr><td width="130" height="80" align="center" valign="middle" style="border:solid 1px #3366ff"><?php echo $dil['Onizleme']; ?><br /><img src="<?php echo $uye_resim; ?>" name="uyeresim" id="uyeresim"  border="0" align="center" width="<?php echo UYE_RESIM_EN; ?>" height="<?php echo UYE_RESIM_BOY; ?>" /></td>
    <td align="left" width="65%" style="padding-left:5px; font-size:9px"><br />Max: <?php echo (BOYUT_IZIN/1024).' KB'; ?><br />
			
			<input type="file" id="resim" name="resim" onblur="if (this.value == '') { resim_degistir('<?php echo $uye_resim; ?>','uyeresim'); } else { resim_degistir(this.value,'uyeresim'); }; return true;" onfocus="if (this.value == '') { resim_degistir('<?php echo $uye_resim; ?>','uyeresim'); } else { resim_degistir(this.value,'uyeresim'); }; return true;" />
   </tr></table></td>
	 </tr
  ><tr>
    <td width="40%" align="right"  height="25" valign="center"><b><?php echo $dil['KullaniciAdiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><?php echo $kuladi; ?></td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25" valign="center"><b><?php echo $dil['Sifreniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><input type="password" name="parola" id="parola" maxlength="25" tabindex="2" />&nbsp;&nbsp;Max: 25</td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25"><b><?php echo $dil['SifrenizTekrar']; ?> : </b></td>
    <td width="60%" align="left" height="25"><input type="password" name="parolatekrar" id="parolatekrar" maxlength="25" tabindex="3" />&nbsp;&nbsp;Max: 25</td>
  </tr>
  <tr>
    <td width="40%" align="right" height="5"><b>* <?php echo $dil['EpostaAdresiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><input type="text" name="eposta" id="eposta" value="<?php echo $eposta; ?>" maxlength="100" tabindex="4" />&nbsp;&nbsp;Max: 100</td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25" valign="center"><b>* <?php echo $dil['Adiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><input type="text" name="adi" id="adi" value="<?php echo $adi; ?>" maxlength="50" tabindex="5" />&nbsp;&nbsp;Max: 50</td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25" valign="center"><b>* <?php echo $dil['Soyadiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25" valign="center"><input type="text" name="soyadi" id="soyadi" value="<?php echo $soyadi; ?>" maxlength="50" tabindex="6" />&nbsp;&nbsp;Max: 50</td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25" valign="top"><b>* <?php echo $dil['DogumTarihiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25">
      <input type="text" name="gun" id="gun" value="<?php echo $gun; ?>" maxlength="2" size="2" style="text-align: center" onkeyup="isNumberic(this)" tabindex="7" /> . 
      <input type="text" name="ay" id="ay" value="<?php echo $ay; ?>" maxlength="2" size="2" style="text-align: center" onkeyup="isNumberic(this)" tabindex="8" /> . 
      <input type="text" name="yil" id="yil" value="<?php echo $yil; ?>" maxlength="4" size="4" style="text-align: center" onkeyup="isNumberic(this)" tabindex="9" />&nbsp;&nbsp;<br />(Örn: 01.01.1978)
    </td>
  </tr>
	 <tr>
    <td width="40%" align="right" height="25" valign="center">&nbsp;</td>
    <td width="60%" align="left" height="25" valign="center"><input type="checkbox" name="yorummesaj" id="yorummesaj" maxlength="50" value="1" checked="checked" tabindex="10" />&nbsp;<?php echo $dil['YorumMesajGonder']; ?></td>
  </tr>
	 <tr>
    <td width="40%" align="right" height="25" valign="center">&nbsp;</td>
    <td width="60%" align="left" height="25" valign="center"><?php 
		if (EPOSTA_DEGISTI == 1) echo $dil['GuncellemeMesaj1'];
		if (EPOSTA_DEGISTI == 2) echo $dil['GuncellemeMesaj2']; ?></td>
  </tr>

  <tr>
    <td align="center" colspan="2" width="100%" height="25"><input style="font-size: 10px" type="submit" name="kayitButon" id="kayitButon" value="<?php echo $dil['GUNCELLE']; ?>" tabindex="11" /></td>
  </tr>
	<tr>
    <td width="100%" height="20" colspan="2" align="center">&nbsp;</td>
  </tr>
	<tr>
    <td width="100%" height="20" colspan="2" align="center"><h1><?php echo $dil['ISTATISTIKLER']; ?></h1></td>
  </tr>
  <tr>
    <td width="40%" align="right"  height="25" valign="center"><b><?php echo $dil['KayitTarihiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><?php echo $fonk->duzgun_tarih_saat($kayittarihi,true); ?></td>
  </tr>
  <tr>
    <td width="40%" align="right" height="25" valign="center"><b><?php echo $dil['SonGirisTarihiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><?php echo $fonk->duzgun_tarih_saat($songiristarihi,true); ?></td>
  </tr>
	<tr>
    <td width="40%" align="right" height="25" valign="center"><b><?php echo $dil['GirisSayiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><?php echo intval($girissayisi); ?></td>
  </tr>
	<tr>
    <td width="40%" align="right" height="25" valign="center"><b><?php echo $dil['GuncellemeTarihiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><?php echo $fonk->duzgun_tarih_saat($guncellemetarihi,true); ?></td>
  </tr>
	<tr>
    <td width="40%" align="right"  height="25" valign="center"><b><?php echo $dil['YaziSayiniz']; ?> : </b></td>
    <td width="60%" align="left" height="25"><?php echo $vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE uyeno=".UYE_NO.""); ?></td>
  </tr>
  <tr>
    <td align="center" colspan="2" width="100%" height="28"><a href="index.php"><?php echo $dil['AnaSayfa']; ?></a></td>
  </tr>
</form>
</table>
<?php
//Hafiza Bosaltiliyor
if (@is_array($_SESSION['profilduzenleme']))
{
  foreach($_SESSION['profilduzenleme'] as $anahtar=>$deger)
  {
    unset(${$anahtar});
		unset($_SESSION['profilduzenleme']);
  }
} 
unset($uye_bilgi,$kuladi,$adi,$soyadi,$eposta,$dogumtarihi,$kayittarihi,$yil,$ay,$gun,$songiristarihi,$guncellemetarihi,$girissayisi);
//============================================
// 1. ADIM SONU
//============================================
} else { //2. ADIM BASLANGICI
//============================================
  $vt = new Baglanti();

	@ $parola       = $fonk->post_duzen($_POST['parola']);
	@ $parolatekrar = $fonk->post_duzen($_POST['parolatekrar']);    
  @ $eposta       = $fonk->post_duzen($_POST['eposta']);
  @ $adi          = $fonk->post_duzen($_POST['adi']);
  @ $soyadi       = $fonk->post_duzen($_POST['soyadi']);
	@ $gun          = intval($_POST['gun']);
	@ $ay           = intval($_POST['ay']);
	@ $yil          = intval($_POST['yil']);
  @ $dogumtarihi  = $gun.'.'.$ay.'.'.$yil;
	@ $yorummesaj   = intval($_POST['yorummesaj']);
	@ $resim        = trim(strip_tags(htmlspecialchars($_FILES['resim']['name'])));

	foreach($_POST as $anahtar=>$deger)
  {
    $_SESSION['profilduzenleme'][$anahtar] = serialize($deger);
  }


  if (UYE_SEVIYE == 0)
  { 
    $_SESSION['sayfaadi'] = serialize($sayfa);
    header('Location: ?sayfa=giris&hata=15');
    exit;
  }

  if (empty($eposta) || empty($adi) || empty($soyadi) || empty($gun) || empty($ay) || empty($yil))
  {
    throw new Exception($dil['IsaretliAlanlariBosBirakmayiniz'],1);
    exit;
  }
  
	if ($resim != '') 
	{
    if (!$fonk->resim_adi_kontrol($resim))
    {
      throw new Exception($dil['ResimAdiGecersiz'],1);
      exit;
    }
  }
  if (!$fonk->eposta_kontrol($eposta)) {
    //E-Posta Adresi Gecersiz
    throw new Exception($dil["EpostaGecersiz"],1);
    exit;
  } elseif ($parola) {
    if (!$fonk->parola_kontrol($parola))
    {
      //Sifrede A-Z Harf, 0-9 Rakam ve Alt Cizgi Kullanilabilir
      throw new Exception($dil["SifreGecersiz"],1);
      exit;
    }
    if (strlen($parola) > 25 || strlen($parola) < 6)
    {
      //Şifre En Fazla 25 Karakter Olabilir
      throw new Exception($dil["ParolaKarakterIzin"],1);
      exit;
    }
    //Sifreler Uyusmuyor
    if ($parola != $parolatekrar)
    {
      throw new Exception($dil["SifrelerUyusmuyor"],1);
      exit;
    } 
  } elseif (!$fonk->turkceharf_kontrol($adi) || !$fonk->turkceharf_kontrol($soyadi)) {
    //Isim ve Soyisimde a-zAZ Harf Kullanilabilir
    throw new Exception($dil["IsimSoyisimGecersiz"],1);
    exit;
  }
		
  if (strlen($eposta) > 100 || strlen($eposta) < 7)
  {
    //Eposta En Fazla 100 En Az 5 Karakter Olabilir
    throw new Exception($dil["EpostaKarakterIzin"],1);
    exit;
  }
		
  if (strlen($adi) > 50 || strlen($adi) < 2)
  {
    //Isim En Fazla 50 En Az 2 Karakter Olabilir
    throw new Exception($dil["IsimKarakterIzin"],1);
    exit;
  }
		
  if (strlen($soyadi) > 50 || strlen($soyadi) < 2)
  {
    //Soyisim En Fazla 50 En Az 2 Karakter Olabilir
    throw new Exception($dil["SoyisimKarakterIzin"],1);
    exit;
  }
		
  //E-Posta Adresi Kontrolu
  $eposta_var = $vt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE uyeno<>".UYE_NO." AND eposta='$eposta'");  
  if ($eposta_var > 0)
  {
    throw new Exception($dil['EpostaSistemdeKayitli'],1);
    exit;
  }

  //Dogum Tarihi Kontrolu
  $yil_izin = intval(date('Y'))-100;
  if ($yil > date('Y',time()-378432000) || $yil<$yil_izin)
  {
    throw new Exception($dil['YilIcinGecerliSayiGiriniz'],1);
    exit;
  }
  if (!$dogumtarihi = $fonk->mysql_tarih_kontrol($dogumtarihi))
  {
    throw new Exception($dil['DogumTarihiGecersiz'],1);
    exit;
  }
		
  //Onay Ayarları
  if (EPOSTA_DEGISTI == 0)
  {
    $onay = 'E';
    $yoneticionayi = 5;
  } elseif (EPOSTA_DEGISTI == 1) {
    $onay = 'E';
    $yoneticionayi = 1;
  } elseif (EPOSTA_DEGISTI == 2 && UYELIK_ONAYI == 4) {
    $onay = 'H';
    $yoneticionayi = 0;
  } else {
    $onay = 'H';
    $yoneticionayi = 5;
  }
  
  $eposta_degistirildi = $vt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE eposta='$eposta' and uyeno=".UYE_NO.""); 
		
  $onay_kodu  = sha1($fonk->kod(10));
	$resim_mesaji = '';
  //============================================
  if ($resim != '') {  // RESIM EKLEME BASLANGICI
  //============================================
    if ($_FILES['resim']['size'] > BOYUT_IZIN)
    {
      throw new Exception($dil['ResimBoyutuBuyuk'],1);
      exit;
    } 
    if (!array_key_exists($_FILES['resim']['type'],$yazi_resim_uzanti))
    {
      throw new Exception($dil['ResimUzantisiGecersiz'],1);
      exit;
    }

    //Resim Ekleme Hatalari
    if ($_FILES['resim']['error'] > 0)
    {
      switch ($_FILES['resim']['error'])
      {
        case 1: throw new Exception($dil['ResimYuklemeBasarisiz'],1);  break;
        case 2: throw new Exception($dil['ResimYuklemeBasarisiz'],1); break;
        case 3: throw new Exception($dil['ResimYuklemeBasarisiz'],1); break;
        case 4: throw new Exception($dil['ResimYuklemeBasarisiz'],1); break;
      }
    }
    $uzanti       = '';
    $resim_mesaji = '';
    //RESİM YUKLENIYOR
    $uzanti = strtr($_FILES['resim']['type'],$yazi_resim_uzanti);
    if(!is_dir(UYE_RESIM_DIZIN))
    {
      throw new Exception($dil['KlasorBulunamadi'],1);
      exit;
    }
		
		if ($resim != '')
    {
      $fonk->ftpChmod(0777,UYE_RESIM_DIZIN);
      $resim_mesaji = resimKayit(UYE_NO,$uzanti);
      ftpChmod(0755,UYE_RESIM_DIZIN);
    }
		
		
    unset($uzanti);
  //============================================
	} //RESIM EKLEME SONU
  //============================================
		 
  if ($vt->query("UPDATE ".TABLO_ONEKI."uyeler SET adi='".$vt->escapeString($adi)."',soyadi='".$vt->escapeString($soyadi)."',eposta='".$vt->escapeString($eposta)."',dogumtarihi='$dogumtarihi',onay='$onay',yonay='$yoneticionayi',guncellemetarihi=NOW(),ip='".UYE_IP."',onaykodu='$onay_kodu',bilgi='$yorummesaj' WHERE uyeno=".UYE_NO.""))
  {
	  $kayit_mesaji = '';
    if (EPOSTA_DEGISTI == 2)
    {
      //Üyelik Bilgilerinin E-Posta İle Üyeye Gönderilmesi
      $eposta_konu      = $dil['UyelikBilgileriniz'];
      $eposta_mesaj     = SITE_ADI.' '.$dil['UyelikBilgilerinizAsagida']."\r\n";
      $eposta_mesaj    .= $dil['KullaniciAdiniz'].' : '.$kullaniciadi."\r\n";
      $eposta_mesaj    .= $dil['Sifreniz'].' : '.$parola."\r\n";
      $eposta_mesaj    .= $fonk->yerine_koy($dil['EpostaMesaj3'],array(SITE_ADRESI,$kullaniciadi,$onay_kodu))."\r\n";
      $eposta_mesaj    .= $dil['SitemiziSectiginizIcinTesekkur']."\r\n";
			$eposta_mesaj    .= SITE_ADI."\r\n";
      $eposta_mesaj    .= SITE_ADRESI;
			
      //==================================================================================
			// E-Posta Gonderiliyor
      $fonk->eposta_gonder(array(trim($eposta)=>$adi.' '.$soyadi), $eposta_konu, $eposta_mesaj,true,'text');
			//==================================================================================
				
      $kayit_mesaji  = '<br/>'.$dil['KayitEpostaOnayi'];
      unset($eposta_ustbilgi,$eposta_konu,$eposta_mesaj);
    }
    if ($parola)
    {
      $yeni_sifre = sha1($parola); 
      if ($vt->query("UPDATE ".TABLO_ONEKI."uyeler SET sifre='$yeni_sifre' WHERE uyeno=".UYE_NO.""))
      {
        $_SESSION['pehepe_kullanici_sifre'] = $yeni_sifre;
      }
    }
    throw new Exception($dil['GuncellemeIslemiTamamlandi'].$kayit_mesaji.$resim_mesaji,2);
  } else {
    throw new Exception($dil['IslemBasarisiz']);
  }
  unset($vt,$kullaniciadi,$parola,$parolatekrar,$eposta,$adi,$soyadi,$gun,$ay,$yil,$dogumtarihi);

////////////////////////////////
///PROFIL KAYIT BOLUMU SONU ///////
////////////////////////////////
//=====================================
} // 2. ADIM SONU
//=====================================
} //try Sonu  
catch (Exception $e)
{
$hatakod = $e->getCode();
if ($hatakod == 1)
{
  $adres = '<a href="index.php?sayfa=profil">'.$dil['Tamam'].'</a>';
	$hata  = false;
} elseif ($hatakod == 2) {
  unset($_SESSION['profilduzenleme']);
  $adres = '<a href="index.php">'.$dil['Tamam'].'</a>';
	$hata = true;
} else {
  unset($_SESSION['profilduzenleme']);
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