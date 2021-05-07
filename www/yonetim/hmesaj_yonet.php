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
  max       = document.hizlimesaj.hmesaj.length;

  for (var idx = 0; idx < max; idx++) 
  {
    if (eval("document.hizlimesaj.hmesaj[" + idx + "].checked") == true) 
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
@ $mesaj_no  = abs(intval($_GET['mesajno']));
$kayit_mesaj = '';
$sil_mesaj   = '';

if ($islem == 2) {					
  if (is_array($_POST['hmesaj']))
	{
    foreach ( $_POST['hmesaj'] as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
			  $deger = trim(strip_tags($deger));
        $ip_sil = $vt->query2("DELETE FROM ".TABLO_ONEKI."hizlimesaj WHERE mesajno=$deger");
      }
    }
    $sil_mesaj = '<font color="#008000">'.$dil['SilmeIslemiTamamlandi'].'</font>';
	} else {
	  $sil_mesaj = '<font color="#ff0000">'.$dil['SecimYapmadiniz'].'</font>';
	}

} elseif ($islem == 3) {
  /* ============================
  // MESAJ DÜZENLEME KAYIT 
  ==============================*/
  
  // FORMDAN GELEN VERİLER
  @ $mesajno   =  abs(intval($_POST['mesajno']));
  @ $mesaj     = trim(strip_tags(htmlspecialchars($_POST['mesaj'])));

  try
	{ 
	  if (empty($mesajno) || empty($mesaj))
	  {
		  throw new Exception($dil['BosAlanBirakmayiniz']);
			exit;
	  }

		if ($vt->kayitSay("SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."hizlimesaj WHERE mesajno=$mesajno") == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }
    
    if ($vt->query2("UPDATE ".TABLO_ONEKI."hizlimesaj SET mesaj='$mesaj' WHERE mesajno=$mesajno"))
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
  // MESAJ ONAY AYARLARI 
  ==============================*/
	@ $mesajno  =  abs(intval($_GET['mesajno']));
  @ $onay     = trim(strip_tags(htmlspecialchars($_GET['onay'])));
  if ($onay == 'E')
	{
	  $onay = 'E';
	} else {
	  $onay = 'H';
	}
  try
	{ 
		if ($vt->kayitSay("SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."hizlimesaj WHERE mesajno=$mesajno") == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }

		if ($vt->query2("UPDATE ".TABLO_ONEKI."hizlimesaj SET onay='$onay' WHERE mesajno=$mesajno"))
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
	  $mesaj_kosul = "WHERE mesaj LIKE '%$mesajara%'";
  }

  $toplammesaj = $vt->kayitSay("SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."hizlimesaj ".$mesaj_kosul."");
	$limit = 30; //Bir Sayfada Gösterilecek Hizli Mesaj Sayısı
  @ $s = abs(intval($_GET['s']));
  
  if(empty($s) || ($s > ceil($toplammesaj/$limit))) 
  {                
    $s = 1;                
    $baslangic = 0;        
  } else {               
    $baslangic = ($s - 1) * $limit;        
  }

	if (!empty($mesaj_no))
	{
	  $vt->query("SELECT mesajno,uyeno,mesaj,tarih FROM ".TABLO_ONEKI."hizlimesaj WHERE mesajno=$mesaj_no");
		$mesaj_bilgi = $vt->fetchObject();
		$mesaj_no    = $mesaj_bilgi->mesajno;
		$mesaj       = $mesaj_bilgi->mesaj;
		$uyeno       = $mesaj_bilgi->uyeno;
		$vt->freeResult();
	}

	$vt->query("SELECT mesajno,uyeno,mesaj,tarih,onay FROM ".TABLO_ONEKI."hizlimesaj ".$mesaj_kosul." ORDER BY tarih DESC LIMIT $baslangic,$limit");
?>

<table border="1" align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['HIZLI_MESAJ_YONETIMI']; ?></b></font></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
			  <?php
				if ($mesaj_no)
				{
				?>
				<tr>
          <td width="100%" colspan="6" align="center">
					<form action="hmesaj_yonet.php?islem=3" method="post" name="mesajduzen" id="mesajduzen">
          <input type="hidden" name="mesajno" id="mesajno" value="<?php echo $mesaj_no; ?>" />
					  <table width="100%" align="center">
						  <tr bgcolor="#b6c5f2">
		            <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['MESAJ_DUZENLEME']; ?></b></td>
		          </tr>
							<tr>
							  <td width="100%" align="center" colspan="2"><b><?php echo $dil['HizliMesaj']; ?></b> <br /><textarea name="mesaj" id="mesaj" cols="70" rows="5"><?php echo $mesaj; ?></textarea></td>
							</tr>
							<tr>
							  <td width="100%" colspan="2" align="center"><input type="submit" id="mesajduzen" name="mesajduzen" value="<?php echo $dil['DUZENLE']; ?>" class="input" /></td>
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
          <td width="100%" colspan="6" align="center"><?php echo $kayit_mesaj; ?></td>
        </tr>
				<?php
				}
				?>
        <tr bgcolor="#b6c5f2">
		      <td colspan="6" align="center" height="20" class="border_4"><a href="hmesaj_yonet.php"><b><?php echo $dil['MESAJLAR']; ?></b></a></td>
		    </tr>
				<form method="post" action="hmesaj_yonet.php">
        <tr>
          <td colspan="3" align="left"><?php echo $dil['ToplamMesaj']; ?> : <b><?php echo $toplammesaj; ?></b></td>
          <td width="100%" align="center" colspan="4"><b><?php echo $dil['MESAJ_ARA']; ?> : </b> <input type="text" name="mesajara" id="mesajara" size="20" class="input" /> <input type="submit" value="<?php echo $dil['ARA']; ?>" class="input" /></td>
        </tr>
        </form>
				<?php 
				if($sil_mesaj)
				{
				  echo '<tr><td width="100%" align="center" colspan="6">'.$sil_mesaj.'</td></tr>';
				}
				?>
				<form action="hmesaj_yonet.php?islem=2" method="post" name="hizlimesaj" id="hizlimesaj">
        <tr bgcolor="#b6c5f2">
        <td width="5%" align="center"><input type="checkbox" onclick="sec('hmesaj','hizlimesaj')" class="input" /></td>
        <td width="5%" align="center"><b>SN</b></td>
        <td width="15%" align="center"><b><?php echo $dil['EKLEYEN']; ?></b></td>
        <td width="50%" align="center"><b><?php echo $dil['MESAJ']; ?></b></td>
        <td width="10%" align="center"><b><?php echo $dil['TARIH']; ?></b></td>
				<td width="15%" align="center"><b><?php echo $dil['ONAY_DURUMU']; ?></b></td>
      </tr>
			<?php
      $sira = 0;
      $sirano = 0;
      if (empty($toplammesaj))
      {
        echo '<tr bgcolor="#f7f7fd">
          <td width="100%" align="center" colspan="9"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td>
        </tr>';
      } else {
        while ($mesaj_veri = $vt->fetchObject())
        {
          $sira++;
          $sirano   = $sira+$baslangic;
					$mesajno  = $mesaj_veri->mesajno;
          $mesaj    = $mesaj_veri->mesaj;
          $uyeno    = $mesaj_veri->uyeno;
					$tarih    = $mesaj_veri->tarih;
					$onay     = $mesaj_veri->onay;
					$mesaj    = substr($mesaj,0,70);
					if ($onay == 'E')
					{
					  $onay_mesaj = '<font color="#008000">'.$dil['Onayli'].'</font><br /><a href="hmesaj_yonet.php?islem=4&mesajno='.$mesajno.'&onay=H">'.$dil['OnayiKaldir'].'</a>';
					} else {
					  $onay_mesaj = '<font color="#ff0000">'.$dil['Onaysiz'].'</font><br /><a href="hmesaj_yonet.php?islem=4&mesajno='.$mesajno.'&onay=E">'.$dil['Onayla'].'</a>';
					}
          echo '
          <tr bgcolor="#f7f7fd">
            <td width="5%" align="center"><input type="checkbox" id="hmesaj" name="hmesaj[]" value="'.$mesajno.'" class="input" /></td>
					  <td width="5%" align="center"><b>'.$sirano.'</b></td>
					  <td width="15%" align="center">'.$fonk->uye_adi($uyeno).'</td>
					  <td width="50%" align="left"><a href="hmesaj_yonet.php?mesajno='.$mesajno.'">'.$mesaj.'</a></td>
					  <td width="10%" align="center">'.$fonk->duzgun_tarih_saat($tarih,true).'</td>
						<td width="15%" align="center">'.$onay_mesaj.'</td>
				   </tr>';
        }
			  unset($sirano,$mesajno,$mesaj,$uyeno,$tarih);
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
                echo '<a href="hmesaj_yonet.php?s='.$onceki.'&ipara='.$ipara.'">«&nbsp;'.$dil['Onceki'].'</a>';
              }
              echo '
            </td>
            <td width="50%" align="right">';
            if ($toplammesaj > ($s*$limit))
            {
              $sonraki = $s+1;
              echo '<a href="hmesaj_yonet.php?s='.$sonraki.'&ipara='.$ipara.'">'.$dil['Sonraki'].'&nbsp;»</a>';
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