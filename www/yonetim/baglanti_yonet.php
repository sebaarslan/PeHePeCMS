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

function islem(baglantilar) 
{
  var total = 0;
  var max   = 0;
  max       = document.baglantiyonetim.baglanti.length;

  for (var idx = 0; idx < max; idx++) 
  {
    if (eval("document.baglantiyonetim.baglanti[" + idx + "].checked") == true) 
    {
      total += 1;
    }
  }
  if (total == 0)
  {
    alert("<?php echo $dil['SecimYapmadiniz']; ?>");
    return false;
  } else {
    return confirm("<?php echo $dil['Secilen']; ?> = " + total + " : " + baglantilar);
  }
}
//  End -->
</script>

</head>
<body background="yonetimresim/bg.gif">

<?php
@ $islem     = strip_tags(trim($_GET['islem']));
@ $baglanti_no  = abs(intval($_GET['baglantino']));
$kayit_mesaj = '';
$sil_baglanti   = '';
$baglantiadi = '';
$baglantiadres = '';
$baglantiadi = '';
$baglantihedef = '';
$duzenleme_tamam = true;
//=====================================
if ($islem == 2) { //SILME ISLEMI
//=====================================			
  if (is_array($_POST['baglanti']))
	{
    foreach ( $_POST['baglanti'] as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
			  $deger = trim(strip_tags($deger));
        $baglanti_sil = $vt->query2("DELETE FROM ".TABLO_ONEKI."baglantilar WHERE baglantino=$deger");
      }
    }
    $sil_baglanti = '<font color="#008000">'.$dil['SilmeIslemiTamamlandi'].'</font>';
	} else {
	  $sil_baglanti = '<font color="#ff0000">'.$dil['SecimYapmadiniz'].'</font>';
	}

} elseif ($islem == 3) {
  /* ============================
  // BAGLANTI DÜZENLEME KAYIT 
  ==============================*/
  $duzenleme_tamam = false;
  // FORMDAN GELEN VERİLER
  @ $baglantino      = abs(intval($_POST['baglantino']));
  @ $baglantiadi     = trim(strip_tags(htmlspecialchars($_POST['baglantiadi'])));
	@ $baglantiadres   = trim(strip_tags(htmlspecialchars($_POST['baglantiadres'])));
	@ $baglantihedef   = trim(strip_tags(htmlspecialchars($_POST['baglantihedef'])));
	
  if (empty($baglantihedef) || $baglantihedef != '_blank')
	$baglantihedef = '_self';
  try
	{ 
	  if (empty($baglantiadres))
	  {
		  throw new Exception($dil['BaglantiAdresiniBosBirakmayiniz']);
			exit;
	  } elseif (!$fonk->website_kontrol($baglantiadres)) {
		  throw new Exception($dil['AdresGecersiz']);
			exit;
		}
		if (empty($baglantiadi))
	  $baglantiadi = $baglantiadres;
    
		if (empty($baglantino))
		{
		  //BAGLANTI KAYIT İŞLEMİ
			
			$vt->query2("INSERT INTO ".TABLO_ONEKI."baglantilar (baglantiadres,baglantiadi,baglantihedef) VALUES ('$baglantiadres','$baglantiadi','$baglantihedef')");
			if ($vt->affectedRows())
			{
			  $kayit_mesaj = '<font color="#008000">'.$dil['KayitIslemiTamamlandi'].'</font>';
			} else {
			  $kayit_mesaj = '<font color="#ff0000">'.$dil['IslemBasarisiz'].'</font>';
			}
		} else {
      //BAGLANTI DÜZENLEME İŞLEMİ
			
		  if ($vt->kayitSay("SELECT COUNT(baglantino) FROM ".TABLO_ONEKI."baglantilar WHERE baglantino=$baglantino") == 0)
      {
        throw new Exception($dil['IslemGecersiz']);
        exit;
      }
      
      if ($vt->query2("UPDATE ".TABLO_ONEKI."baglantilar SET baglantiadres='$baglantiadres',baglantiadi='$baglantiadi',baglantihedef='$baglantihedef' WHERE baglantino=$baglantino"))
      {
			  $baglanti_no = $baglantino;
				$duzenleme_tamam = true;
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
  // BAGLANTI ONAY AYARLARI 
  ==============================*/
	@ $baglantino  =  abs(intval($_GET['baglantino']));
  @ $onay     = trim(strip_tags(htmlspecialchars($_GET['onay'])));
  if ($onay == 'E')
	{
	  $onay = 'E';
	} else {
	  $onay = 'H';
	}
  try
	{ 
		if ($vt->kayitSay("SELECT COUNT(baglantino) FROM ".TABLO_ONEKI."baglantilar WHERE baglantino=$baglantino") == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }

		if ($vt->query2("UPDATE ".TABLO_ONEKI."baglantilar SET baglantionay='$onay' WHERE baglantino=$baglantino"))
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


  @ $baglantiara = trim(htmlspecialchars($_REQUEST['baglantiara']));
  if (empty($baglantiara))
  {
    $baglanti_kosul = '';
  } else {
	  $baglanti_kosul = "WHERE baglantiadres LIKE '%$baglantiara%' OR baglantiadi LIKE '%$baglantiara%'";
  }

  $toplambaglanti = $vt->kayitSay("SELECT COUNT(baglantino) FROM ".TABLO_ONEKI."baglantilar ".$baglanti_kosul."");
	$limit = 30; //Bir Sayfada Gösterilecek Dosya Sayısı
  @ $s = abs(intval($_GET['s']));
  
  if(empty($s) || ($s > ceil($toplambaglanti/$limit))) 
  {                
    $s = 1;                
    $baslangic = 0;        
  } else {               
    $baslangic = ($s - 1) * $limit;        
  }

	
	if (empty($baglanti_no)) 
	{
	  $baglanti_buton = $dil['KAYDET'];
  } else {
	  if ($duzenleme_tamam==true)
		{
	  $vt->query("SELECT baglantino,baglantiadres,baglantiadi,baglantihedef,baglantionay FROM ".TABLO_ONEKI."baglantilar WHERE baglantino=$baglanti_no");
		$baglanti_bilgi   = $vt->fetchObject();
		$baglanti_no      = $baglanti_bilgi->baglantino;
		$baglantiadres    = $baglanti_bilgi->baglantiadres;
		$baglantiadi      = $baglanti_bilgi->baglantiadi;
		$baglantihedef    = $baglanti_bilgi->baglantihedef;
		$baglantionay     = $baglanti_bilgi->baglantionay;
		$vt->freeResult();
		}
		$baglanti_buton = $dil['DUZENLE'];
		
	}

	$vt->query("SELECT baglantino,baglantiadres,baglantiadi,baglantihedef,baglantionay FROM ".TABLO_ONEKI."baglantilar ".$baglanti_kosul." ORDER BY baglantino DESC LIMIT $baslangic,$limit");
?>

<table border="1" align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['BAGLANTI_YONETIMI']; ?></b></font></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
				<tr>
          <td width="100%" colspan="6" align="center">
					<form action="baglanti_yonet.php?islem=3" method="post" name="baglantiduzen" id="baglantiduzen">
          <input type="hidden" name="baglantino" id="baglantino" value="<?php echo $baglanti_no; ?>" />
					  <table width="100%" align="center">
						  <tr bgcolor="#b6c5f2">
		            <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['BAGLANTI_EKLE_DUZENLE']; ?></b></td>
		          </tr>
							<tr>
							  <td align="right"><font color="#ff0000">*</font>&nbsp;<b><?php echo $dil['Adres']; ?> : </b></td>
								<td align="left"><input type="text" name="baglantiadres" id="baglantiadres" size="60" class="input" value="<?php echo $baglantiadres; ?>" />&nbsp;(Örn: http://www.arslandesign.com)</td>
							</tr>
							<tr>
							  <td align="right"><b><?php echo $dil['BaglantiAdi']; ?> : </b></td>
								<td align="left"><input type="text" name="baglantiadi" id="baglantiadi" class="input" size="60" value="<?php echo $baglantiadi; ?>" />&nbsp;(Örn: ArslanDesign)</td>
							<tr>
							<tr>
							  <td align="right"><b><?php echo $dil['Hedef']; ?> : </b></td>
								<td align="left"><select name="baglantihedef" class="select">
								<option value="_self"<?php if ($baglantihedef=='_self') echo ' selected="selected"'; ?>>_self</option>
								<option value="_blank"<?php if ($baglantihedef=='_blank') echo ' selected="selected"'; ?>>_blank</option>
								</select></td>
							<tr>
							
							<tr>
							  <td width="100%" colspan="2" align="center"><input type="submit" id="baglantiduzen" name="baglantiduzen" value="<?php echo $baglanti_buton; ?>" class="input" /></td>
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
		      <td colspan="6" align="center" height="20" class="border_4"><a href="baglanti_yonet.php"><b><?php echo $dil['BAGLANTILAR']; ?></b></a></td>
		    </tr>
				<form method="post" action="baglanti_yonet.php">
        <tr>
          <td colspan="3" align="left"><?php echo $dil['ToplamKayit']; ?> : <b><?php echo $toplambaglanti; ?></b></td>
          <td width="100%" align="center" colspan="4"><b><?php echo $dil['ARA']; ?> : </b> <input type="text" name="baglantiara" id="baglantiara" size="20" class="input" /> <input type="submit" value="<?php echo $dil['ARA']; ?>" class="input" /></td>
        </tr>
        </form>
				<?php 
				if($sil_baglanti)
				{
				  echo '<tr><td width="100%" align="center" colspan="6">'.$sil_baglanti.'</td></tr>';
				}
				?>
				<form action="baglanti_yonet.php?islem=2" method="post" name="baglantiyonetim" id="baglantiyonetim">
        <tr bgcolor="#b6c5f2">
        <td width="5%" align="center"><input type="checkbox" onclick="sec('baglanti','baglantiyonetim')" class="input" /></td>
        <td width="5%" align="center"><b>SN</b></td>
        <td width="35%" align="center"><b><?php echo $dil['Adres']; ?></b></td>
        <td width="35%" align="center"><b><?php echo $dil['BaglantiAdi']; ?></b></td>
        <td width="5%" align="center"><b><?php echo $dil['Hedef']; ?></b></td>
				<td width="15%" align="center"><b><?php echo $dil['Onay']; ?></b></td>
      </tr>
			<?php
      $sira = 0;
      $sirano = 0;
      if (empty($toplambaglanti))
      {
        echo '<tr bgcolor="#f7f7fd">
          <td width="100%" align="center" colspan="9"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td>
        </tr>';
      } else {
        while ($baglanti_veri = $vt->fetchObject())
        {
          $sira++;
          $sirano          = $sira+$baslangic;
					$baglantino         = $baglanti_veri->baglantino;
					$baglantiadres   = $baglanti_veri->baglantiadres;
          $baglantiadi        = $baglanti_veri->baglantiadi;
          $baglantihedef   = $baglanti_veri->baglantihedef;
					$baglantionay    = $baglanti_veri->baglantionay;

					if ($baglantionay == 'E')
					{
					  $onay_baglanti = '<font color="#008000">'.$dil['Onayli'].'</font><br /><a href="baglanti_yonet.php?islem=4&baglantino='.$baglantino.'&onay=H">'.$dil['OnayiKaldir'].'</a>';
					} else {
					  $onay_baglanti = '<font color="#ff0000">'.$dil['Onaysiz'].'</font><br /><a href="baglanti_yonet.php?islem=4&baglantino='.$baglantino.'&onay=E">'.$dil['Onayla'].'</a>';
					}
          ?>
          <tr bgcolor="#f7f7fd">
            <td width="5%" align="center"><input type="checkbox" id="baglanti" name="baglanti[]" value="<?php echo $baglantino; ?>" class="input" /></td>
					  <td width="5%" align="center"><b><?php echo $sirano; ?></b></td>
					  <td width="35%" align="left"><a href="baglanti_yonet.php?baglantino=<?php echo $baglantino; ?>"><?php echo $baglantiadres; ?></a></td>
					  <td width="35%" align="center"><?php echo $baglantiadi; ?></td>
					  <td width="5%" align="center"><?php echo $baglantihedef; ?></td>
						<td width="15%" align="center"><?php echo $onay_baglanti; ?></td>
				   </tr>
				<?php
        }
			  unset($sirano,$baglantino,$baglantiadres,$baglantiadi,$baglantihedef,$baglantionay);
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
                echo '<a href="baglanti_yonet.php?s='.$onceki.'&baglantiara='.$baglantiara.'">«&nbsp;'.$dil['Onceki'].'</a>';
              }
              echo '
            </td>
            <td width="50%" align="right">';
            if ($toplambaglanti > ($s*$limit))
            {
              $sonraki = $s+1;
              echo '<a href="baglanti_yonet.php?s='.$sonraki.'&baglantiara='.$baglantiara.'">'.$dil['Sonraki'].'&nbsp;»</a>';
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