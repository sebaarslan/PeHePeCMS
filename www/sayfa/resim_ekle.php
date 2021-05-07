<?php
/*======================================================================*\
|| #################################################################### ||
|| #                                                                  # ||
|| # ---------------------------------------------------------------- # ||
|| # Resim Ekleme Formu      ...                                      # ||
|| #                                                                  # ||
|| # ---------------------------------------------------------------- # ||
|| #                      www.arslandizayn.com                        # ||
|| #################################################################### ||
\*======================================================================*/
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;

function resimKayit($albumno,$resimno,$uzanti='')
{
//YENİ KAYITTA RESİM YÜKLEME İŞLEMLERİ
global $fonk,$dil;
$resim_dizin  = GALERI_ALBUM_DIZIN.'/album_'.$albumno.'/r_'.$resimno.'.'.$uzanti;
if (is_uploaded_file($_FILES['resim']['tmp_name']))
{
  if (!move_uploaded_file($_FILES['resim']['tmp_name'], $resim_dizin))
  {
    return '<br /><font color="#ff0000">'.$dil['ResimYuklemeBasarisiz'].'</font>';
  } else {
    // Yeniden Boyutlandırılmış Resim Çekiliyor ve Üzerine Yazılıyor 
		
    $icerik = $fonk->boyutlandir($resim_dizin,GALERI_RESIM_EN,GALERI_RESIM_BOY); 
    if ($dosya  = fopen ($resim_dizin,"w+"))
    {
      if (fwrite($dosya,$icerik))
      {
        fclose($dosya);
      }
    }
  }
} else {
  return '<br /><font color="#ff0000">'.$dil['ResimYuklemeBasarisiz'].'</font>';
}
//Eski Resmin Adi Aliniyor
$rvt = new Baglanti();
$rvt->query("SELECT resim FROM ".TABLO_ONEKI."resim WHERE resimno=$resimno ");
$eski = $rvt->fetchObject();
$eski_resim = $eski->resim;
$yeni_resim = 'r_'.$resimno.'.'.$uzanti;
if ($rvt->query2("UPDATE ".TABLO_ONEKI."resim SET resim='$yeni_resim' WHERE resimno=$resimno AND (onay='E' OR uyeno=".UYE_NO.")"))
{
  if ($eski_resim !== $yeni_resim)
  {
    $sil = GALERI_ALBUM_DIZIN.'/album_'.$albumno.'/'.$eski_resim;
    @ unlink($sil);
  }
} else {
  return '<br /><font color="#ff0000">'.$dil['ResimYuklemeBasarisiz'].'</font>';
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

  if (UYE_SEVIYE < GALERI_RESIM_EKLEME_IZIN)
  {
    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[GALERI_RESIM_EKLEME_IZIN]));
    exit;
  }
	
	
	
  //============================================================
	if ($islem == 1 || empty($islem)) { // 1. ADIM BASLANGICI 
	//============================================================
	$vt = new Baglanti();
  //Yazi Duzenleme Bilgileri
  @ $resimno       = intval($_GET['resimno']);
	@ $resim_albumno = intval($_GET['albumno']);
  $resim_baslik = '';
  $resim_aciklama = '';
  $resim       = GALERI_ALBUM_DIZIN.'/bos.gif';
	if (UYE_SEVIYE < 6)
	{
	  if ($vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE albumno=$resim_albumno AND onay='H' AND uyeno<>".UYE_NO."")>0)
    {
      throw new Exception($dil['AlbumOnayliDegil']);
      exit;
		}
  }
	if (UYE_SEVIYE < 6)
	{
    if ($vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE albumno=$resim_albumno AND izin='H' AND uyeno<>".UYE_NO."")>0)
    {
      throw new Exception($dil['AlbumDigerUyelereKapali']);
      exit;
		}
  }
  if (empty($resimno))
  {
    $resimsayfaadi  = $dil['RESIM_EKLE'];
    $buton_adi     = $dil['RESIM_EKLE']; 
  
    //Yazi Gonderme Arasi Sure 
    
    $eklenen_resim = $vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE tarih > DATE_SUB(NOW(), INTERVAL ".GALERI_RESIM_KAYIT_SURE." MINUTE) AND uyeno=".UYE_NO."");

    if ($eklenen_resim > 0 && UYE_SEVIYE < GALERI_RESIM_KAYIT_SURE)
    {
      throw new Exception($fonk->yerine_koy($dil['IslemIcinBeklemenizGerekiyor'],GALERI_RESIM_KAYIT_SURE));
      exit;
    }
  } else {
    $resimsayfaadi  = $dil['RESIM_DUZENLE'];
    $buton_adi     = $dil['RESIM_DUZENLE'];

    $sure_asimi = $vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE tarih > DATE_SUB(NOW(), INTERVAL ".GALERI_RESIM_DUZEN_SURE." HOUR) AND uyeno=".UYE_NO." AND resimno=$resimno");
		
    if ($sure_asimi == 0 && UYE_SEVIYE < 6)
    {
      throw new Exception($dil['ResimDuzenlemeIzninizYok']);
      exit;
    }
    if (UYE_SEVIYE == 6)
    {
      $duzenleme_kosul = "resimno=$resimno";
    } else {
      $duzenleme_kosul = "uyeno=".UYE_NO." AND resimno=$resimno";
    }
		
    $vt->query("SELECT resimno,albumno,resim,resimadi,aciklama FROM ".TABLO_ONEKI."resim WHERE $duzenleme_kosul");
    $resim_duzen_izin      = $vt->numRows();
    if ($resim_duzen_izin == 0)
    {
      throw new Exception($dil['ResimDuzenlemeYetkinizYok']);
      exit;
    }
    $resim_ayrinti        = $vt->fetchObject();
    $resimno              = $resim_ayrinti->resimno;
    $resim_baslik         = $fonk->post_duzen($resim_ayrinti->resimadi);
    $resim_aciklama       = $fonk->post_duzen($resim_ayrinti->aciklama);
    $resim_resim          = $resim_ayrinti->resim;
    $resim_albumno        = $resim_ayrinti->albumno;

    if ($resim_duzen_izin == 0)
    {
      $resim = GALERI_ALBUM_DIZIN.'/bos.gif';
    } else {
      $resim = GALERI_ALBUM_DIZIN.'/album_'.$resim_albumno.'/'.$resim_resim;
    } 
    if (!file_exists($resim) || empty($resim_resim))
    {
      $resim = GALERI_ALBUM_DIZIN.'/bos.gif';
    }
    $vt->freeResult();
  }
	if (is_array(@$_SESSION['resimekle']))
	{
	  $resim_albumno   = unserialize($_SESSION['resimekle']['albumno']);
	  $resim_baslik    = unserialize($_SESSION['resimekle']['baslik']);
		$resim_aciklama  = unserialize($_SESSION['resimekle']['aciklama']);
	}
  ?>
  <p>&nbsp;</p>
  <p align="center">
  <h1 align="center"><?php echo $resimsayfaadi; ?></h1>
  <form id="kayit" name="kayit" action="?sayfa=resimekle&islem=2" method="post" enctype="multipart/form-data" autocomplete="off">
  <input type="hidden" name="resimno" value="<?php echo trim($resimno);?>">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo BOYUT_IZIN; ?>" />
  <table align="center" cellpadding="0" cellspacing="0" width="85%">
  <tr>
	<td width="100%" height="30" valign="middle" align="left"><b>* <?php echo $dil['Album']; ?>&nbsp;&nbsp;:</b><br />
	<select name="albumno">
	<?php
	echo '
	<option value="0">-- '.$dil['AlbumSeciniz'].' --</option>';
	$vt->query("SELECT albumno,albumadi FROM ".TABLO_ONEKI."album WHERE (onay='E' AND izin='E') OR uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5 ORDER BY albumadi ASC");
	if ($vt->numRows()>0)
	{
	  $ano = 0;
    while($album_veri = $vt->fetchObject())
    {
		  $ano++;
		  $album_no  = $album_veri->albumno;
			$album_adi = $album_veri->albumadi;
      echo '<option value="'.$album_no.'"'; if ($resim_albumno == $album_no) echo ' selected="selected"'; echo '>'.$ano.' - '.$album_adi.'</option>';
    }
	} 
	?>
  </select></td></tr>
  <tr>
    <td width="100%" height="30" valign="middle" align="left"><b>* <?php echo $dil['Baslik']; ?>&nbsp;&nbsp;:</b><br /><input type="text" class="input" id="baslik" style="width: 230px" tabindex="1" name="baslik" maxlength="100" value="<?php echo $resim_baslik; ?>" /></td>
  </tr>
  <tr>
    <td width="100%" align="left" valign="top">
		<b>&nbsp;&nbsp;<?php echo $dil['ACIKLAMA']; ?>&nbsp;&nbsp;:</b><br />
    <textarea name="aciklama" id="aciklama"  style="background: transparent url() repeat scroll 0%; -moz-background-clip: -moz-initial; -moz-background-origin:-moz-initial; -moz-background-inline-policy:-moz-initial; height:100px; width:400px;" tabindex="2" onkeyup="karakter_sayi_kontrol('aciklama',150);storeCaret(this);" onselect="storeCaret(this);" onclick="storeCaret(this);"><?php echo $resim_aciklama; ?></textarea></td>
  </tr>
  <tr>
    <td width="100%" height="25" align="left"><input type="text" name="aciklama_sayac" id="aciklama_sayac" size="5" value="150" disabled="disabled" />
  </tr>
  <tr>
    <td width="100%" align="right" valign="center">
      <table width="130" align="center">
        <tr>
          <td width="130" height="80" align="center" valign="middle" style="border:solid 1px #3366ff"><?php echo $dil['Onizleme']; ?><br /><img src="<?php echo $resim; ?>" name="resimresim" id="resimresim"  border="0" align="center" width="200" height="120" /></td>
          <td align="left" width="65%" style="padding-left:5px;"><br /><b>*</b>&nbsp;<span style="font-size:9px">Max:</span> <?php echo (BOYUT_IZIN/1024).' KB'; ?><br />
			
          <input type="file" id="resim" name="resim" onblur="if (this.value == '') { resim_degistir('<?php echo $resim; ?>','resimresim'); } else { resim_degistir(this.value,'resimresim'); }; return true;" onfocus="if (this.value == '') { resim_degistir('<?php echo $resim; ?>','resimresim'); } else { resim_degistir(this.value,'resimresim'); };" />
        </tr>
      </table>
    </td>
  </tr> 
  <tr>
    <td  width="100%" valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"width="100%"  height="20"><input type="submit" name="resimGonder" id="resimGonder" tabindex="9" value="<?php echo $buton_adi; ?>" /></td>
  </tr>
  </table>
  </form>
  <?php
	if (@ is_array($_SESSION['resimekle']))
	{
    unset($_SESSION['resimekle']); 
	}
  //==============================================================
  // 1. ADIM SONU
  //==============================================================
  } else { // 2. ADIM BASLANGICI
  //==============================================================
    $vt = new Baglanti();
	  @ $resimno  = intval($_POST['resimno']);
	  @ $albumno  = intval($_POST['albumno']);
		
		if (UYE_SEVIYE < 6 && $vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE albumno=$albumno AND (izin='H' AND uyeno<>".UYE_NO.")")>0)
    {
      throw new Exception($dil['AlbumDigerUyelereKapali']);
      exit;
    }
    
    // Veriler Kontrol Ediliyor
    @ $baslik    = $fonk->post_duzen($_POST['baslik']);
    @ $aciklama  = $fonk->post_duzen($_POST['aciklama']);
    @ $resim     = trim(strip_tags(htmlspecialchars($_FILES['resim']['name'])));
		

		$_SESSION['resimekle']['baslik']   = serialize($baslik);
    $_SESSION['resimekle']['aciklama'] = serialize($aciklama);
    $_SESSION['resimekle']['resim']    = serialize($resim);
		$_SESSION['resimekle']['albumno']  = serialize($albumno);

    if (!$albumno || !$baslik)
    {
      throw new Exception($dil['IsaretliAlanlariBosBirakmayiniz'],1);
      exit;
		}
		if (empty($resimno))
		{
		  if (!$resim)
			{
        throw new Exception($dil['IsaretliAlanlariBosBirakmayiniz'],1);
        exit;
      } 
		}
    if($vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE albumno=$albumno") == 0) {
      throw new Exception($dil['IslemGecersiz'],1);
      exit;
    } elseif (strlen($fonk->yazdir_duzen($_POST['baslik'])) > GALERI_RESIM_AD_KARAKTER) {
      throw new Exception($fonk->yerine_koy($dil['BaslikKarakterIzin'],GALERI_RESIM_AD_KARAKTER),1);
      exit;
    } elseif (strlen($fonk->yazdir_duzen($_POST['aciklama'])) > GALERI_RESIM_ACIKLAMA_KARAKTER) {
      throw new Exception($fonk->yerine_koy($dil['YaziKarakterIzin'],GALERI_RESIM_ACIKLAMA_KARAKTER),1);
      exit;
    } 
		/*
		//Resim Adi Kontrolu Iptal Edildi
		elseif ($resim != '') {
      if (!$fonk->resim_adi_kontrol($resim))
      {
        throw new Exception($dil['ResimAdiGecersiz'],1);
        exit;
      }
    }
		*/
		//RESİM KONTROLLERİ
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
          case 1: throw new Exception($dil['ResimBoyutuBuyuk'],1);  break;
          case 2: throw new Exception($dil['ResimBoyutuBuyuk'],1); break;
          case 3: throw new Exception($dil['ResimYuklemeBasarisiz'],1); break;
          case 4: throw new Exception($dil['ResimYuklemeBasarisiz'],1); break;
        }
      }
      $uzanti       = '';
      $resim_mesaji = '';
        
      //RESİM YUKLENIYOR
			
      $uzanti = strtr($_FILES['resim']['type'],$yazi_resim_uzanti);
      if (!is_dir(GALERI_ALBUM_DIZIN.'/album_'.$albumno))
      {
        throw new Exception($dil['KlasorBulunamadi'],1);
        exit;
      }
    }
    //RESİM KONTROLLERİ BİTTİ

    //RESIM KAYIT BOLUMU
    if (empty($resimno))
    {
      //Resim Gonderme Arasi Sure 
      $eklenen_resim = $vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE tarih > DATE_SUB(NOW(), INTERVAL ".GALERI_RESIM_KAYIT_SURE." MINUTE) AND uyeno=".UYE_NO."");

      if ($eklenen_resim > 0 && UYE_SEVIYE < 6)
      {
        throw new Exception($fonk->yerine_koy($dil['BuIslemIcinBeklemenizGerekiyor'],GALERI_RESIM_KAYIT_SURE),1);
        exit;
      }
      
      if (UYE_SEVIYE >= GALERI_RESIM_ONAY)
      {
        $onay = 'E';
      } else {
        $onay = 'H';
      }
      if ($vt->query("INSERT INTO ".TABLO_ONEKI."resim (albumno,uyeno,resimadi,aciklama,tarih,onay) VALUES ($albumno,".UYE_NO.",'".$vt->escapeString($baslik)."','".$vt->escapeString($aciklama)."',NOW(),'$onay')"))
      {
        $resim_nosu = $vt->insertId();
				//RESİM YÜKLEME BAŞLANGICI
				if ($resim != '')
				{
				  $fonk->ftpChmod(0777,GALERI_ALBUM_DIZIN.'/album_'.$albumno);
				  $resim_mesaji = resimKayit($albumno,$resim_nosu,$uzanti);
				}
				$fonk->ftpChmod(0755,GALERI_ALBUM_DIZIN.'/album_'.$albumno);
				// RESİM YÜKLEME SONU

        if ($onay == 'H')
        {
			    unset($_SESSION['resimekle']);
          throw new Exception($dil['KayitIslemiTamamlandi']."\n".$dil['YoneticiOnayiGerekiyor'],4);
        } else {
			    unset($_SESSION['resimekle']);
          throw new Exception($dil['KayitIslemiTamamlandi'].$resim_mesaji,4);
        }
      } else {
        throw new Exception($dil['IslemBasarisiz'],1);
      }
    } else {
    //RESIM DUZENLEME KAYIT
    $sure_asimi = $vt->query("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE tarih > DATE_SUB(NOW(), INTERVAL ".GALERI_RESIM_DUZEN_SURE." HOUR) AND uyeno=".UYE_NO." AND resimno=$resimno");
    if ($sure_asimi == 0 && UYE_SEVIYE < 6)
    {
      throw new Exception($dil['ResimDuzenlemeIzninizYok']);
      exit;
    }
    //Yazi Guncelleniyor
    if (UYE_SEVIYE > 5)
    {
      $duzenleme_kosul = "resimno=$resimno";
    } else {
      $duzenleme_kosul = "uyeno=".UYE_NO." AND resimno=$resimno";
    }
    $resimno_no = $vt->query("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE $duzenleme_kosul");
    if ($resimno_no == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }
    $vt->query2("UPDATE ".TABLO_ONEKI."resim SET albumno=$albumno,resimadi='".$vt->escapeString($baslik)."', aciklama='".$vt->escapeString($aciklama)."',duzentarih=NOW() WHERE $duzenleme_kosul");
		if ($resim != '')
		{
		  $fonk->ftpChmod(0777,GALERI_ALBUM_DIZIN.'/album_'.$albumno);
		  $resim_mesaji = resimKayit($albumno,$resimno,$uzanti);
		}
		$fonk->ftpChmod(0755,GALERI_ALBUM_DIZIN.'/album_'.$albumno); //Klasor Izne Tekrar Eski Haline Getiriliyor
    unset($_SESSION['resimekle']);
    throw new Exception($dil['DuzenlemeIslemiTamamlandi'].$resim_mesaji,5);
    
  } // Kayit Alani Sonu
  unset($duzenleme_kosul,$resimno,$kategori,$buton,$baslik,$resim,$resim,$onay);
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
	if ($resimno>0)
	{
	  $adres = '<a href="index.php?sayfa=resimekle&resimno='.$resimno.'">'.$dil['Tamam'].'</a>';
	} else {
	  $adres = '<a href="index.php?sayfa=resimekle">'.$dil['Tamam'].'</a>';
	}
} elseif ($hatakod == 4) {
  unset($_SESSION['resimekle']);
  $adres = '<a href="index.php?sayfa=galeri&resim='.$resim_nosu.'&islem=3">'.$dil['Tamam'].'</a>';
	$hata = true;
} elseif ($hatakod == 5) {
  unset($_SESSION['resimekle']);
  $adres = '<a href="index.php?sayfa=galeri&resim='.$resimno.'&islem=3">'.$dil['Tamam'].'</a>';
	$hata = true;
} else {
  unset($_SESSION['resimekle']);
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