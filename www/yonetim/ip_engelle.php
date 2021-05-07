<?php
ob_start();
session_start();
require_once ("../icerik/vt.inc.php");
require_once ("../icerik/ayar.inc.php");
require_once ("../icerik/dil.inc.php");
dil_belirle('','yonetimdil');
require_once ("../icerik/sev.inc.php");
require_once ("../icerik/fonk.inc.php");


//Yönetici Girişi Yapılmamışsa Yasakla
if (UYE_SEVIYE < 6) 
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
  max       = document.ipengelle.ip.length;

  for (var idx = 0; idx < max; idx++) 
  {
    if (eval("document.ipengelle.ip[" + idx + "].checked") == true) 
    {
      total += 1;
    }
  }
  if (total == 0)
  {
    alert("<?php echo $dil['SecimYapmadiniz']; ?>");
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
@ $ip_no     = abs(intval($_GET['ipno']));
$kayit_mesaj = '';
$sil_mesaj   = '';

if ($islem == 2) {					
  if (is_array($_POST['ip']))
	{
    foreach ( $_POST['ip'] as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
			  $deger = trim(strip_tags($deger));
        $ip_sil = $vt->query2("DELETE FROM ".TABLO_ONEKI."ipengelle WHERE ip=TRIM('$deger')");
      }
    }
    $sil_mesaj = '<font color="#008000">'.$dil['SilmeIslemiTamamlandi'].'</font>';
	} else {
	  $sil_mesaj = '<font color="#ff0000">'.$dil['SecimYapmadiniz'].'</font>';
	}

} elseif ($islem == 3) {
  /* ============================
  // IP DÜZENLEME KAYIT 
  ==============================*/
  
  // FORMDAN GELEN VERİLER
  @ $ipno            =  abs(intval($_POST['ipno']));
  @ $aciklama        = trim(strip_tags(htmlspecialchars($_POST['aciklama'])));
  @ $ip              = trim(strip_tags(htmlspecialchars($_POST['ipadresi'])));

  try
	{ 
	  if (empty($ip))
	  {
		  throw new Exception($dil['BosAlanBirakmayiniz']);
			exit;
	  }

	  if (strlen($aciklama) > 250)
	  {
		  throw new Exception($dil['KarakterSayisiGecersiz'].' : '.$dil['EngellemeSebebi'].' 250');
	  }
		if (strlen($ip) > 50)
		{
		  throw new Exception($dil['KarakterSayisiGecersiz'].' : '.$dil['IPAdresi'].' 50');
		  exit;
		}
    if (empty($ipno))
		{
		  if ($vt->kayitSay("SELECT COUNT(ip) FROM ".TABLO_ONEKI."ipengelle WHERE ip='$ip'") > 0)
	    {
		    throw new Exception($dil['IPAdresiKayitli']);
			  exit;
	    }
			if ($vt->query2("INSERT INTO ".TABLO_ONEKI."ipengelle (ip,aciklama,tarih) VALUES ('$ip','$aciklama',NOW())"))
			{
        $kayit_mesaj = '<font color="#008000">'.$dil['EngellenecekIpAdresiKaydedildi'].'<a/font>';
      } else {
        $kayit_mesaj = '<font color="#ff0000">'.$dil['IslemBasarisiz'].'</font>';
			}
    } else {
		  if ($vt->kayitSay("SELECT COUNT(ip) FROM ".TABLO_ONEKI."ipengelle WHERE ip<>'$ip'") > 0)
			{
			  throw new Exception($dil['IPAdresiKayitli']);
				exit;
			}
			if ($vt->query2("UPDATE ".TABLO_ONEKI."ipengelle SET ip='$ip',aciklama='$aciklama' WHERE ipno=$ipno"))
			{
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
}


  @ $ipara = trim(htmlspecialchars($_REQUEST['ipara']));
  if (empty($ipara))
  {
    $ip_kosul = '';
  } else {
	  $ip_kosul = "WHERE ip LIKE '%$ipara%'";
  }

  $toplamip = $vt->kayitSay("SELECT COUNT(ip) FROM ".TABLO_ONEKI."ipengelle ".$ip_kosul."");
	$limit = 30; //Bir Sayfada Gösterilecek IP Sayısı
  @ $s = abs(intval($_GET['s']));
  
  if(empty($s) || ($s > ceil($toplamip/$limit))) 
  {                
    $s = 1;                
    $baslangic = 0;        
  } else {               
    $baslangic = ($s - 1) * $limit;        
  }

	if (empty($ip_no))
	{
	  $ip_ekle = $dil['IP_ENGELLEME_KAYDI'];
		$ip_no = 0;
		@ $ip_adresi = trim(htmlspecialchars(strip_tags($_GET['ip'])));
		$ip_aciklama = '';
		$ip_buton = $dil['KAYDET'];
	} else {
	  $vt->query("SELECT ipno,ip,aciklama,tarih FROM ".TABLO_ONEKI."ipengelle WHERE ipno=$ip_no");
		$ip_bilgi    = $vt->fetchObject();
		$ip_no       = $ip_bilgi->ipno;
		$ip_adresi   = $ip_bilgi->ip;
		$ip_aciklama = $ip_bilgi->aciklama;
		$vt->freeResult();
		
	  $ip_ekle = $dil['IP_ADRESI_DUZENLEME'];
		$ip_buton = $dil['DUZENLE'];
	}
	
	$vt->query("SELECT ipno,ip,aciklama,tarih FROM ".TABLO_ONEKI."ipengelle ".$ip_kosul." ORDER BY tarih DESC LIMIT $baslangic,$limit");
?>

<table border="1" align="center" cellpadding="0" cellspacing="0" width="95%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['IP_ENGELLEME_YONETIMI']; ?></b></font></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
			  <tr>
          <td width="100%" colspan="5" align="center">
					<form action="ip_engelle.php?islem=3" method="post" name="ipkayit" id="ipkayit">
          <input type="hidden" name="ipno" id="ipno" value="<?php echo $ip_no; ?>" />
					  <table width="100%" align="center">
						  <tr bgcolor="#b6c5f2">
		            <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $ip_ekle; ?></b></td>
		          </tr>
							<tr>
							  <td width="50%" align="right"><?php echo $dil['IPAdresi']; ?></td>
								<td width="50%" align="left">: <input type="text" name="ipadresi" id="ipadresi" class="input" value="<?php echo $ip_adresi; ?>" maxlength="50" /> Maks: 50</td>
							</tr>
							<tr>
							  <td width="50%" align="right"><?php echo $dil['EngellemeSebebi']; ?></td>
								<td width="50%" align="left">: <input type="text" name="aciklama" id="aciklama" class="input" value="<?php echo $ip_aciklama; ?>" maxlength="250" /> Maks: 250</td>
							</tr>
							<tr>
							  <td width="100%" colspan="2" align="center"><input type="submit" id="ipkayit" name="ipkayit" value="<?php echo $ip_buton; ?>" class="input" /></td>
							</tr>
							<tr>
							  <td width="100%" colspan="2" align="center"><?php echo $kayit_mesaj; ?></td>
							</tr>
						</table>
					  </form>
					</td>
        </tr>
        <tr bgcolor="#b6c5f2">
		      <td colspan="5" align="center" height="20" class="border_4"><a href="ip_engelle.php"><b><?php echo $dil['ENGELLI_IP_ADRESLERI']; ?></b></a></td>
		    </tr>
				<form method="post" action="ip_engelle.php">
        <tr>
          <td colspan="3" align="left"><?php echo $dil['ToplamEngelliIp']; ?> : <b><?php echo $toplamip; ?></b></td>
          <td width="100%" align="center" colspan="2"><b><?php echo $dil['IP_ARA']; ?> : </b> <input type="text" name="ipara" id="ipara" size="20" class="input" /> <input type="submit" value="<?php echo $dil['ARA']; ?>" class="input" /></td>
        </tr>
        </form>
				<?php 
				if($sil_mesaj)
				{
				  echo '<tr><td width="100%" align="center" colspan="5">'.$sil_mesaj.'</td></tr>';
				}
				?>
				<form action="ip_engelle.php?islem=2" method="post" name="ipengelle" id="ipengelle">
        <tr bgcolor="#b6c5f2">
        <td width="5%" align="center"><input type="checkbox" onclick="sec('ip','ipengelle')" class="input" /></td>
        <td width="5%" align="center"><b>SN</b></td>
        <td width="15%" align="center"><b>IP</b></td>
        <td width="50%" align="center"><b><?php echo $dil['ENGELLEME_SEBEBI']; ?></b></td>
        <td width="25%" align="center"><b><?php echo $dil['TARIH']; ?></b></td>
      </tr>
			<?php
      $sira = 0;
      $sirano = 0;
      if (empty($toplamip))
      {
        echo '<tr bgcolor="#f7f7fd">
          <td width="100%" align="center" colspan="9"><font color="#ff0000">'.$dil['EngelliIpYok'].'</font></td>
        </tr>';
      } else {
        while ($ip_veri = $vt->fetchObject())
        {
          $sira++;
          $sirano    = $sira+$baslangic;
					$ipno      = $ip_veri->ipno;
          $ip        = $ip_veri->ip;
          $aciklama  = $ip_veri->aciklama;
					$tarih     = $ip_veri->tarih;
          echo '
          <tr bgcolor="#f7f7fd">
            <td width="5%" align="center"><input type="checkbox" id="ip" name="ip[]" value="'.$ip.'" class="input" /></td>
					  <td width="5%" align="center"><b>'.$sirano.'</b></td>
					  <td width="15%" align="center"><a href="ip_engelle.php?ipno='.$ipno.'"><b>'.$ip.'</b></a></td>
					  <td width="50%" align="left">'.$aciklama.'</td>
					  <td width="25%" align="left">'.$fonk->duzgun_tarih_saat($tarih,true).'</td>
				   </tr>';
        }
			  unset($sira,$sirano,$ip,$aciklama,$tarih);
			  $vt->freeResult();
	  }
			?>

      <tr>
        <td colspan="5" width="100%" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dil['Secilileri']; ?> : </font>
          <input type="submit" value="<?php echo $dil['Sil']; ?>" name="islemsil" onclick="return islem('<?php echo $dil['IpAdresiniSilmekIstiyormusunuz']; ?>')" class="input" />&nbsp;</td>
      </tr>	
			</form>
      <tr>
        <td colspan="5" width="100%" align="center">
				  <table width="90%" align="center">
          <tr>
            <td width="50%" align="left">&nbsp;&nbsp;
						  <?php


              if ($s > 1)
              {
                $onceki = $s-1;
                echo '<a href="ip_engelle.php?s='.$onceki.'&ipara='.$ipara.'">«&nbsp;'.$dil['Onceki'].'</a>';
              }
              echo '
            </td>
            <td width="50%" align="right">';
            if ($toplamip > ($s*$limit))
            {
              $sonraki = $s+1;
              echo '<a href="ip_engelle.php?s='.$sonraki.'&ipara='.$ipara.'">'.$dil['Sonraki'].'&nbsp;»</a>';
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