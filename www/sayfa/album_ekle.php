<?php
/*======================================================================*\
|| #################################################################### ||
|| #                                                                  # ||
|| # ---------------------------------------------------------------- # ||
|| # Album Ekleme Formu      ...                                      # ||
|| #                                                                  # ||
|| # ---------------------------------------------------------------- # ||
|| #                      www.arslandizayn.com                        # ||
|| #################################################################### ||
\*======================================================================*/
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;

function resimKayit($albumno,$uzanti='')
{
//YENİ KAYITTA RESİM YÜKLEME İŞLEMLERİ
global $fonk,$dil;
$resim_dizin  = GALERI_ALBUM_DIZIN.'/a_'.$albumno.'.'.$uzanti;

if (is_uploaded_file($_FILES['resim']['tmp_name']))
{
  if (!move_uploaded_file($_FILES['resim']['tmp_name'], $resim_dizin))
  {
    return '<br /><font color="#ff0000">'.$dil['ResimYuklemeBasarisiz'].'</font>';
  } else {
    // Yeniden Boyutlandırılmış Resim Çekiliyor ve Üzerine Yazılıyor
    $icerik = $fonk->boyutlandir($resim_dizin,130,80);

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
$rvt->query("SELECT resim FROM ".TABLO_ONEKI."album WHERE albumno=$albumno");
$eski = $rvt->fetchObject();
$eski_resim = $eski->resim;
$yeni_resim = 'a_'.$albumno.'.'.$uzanti;
if ($rvt->query2("UPDATE ".TABLO_ONEKI."album SET resim='$yeni_resim' WHERE albumno=$albumno"))
{
  if ($eski_resim !== $yeni_resim)
  {
    $sil = GALERI_ALBUM_DIZIN.'/'.$eski_resim;
    @ unlink($sil);
  }
} else {
  return '<br /><font color="#ff0000">'.$dil['ResimYuklemeBasarisiz'].'</font>';
}
unset($rvt);
return '<br />'.$dil['ResimYuklendi'];
//RESİM YÜKLEME İŞLEMLERİ BİTTİ
}//Resim Yükleme Fonksiyon Sonu

//Album Klasoru FTP Ile Olusturma Fonksiyon Baslangici
function ftpKlasorOlustur($yol)
{
  $return = false;
	if (function_exists('ftp_connect'))
  {
    $ftpconn_id = ftp_connect(FTP_SERVER);
    if ($ftpconn_id)
		{
      if (ftp_login($ftpconn_id, FTP_KULLANICI_ADI, FTP_KULLANICI_SIFRE))
      {
			  if (ftp_chdir($ftpconn_id,FTP_YOL))
				{
          if(!@ftp_mkdir($ftpconn_id,$yol))
          {
            $return=false;
          } else {
            if (@ftp_chmod($ftpconn_id, 0777, $yol))
					  {
						  $return = true;
					  } else {
						  $return = false;
					  }
					}
				} else {
					$return = false;
				}
			} else {
			  $return = false;
			}
		} else {
		  $return = false;
		}
		ftp_close($ftpconn_id);
	} else {
	  $return = false;
	}
	return $return;
}
//Album Klasoru FTP Ile Olusturma Fonksiyon Sonu	


					

try
{
	if (UYE_SEVIYE == 0)
	{
    $_SESSION['sayfaadi'] = serialize($sayfa);
    header('Location: ?sayfa=giris&hata=15');
    exit;
  } 
  if (UYE_SEVIYE < GALERI_ALBUM_EKLEME_IZIN)
  {
    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[GALERI_ALBUM_EKLEME_IZIN]));
    exit;
  }
  //============================================================
	if ($islem == 1 || empty($islem)) { // 1. ADIM BASLANGICI 
	//============================================================
	$vt = new Baglanti();
  //Yazi Duzenleme Bilgileri
  @ $albumno      = intval($_GET['albumno']);
  $album_baslik   = '';
  $album_aciklama = '';
	$album_izin     = 'H';
  $resim       = GALERI_ALBUM_DIZIN.'/bos.gif';
  if (empty($albumno))
  {
    $albumsayfaadi  = $dil['ALBUM_EKLE'];
    $buton_adi     = $dil['ALBUM_EKLE']; 
  
    //Yazi Gonderme Arasi Sure 
    
    $eklenen_album = $vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE tarih > DATE_SUB(NOW(), INTERVAL ".GALERI_ALBUM_KAYIT_SURE." MINUTE) AND uyeno=".UYE_NO."");

    if ($eklenen_album > 0 && UYE_SEVIYE < GALERI_ALBUM_KAYIT_SURE)
    {
      throw new Exception($fonk->yerine_koy($dil['IslemIcinBeklemenizGerekiyor'],GALERI_ALBUM_KAYIT_SURE));
      exit;
    }
  } else {
    $albumsayfaadi  = $dil['ALBUM_DUZENLE'];
    $buton_adi     = $dil['ALBUM_DUZENLE'];

    $sure_asimi = $vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE duzentarih > DATE_SUB(NOW(), INTERVAL ".GALERI_ALBUM_DUZEN_SURE." HOUR) AND uyeno=".UYE_NO." AND albumno=$albumno");
		
    if ($sure_asimi == 0 && UYE_SEVIYE < 6)
    {
      throw new Exception($dil['ResimDuzenlemeYetkinizYok']);
      exit;
    }
    if (UYE_SEVIYE == 6)
    {
      $duzenleme_kosul = "albumno=$albumno";
    } else {
      $duzenleme_kosul = "uyeno=".UYE_NO." AND albumno=$albumno";
    }
		
    $vt->query("SELECT albumno,resim,albumadi,aciklama,izin FROM ".TABLO_ONEKI."album WHERE $duzenleme_kosul");
    $album_duzen_izin      = $vt->numRows();
    if ($album_duzen_izin == 0)
    {
      throw new Exception($dil['ResimDuzenlemeYetkinizYok']);
      exit;
    }
    $album_ayrinti        = $vt->fetchObject();
    $album_albumno        = $album_ayrinti->albumno;
    $album_baslik         = $fonk->yazdir_duzen($album_ayrinti->albumadi);
    $album_aciklama       = $fonk->yazdir_duzen($album_ayrinti->aciklama);
    $album_resim          = $album_ayrinti->resim;
		$album_izin           = $album_ayrinti->izin;

    if ($album_duzen_izin == 0)
    {
      $resim = GALERI_ALBUM_DIZIN.'/bos.gif';
    } else {
      $resim = GALERI_ALBUM_DIZIN.'/album_'.$albumno.'/'.$album_resim;
    } 
    if (!file_exists($resim) || empty($album_resim))
    {
      $resim = GALERI_ALBUM_DIZIN.'/bos.gif';
    }
    $vt->freeResult();
  }
	if (@is_array($_SESSION['albumekle']))
	{
	  $album_albumno   = unserialize($_SESSION['albumekle']['albumno']);
	  $album_baslik    = unserialize($_SESSION['albumekle']['baslik']);
		$album_aciklama  = unserialize($_SESSION['albumekle']['aciklama']);
		$album_izin      = unserialize($_SESSION['albumekle']['izin']);
	}
  ?>
  <p>&nbsp;</p>
  <p align="center">
  <h1 align="center"><?php echo $albumsayfaadi; ?></h1>
  <form id="kayit" name="kayit" action="?sayfa=albumekle&islem=2" method="post" enctype="multipart/form-data" autocomplete="off">
  <input type="hidden" name="albumno" value="<?php echo trim($albumno);?>">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo BOYUT_IZIN; ?>" />
  <table align="center" cellpadding="0" cellspacing="0" width="85%">
  <tr>
    <td width="100%" height="30" valign="middle" align="left"><b>* <?php echo $dil['Baslik']; ?>&nbsp;&nbsp;:</b><br /><input type="text" class="input" id="baslik" style="width: 230px" tabindex="1" name="baslik" maxlength="100" value="<?php echo $album_baslik; ?>" /></td>
  </tr>
  <tr>
    <td width="100%" align="left" valign="top">
		<b>&nbsp;&nbsp;<?php echo $dil['ACIKLAMA']; ?>&nbsp;&nbsp;:</b><br />
    <textarea name="aciklama" id="aciklama"  style="background: transparent url() repeat scroll 0%; -moz-background-clip: -moz-initial; -moz-background-origin:-moz-initial; -moz-background-inline-policy:-moz-initial; height:100px; width:400px;" tabindex="2" onkeyup="karakter_sayi_kontrol('aciklama',150);storeCaret(this);" onselect="storeCaret(this);" onclick="storeCaret(this);"><?php echo $album_aciklama; ?></textarea></td>
  </tr>
  <tr>
    <td width="100%" height="25" align="left"><input type="text" name="aciklama_sayac" id="aciklama_sayac" size="5" value="150" disabled="disabled" /></td>
  </tr>
	 <tr>
    <td width="100%" height="25" align="left"><input type="checkbox" name="izin" id="izin" value="E"<?php if($album_izin=='E') echo ' checked="checked"';?> />&nbsp;<?php echo $dil['HerkesResimEkleyebilir']; ?></td>
  </tr>
  <tr>
    <td width="100%" align="right" valign="center">
      <table width="130" align="center">
        <tr>
          <td width="130" height="80" align="center" valign="middle" style="border:solid 1px #3366ff"><?php echo $dil['Onizleme']; ?><br /><img src="<?php echo $resim; ?>" name="albumresim" id="albumresim"  border="0" align="center" width="200" height="120" /></td>
          <td align="left" width="65%" style="padding-left:5px; font-size:9px"><br />Max: <?php echo (BOYUT_IZIN/1024).' KB'; ?><br />
			
          <input type="file" id="resim" name="resim" onblur="if (this.value == '') { resim_degistir('<?php echo $resim; ?>','albumresim'); } else { resim_degistir(this.value,'albumresim'); }; return true;" onfocus="if (this.value == '') { resim_degistir('<?php echo $resim; ?>','albumresim'); } else { resim_degistir(this.value,'albumresim'); }; return true;" />
        </tr>
      </table>
    </td>
  </tr> 
  <tr>
    <td  width="100%" valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"width="100%"  height="20"><input type="submit" name="albumGonder" id="albumGonder" tabindex="9" value="<?php echo $buton_adi; ?>" /></td>
  </tr>
  </table>
  </form>
  <?php
	if (@ is_array($_SESSION['albumekle']))
	{
    unset($_SESSION['albumekle']); 
	}
  //==============================================================
  // 1. ADIM SONU
  //==============================================================
  } else { // 2. ADIM BASLANGICI
  //==============================================================
    $vt = new Baglanti();
	  @ $albumno  = intval($_POST['albumno']);

    // Veriler Kontrol Ediliyor
    @ $baslik    = $fonk->post_duzen($_POST['baslik']);
    @ $aciklama  = $fonk->post_duzen($_POST['aciklama']);
    @ $resim     = trim(strip_tags(htmlspecialchars($_FILES['resim']['name'])));
    @ $izin      = $fonk->post_duzen($_POST['izin']);

		$_SESSION['albumekle']['baslik']   = serialize($baslik);
    $_SESSION['albumekle']['aciklama'] = serialize($aciklama);
    $_SESSION['albumekle']['resim']    = serialize($resim);
		$_SESSION['albumekle']['albumno']  = serialize($albumno);
		$_SESSION['albumekle']['izin']     = serialize($izin);
		if ($izin != 'E') $izin = 'H';

    if (!$baslik)
    {
      throw new Exception($dil['IsaretliAlanlariBosBirakmayiniz'],1);
      exit;
		}
		if ($albumno)
		{
      if($vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE albumno=$albumno") == 0) 
			{
        throw new Exception($dil['IslemGecersiz'],1);
        exit;
      }
		} else {
		  if (UYE_SEVIYE<6)
			{
			  //Genel Yonetici Altindaki Uyeler Belirlenen Sayi Kadar Album Ekleyebilir
			  if($vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE uyeno=".UYE_NO."") > GALERI_ALBUM_EKLEME_SAYI) 
			  {
          throw new Exception($fonk->yerine_koy($dil['AlbumEklemeSayisi'],GALERI_ALBUM_EKLEME_SAYI));
          exit;
        }
			}
		}
		if (strlen($fonk->yazdir_duzen($_POST['baslik'])) > GALERI_ALBUM_AD_KARAKTER) {
      throw new Exception($fonk->yerine_koy($dil['BaslikKarakterIzin'],GALERI_ALBUM_AD_KARAKTER),1);
      exit;
    } elseif (strlen($fonk->yazdir_duzen($_POST['aciklama'])) > GALERI_ALBUM_ACIKLAMA_KARAKTER) {
      throw new Exception($fonk->yerine_koy($dil['YaziKarakterIzin'],GALERI_ALBUM_ACIKLAMA_KARAKTER),1);
      exit;
    } 
		/*
		Resim Adi Kontrolu Iptal Edildi
		if ($resim != '') {
      if (!$fonk->resim_adi_kontrol($resim))
      {
        throw new Exception($dil['ResimAdiGecersiz'],1);
        exit;
      }
    }
		*/
		//ALBUM KONTROLLERİ
		if ($resim != '')
    {
      // ALBUM EKLEME BASLANGICI
      if ($_FILES['resim']['size'] > BOYUT_IZIN)
      {
        throw new Exception($dil['ResimBoyutuBuyuk'],1);
        exit;
      } 

     if (!array_key_exists(strtolower($_FILES['resim']['type']),$yazi_resim_uzanti))
      {
        throw new Exception($dil['ResimUzantisiGecersiz'],1);
        exit;
      }

      //Resim Ekleme Hatalari
      if ($_FILES['resim']['error'] > 0)
      {
        switch ($_FILES['resim']['error'])
        {
          case 1: throw new Exception($dil['ResimBoyutuBuyuk'],1); exit; break;
          case 2: throw new Exception($dil['ResimBoyutuBuyuk'],1); exit; break;
          case 3: throw new Exception($dil['ResimYuklemeBasarisiz'],1); exit; break;
          case 4: throw new Exception($dil['ResimYuklemeBasarisiz'],1); exit; break;
        }
      }
      $uzanti       = '';
      $resim_mesaji = '';
        
      //RESİM YUKLENIYOR
      $uzanti = strtr($_FILES['resim']['type'],$yazi_resim_uzanti);
      if (!is_dir(GALERI_ALBUM_DIZIN))
      {
        throw new Exception($dil['KlasorBulunamadi'],1);
        exit;
      }
    }
    //RESİM KONTROLLERİ BİTTİ

    //ALBUM KAYIT BOLUMU
    if (empty($albumno))
    {
      //Album Gonderme Arasi Sure 
      $eklenen_album = $vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE tarih > DATE_SUB(NOW(), INTERVAL ".GALERI_ALBUM_KAYIT_SURE." MINUTE) AND uyeno=".UYE_NO."");

      if ($eklenen_album > 0 && UYE_SEVIYE < 6)
      {
        throw new Exception($fonk->yerine_koy($dil['BuIslemIcinBeklemenizGerekiyor'],GALERI_ALBUM_KAYIT_SURE),1);
        exit;
      }
      
      if (UYE_SEVIYE >= GALERI_ALBUM_ONAY)
      {
        $onay = 'E';
      } else {
        $onay = 'H';
      }
      if ($vt->query("INSERT INTO ".TABLO_ONEKI."album (albumno,uyeno,albumadi,aciklama,tarih,onay,izin) VALUES ($albumno,".UYE_NO.",'".$vt->escapeString($baslik)."','".$vt->escapeString($aciklama)."',NOW(),'$onay','$izin')"))
      {
        $album_nosu = $vt->insertId();
				$album_klasor = GALERI_ALBUM_DIZIN.'/album_'.$album_nosu;
				if (!@is_dir($album_klasor))
				{
				  
					//Klasor Eger FTP Ile Olusturulamadiysa Normal Olusturuluyor
					if (!ftpKlasorOlustur($album_klasor))
					{
				    if (!mkdir(GALERI_ALBUM_DIZIN.'/album_'.$album_nosu))
			      {
			        throw new Exception($dil['AlbumOlusturulamadi'],1);
				      exit;
			      }
					}
				}
				//RESİM YÜKLEME BAŞLANGICI
				if ($resim != '')
				{
				  $fonk->ftpChmod(0777,GALERI_ALBUM_DIZIN.'/album_'.$albumno);
				  $resim_mesaji = resimKayit($album_nosu,$uzanti);
				}
				$fonk->ftpChmod(0755,GALERI_ALBUM_DIZIN.'/album_'.$albumno);
				// RESİM YÜKLEME SONU

        if ($onay == 'H')
        {
			    unset($_SESSION['albumekle']);
          throw new Exception($dil['KayitIslemiTamamlandi']."\n".$dil['YoneticiOnayiGerekiyor'],4);
        } else {
			    unset($_SESSION['albumekle']);
          throw new Exception($dil['KayitIslemiTamamlandi'].$resim_mesaji,4);
        }
      } else {
			  @ rmdir(GALERI_ALBUM_DIZIN.'/album_'.$albumno);
        throw new Exception($dil['IslemBasarisiz'],1);
      }
    } else {
    //ALBUM DUZENLEME KAYIT
		if (!is_dir(GALERI_ALBUM_DIZIN.'/album_'.$albumno))
		{
      //Klasor Eger FTP Ile Olusturulamadiysa Normal Olusturuluyor
			if (!ftpKlasorOlustur($album_klasor))
			{
			  if (!@mkdir(GALERI_ALBUM_DIZIN.'/album_'.$albumno))
        {
          throw new Exception($dil['AlbumOlusturulamadi'],1);
          exit;
        }
			}
		}
    $sure_asimi = $vt->query("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE duzentarih > DATE_SUB(NOW(), INTERVAL ".GALERI_ALBUM_DUZEN_SURE." HOUR) AND uyeno=".UYE_NO." AND albumno=$albumno");
    if ($sure_asimi == 0 && UYE_SEVIYE < GALERI_ALBUM_DUZEN_SURE)
    {
      throw new Exception($dil['ResimDuzenlemeIzninizYok']);
      exit;
    }
    //Album Guncelleniyor
    if (UYE_SEVIYE > 5)
    {
      $duzenleme_kosul = "albumno=$albumno";
    } else {
      $duzenleme_kosul = "uyeno=".UYE_NO." AND albumno=$albumno";
    }
    $albumno_no = $vt->query("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE $duzenleme_kosul");
    if ($albumno_no == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }
    $vt->query2("UPDATE ".TABLO_ONEKI."album SET albumno=$albumno,albumadi='".$vt->escapeString($baslik)."', aciklama='".$vt->escapeString($aciklama)."',duzentarih=NOW(),izin='$izin' WHERE $duzenleme_kosul");
		
		if ($resim != '')
		{
		  $fonk->ftpChmod(0777,GALERI_ALBUM_DIZIN.'/album_'.$albumno);
		  $resim_mesaji = resimKayit($albumno,$uzanti);
		}
		$fonk->ftpChmod(0755,GALERI_ALBUM_DIZIN.'/album_'.$albumno);
    unset($_SESSION['albumekle']);
    throw new Exception($dil['DuzenlemeIslemiTamamlandi'].$resim_mesaji,5);
    
  } // Kayit Alani Sonu
  unset($duzenleme_kosul,$albumno,$buton,$baslik,$resim,$resim,$onay);
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
	if ($albumno>0)
	{
	  $adres = '<a href="index.php?sayfa=albumekle&albumno='.$albumno.'">'.$dil['Tamam'].'</a>';
	} else {
	  $adres = '<a href="index.php?sayfa=albumekle">'.$dil['Tamam'].'</a>';
	}
} elseif ($hatakod == 4) {
  unset($_SESSION['albumekle']);
  $adres = '<a href="index.php?sayfa=resimekle&albumno='.$albumno.'">'.$dil['Tamam'].'</a>';
	$hata = true;
} elseif ($hatakod == 5) {
  unset($_SESSION['albumekle']);
  $adres = '<a href="index.php?sayfa=galeri&album='.$albumno.'&islem=2">'.$dil['Tamam'].'</a>';
	$hata = true;
} else {
  unset($_SESSION['albumekle']);
  $adres = '<a href="index.php">'.$dil['Tamam'].'</a>';
	$hata = false;
}
unset($resim_dizin,$resim,$albumno,$resim_mesaji,$uzanti,$yeni_resim,$eski_resim,$rvt,$sil,$eski);
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