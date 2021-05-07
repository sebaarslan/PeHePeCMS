<?php
ob_start();
session_start();
require_once ("../icerik/vt.inc.php");
require_once ("../icerik/ayar.inc.php");
require_once ("../icerik/dil.inc.php");
dil_belirle('','yonetimdil');
require_once ("../icerik/sev.inc.php");
require_once ("../icerik/fonk.inc.php");


//Yönetici Girişi Yapılmamissa Yasakla
if (UYE_SEVIYE < 5) 
{
  header('Location: ../index.php');
  exit;
}
$fonk = new Fonksiyon();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo SITE_ADI; ?> : <?php echo $dil['YonetimPaneli']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="ystil.css" />
<script language="Javascript" type="text/Javascript">
<!-- Begin
var secim = "false";
function sec(alan,form) 
{
  dml = document.forms[form];
  len = dml.elements.length;
  
  if (secim == "false") 
  {
    for (i=0; i<len; i++) 
    {
      dml.elements[i].checked=true;
    }
    secim = "true";
  } else {
    for (i=0; i<len; i++) 
    {
      dml.elements[i].checked=false;
    }
    secim = "false";
  }
}

function islem(mesaj) 
{
  var total = 0;
  var max   = 0;
  max       = document.hizlimesaj.albumm.length;

  for (var idx = 0; idx < max; idx++) 
  {
    if (eval("document.hizlimesaj.albumm[" + idx + "].checked") == true) 
    {
      total += 1;
    }
  }
  if (total == 0)
  {
    alert("Hiç Seçim Yapmadınız");
    return false;
  } else {
    return confirm("<?php echo $dil['Secilen']; ?> = " + total + " : " + mesaj);
  }
}
//  End -->
</script>

</head>
<body background="yonetimresim/bg.gif">

<?php
@ $islem     = strip_tags(trim($_GET['islem']));
@ $album_no  = abs(intval($_GET['albumno']));
$kayit_mesaj = '';
$sil_mesaj   = '';
//=============================================
if ($islem == 2) { // Silme Islemi Baslangici
//=============================================		
  if (@is_array($_POST['albumm']))
	{
	  $klasorsil = 0;
    $albumsil  = 0;
    $resimsil  = 0;
		$vt2 = new Baglanti();

    foreach ( $_POST['albumm'] as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
			  $deger = trim(strip_tags($deger));
				
				if ($vt2->query("SELECT resim FROM ".TABLO_ONEKI."album WHERE albumno=$deger"))
				{
          $resim_ad = $vt->fetchObject()->resim;

          if ($vt->query2("DELETE FROM ".TABLO_ONEKI."resim WHERE albumno=$deger"))
				  {
						if ($vt->query2("DELETE FROM ".TABLO_ONEKI."album WHERE albumno=$deger"))
						{
						  if ($vt->affectedRows()>0)
						  {
						    if (@ unlink('../'.GALERI_ALBUM_DIZIN.'/album_'.$deger.'/'.$resim_ad))
						    {
				          if (!@rmdir('../'.GALERI_ALBUM_DIZIN.'/album_'.$deger))
					        {
					          $klasorsil++;
					          @ unlink('../'.GALERI_ALBUM_DIZIN.'/'.$resim_ad);
					        }
							  } else {
							    $resimsil++;
							  }
							} else {
							  $albumsil++; 
							}
						} else {
						  $albumsil++; 
						}
				  } else {
				    $resimsil++;
				  }
				}
      }
    }
    $sil_mesaj = '<font color="#008000">';
		if ($klasorsil>0)
		$sil_mesaj .= '<font color="#ff0000">'.$fonk->yerine_koy($dil['KlasorSilinemedi'],$klasorsil).'</font><br />';
		if ($albumsil>0)
		$sil_mesaj .= '<font color="#ff0000">'.$fonk->yerine_koy($dil['AlbumSilinemedi'],$albumsil).'</font><br />';
		if ($resimsil>0)
		$sil_mesaj .= '<font color="#ff0000">'.$fonk->yerine_koy($dil['ResimSilinemedi'],$resimsil).'</font><br />';
		
		$sil_mesaj .= $dil['SilmeIslemiTamamlandi'].'</font>';
	} else {
	  $sil_mesaj = '<font color="#ff0000">'.$dil['SecimYapmadiniz'].'</font>';
	}

} elseif ($islem == 3) {
  /* ============================
  // ALBUM ONAY AYARLARI 
  ==============================*/
	@ $albumno  =  abs(intval($_GET['albumno']));
  @ $onay     = trim(strip_tags(htmlspecialchars($_GET['onay'])));
  if ($onay == 'E')
	{
	  $onay = 'E';
	} else {
	  $onay = 'H';
	}
  try
	{ 
	  
		if ($vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE albumno=$albumno") == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }
    
		if ($vt->query2("UPDATE ".TABLO_ONEKI."album SET onay='$onay' WHERE albumno=$albumno"))
    {
      $kayit_mesaj = '<font color="#008000">'.$dil['IslemTamamlandi'].'</font>';
    } else {
      $kayit_mesaj = '<font color="#ff0000">'.$dil['IslemBasarisiz'].'</font>';
    }
	}	
	catch (Exception $e)
  {
    $kayit_mesaj = '<font color="#ff0000">'.$e->getMessage().'</font>';
  }	
}


  @ $mesajara = trim(htmlspecialchars($_REQUEST['mesajara']));
  if (empty($mesajara))
  {
    $mesaj_kosul = '';
  } else {
	  $mesaj_kosul = "WHERE albumadi LIKE '%$mesajara%' OR aciklama LIKE '%$mesajara%'";
  }

  $toplamalbum = $vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album ".$mesaj_kosul."");
	$limit = 30; //Bir Sayfada Gösterilecek Hizli Mesaj Sayısı
  @ $s = abs(intval($_GET['s']));
  
  if(empty($s) || ($s > ceil($toplamalbum/$limit))) 
  {                
    $s = 1;                
    $baslangic = 0;        
  } else {               
    $baslangic = ($s - 1) * $limit;        
  }

	if (!empty($album_no))
	{
	  $vt->query("SELECT albumno,uyeno,albumadi,aciklama,tarih,onay FROM ".TABLO_ONEKI."album WHERE albumno=$album_no");
		$album_bilgi = $vt->fetchObject();
		$album_no    = $album_bilgi->albumno;
		$aciklama    = $album_bilgi->aciklama;
		$uyeno       = $album_bilgi->uyeno;
		$vt->freeResult();
	}

	$vt->query("SELECT albumno,uyeno,resim,albumadi,aciklama,tarih,onay FROM ".TABLO_ONEKI."album ".$mesaj_kosul." ORDER BY onay DESC,tarih DESC LIMIT $baslangic,$limit");
?>

<table border="1" align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['RESIM_GALERISI']; ?></b></font></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
				<?php
				if ($kayit_mesaj)
				{
				?>
				<tr>
          <td width="100%" colspan="6" align="center"><?php echo $kayit_mesaj; ?></td>
        </tr>
				<?php
				}
				?>
        <tr bgcolor="#b6c5f2">
		      <td colspan="6" align="center" height="20" class="border_4"><a href="album_yonet.php"><b><?php echo $dil['AlbumYonet']; ?></b></a></td>
		    </tr>
				<form method="post" action="album_yonet.php">
        <tr>
          <td colspan="3" align="left"><?php echo $dil['ToplamAlbum']; ?> : <b><?php echo $toplamalbum; ?></b></td>
          <td width="100%" align="center" colspan="4"><input type="text" name="mesajara" id="mesajara" size="20" class="input" value="<?php echo $mesajara; ?>" /> <input type="submit" value="<?php echo $dil['ARA']; ?>" class="input" /></td>
        </tr>
        </form>
				<?php 
				if($sil_mesaj)
				{
				  echo '<tr><td width="100%" align="center" colspan="6">'.$sil_mesaj.'</td></tr>';
				}
				?>
				<form action="album_yonet.php?islem=2" method="post" name="album" id="album">
        <tr bgcolor="#b6c5f2">
        <td width="5%" align="center"><input type="checkbox" onclick="sec('albumm','album')" class="input" /></td>
        <td width="5%" align="center"><b>SN</b></td>
        <td width="15%" align="center"><b><?php echo $dil['ALBUM']; ?></b></td>
        <td width="50%" align="center"><b><?php echo $dil['ACIKLAMA']; ?></b></td>
        <td width="10%" align="center"><b><?php echo $dil['TARIH']; ?></b></td>
				<td width="15%" align="center"><b><?php echo $dil['ONAY_DURUMU']; ?></b></td>
      </tr>
			<?php
      $sira = 0;
      $sirano = 0;
      if (empty($toplamalbum))
      {
        echo '<tr bgcolor="#f7f7fd">
          <td width="100%" align="center" colspan="9"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td>
        </tr>';
      } else {
			  $vt2 = new Baglanti();
        while ($album_veri = $vt->fetchObject())
        {
          $sira++;
          $sirano      = $sira+$baslangic;
					$albumno     = $album_veri->albumno;
					$albumadi    = $album_veri->albumadi;
          $aciklama    = $album_veri->aciklama;
          $uyeno       = $album_veri->uyeno;
					$tarih       = $album_veri->tarih;
					$onay        = $album_veri->onay;
					$album_resim = $album_veri->resim;
					$aciklama = substr($aciklama,0,70);
					
					$resim = '../'.GALERI_ALBUM_DIZIN.'/'.$album_resim;
          if (!file_exists($resim) || !$album_resim)
          $resim = GALERI_ALBUM_DIZIN.'/klasor.gif';
					else
					$resim = GALERI_ALBUM_DIZIN.'/'.$album_resim;
						
					$resim_sayi = $vt2->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE albumno=$albumno");
						
					if ($onay == 'E')
					{
					  $onay_mesaj = '<font color="#008000">'.$dil['Onayli'].'</font><br /><a href="album_yonet.php?islem=3&albumno='.$albumno.'&onay=H">'.$dil['OnayiKaldir'].'</a>';
					} else {
					  $onay_mesaj = '<font color="#ff0000">'.$dil['Onaysiz'].'</font><br /><a href="album_yonet.php?islem=3&albumno='.$albumno.'&onay=E">'.$dil['Onayla'].'</a>';
					}
          echo '
          <tr bgcolor="#f7f7fd">
            <td width="5%" align="center"><input type="checkbox" id="albumm" name="albumm[]" value="'.$albumno.'" class="input" /></td>
					  <td width="5%" align="center"><b>'.$sirano.'</b></td>
					  <td width="15%" align="center">
						<a href="../?sayfa=albumekle&albumno='.$albumno.'"><img src="../resim.php?resim='.$resim.'&en=130&boy=80" border="0" alt="'.$albumadi.'" title="'.$albumadi.'" align="absmiddle" /></a></td>
					  <td width="50%" align="left"><a href="../?sayfa=albumekle&albumno='.$albumno.'">'.$albumadi.'</a><br />'.$aciklama.'</td>
					  <td width="10%" align="center">'.$fonk->uye_adi($uyeno).'<br />'.$fonk->duzgun_tarih_saat($tarih,true).'<br />'.$dil['Resim'].' : '.$resim_sayi.'</td>
						<td width="15%" align="center">'.$onay_mesaj.'</td>
				   </tr>';
        }
			  unset($vt2,$sirano,$albumno,$aciklama,$uyeno,$tarih);
			  $vt->freeResult();
	  }
			?>

      <tr>
        <td colspan="6" width="100%" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dil['Secilileri']; ?> : </font>
          <input type="submit" value="<?php echo $dil['Sil']; ?>" name="islemsil" onclick="return islem('<?php echo $dil['SilmekIstiyormusunuz']; ?>')" class="input" />&nbsp;</td>
      </tr>	
			</form>
      <tr>
        <td colspan="6" width="100%" align="center">
				  <table width="90%" align="center">
          <tr>
            <td width="50%" align="left">&nbsp;&nbsp;
						  <?php
              if ($s > 1)
              {
                $onceki = $s-1;
                echo '<a href="album_yonet.php?s='.$onceki.'&ipara='.$ipara.'">«&nbsp;'.$dil['Onceki'].'</a>';
              }
              echo '
            </td>
            <td width="50%" align="right">';
            if ($toplamalbum > ($s*$limit))
            {
              $sonraki = $s+1;
              echo '<a href="album_yonet.php?s='.$sonraki.'&ipara='.$ipara.'">'.$dil['Sonraki'].'&nbsp;»</a>';
            }
            ?>
						&nbsp;&nbsp;
           </td>
          </tr>
        </table>
				</td>
      </tr>
	  </table>
  </td>
</tr>
</table>
</body>
</html>