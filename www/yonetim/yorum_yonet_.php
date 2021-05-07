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
  max       = document.yorum.yyorum.length;

  for (var idx = 0; idx < max; idx++) 
  {
    if (eval("document.yorum.yyorum[" + idx + "].checked") == true) 
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
@ $yorum_no  = abs(intval($_GET['yorumno']));
$kayit_mesaj = '';
$sil_mesaj   = '';

if ($islem == 2) {					
  if (is_array($_POST['yyorum']))
	{
    foreach ( $_POST['yyorum'] as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
			  $deger = trim(strip_tags($deger));
        $ip_sil = $vt->query2("DELETE FROM ".TABLO_ONEKI."yorumlar WHERE yorumno=$deger");
      }
    }
    $sil_mesaj = '<font color="#008000">'.$dil['SilmeIslemiTamamlandi'].'</font>';
	} else {
	  $sil_mesaj = '<font color="#ff0000">'.$dil['SecimYapmadiniz'].'</font>';
	}

} elseif ($islem == 3) {
  /* ============================
  // YORUM DÜZENLEME KAYIT 
  ==============================*/
  
  // FORMDAN GELEN VERİLER
  @ $yorumno   =  abs(intval($_POST['yorumno']));
  @ $yorum     = trim(strip_tags(htmlspecialchars($_POST['yorum'])));

  try
	{ 
	  if (empty($yorumno) || empty($yorum))
	  {
		  throw new Exception($dil['BosAlanBirakmayiniz']);
			exit;
	  }

		if ($vt->kayitSay("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE yorumno=$yorumno") == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }
    
    if ($vt->query2("UPDATE ".TABLO_ONEKI."yorumlar SET yorum='$yorum' WHERE yorumno=$yorumno"))
    {
      $kayit_mesaj = '<font color="#008000">'.$dil['GuncellemeIslemiTamamlandi'].'</font>';
    } else {
      $kayit_mesaj = '<font color="#ff0000">'.$dil['IslemBasarisiz'].'</font>';
    }
	}
	catch (Exception $e)
  {
    $kayit_mesaj = '<font color="#ff0000">'.$e->getMessage().'</font>';
  }
} elseif ($islem == 4) {
  /* ============================
  // YORUM ONAY AYARLARI 
  ==============================*/
	@ $yorumno  =  abs(intval($_GET['yorumno']));
  @ $onay     = trim(strip_tags(htmlspecialchars($_GET['onay'])));
  if ($onay == 'E')
	{
	  $onay = 'E';
	} else {
	  $onay = 'H';
	}
  try
	{ 
		if ($vt->kayitSay("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE yorumno=$yorumno") == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }

		if ($vt->query2("UPDATE ".TABLO_ONEKI."yorumlar SET onay='$onay' WHERE yorumno=$yorumno"))
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


  @ $yorumara = trim(htmlspecialchars($_REQUEST['yorumara']));
  if (empty($yorumara))
  {
    $yorum_kosul = '';
  } else {
	  $yorum_kosul = "WHERE yorum LIKE '%$yorumara%'";
  }

  $toplamyorum = $vt->kayitSay("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar ".$yorum_kosul."");
	$limit = 30; //Bir Sayfada Gösterilecek Hizli Mesaj Sayısı
  @ $s = abs(intval($_GET['s']));
  
  if(empty($s) || ($s > ceil($toplamyorum/$limit))) 
  {                
    $s = 1;                
    $baslangic = 0;        
  } else {               
    $baslangic = ($s - 1) * $limit;        
  }

	if (!empty($yorum_no))
	{
	  $vt->query("SELECT yorumno,uyeno,yazino,resimno,yorum,tarih FROM ".TABLO_ONEKI."yorumlar WHERE yorumno=$yorum_no");
		$yorum_bilgi = $vt->fetchObject();
		$yorum_no    = $yorum_bilgi->yorumno;
		$yorum       = $yorum_bilgi->yorum;
		$uyeno       = $yorum_bilgi->uyeno;
		$vt->freeResult();
	}

	$vt->query("SELECT yorumno,uyeno,yazino,resimno,yorum,tarih,onay FROM ".TABLO_ONEKI."yorumlar ".$yorum_kosul." ORDER BY tarih DESC LIMIT $baslangic,$limit");
?>

<table border="1" align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['YorumYonet']; ?></b></font></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
			  <?php
				if ($yorum_no)
				{
				?>
				<tr>
          <td width="100%" colspan="7" align="center">
					<form action="yorum_yonet.php?islem=3" method="post" name="yorumduzen" id="yorumduzen">
          <input type="hidden" name="yorumno" id="yorumno" value="<?php echo $yorum_no; ?>" />
					  <table width="100%" align="center">
						  <tr bgcolor="#b6c5f2">
		            <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['YORUM_DUZENLEME']; ?></b></td>
		          </tr>
							<tr>
							  <td width="100%" align="center" colspan="2"><b><?php echo $dil['Yorum']; ?></b> <br /><textarea name="yorum" id="yorum" cols="70" rows="5"><?php echo $yorum; ?></textarea></td>
							</tr>
							<tr>
							  <td width="100%" colspan="2" align="center"><input type="submit" id="yorumduzen" name="yorumduzen" value="<?php echo $dil['DUZENLE']; ?>" class="input" /></td>
							</tr>
							
						</table>
					  </form>
					</td>
        </tr>
				<?php
				}
				if ($kayit_mesaj)
				{
				?>
				<tr>
          <td width="100%" colspan="7" align="center"><?php echo $kayit_mesaj; ?></td>
        </tr>
				<?php
				}
				?>
        <tr bgcolor="#b6c5f2">
		      <td colspan="7" align="center" height="20" class="border_4"><a href="yorum_yonet.php"><b><?php echo $dil['YORUMLAR']; ?></b></a></td>
		    </tr>
				<form method="post" action="yorum_yonet.php">
        <tr>
          <td colspan="3" align="left"><?php echo $dil['ToplamYorum']; ?> : <b><?php echo $toplamyorum; ?></b></td>
          <td width="100%" align="center" colspan="4"><b><?php echo $dil['ARA']; ?> : </b> <input type="text" name="yorumara" id="yorumara" size="20" class="input" /> <input type="submit" value="<?php echo $dil['ARA']; ?>" class="input" /></td>
        </tr>
        </form>
				<?php 
				if($sil_mesaj)
				{
				  echo '<tr><td width="100%" align="center" colspan="7">'.$sil_mesaj.'</td></tr>';
				}
				?>
				<form action="yorum_yonet.php?islem=2" method="post" name="yorum" id="yorum">
        <tr bgcolor="#b6c5f2">
        <td width="5%" align="center"><input type="checkbox" onclick="sec('yyorum','yorum')" class="input" /></td>
        <td width="5%" align="center"><b>SN</b></td>
        <td width="15%" align="center"><b><?php echo $dil['EKLEYEN']; ?></b></td>
        <td width="50%" align="center"><b><?php echo $dil['YORUM']; ?></b></td>
        <td width="10%" align="center"><b><?php echo $dil['TARIH']; ?></b></td>
				<td width="50%" align="center"><b><?php echo $dil['GRUBU']; ?></b></td>
				<td width="15%" align="center"><b><?php echo $dil['ONAY_DURUMU']; ?></b></td>
				
      </tr>
			<?php
      $sira = 0;
      $sirano = 0;
      if (empty($toplamyorum))
      {
        echo '<tr bgcolor="#f7f7fd">
          <td width="100%" align="center" colspan="9"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td>
        </tr>';
      } else {
        while ($yorum_veri = $vt->fetchObject())
        {
          $sira++;
          $sirano   = $sira+$baslangic;
					$yorumno  = $yorum_veri->yorumno;
          $yorum    = $yorum_veri->yorum;
          $uyeno    = $yorum_veri->uyeno;
					$tarih    = $yorum_veri->tarih;
					$onay     = $yorum_veri->onay;
					$resimno  = $yorum_veri->resimno;
					$yazino   = $yorum_veri->yazino;
					$yorum    = substr($yorum,0,70);
					if ($onay == 'E')
					{
					  $onay_mesaj = '<font color="#008000">'.$dil['Onayli'].'</font><br /><a href="yorum_yonet.php?islem=4&yorumno='.$yorumno.'&onay=H">'.$dil['OnayiKaldir'].'</a>';
					} else {
					  $onay_mesaj = '<font color="#ff0000">'.$dil['Onaysiz'].'</font><br /><a href="yorum_yonet.php?islem=4&yorumno='.$yorumno.'&onay=E">'.$dil['Onayla'].'</a>';
					}
          echo '
          <tr bgcolor="#f7f7fd">
            <td width="5%" align="center"><input type="checkbox" id="yyorum" name="yyorum[]" value="'.$yorumno.'" class="input" /></td>
					  <td width="5%" align="center"><b>'.$sirano.'</b></td>
					  <td width="15%" align="center">'.$fonk->uye_adi($uyeno).'</td>
					  <td width="50%" align="left"><a href="yorum_yonet.php?yorumno='.$yorumno.'">'.$yorum.'</a></td>
					  <td width="10%" align="center">'.$fonk->duzgun_tarih_saat($tarih,true).'</td>
						<td width="10%" align="center">';
						if ($resimno>0) 
						echo $dil['Resimler'];
						else
						echo $dil['Yazilar'];
						echo '</td>
						<td width="15%" align="center">'.$onay_mesaj.'</td>
				   </tr>';
        }
			  unset($sirano,$yorumno,$yorum,$uyeno,$tarih);
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
                echo '<a href="yorum_yonet.php?s='.$onceki.'&ipara='.$ipara.'">«&nbsp;'.$dil['Onceki'].'</a>';
              }
              echo '
            </td>
            <td width="50%" align="right">';
            if ($toplamyorum > ($s*$limit))
            {
              $sonraki = $s+1;
              echo '<a href="yorum_yonet.php?s='.$sonraki.'&ipara='.$ipara.'">'.$dil['Sonraki'].'&nbsp;»</a>';
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