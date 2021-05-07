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
  max       = document.hizlimesaj.resimm.length;

  for (var idx = 0; idx < max; idx++) 
  {
    if (eval("document.hizlimesaj.resimm[" + idx + "].checked") == true) 
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

function atla(targ,selObj,restore)
{ 
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function resimAc(resimno,en,boy)
{
  window.open("../resimac.php?resimno="+resimno+"r=2","","width="+en+",height="+boy);
}
//  End -->
</script>

</head>
<body background="yonetimresim/bg.gif">

<?php
@ $islem     = strip_tags(trim($_GET['islem']));
@ $resim_no  = abs(intval($_GET['resimno']));
$kayit_mesaj = '';
$sil_mesaj   = '';
//=============================================
if ($islem == 2) { // Silme Islemi Baslangici	
//=============================================
  $vt3 = new Baglanti();
  	
  if (is_array($_POST['resimm']))
	{
    foreach ( $_POST['resimm'] as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
			  $vt3->query("SELECT resim,albumno FROM ".TABLO_ONEKI."resim WHERE resimno=$deger");
        if ($vt3->numRows()>0)
        {
          $resim_veri = $vt3->fetchObject();
					$resim      = $resim_veri->resim;
          $albumno    = $resim_veri->albumno;	
			    $deger = trim(strip_tags($deger));
          if ($vt->query2("DELETE FROM ".TABLO_ONEKI."resim WHERE resimno=$deger"))
				  {
				    unlink('../'.GALERI_ALBUM_DIZIN.'/album_'.$albumno.'/'.$resim);
				  }
        }
				unset($resim_veri,$resim,$albumno);
      } 
    }
    $sil_mesaj = '<font color="#008000">'.$dil['SilmeIslemiTamamlandi'].'</font>';
	} else {
	  $sil_mesaj = '<font color="#ff0000">'.$dil['SecimYapmadiniz'].'</font>';
	}
	unset($vt3);
} elseif ($islem == 3) {
  /* ============================
  // ALBUM ONAY AYARLARI 
  ==============================*/
	@ $resimno  =  abs(intval($_GET['resimno']));
  @ $onay     = trim(strip_tags(htmlspecialchars($_GET['onay'])));
  if ($onay == 'E')
	{
	  $onay = 'E';
	} else {
	  $onay = 'H';
	}
  try
	{ 
		if ($vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE resimno=$resimno") == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }

		if ($vt->query2("UPDATE ".TABLO_ONEKI."resim SET onay='$onay' WHERE resimno=$resimno"))
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
} elseif ($islem == 4) {
  $rvt = new Baglanti();
  @ $resimno = intval($_GET['resimno']);
  @ $albumno = intval($_GET['albumno']);
  @ $s       = intval($_GET['s']);
  if ($rvt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE resimno=$resimno")==0)
  {
    $kayit_mesaj = '<font color="#ff0000">'.$dil['IslemGecersiz'].'</font>';
  }
  if ($rvt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE albumno=$albumno")==0)
  {
    $kayit_mesaj = '<font color="#ff0000">'.$dil['IslemGecersiz'].'</font>';
  }
  $rvt->query("SELECT albumno,resim FROM ".TABLO_ONEKI."resim WHERE resimno=$resimno");
  $resim_veri = $rvt->fetchObject();
  $resim_albumno = $resim_veri->albumno;
  $resim_resim   = $resim_veri->resim;
  $rvt->freeResult();

  if ($rvt->query2("UPDATE ".TABLO_ONEKI."resim SET albumno=$albumno WHERE resimno=$resimno"))
  {
    if (copy('../'.GALERI_ALBUM_DIZIN.'/album_'.$resim_albumno.'/'.$resim_resim,'../'.GALERI_ALBUM_DIZIN.'/album_'.$albumno.'/'.$resim_resim))
	unlink('../'.GALERI_ALBUM_DIZIN.'/album_'.$resim_albumno.'/'.$resim_resim);
	
    $kayit_mesaj = '<font color="008000">'.$dil['IslemTamamlandi'].'</font>';
  } else {
    $kayit_mesaj = '<font color="#ff0000">'.$dil['IslemBasarisiz'].'</font>';
  }
  unset($resim_veri,$resim_albumno,$resim_resim,$rvt);
} //Islem Sonu

@ $resimara = trim(htmlspecialchars($_REQUEST['resimara']));
@ $albumno  = intval($_REQUEST['albumno']);
$mesaj_kosul = "WHERE resimno>0";
if ($resimara)
$mesaj_kosul .= " AND (resimadi LIKE '%$resimara%' OR aciklama LIKE '%$resimara%')";

if ($albumno)
$mesaj_kosul .= " AND albumno=$albumno";

$toplamresim = $vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim ".$mesaj_kosul."");
$limit = 30; //Bir Sayfada Gösterilecek Hizli Mesaj Sayısı
@ $s = abs(intval($_GET['s']));
  
if(empty($s) || ($s > ceil($toplamresim/$limit))) 
{                
  $s = 1;                
  $baslangic = 0;        
} else {               
  $baslangic = ($s - 1) * $limit;        
}

if (!empty($resim_no))
{
  $vt->query("SELECT resimno,uyeno,resimadi,aciklama,tarih,onay FROM ".TABLO_ONEKI."resim WHERE resimno=$resim_no");
  $resim_bilgi = $vt->fetchObject();
  $resim_no    = $resim_bilgi->resimno;
  $aciklama    = $resim_bilgi->aciklama;
  $uyeno       = $resim_bilgi->uyeno;
  $vt->freeResult();
}

$vt->query("SELECT resimno,uyeno,albumno,resim,resimadi,aciklama,tarih,onay FROM ".TABLO_ONEKI."resim ".$mesaj_kosul." ORDER BY onay DESC,tarih DESC LIMIT $baslangic,$limit");

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
		      <td colspan="6" align="center" height="20" class="border_4"><a href="resim_yonet.php"><b><?php echo $dil['ResimYonet']; ?></b></a></td>
		    </tr>
				<form method="post" action="resim_yonet.php">
        <tr>
          <td colspan="3" align="left"><?php echo $dil['ToplamResim']; ?> : <b><?php echo $toplamresim; ?></b></td>
          <td width="100%" align="center" colspan="3"><select name="albumno">
	        <?php
	        echo '
	        <option value="0">-- '.$dil['Hepsi'].' --</option>';
					$a_vt = new Baglanti();
	        $a_vt->query("SELECT albumno,albumadi FROM ".TABLO_ONEKI."album ORDER BY albumadi ASC");
          if ($a_vt->numRows()>0)
	        {
	          $ano = 0;
            while($album_veri = $a_vt->fetchObject())
            {
		          $ano++;
		          $album_no  = $album_veri->albumno;
			        $album_adi = $album_veri->albumadi;
              echo '<option value="'.$album_no.'"'; if ($albumno == $album_no) echo ' selected="selected"'; echo '>'.$ano.' - '.$album_adi.'</option>';
            }
	        } 
					unset($a_vt);
	        ?>
          </select>&nbsp;
					<input type="text" name="resimara" id="resimara" size="20" class="input" value="<?php echo $resimara; ?>" /> <input type="submit" value="<?php echo $dil['ARA']; ?>" class="input" /></td>
        </tr>
        </form>
				<?php 
				if($sil_mesaj)
				{
				  echo '<tr><td width="100%" align="center" colspan="6">'.$sil_mesaj.'</td></tr>';
				}
				?>
				<form action="resim_yonet.php?islem=2" method="post" name="resim" id="resim">
        <tr bgcolor="#b6c5f2">
        <td width="5%" align="center"><input type="checkbox" onclick="sec('resimm','resim')" class="input" /></td>
        <td width="5%" align="center"><b>SN</b></td>
        <td width="15%" align="center"><b><?php echo $dil['RESIM']; ?></b></td>
        <td width="40%" align="center"><b><?php echo $dil['ACIKLAMA']; ?></b></td>
        <td width="10%" align="center"><b><?php echo $dil['TARIH']; ?></b></td>
				<td width="10%" align="center"><b><?php echo $dil['ONAY_DURUMU']; ?></b></td>
      </tr>
			<?php
      $sira = 0;
      $sirano = 0;
      if (empty($toplamresim))
      {
        echo '<tr bgcolor="#f7f7fd">
          <td width="100%" align="center" colspan="6"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td>
        </tr>';
      } else {
			  $albumler = array();
			  $vt2 = new Baglanti();
				if ($vt2->query("SELECT albumno,albumadi FROM ".TABLO_ONEKI."album"))
				{
				  while ($album_veri = $vt2->fetchObject())
					{
					  $albumler[$album_veri->albumno] = $album_veri->albumadi;
					}
				}
				$vt2->freeResult();
				unset($vt2);
				
        while ($resim_veri = $vt->fetchObject())
        {
          $sira++;
          $sirano      = $sira+$baslangic;
					$resimno     = $resim_veri->resimno;
					$resimalbumno = $resim_veri->albumno;
					$resimadi    = $resim_veri->resimadi;
          $aciklama    = $resim_veri->aciklama;
          $uyeno       = $resim_veri->uyeno;
					$tarih       = $resim_veri->tarih;
					$onay        = $resim_veri->onay;
					$resim_resim = $resim_veri->resim;
					$aciklama = substr($aciklama,0,70);
					
					$resim_adres = '../'.GALERI_ALBUM_DIZIN.'/album_'.$resimalbumno.'/'.$resim_resim;
          if (!file_exists($resim_adres) || !$resim_resim)
          $resim = GALERI_ALBUM_DIZIN.'/bos.gif';
					else
					$resim = GALERI_ALBUM_DIZIN.'/album_'.$resimalbumno.'/'.$resim_resim;

					if ($onay == 'E')
					{
					  $onay_mesaj = '<font color="#008000">'.$dil['Onayli'].'</font><br /><a href="resim_yonet.php?islem=3&resimno='.$resimno.'&onay=H">'.$dil['OnayiKaldir'].'</a>';
					} else {
					  $onay_mesaj = '<font color="#ff0000">'.$dil['Onaysiz'].'</font><br /><a href="resim_yonet.php?islem=3&resimno='.$resimno.'&onay=E">'.$dil['Onayla'].'</a>';
					}
          ?>
          <tr bgcolor="#f7f7fd">
            <td width="5%" align="center" rowspan="2"><input type="checkbox" id="resimm" name="resimm[]" value="<?php echo $resimno; ?>" class="input" /></td>
					  <td width="5%" align="center" rowspan="2"><b><?php echo $sirano; ?></b></td>
					  <td width="15%" align="center" rowspan="2">
						<?php
						@$resim_boyut = getimagesize($resim_adres);
            $resim_en    = $resim_boyut[0];
            $resim_boy   = $resim_boyut[1];
            ?>
            <a href="javascript:resimAc('<?php echo $resimno; ?>','<?php echo $resim_en; ?>','<?php echo $resim_boy; ?>')"><img alt="<?php echo $resimadi; ?>" src="../resim.php?resim=<?php echo $resim; ?>&en=150&boy=120" align="absmiddle" class="albumresim" border="0" /></a>
						</td>
					  <td width="40%" align="left"><a href="../?sayfa=resim_ekle&resimno=<?php echo $resimno; ?>" targen="_self"><?php echo $resimadi; ?></a><br /><?php echo $aciklama; ?></td>
					  <td width="10%" align="center"><?php echo $fonk->uye_adi($uyeno).'<br />'.$fonk->duzgun_tarih_saat($tarih,true).'<br />'.$dil['ResimNo'].' : '.$resimno; ?></td>
						<td width="10%" align="center"><?php echo $onay_mesaj; ?></td>
						</tr>
						<tr bgcolor="#f7f7fd">
						  <td align="left" colspan="3" style="padding-left:5px">
							<?php
								if (count($albumler) > 0)
								{
								  echo '<select name="kategorino" id="kategorino" class="input" onchange="atla(\'self\',this,0);">';
								  foreach($albumler AS $albumno=>$albumisim) //for baslangici - (b)
									{
								    echo '<option value="resim_yonet.php?islem=4&resimno='.$resimno.'&albumno='.$albumno.'&s='.$s.'"'; 
										if ($albumno == $resimalbumno) echo ' selected="selected"'; echo ' title="'.$albumisim.'">'.$albumisim.'</option>';
								  } //for sonu - (b)
									echo '</select>';
								} else {
								  echo $dil['KategoriBulunamadi'];
								}
            ?>
				   </tr>
				<?php
        }
			  unset($sirano,$resimno,$aciklama,$uyeno,$tarih);
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
                echo '<a href="resim_yonet.php?s='.$onceki.'&ipara='.$ipara.'">«&nbsp;'.$dil['Onceki'].'</a>';
              }
              echo '
            </td>
            <td width="50%" align="right">';
            if ($toplamresim > ($s*$limit))
            {
              $sonraki = $s+1;
              echo '<a href="resim_yonet.php?s='.$sonraki.'&ipara='.$ipara.'">'.$dil['Sonraki'].'&nbsp;»</a>';
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