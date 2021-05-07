<?php
/*======================================================================*\
|| #################################################################### ||
|| #                                                                  # ||
|| # ---------------------------------------------------------------- # ||
|| # Yazi Ekleme Formu      ...                                       # ||
|| #                                                                  # ||
|| # ---------------------------------------------------------------- # ||
|| #                      www.arslandizayn.com                        # ||
|| #################################################################### ||
\*======================================================================*/
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;

function resimKayit($yazino,$uzanti='')
{
//YENİ KAYITTA RESİM YÜKLEME İŞLEMLERİ
global $fonk,$dil,$sayfal;
$resim_dizin  = RESIM_DIZIN.'/r_'.$yazino.'.'.$uzanti;
if (is_uploaded_file($_FILES['resim']['tmp_name']))
{
  if (!@move_uploaded_file($_FILES['resim']['tmp_name'], $resim_dizin))
  {
    return '<br />'.$dil['ResimYuklemeBasarisiz'];
  } else {
    // Yeniden Boyutlandırılmış Resim Çekiliyor ve Üzerine Yazılıyor 
		
    $icerik = $fonk->boyutlandir($resim_dizin,YAZI_RESIM_KAYIT_EN,YAZI_RESIM_KAYIT_BOY); 
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
$rvt->query("SELECT resim FROM ".TABLO_ONEKI."yazilar WHERE yazino=$yazino");
$eski = $rvt->fetchObject();
$eski_resim = $eski->resim;
$yeni_resim = 'r_'.$yazino.'.'.$uzanti;
if ($rvt->query2("UPDATE ".TABLO_ONEKI."yazilar SET resim='$yeni_resim' WHERE yazino=$yazino"))
{
  if ($eski_resim !== $yeni_resim)
  {
    $sil = RESIM_DIZIN.'/'.$eski_resim;
    @ unlink($sil);
  }
} else {
  return '<br />'.$dil['ResimYuklemeBasarisiz'];
}
unset($rvt);
return '<br />'.$dil['ResimYuklendi'];
//RESİM YÜKLEME İŞLEMLERİ BİTTİ
}//Resim Yükleme Fonksiyon Sonu
				

try
{
	if (UYE_SEVIYE == 0)
	{
	  $_SESSION['sayfaadi'] = serialize($sayfa);
    header('Location: ?sayfa=giris&hata=15');
		exit;
  } 
  if (UYE_SEVIYE < YAZI_EKLEME_IZIN)
  {
    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler['YAZI_EKLEME_IZIN']));
    exit;
  }
  //============================================================
	if ($islem == 1 || empty($islem)) { // 1. ADIM BASLANGICI 
	//============================================================
	$vt = new Baglanti();
  //Yazi Duzenleme Bilgileri
	@ $yazi_kategori = intval($_GET['kategori']);
  @ $yazino    = intval($_GET['yazino']);
  $yazi_baslik = '';
  $yazi_icerik = '';
  $resim       = RESIM_DIZIN.'/bos.gif';
  if (empty($yazino))
  {
    $yazisayfaadi  = $dil['YAZI_EKLEME_FORMU'];
    $buton_adi     = $dil['YAZI_EKLE']; 
  
    //Yazi Gonderme Arasi Sure 
    
    $eklenen_yazi = $vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE eklemetarihi > DATE_SUB(NOW(), INTERVAL ".YAZI_KAYIT_ARASI_SURE." MINUTE) AND uyeno=".UYE_NO."");

    if ($eklenen_yazi > 0 && UYE_SEVIYE < YAZI_DUZENLEME_IZIN)
    {
      throw new Exception($fonk->yerine_koy($dil['IslemIcinBeklemenizGerekiyor'],YAZI_KAYIT_ARASI_SURE));
      exit;
    }
  } else {
    $yazisayfaadi  = $dil['YAZI_DUZENLEME_FORMU'];
    $buton_adi     = $dil['YAZI_DUZENLE'];

    $sure_asimi = $vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE eklemetarihi > DATE_SUB(NOW(), INTERVAL ".YAZI_DUZENLEME_SURESI." HOUR) AND uyeno=".UYE_NO." AND yazino=$yazino");
		
    if ($sure_asimi == 0 && UYE_SEVIYE < 6)
    {
      throw new Exception($dil['YaziDuzenlemeYetkinizYok']);
      exit;
    }
    if (UYE_SEVIYE == 6)
    {
      $duzenleme_kosul = "yazino=$yazino";
    } else {
      $duzenleme_kosul = "uyeno=".UYE_NO." AND yazino=$yazino";
    }
		
    $vt->query("SELECT yazino,kategorino,resim,baslik,yazi FROM ".TABLO_ONEKI."yazilar WHERE $duzenleme_kosul");
    $yazi_duzen_izin      = $vt->numRows();
    if ($yazi_duzen_izin == 0)
    {
      throw new Exception($dil['YaziDuzenlemeYetkinizYok']);
      exit;
    }
    $yazi_ayrinti         = $vt->fetchObject();
    $yazino               = $yazi_ayrinti->yazino;
    $yazi_baslik          = $fonk->yazdir_duzen($yazi_ayrinti->baslik);
    $yazi_icerik          = $fonk->yazdir_duzen($yazi_ayrinti->yazi);
    $yazi_resim           = $yazi_ayrinti->resim;
    $yazi_kategori        = $yazi_ayrinti->kategorino;

    if ($yazi_duzen_izin == 0)
    {
      $resim = 'resim/bos.gif';
    } else {
      $resim = RESIM_DIZIN.'/'.$yazi_resim;
    } 
    if (!file_exists($resim) || empty($yazi_resim))
    {
      $resim = RESIM_DIZIN.'/bos.gif';
    }
    $vt->freeResult();
  }

	if (is_array(@$_SESSION['yaziekle']))
	{
	
	  $yazi_kategori = unserialize($_SESSION['yaziekle']['kategori']);
	  $yazi_baslik   = $fonk->yazdir_duzen(unserialize($_SESSION['yaziekle']['baslik']));
		$yazi_icerik   = $fonk->yazdir_duzen(unserialize($_SESSION['yaziekle']['yazi']));
	}
  ?>
  <p>&nbsp;</p>
  <p align="center">
  <h1 align="center"><?php echo $yazisayfaadi; ?></h1>
  <form id="kayit" name="kayit" action="?sayfa=yaziekle&islem=2" method="post" enctype="multipart/form-data" autocomplete="off">
  <input type="hidden" name="yazino" value="<?php echo trim($yazino);?>">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo BOYUT_IZIN; ?>" />
  <table align="center" cellpadding="0" cellspacing="0" width="85%">
  <?php
  if (KATEGORI_SAYI > 0)
  {
    echo '<tr><td width="100%" height="30" valign="middle" align="left"><b>* '.$dil['Kategori'].'&nbsp;&nbsp;:</b><br /><select name="kategori">';
    
    $kategoriler_dizi = $fonk->kategoriListe(0,0); 
		if (empty($yazi_kategori))
    echo '<option value="'.$kategori_no.'">'.$dil['KategoriSec'].'</option>';
    for ($i=0; $i<count($kategoriler_dizi); $i++) 
    { 
      $kategori_no  = $kategoriler_dizi[$i][0];
      $kategori_adi = $kategoriler_dizi[$i][1];
      echo '<option value="'.$kategori_no.'"'; if ($yazi_kategori==$kategori_no) echo ' selected="selected"'; echo '>';
      $level = $kategoriler_dizi[$i][2];
      for ($j=0;$j<$level;$j++) echo '&nbsp;&nbsp;'; //Alt Kategorileri Iceri Kaydirma Bosluklari 
      echo $kategori_adi.'</option>';
    } 
    unset($kategoriler_dizi,$kategorinosu,$kategoriismi);
    echo '</select></td></tr>';
  }
  ?>
  <tr>
    <td width="100%" height="30" valign="middle" align="left"><b>* <?php echo $dil['Baslik']; ?>&nbsp;&nbsp;:</b><br /><input type="text" class="input" id="baslik" style="width: 230px" tabindex="1" name="baslik" maxlength="100" value="<?php echo $yazi_baslik; ?>" /></td>
  </tr>
  <tr>
    <td width="100%" align="left" valign="top">
		<b>* <?php echo $dil['Yaziniz']; ?>&nbsp;&nbsp;:</b><br />
		<?php
		echo $fonk->html_duzen('kayit','metin');
    ?>
    <textarea name="metin" id="metin"  style="background: transparent url() repeat scroll 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; height: 170px; width: 400px;" tabindex="2" onkeyup="karakter_sayi_kontrol('metin',<?php echo YAZI_KARAKTER; ?>);storeCaret(this);" onselect="storeCaret(this);" onclick="storeCaret(this);"><?php echo $yazi_icerik; ?></textarea></td>
  </tr>
  <tr>
    <td width="100%" height="25" align="left"><input type="text" name="metin_sayac" id="metin_sayac" size="5" value="<?php echo YAZI_KARAKTER; ?>" disabled="disabled" />
  </tr>
  <tr>
    <td width="100%" height="30" align="center" valign="bottom">
    <?php 
    if ($yazino) 
    {
      echo '<a href="?sayfa=yazi&yazino='.$yazino.'&islem=2"><b>'.$dil['GeriDon'].'</b></a>';
    } else {
      echo '<a href="?sayfa=yazi"><b>'.$dil['Yazilar'].'</a>';
    }
    ?></td>
  </tr>
  <tr>
    <td width="100%" align="right" valign="center">
      <table width="130" align="center">
        <tr>
          <td width="130" height="80" align="center" valign="middle" style="border:solid 1px #3366ff"><?php echo $dil['Onizleme']; ?><br /><img src="<?php echo $resim; ?>" name="yaziresim" id="yaziresim"  border="0" align="center" width="200" height="120" /></td>
          <td align="left" width="65%" style="padding-left:5px; font-size:9px"><br />Max: <?php echo (BOYUT_IZIN/1024).' KB'; ?><br />
			
          <input type="file" id="resim" name="resim" onblur="if (this.value == '') { resim_degistir('<?php echo $resim; ?>','yaziresim'); } else { resim_degistir(this.value,'yaziresim'); }; return true;" onfocus="if (this.value == '') { resim_degistir('<?php echo $resim; ?>','yaziresim'); } else { resim_degistir(this.value,'yaziresim'); }; return true;" />
        </tr>
      </table>
    </td>
  </tr> 
  <tr>
    <td  width="100%" valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"width="100%"  height="20"><input type="submit" name="yaziGonder" id="yaziGonder" tabindex="9" value="<?php echo $buton_adi; ?>" /></td>
  </tr>
  </table>
  </form>
  <?php
	if (@ is_array($_SESSION['yaziekle']))
	{
    unset($_SESSION['yaziekle']); 
	}
  //==============================================================
  // 1. ADIM SONU
  //==============================================================
  } else { // 2. ADIM BASLANGICI
  //==============================================================
    $vt = new Baglanti();
	
	  @ $yazino    = intval($_POST['yazino']);
	  @ $kategori  = intval($_POST['kategori']);

    if(empty($yazino))
    {
      $buton = $dil['YAZI_EKLE'];
    } else {
      $buton = $dil['YAZI_DUZENLE'];
    }

    // Veriler Kontrol Ediliyor

    @ $baslik    = $fonk->post_duzen($_POST['baslik']);
    @ $yazi      = $fonk->post_duzen($_POST['metin']);
		
    @ $resim     = trim(strip_tags(htmlspecialchars($_FILES['resim']['name'])));
		$_SESSION['yaziekle']['baslik']    = serialize($baslik);
    $_SESSION['yaziekle']['yazi']      = serialize($yazi);
    $_SESSION['yaziekle']['resim']     = serialize($resim);
		$_SESSION['yaziekle']['kategori']  = serialize($kategori);

    if (!$yazi || !$baslik)
    {
      throw new Exception($dil['IsaretliAlanlariBosBirakmayiniz'],1);
      exit;
    } elseif (KATEGORI_SAYI > 0) {
      if (!$kategori)
      {
        throw new Exception($dil['LutfenKategoriSeciniz'],1);
        exit;
      }
      if($vt->kayitSay("SELECT COUNT(kategorino) FROM ".TABLO_ONEKI."yazikategori WHERE kategorino=$kategori") == 0)
      {
        throw new Exception($dil['KategoriGecersiz'],1);
        exit;
      }
    } elseif (strlen($fonk->yazdir_duzen($_POST['baslik'])) > YAZI_BASLIK_KARAKTER) {
      throw new Exception($fonk->yerine_koy($dil['BaslikKarakterIzin'],YAZI_BASLIK_KARAKTER),1);
      exit;
    } elseif (strlen($fonk->yazdir_duzen($_POST['yazi'])) > YAZI_KARAKTER) {
      throw new Exception($fonk->yerine_koy($dil['YaziKarakterIzin'],YAZI_KARAKTER),1);
      exit;
    } 
		/*
		Resim Ad Kontrolu Iptal Edildi
		elseif ($resim != '') {
      if (!$fonk->resim_adi_kontrol($resim))
      {
        throw new Exception($dil['ResimAdiGecersiz'],1);
        exit;
      }
    }
		*/
		//RESİM KONTROLLERİ
		$uzanti       = '';
    $resim_mesaji = '';
		if ($resim != '')
    {
      // RESIM EKLEME BASLANGICI
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
      
        
      //RESİM YUKLENIYOR
      $uzanti = strtr($_FILES['resim']['type'],$yazi_resim_uzanti);
      if(!is_dir(RESIM_DIZIN))
      {
        throw new Exception($dil['KlasorBulunamadi'],1);
        exit;
      }
    }
    //RESİM KONTROLLERİ BİTTİ
		
    if (KATEGORI_SAYI == 0)
    {
      $kategori=0;
    }
    //YAZI KAYIT BOLUMU
    if (empty($yazino))
    {
      //Yazı Gonderme Arasi Sure 
      $eklenen_yazi = $vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE eklemetarihi > DATE_SUB(NOW(), INTERVAL ".YAZI_KAYIT_ARASI_SURE." MINUTE) AND uyeno=".UYE_NO."");

      if ($eklenen_yazi > 0 && UYE_SEVIYE < 6)
      {
        throw new Exception($fonk->yerine_koy($dil['BuIslemIcinBeklemenizGerekiyor'],YAZI_KAYIT_ARASI_SURE),1);
        exit;
      }
      
      if (UYE_SEVIYE >= YAZI_ONAY)
      {
        $onay = 'E';
      } else {
        $onay = 'H';
      }
      if ($vt->query("INSERT INTO ".TABLO_ONEKI."yazilar (kategorino,uyeno,baslik,yazi,eklemetarihi,onay) VALUES ($kategori,".UYE_NO.",'".$vt->escapeString($baslik)."','".$vt->escapeString($yazi)."',NOW(),'$onay')"))
      {
        $yazi_nosu = $vt->insertId();
				//RESİM YÜKLEME BAŞLANGICI
				if ($resim != '')
				{
					$fonk->ftpChmod(0777,RESIM_DIZIN);
					$resim_mesaji = resimKayit($yazi_nosu,$uzanti);
				}
				$fonk->ftpChmod(0755,RESIM_DIZIN);
				// RESİM YÜKLEME SONU

        if ($onay == 'H')
        {
			    unset($_SESSION['yaziekle']);
          throw new Exception($dil['KayitIslemiTamamlandi']."\n".$dil['YaziOnaySonrasiYayinlanacak'],4);
        } else {
			    unset($_SESSION['yaziekle']);
          throw new Exception($dil['KayitIslemiTamamlandi'].$resim_mesaji,4);
        }
      } else {
        throw new Exception($dil['IslemBasarisiz'],1);
      }
    } else {
    //YAZI DUZENLEME KAYIT
    $sure_asimi = $vt->query("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE eklemetarihi > DATE_SUB(NOW(), INTERVAL ".YAZI_DUZENLEME_SURESI." HOUR) AND uyeno=".UYE_NO." AND yazino=$yazino");
    if ($sure_asimi == 0 && UYE_SEVIYE < YAZI_DUZENLEME_IZIN)
    {
      throw new Exception($dil['YaziDuzenlemeIzninizYok']);
      exit;
    }
    //Yazi Guncelleniyor
    if (UYE_SEVIYE > 5)
    {
      $duzenleme_kosul = "yazino=$yazino";
    } else {
      $duzenleme_kosul = "uyeno=".UYE_NO." AND yazino=$yazino";
    }
    $yazino_no = $vt->query("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE $duzenleme_kosul");
    if ($yazino_no == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }
    $vt->query2("UPDATE ".TABLO_ONEKI."yazilar SET kategorino=$kategori,baslik='".$vt->escapeString($baslik)."', yazi='".$vt->escapeString($yazi)."', duzenlemetarihi=NOW() WHERE $duzenleme_kosul");
		if ($resim != '')
		{
			$fonk->ftpChmod(0777,RESIM_DIZIN);
			$resim_mesaji = resimKayit($yazino,$uzanti);
		}
		$fonk->ftpChmod(0755,RESIM_DIZIN);
    unset($_SESSION['yaziekle']);
    throw new Exception($dil['DuzenlemeIslemiTamamlandi'].$resim_mesaji,4);
    
  } // Kayit Alani Sonu
  unset($duzenleme_kosul,$yazino,$kategori,$buton,$baslik,$yazi,$resim,$onay);
  unset($vt);
//===================================================
} // 2. ADIM SONU
//===================================================
} //try Sonu
catch (Exception $e)
{
$hatakod = $e->getCode();
if ($hatakod == 1)
{
  $hata  = false;
	if ($yazino>0)
	{
	  $adres = '<a href="index.php?sayfa=yaziekle&yazino='.$yazino.'">'.$dil['Tamam'].'</a>';
	} else {
	  $adres = '<a href="index.php?sayfa=yaziekle">'.$dil['Tamam'].'</a>';
	}
} elseif ($hatakod == 4) {
  unset($_SESSION['yaziekle']);
  $adres = '<a href="index.php?sayfa=yazi">'.$dil['Tamam'].'</a>';
	$hata = true;
} else {
  unset($_SESSION['yaziekle']);
  $adres = '<a href="index.php">'.$dil['Tamam'].'</a>';
	$hata = false;
}
unset($resim_dizin,$resim,$resimno,$resim_mesaji,$uzanti,$yeni_resim,$eski_resim,$rvt,$sil,$eski);
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