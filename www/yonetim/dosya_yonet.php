<?php
ob_start();
session_start();
require_once ("../icerik/vt.inc.php");
require_once ("../icerik/ayar.inc.php");
require_once ("../icerik/dil.inc.php");
@ dil_belirle('','yonetimdil');
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

function islem(dosyalar) 
{
  var total = 0;
  var max   = 0;
  max       = document.dosyayonetim.dosya.length;

  for (var idx = 0; idx < max; idx++) 
  {
    if (eval("document.dosyayonetim.dosya[" + idx + "].checked") == true) 
    {
      total += 1;
    }
  }
  if (total == 0)
  {
    alert("<?php echo $dil['SecimYapmadiniz']; ?>");
    return false;
  } else {
    return confirm("<?php echo $dil['Secilen']; ?> = " + total + " : " + dosyalar);
  }
}
//  End -->
</script>

</head>
<body background="yonetimresim/bg.gif">

<?php
@ $islem     = strip_tags(trim($_GET['islem']));
@ $dosya_no  = abs(intval($_GET['dosyano']));
$kayit_mesaj = '';
$sil_dosya   = '';
//=====================================
if ($islem == 2) { //SILME ISLEMI
//=====================================			
  if (is_array($_POST['dosya']))
	{
    foreach ( $_POST['dosya'] as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
			  $deger = trim(strip_tags($deger));
        $dosya_sil = $vt->query2("DELETE FROM ".TABLO_ONEKI."dosyalar WHERE dosyano=$deger");
      }
    }
    $sil_dosya = '<font color="#008000">'.$dil['SilmeIslemiTamamlandi'].'</font>';
	} else {
	  $sil_dosya = '<font color="#ff0000">'.$dil['SecimYapmadiniz'].'</font>';
	}

} elseif ($islem == 3) {
  /* ============================
  // DOSYA DÜZENLEME KAYIT 
  ==============================*/
  
  // FORMDAN GELEN VERİLER
  @ $dosyano        = abs(intval($_POST['dosyano']));
  @ $dosyaadi       = trim(strip_tags(htmlspecialchars($_POST['dosyaadi'])));
	@ $dosyayolu      = trim(strip_tags(htmlspecialchars($_POST['dosyayolu'])));
	@ $dosyadeneme    = trim(strip_tags(htmlspecialchars($_POST['dosyadeneme'])));
	@ $dosyaaciklama  = trim(strip_tags(htmlspecialchars($_POST['dosyaaciklama'])));

  try
	{ 
	  if (empty($dosyaadi) || empty($dosyayolu))
	  {
		  throw new Exception($dil['DosyaAdiDosyaYoluBos']);
			exit;
	  }
    if (empty($dosyano))
		{
		  //DOSYA KAYIT İŞLEMİ
			if ($vt->query2("INSERT INTO ".TABLO_ONEKI."dosyalar (dosyaadi,dosyayolu,dosyadeneme,dosyaaciklama,dosyakayittarih) VALUES ('$dosyaadi','$dosyayolu','$dosyadeneme','$dosyaaciklama',NOW())"))
			{
			  $kayit_mesaj = '<font color="#008000">'.$dil['KayitIslemiTamamlandi'].'</font>';
			} else {
			  $kayit_mesaj = '<font color="#ff0000">'.$dil['IslemBasarisiz'].'</font>';
			}
		} else {
      //DOSYA DÜZENLEME İŞLEMİ
			
		  if ($vt->kayitSay("SELECT COUNT(dosyano) FROM ".TABLO_ONEKI."dosyalar WHERE dosyano=$dosyano") == 0)
      {
        throw new Exception($dil['IslemGecersiz']);
        exit;
      }
      $duzenkayit = intval($_POST['duzenkayit']);
			if ($duzenkayit == 1)
			$duzen_kayit = ',dosyaduzentarih=NOW()';
			else
			$duzenkayit = '';
      if ($vt->query2("UPDATE ".TABLO_ONEKI."dosyalar SET dosyaadi='$dosyaadi',dosyayolu='$dosyayolu',dosyadeneme='$dosyadeneme',dosyaaciklama='$dosyaaciklama'".$duzen_kayit." WHERE dosyano=$dosyano"))
      {
			  $dosya_no = $dosyano;
        $kayit_mesaj = '<font color="#008000">'.$dil['GuncellemeIslemiTamamlandi'].'</font>';
      } else {
        $kayit_mesaj = '<font color="#ff0000">'.$dil['IslemBasarisiz'].'</font>';
      }
		}
	}
	catch (Exception $e)
  {
    $kayit_mesaj = '<font color="#ff0000">'.$e->getMessage().'</font>';
  }
} elseif ($islem == 4) {
  /* ============================
  // DOSYA ONAY AYARLARI 
  ==============================*/
	@ $dosyano  =  abs(intval($_GET['dosyano']));
  @ $onay     = trim(strip_tags(htmlspecialchars($_GET['onay'])));
  if ($onay == 'E')
	{
	  $onay = 'E';
	} else {
	  $onay = 'H';
	}
  try
	{ 
		if ($vt->kayitSay("SELECT COUNT(dosyano) FROM ".TABLO_ONEKI."dosyalar WHERE dosyano=$dosyano") == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }

		if ($vt->query2("UPDATE ".TABLO_ONEKI."dosyalar SET dosyaonay='$onay' WHERE dosyano=$dosyano"))
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


  @ $dosyaara = trim(htmlspecialchars($_REQUEST['dosyaara']));
  if (empty($dosyaara))
  {
    $dosya_kosul = '';
  } else {
	  $dosya_kosul = "WHERE dosyaadi LIKE '%$dosyaara%' OR dosyaaciklama LIKE '%$dosyaara%'";
  }

  $toplamdosya = $vt->kayitSay("SELECT COUNT(dosyano) FROM ".TABLO_ONEKI."dosyalar ".$dosya_kosul."");
	$limit = 30; //Bir Sayfada Gösterilecek Dosya Sayısı
  @ $s = abs(intval($_GET['s']));
  
  if(empty($s) || ($s > ceil($toplamdosya/$limit))) 
  {                
    $s = 1;                
    $baslangic = 0;        
  } else {               
    $baslangic = ($s - 1) * $limit;        
  }

	if (empty($dosya_no))
	{
	  $dosya_no = 0;
		$dosyaadi = '';
		$dosyayolu = '';
		$dosyadeneme = '';
		$dosyaaciklama = '';
	  $dosya_buton = 'YENİ DOSYAYI KAYDET';
	} else {
	  $vt->query("SELECT dosyano,dosyaadi,dosyayolu,dosyadeneme,dosyaaciklama,dosyakayittarih,dosyaduzentarih,dosyaindirsayi,dosyaonay FROM ".TABLO_ONEKI."dosyalar WHERE dosyano=$dosya_no");
		$dosya_bilgi   = $vt->fetchObject();
		$dosya_no      = $dosya_bilgi->dosyano;
		$dosyaadi      = $dosya_bilgi->dosyaadi;
		$dosyayolu     = $dosya_bilgi->dosyayolu;
		$dosyadeneme   = $dosya_bilgi->dosyadeneme;
		$dosyaaciklama = $dosya_bilgi->dosyaaciklama;
		$vt->freeResult();
		$dosya_buton = 'DOSYAYI DÜZENLE';
	}

	$vt->query("SELECT dosyano,dosyaadi,dosyayolu,dosyadeneme,dosyaaciklama,dosyakayittarih,dosyaduzentarih,dosyaindirsayi,dosyaonay FROM ".TABLO_ONEKI."dosyalar ".$dosya_kosul." ORDER BY dosyakayittarih DESC LIMIT $baslangic,$limit");
?>

<table border="1" align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['DOSYA_YONETIMI']; ?></b></font></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
				<tr>
          <td width="100%" colspan="6" align="center">
					<form action="dosya_yonet.php?islem=3" method="post" name="dosyaduzen" id="dosyaduzen">
          <input type="hidden" name="dosyano" id="dosyano" value="<?php echo $dosya_no; ?>" />
					  <table width="100%" align="center">
						  <tr bgcolor="#b6c5f2">
		            <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['DOSYA_EKLE_DUZENLE']; ?></b></td>
		          </tr>
							<tr>
							  <td align="center"><font color="#ff0000">*</font>&nbsp;<b><?php echo $dil['DosyaAdi']; ?> : </b><br /><input type="text" name="dosyaadi" id="dosyaadi" size="80" class="input" maxlength="100" value="<?php echo $dosyaadi; ?>" /></td>
							</tr>
							<tr>
							  <td align="center"><font color="#ff0000">*</font>&nbsp;<b><?php echo $dil['DosyaYolu']; ?> : </b><br /><input type="text" name="dosyayolu" id="dosyayolu" size="80" class="input" maxlength="100" value="<?php echo $dosyayolu; ?>" /></td>
							<tr>
							<tr>
							  <td align="center"><b><?php echo $dil['DosyaDenemeAdresi']; ?> : </b><br /><input type="text" name="dosyadeneme" id="dosyadeneme" size="80" class="input" maxlength="100" value="<?php echo $dosyadeneme; ?>" /></td>
							<tr>
							<tr>
							  <td width="100%" align="center" colspan="2"><b><?php echo $dil['DosyaAciklama']; ?></b> <br /><textarea name="dosyaaciklama" id="dosyaaciklama" cols="70" rows="7"><?php echo $dosyaaciklama; ?></textarea></td>
							</tr>
							<tr>
							  <td width="100%" colspan="2" align="center">
								<?php 
								if ($dosya_no)
								{
								  echo '<a href="dosya_yonet.php">'.$dil['YeniDosyaEkle'].'</a><br />';
									echo '<input type="checkbox" name="duzenkayit" value="1" title="'.$dil['DuzenTarihGuncelle'].'" />&nbsp;&nbsp;';
								}
								?>
								<input type="submit" id="dosyaduzen" name="dosyaduzen" value="<?php echo $dosya_buton; ?>" class="input" /></td>
							</tr>
							
						</table>
					  </form>
					</td>
        </tr>
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
		      <td colspan="6" align="center" height="20" class="border_4"><a href="dosya_yonet.php"><b><?php echo $dil['DOSYALAR']; ?></b></a></td>
		    </tr>
				<form method="post" action="dosya_yonet.php">
        <tr>
          <td colspan="3" align="left"><?php echo $dil['ToplamDosya']; ?> : <b><?php echo $toplamdosya; ?></b></td>
          <td width="100%" align="center" colspan="4"><b><?php echo $dil['DOSYA_ARA']; ?> : </b> <input type="text" name="dosyaara" id="dosyaara" size="20" class="input" /> <input type="submit" value="<?php echo $dil['ARA']; ?>" class="input" /></td>
        </tr>
        </form>
				<?php 
				if($sil_dosya)
				{
				  echo '<tr><td width="100%" align="center" colspan="6">'.$sil_dosya.'</td></tr>';
				}
				?>
				<form action="dosya_yonet.php?islem=2" method="post" name="dosyayonetim" id="dosyayonetim">
        <tr bgcolor="#b6c5f2">
        <td width="5%" align="center"><input type="checkbox" onclick="sec('dosya','dosyayonetim')" class="input" /></td>
        <td width="5%" align="center"><b>SN</b></td>
        <td width="50%" align="center"><b><?php echo $dil['DOSYA_ADI']; ?></b></td>
        <td width="10%" align="center"><b><?php echo $dil['KAYIT_TARIHI']; ?></b></td>
        <td width="10%" align="center"><b><?php echo $dil['DUZENLEME_TARIHI']; ?></b></td>
				<td width="20%" align="center"><b><?php echo $dil['ONAY_DURUMU']; ?></b></td>
      </tr>
			<?php
      $sira = 0;
      $sirano = 0;
      if (empty($toplamdosya))
      {
        echo '<tr bgcolor="#f7f7fd">
          <td width="100%" align="center" colspan="9"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td>
        </tr>';
      } else {
        while ($dosya_veri = $vt->fetchObject())
        {
          $sira++;
          $sirano          = $sira+$baslangic;
					$dosyano         = $dosya_veri->dosyano;
          $dosyaadi        = $dosya_veri->dosyaadi;
          $dosyayolu       = $dosya_veri->dosyayolu;
					$dosyakayittarih = $dosya_veri->dosyakayittarih;
					$dosyaduzentarih = $dosya_veri->dosyaduzentarih;
					$onay            = $dosya_veri->dosyaonay;
					$dosyaaciklama   = $dosya_veri->dosyaaciklama;
					$dosyaaciklama   = substr($dosyaaciklama,0,70);
					if ($onay == 'E')
					{
					  $onay_dosya = '<font color="#008000">'.$dil['Onayli'].'</font><br /><a href="dosya_yonet.php?islem=4&dosyano='.$dosyano.'&onay=H">'.$dil['OnayiKaldir'].'</a>';
					} else {
					  $onay_dosya = '<font color="#ff0000">'.$dil['Onaysiz'].'</font><br /><a href="dosya_yonet.php?islem=4&dosyano='.$dosyano.'&onay=E">'.$dil['Onayla'].'</a>';
					}
          ?>
          <tr bgcolor="#f7f7fd">
            <td width="5%" align="center"><input type="checkbox" id="dosya" name="dosya[]" value="<?php echo $dosyano; ?>" class="input" /></td>
					  <td width="5%" align="center"><b><?php echo $sirano; ?></b></td>
					  <td width="50%" align="left"><a href="dosya_yonet.php?dosyano=<?php echo $dosyano; ?>"><?php echo $dosyaadi; ?></a></td>
					  <td width="10%" align="center"><?php echo $fonk->duzgun_tarih_saat($dosyakayittarih,true); ?></td>
					  <td width="10%" align="center"><?php echo $fonk->duzgun_tarih_saat($dosyaduzentarih,true); ?></td>
						<td width="20%" align="center"><?php echo $onay_dosya; ?></td>
				   </tr>
				<?php
        }
			  unset($sirano,$dosyano,$dosyaadi,$dosyayolu,$tarih);
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
                echo '<a href="dosya_yonet.php?s='.$onceki.'&dosyaara='.$dosyaara.'">«&nbsp;'.$dil['Onceki'].'</a>';
              }
              echo '
            </td>
            <td width="50%" align="right">';
            if ($toplamdosya > ($s*$limit))
            {
              $sonraki = $s+1;
              echo '<a href="dosya_yonet.php?s='.$sonraki.'&dosyaara='.$dosyaara.'">'.$dil['Sonraki'].'&nbsp;»</a>';
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