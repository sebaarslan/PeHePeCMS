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
var checkflag = "false";
function sec(field) 
{
  if (checkflag == "false") 
  {
    for (i = 0; i < field.length; i++) 
    {
      field[i].checked = true;
    }
    checkflag = "true";
  } else {
    for (i = 0; i < field.length; i++) 
    {
      field[i].checked = false; 
    }
    checkflag = "false";
  }
}


function onaylama(islem) 
{
  var total = 0;
  var uyelik_onayi = islem;
  var max = uye_yonet.uyeler.length;

  for (var idx = 0; idx < max; idx++) 
  {
    if (eval("document.uye_yonet.uyeler[" + idx + "].checked") == true) 
    {
      total += 1;
    }
  }
  
	if (total == 0)
  {
    alert("<?php echo $dil['SecimYapmadiniz']; ?>");
    return false;
  } else {
    if (uyelik_onayi > 2)
    {
      return confirm("<?php echo $fonk->yerine_koy($dil['UyeOnaylamakIstiyormusunuz1'],'"+total+"'); ?>");
    } else {
      return confirm("<?php echo $fonk->yerine_koy($dil['UyeOnaylamakIstiyormusunuz2'],'"+total+"'); ?>");
    }
  }

}

function islemler(mesaj) 
{
  var total = 0;
  var max   = 0;
  max       = uyeler.uyeler.length;
  for (var idx = 0; idx < max; idx++) 
  {
    if (eval("document.uye_yonet.uyeler[" + idx + "].checked") == true) 
    {
      total += 1;
    }
  }
  if (total == 0)
  {
    alert("<?php echo $dil['SecimYapmadiniz']; ?>");
    return false;
  } else {
    return confirm("<?php echo $dil['Secilen']; ?>=" + total + " : " + mesaj);
  }
}
//  End -->
</script>

</head>
<body background="yonetimresim/bg.gif">

<?php
@ $islem      = strip_tags(trim($_GET['islem']));

if (!$islem)
{
  /* ================================ *\
  || -- ÜYELER          -- 		      ||
  \* ================================ */
  @ $sirala = intval($_GET['sirala']);
  if (!$sirala)
	{
	  $sirala = 1;
		$siralama = 'kayittarihi DESC';
  } elseif ($sirala == 2) {
	  $sirala = 1;
		$siralama = 'kayittarihi ASC';
  } elseif ($sirala == 3) {
    $siralama = 'uyeadi ASC';
  } elseif ($sirala == 4) {
    $siralama = 'uyeadi DESC';
  } else {
	  $sirala = 3;
	  $siralama = 'kayittarihi DESC';
  } 

  $limit = 30;
  @ $s = abs(intval($_GET['s']));
  
  if(empty($s)) 
  {                
    $s = 1;                
    $baslangic = 0;        
  } else {               
    $baslangic = ($s - 1) * $limit;        
  }
	
  @ $uyeonay      = intval($_REQUEST['uyeonay']);
	@ $yoneticionay = intval($_REQUEST['yonay']);
  @ $uyeadi       = trim(htmlspecialchars($_REQUEST['uyeadi']));

	$sorgu  = '';
	$aranan = '';
  if ($uyeadi)
  {
	  $sorgu .= " AND (uyeadi LIKE '%$uyeadi%' OR adi LIKE '%$uyeadi%' OR soyadi LIKE '%$uyeadi%')";
		$aranan .= "&uyeadi=$uyeadi";
  }

  if ($uyeonay==1)
  $sorgu .= " AND onay='H'";
  elseif ($uyeonay==2)
  $sorgu .= "AND onay='E'";
  $aranan .= "&uyeonay=$uyeonay";

  if ($yoneticionay==1)
  $sorgu .= " AND (yonay<5 AND yonay>1)";
  elseif ($yoneticionay==2)
  $sorgu .= " AND yonay=5";
  $aranan .= "&uyeonay=$uyeonaylamis";

  $vt->query("SELECT uyeno, uyeadi, eposta, adi, soyadi, dogumtarihi, seviye, kayittarihi, songiristarihi, girissayisi, guncellemetarihi,onay,yonay FROM ".TABLO_ONEKI."uyeler WHERE uyeno>0 ".$sorgu." ORDER BY $siralama LIMIT $baslangic,$limit");

  $toplamuye = $vt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE uyeno>0 ".$sorgu."");

?>

<table border="1" align="center" cellpadding="0" cellspacing="0" width="95%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['UYE_YONETIMI']; ?></b></font></td>
  </tr>
  <form method="post" action="uye_yonet.php">
	<input type="hidden" name="uyearakontrol" value="1" />
  <tr>
    <td width="100%" align="center"><b><?php echo $dil['UYE_ARA']; ?></b><br />
		<input type="radio" name="uyeonay" value="1"<?php if ($uyeonay==1) echo ' checked="checked"'; ?>/> <?php echo $dil['UyeOnayiBekliyor']; ?>&nbsp;&nbsp;
		<input type="radio" name="uyeonay" value="2"<?php if ($uyeonay==2) echo ' checked="checked"'; ?>/> <?php echo $dil['UyeOnaylamis']; ?>&nbsp;&nbsp;
		<input type="radio" name="uyeonay" value="0"<?php if ($uyeonay==0) echo ' checked="checked"'; ?>/> <?php echo $dil['Hepsi']; ?><br />
		<input type="radio" name="yonay" value="1"<?php if ($yoneticionay==1) echo ' checked="checked"'; ?>/> <?php echo $dil['YoneticiOnayiBekliyor']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="yonay" value="2"<?php if ($yoneticionay==2) echo ' checked="checked"'; ?>/> <?php echo $dil['YoneticiOnaylamis']; ?>
		<input type="radio" name="yonay" value="0"<?php if ($yoneticionay==0) echo ' checked="checked"'; ?>/> <?php echo $dil['Hepsi']; ?><br />
		
		<?php echo $dil['UYE_ADI'].' & '.$dil['ADI_SOYADI']; ?> : <input type="text" name="uyeadi" size="20" class="input"  value="<?php echo $uyeadi; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="uyeara" value="<?php echo $dil['Ara']; ?>" class="input" /></td>
  </tr>
  </form>
  <form action="uye_yonet.php?islem=2&s=<?php echo $s; ?>" method="post" name="uye_yonet" id="uye_yonet">
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
        <?php
        $siralama_dizi = array(1=>$dil['KayitTarihiYeni'], 2=>$dil['KayitTarihiEski'], 3=>$dil['UyeAdiAZ'], 4=>$dil['UyeAdiZA']);
        ?>
        <tr>
          <td width="100%" colspan="9" align="center"><b> <?php echo $dil['UyeBilgileri']; ?> </b></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><?php echo $dil['Siralama']; ?> : 
            <select size="1" class="select" name="url" onchange="if(options[selectedIndex].value)   window.location.href=(options[selectedIndex].value)">
						<?php
            foreach($siralama_dizi AS $anahtar => $deger)
            {
              echo '<option value=?sayfa=2&sirala='.$anahtar.''; if ($sirala == $anahtar) echo ' selected'; echo '>'.$deger.'</option>';
            }
						?>

            </select>
          </td>
          <td colspan="5" align="center"><?php echo $dil['ToplamUyeSayisi']; ?> : <b><?php echo $toplamuye; ?></b></td>
        </tr>
        <tr bgcolor="#b6c5f2">
        <td width="3%" align="center"><input type="checkbox" onclick="this.value=sec(this.form.uyeler)" class="input" /></td>
        <td width="2%" align="center"><b><?php echo $dil['UYE_NO']; ?></b></td>
        <td width="15%" align="center"><b><?php echo $dil['UYE_ADI']; ?></b></td>
        <td width="15%" align="center"><b><?php echo $dil['ADI_SOYADI']; ?></b></td>
        <td width="15%" align="center"><b><?php echo $dil['E_POSTA']; ?></b></td>
				<td width="10%" align="center"><b><?php echo $dil['SEVIYE']; ?></b></td>
        <td width="5%" align="center"><b><?php echo $dil['GIRIS_SAYISI']; ?></b></td>
        <td width="5%" align="center"><b><?php echo $dil['DOGUM_TARIHI']; ?></b></td>
        <td width="10%" align="center"><b><?php echo $dil['KAYIT_TARIHI']; ?></b></td>
        <td width="15%" align="center"><b><?php echo $dil['ONAY_DURUMU']; ?></b></td>
      </tr>
			<?php
      $sira = 0;
      $sirano = 0;
	    if (empty($toplamuye))
	    {
         echo '<tr bgcolor="#f7f7fd">
          <td width="100%" align="center" colspan="10"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td>
		    </tr>';
	    } else {
      while ($uyeler_veri = $vt->fetchObject())
      {
        $sira++;
        $sirano = $sira+$baslangic;
        $uyeler_no             = $uyeler_veri->uyeno;
        $uyeler_eposta         = $uyeler_veri->eposta;
        $uyeler_kuladi         = $uyeler_veri->uyeadi;
        $uyeler_adi            = $uyeler_veri->adi;
        $uyeler_soyadi         = $uyeler_veri->soyadi;
        $uyeler_kayittarihi    = $uyeler_veri->kayittarihi;
        $uyeler_songiristarihi = $uyeler_veri->songiristarihi;
        $uyeler_girissayisi    = $uyeler_veri->girissayisi;
        $uyeler_songuncelleme  = $uyeler_veri->guncellemetarihi;
        $uyeler_yas            = $uyeler_veri->dogumtarihi;
        $uyeler_onay           = $uyeler_veri->onay;
        $uyeler_yoneticionayi  = $uyeler_veri->yonay;
				$uyeler_seviye         = $uyeler_veri->seviye;
				  
        if ($uyeler_onay == 'E')
        {
          $onay_durumu = '<font color="#008000">- '.$dil['UyeOnaylamis'].' -</font>';
        } else {
          $onay_durumu = '<font color="#FF0000">- '.$dil['UyeOnayiBekliyor'].' -</font>';
        }
        
				if ($uyeler_yoneticionayi == 5)
        {
          $yonay_durumu = '<font color="#008000">- '.$dil['YoneticiOnaylamis'].' -</font>';
          $yonay = '<a href="uye_yonet.php?islem=2&islemonaykaldir=1&uyeler_'.$uyeler_no.'='.$uyeler_no.'"><font color="#008000">['.$dil['OnayiKaldir'].']</a></font>&nbsp;&nbsp;&nbsp;<a href="uye_yonet.php?islem=4&uyeno='.$uyeler_no.'&onay=1"><font color="#008000">['.$dil['AskiyaAl'].']</a></font>';
				} elseif ($uyeler_yoneticionayi == 1) {
          $yonay_durumu = '<font color="#FF0000">- '.$dil['UyelikAskida'].' -</font>';
          $yonay = '<a href="uye_yonet.php?islem=4&uyeno='.$uyeler_no.'&onay=5"><font color="#008000">'.$dil['AskiyiKaldir'].'</font></a>';
        } else {
          $yonay_durumu = '<font color="#FF0000">- '.$dil['YoneticiOnayiBekliyor'].' -</font>';
          $yonay = '<a href="uye_yonet.php?islem=2&islemonay=1&uyeler_'.$uyeler_no.'='.$uyeler_no.'"><font color="#FF0000">'.$dil['Onayla'].'</font></font>';
        }
				if ($uyeler_seviye == 6)
				{
				  $renk = '#cocoec';
				} elseif ($uyeler_seviye == 5) {
				  $renk = '#dfdff5';
				} else {
				  $renk = '#f7f7fd';
				}
        ?>
        <tr bgcolor="<?php echo $renk; ?>">
          <td width="3%" align="center"><input type="checkbox" id="uyeler" name="<?php echo 'uyeler_'.$uyeler_no; ?>" value="<?php echo $uyeler_no; ?>" class="input" /></td>
					<td width="2%" align="center"><b><?php echo $uyeler_no; ?></b></td>
					<td width="15%" align="left">&nbsp;&nbsp;<a href="?sayfa=2&islem=1&uyeno=<?php echo $uyeler_no; ?>&s=<?php echo $s; ?>"><?php echo $uyeler_kuladi; ?></a></td>
					<td width="15%" align="left">&nbsp;<?php echo $uyeler_adi.' '.$uyeler_soyadi; ?></td>
					<td width="15%" align="center"><?php echo $uyeler_eposta; ?></td>
					<td width="10%" align="center"><?php echo $seviyeler[$uyeler_seviye]; ?></td>
          <td width="5%" align="center"><?php echo $uyeler_girissayisi; ?></td>
					<td width="5%" align="center"><?php echo $fonk->duzgun_tarih_saat($uyeler_yas); ?></td>
					<td width="10%" align="center"><?php echo $fonk->duzgun_tarih_saat($uyeler_kayittarihi,true); ?></td>
					<td width="15%" align="center" nowrap="nowrap">
					<?php
					if ($uyeler_seviye < UYE_SEVIYE) echo $onay_durumu.'<br>'.$yonay_durumu.'<br />'.$yonay;
					else
					echo '-------------------';
					?>
					</td>
				</tr>
			<?php
      }
			unset($uyeler_no,$uyeler_eposta,$uyeler_kuladi,$uyeler_adi,$uyeler_soyadi,$uyeler_kayittarihi,$uyeler_songiristarihi);
			$vt->freeResult();
	}
			?>
      <tr>
        <td colspan="8" width="100%" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seçilileri : </font>
          <input type="submit" value="Onayla" name="islemonay" onclick="return onaylama(<?php echo UYELIK_ONAYI; ?>)" class="input" />&nbsp;
          <input type="submit" value="Onayı Kaldır" name="islemonaykaldir" onclick="return islemler('<?php echo $dil['OnayiKaldirmakIstiyormusunuz']; ?>')" class="input" />&nbsp;
          <input type="submit" value="<?php echo $dil['Sil']; ?>" name="islemsil" onclick="return islemler('<?php echo $dil['SilmekIstiyormusunuz']; ?>')" class="input" />&nbsp;</td>
      </tr>	
      <tr>
        <td colspan="10" width="100%" align="center">
				  <table width="90%" align="center">
          <tr>
            <td width="50%" align="left">&nbsp;&nbsp;
						  <?php
              if ($s > 1)
              {
                $onceki = $s-1;
                echo '<a href="uye_yonet.php?s='.$onceki.'&sirala='.$sirala.$aranan.'">«&nbsp;'.$dil['Onceki'].'</a>';
              }
              echo '
            </td>
            <td width="50%" align="right">';
            if ($toplamuye > ($s*$limit))
            {
              $sonraki = $s+1;
              echo '<a href="uye_yonet.php?s='.$sonraki.'&sirala='.$sirala.$aranan.'">'.$dil['Sonraki'].'&nbsp;»</a>';
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
</form>
</table>


<?php
} elseif ($islem == 1) {					
/* ================================ *\
|| -- ÜYE AYRINTILARI -- 		    ||
\* ================================ */

  //Üyenin Bilgilerinin Alınması
  $uye_nosu = trim(intval($_GET['uyeno']));
	$sayfa_nosu = intval(trim($_GET['s']));
	
	$vt->query("SELECT uyeno, uyeadi, eposta, adi, soyadi, dogumtarihi, seviye, onay, yonay, kayittarihi, girissayisi, songiristarihi, guncellemetarihi ,ip FROM ".TABLO_ONEKI."uyeler WHERE uyeno=$uye_nosu");
	
	$uye_veri           = $vt->fetchObject();
  $uye_no             = $uye_veri->uyeno;
  $uye_eposta         = $uye_veri->eposta;
  $uye_kuladi         = $uye_veri->uyeadi;
  $uye_adi            = $uye_veri->adi;
  $uye_soyadi         = $uye_veri->soyadi;
  $uye_kayittarihi    = $uye_veri->kayittarihi;
  $uye_songiristarihi = $uye_veri->songiristarihi;
  $uye_girissayisi    = $uye_veri->girissayisi;
  $uye_songuncelleme  = $uye_veri->guncellemetarihi;
  $uye_yas            = $uye_veri->dogumtarihi;
	$uye_ip             = $uye_veri->ip;
  $uye_onay           = $uye_veri->onay;
  $uye_yoneticionayi  = $uye_veri->yonay;
	$uye_seviye         = $uye_veri->seviye;
  $vt->freeResult();

?> 
<form action="uye_yonet.php?islem=3"  method="post"> 
<input type="hidden" id="uye_no" name="s" value="<?php echo $sayfa_nosu;?>">     
<input type="hidden" id="uye_no" name="uye_no" value="<?php echo $uye_nosu;?>">
<table border="1" align="center" cellpadding="0" cellspacing="0" width="80%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><a href="uye_yonet.php?uyeno=<?php echo $uye_nosu; ?>&s=<?php echo $sayfa_nosu; ?>"><font color="#ffffff"><b><?php echo $dil['UYE_YONETIMI']; ?></b></font></a></td>
  </tr>
	 <tr>
    <td width="100%" align="center"><a href="uye_yonet.php?uyeno=<?php echo $uye_nosu; ?>&s=<?php echo $sayfa_nosu; ?>"><?php echo $dil['GeriDon']; ?></b></font></a></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" bgcolor="#efeffa">
        <tr bgcolor="#b6c5f2">
          <td colspan="2" width="95%" align="center" height="20" class="border_4"><b><?php echo $uye_kuladi?></b></td>
        </tr>
				<tr>
          <td colspan="2" width="95%" align="center" height="20" class="border_4"><?php if (UYE_SEVIYE < 6) echo $dil['AltSeviyedekiUyeleriDuzenleyebilirsiniz']; ?></td>
        </tr>
        <tr bgcolor="#f7f7fd">
					<td width="40%" align="right" height="25"><b>* <?php echo $dil['KullaniciAdi']; ?> :</b> </td>
					<td width="60%" align="left" height="25">&nbsp;<input type="text" id="kullaniciadi" name="kullaniciadi" maxlength="20" value="<?php echo $uye_kuladi;?>" class="input" />&nbsp;&nbsp;4-20&nbsp;&nbsp;(a-z, A-Z, 0-9, _)</td>
				</tr>
				  <tr bgcolor="#ffffff">
					<td width="40%" align="right" height="25"><b>* <?php echo $dil['EpostaAdresi']; ?> :</b> </td>
					<td width="60%" align="left" height="25">&nbsp;<input type="text" id="eposta" name="eposta" maxlength="100" value="<?php echo $uye_eposta;?>" class="input" />&nbsp;&nbsp;7-100</td>
				</tr>
				<tr bgcolor="#f7f7fd">
					<td width="40%" align="right" height="25"><b>* <?php echo $dil['Sifre']; ?> :</b> </td>
					<td width="60%" align="left" height="25">&nbsp;<input type="text" id="sifre" name="sifre" maxlength="25" class="input" />&nbsp;&nbsp;6-25&nbsp;&nbsp;(a-z, A-Z, 0-9, _)</td>
				</tr>
				<tr bgcolor="#ffffff">
					<td width="40%" align="right" height="25"><b>* <?php echo $dil['SifreTekrar']; ?> :</b> </td>
					<td width="60%" align="left" height="25">&nbsp;<input type="text" id="sifretekrar" name="sifretekrar" maxlength="25" class="input" />&nbsp;&nbsp;6-25</td>
				</tr>
				<tr bgcolor="#f7f7fd">
					<td width="40%" align="right" height="25"><b>* <?php echo $dil['Isim']; ?> :</b> </td>
					<td width="60%" align="left" height="25">&nbsp;<input type="text" id="adi" name="adi" maxlength="25" value="<?php echo $uye_adi;?>" class="input" />&nbsp;&nbsp;2-50</td>
				</tr>
				<tr bgcolor="#ffffff">
					<td width="40%" align="right" height="25"><b>* <?php echo $dil['Soyisim']; ?> :</b> </td>
					<td width="60%" align="left" height="25">&nbsp;<input type="text" id="soyadi" name="soyadi" maxlength="25" value="<?php echo $uye_soyadi;?>" class="input" />&nbsp;&nbsp;2-50</td>
				</tr>
				
				<tr bgcolor="#ffffff">
					<td width="40%" align="right" height="25"><b>* <?php echo $dil['Seviye']; ?> :</b> </td>
					<td width="60%" align="left" height="25">
					<select name="seviye" id="seviye" class="input">
					<?php
					foreach($seviyeler AS $seviyeno=>$seviyeadi)
					{
					  echo '<option value="'.$seviyeno.'"'; if ($seviyeno == $uye_seviye) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
					}
					?>
					</select>
					</td>
				</tr>
				
        <tr  bgcolor="#f7f7fd">
          <td align="center" colspan="2" width="100%" height="28"><input type="submit" value="<?php echo $dil['GUNCELLE']; ?>" class="input" /></td>
        </tr>
				
				<tr bgcolor="#b6c5f2">
		      <td colspan="2" width="95%" align="center" height="20" class="border_4"><b><?php echo $dil['IstatistikiBilgiler']; ?></b></td>
				</tr>
				
				<tr>
					<td width="40%" align="right" height="25"><b><?php echo $dil['IPAdresi']; ?> :</b> </td>
					<td width="60%" align="left" height="25">&nbsp;<?php echo $uye_ip;
					if ($vt->kayitSay("SELECT COUNT(ip) FROM ".TABLO_ONEKI."ipengelle WHERE ip=TRIM('$uye_ip')")>0 )
					{ 
					  echo '&nbsp;&nbsp;&nbsp;<font color="#ff0000">'.$dil['IPAdresiEngelli'].'</font>&nbsp;&nbsp;<a href="ip_engelle.php?ipara='.$uye_ip.'"><font color="#008000">'.$dil['IPEngeliniKaldir'].'</a>';
					} else {
					  if ($uye_seviye < 6)
					  echo '&nbsp;&nbsp;&nbsp;<a href="ip_engelle.php?ip='.$uye_ip.'"><font color="#ff0000">'.$dil['IPAdresiEngelle'].'</font></a>';
					}
					?><br />
					</td>
				</tr>
				
				<tr>
					<td width="40%" align="right" height="25"><b><?php echo $dil['DogumTarihi']; ?> :</b> </td>
					<td width="60%" align="left" height="25">&nbsp;<?php echo $fonk->duzgun_tarih_saat($uye_yas); ?></td>
				</tr>
				
				<tr>
					<td width="40%" align="right" height=25><b><?php echo $dil['KayitTarihi']; ?> :</b> </td>
					<td width="60%" align="left" height=25>&nbsp;<?php echo $fonk->duzgun_tarih_saat($uye_kayittarihi,true);?></td>
				</tr>
				<tr>
					<td width="40%" align="right" height=25><b><?php echo $dil['SonGirisTarihi']; ?> :</b> </td>
					<td width="60%" align="left" height=25>&nbsp;<?php echo $fonk->duzgun_tarih_saat($uye_songiristarihi,true);?></td>
				  </tr>
				<tr>
					<td width="40%" align="right" height=25><b><?php echo $dil['GirisSayisi']; ?> :</b> </td>
					<td width="60%" align="left" height=25>&nbsp;<?php echo $uye_girissayisi;?></td>
				</tr>
				<tr>
					<td width="40%" align="right" height=25><b><?php echo $dil['SonGuncellemeTarihi']; ?> :</b> </td>
					<td width="60%" align="left" height=25>&nbsp;<?php echo $fonk->duzgun_tarih_saat($uye_songuncelleme,true);?></td>
				</tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?php
/* ============================================== */
/* ÜYE ONAYLAMA, SİLME, ONAY KALDIRMA İŞLEMLERİ
/* ============================================== */
/* ÜYE İSLEMLERİ */
} elseif ($islem == 2) {
  @ $islemsil        = $_REQUEST['islemsil'];
  @ $islemonay       = $_REQUEST['islemonay'];
  @ $islemonaykaldir = $_REQUEST['islemonaykaldir'];
  @ $form_bilgisi    = $_REQUEST;
	@ $sayfa_nosu      = intval($_REQUEST['s']);
  
  if ($islemsil)
  {
    //SİLME İŞLEMLERİ
    foreach ( $form_bilgisi as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
        if ($anahtar == "uyeler_$deger")
        {
          $uyeler_sil = $vt->query2("DELETE FROM ".TABLO_ONEKI."uyeler WHERE uyeno=$deger AND seviye<".UYE_SEVIYE."");
        }
      }
    }
    echo "<script>alert('".$dil['SilmeIslemiTamamlandi']."');location.href='?sayfa=2';</script>";
  } elseif ($islemonay) {

    //ONAYLAMA İŞLEMLERİ
    foreach ( $form_bilgisi as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
        if ($anahtar == "uyeler_$deger")
        {
          if (!$vt->query2("UPDATE ".TABLO_ONEKI."uyeler SET onay='E',yonay=5 WHERE uyeno=$deger"))
					{
					  echo $dil['OnaylamaIslemiBasarisiz'];
						exit;
					}
        }
      }
    }
    echo "<script>alert('".$dil['OnaylamaIslemiTamamlandi']."');location.href='?sayfa=2&s=".$sayfa_nosu."';</script>";
  } elseif ($islemonaykaldir) {
    //ONAY KALDIRMA İŞLEMLERİ
    foreach ( $form_bilgisi as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
        if ($anahtar == "uyeler_$deger")
        {
				  if (UYE_SEVIYE > 5)
					{
					  if (!$vt->query("UPDATE ".TABLO_ONEKI."uyeler SET yonay=0 WHERE uyeno=$deger AND uyeno<>".UYE_NO.""))
					  {
					    echo 'İşlem Başarısız';
					    exit;
					  }
					} else {
            if (!$vt->query("UPDATE ".TABLO_ONEKI."uyeler SET yonay=0 WHERE uyeno=$deger AND seviye<".UYE_SEVIYE.""))
					  {
					    echo $dil['IslemBasarisiz'];
					    exit;
					  }
					}
        }
      }
    }
    echo "<script>alert('".$dil['OnayKaldirildi']."');location.href='?sayfa=2';</script>";
  }
} elseif ($islem == 3) {
/* ============================
// ÜYE DÜZENLEME KAYIT 
==============================*/
  
  // FORMDAN GELEN VERİLER
  @ $uyeno           = trim(intval($_POST['uye_no']));
  @ $kullaniciadi    = trim(strip_tags(htmlspecialchars($_POST['kullaniciadi'])));
  @ $eposta          = trim(strip_tags(htmlspecialchars($_POST['eposta'])));
  @ $adi             = trim(strip_tags(htmlspecialchars($_POST['adi'])));
  @ $soyadi          = trim(strip_tags(htmlspecialchars($_POST['soyadi'])));
	@ $sifre           = trim(strip_tags(htmlspecialchars($_POST['sifre'])));
	@ $sifretekrar     = trim(strip_tags(htmlspecialchars($_POST['sifretekrar'])));
  @ $seviye          = intval($_POST['seviye']);

  try
	{ 
	  if (empty($uyeno) || empty($kullaniciadi) || empty($eposta) || empty($adi) || empty($soyadi))
	  {
		  throw new Exception($dil['IsaretliAlaniBosBirakmayiniz'],1);
			exit;
	  }
	  
	  if (!$fonk->kuladi_kontrol($kullaniciadi))
	  {
		  throw new Exception($dil['GecersizKarakterGirdiniz'].' : '.$dil['KullaniciAdi']);
	  }
		
		if (!$fonk->parola_kontrol($sifre) || !$fonk->parola_kontrol($sifretekrar))
	  {
		  throw new Exception($dil['GecersizKarakterGirdiniz'].' : '.$dil['Sifre']);
	  }

	  if (strlen($kullaniciadi) > 20 || strlen($kullaniciadi) < 4)
	  {
		  throw new Exception($dil['KarakterSayisiGecersiz'].' : '.$dil['KullaniciAdi'].' 4-20');
	  }
		//Şifre Boş Değilse Kontrol Ediliyor
    if ($sifre)
		{
	    if (strlen($sifre) > 25 || strlen($sifre) < 6)
	    {
		    throw new Exception($dil['KarakterSayisiGecersiz'].' : '.$dil['KullaniciAdi'].' 6-25');
	    }
	  
	    if ($sifre != $sifretekrar)
	    {
		    throw new Exception($dil['SifrelerUyusmuyor']);
	    }
		}
		if (strlen($eposta) > 100 || strlen($eposta) < 7)
	  {
		  throw new Exception($dil['KarakterSayisiGecersiz'].' : '.$dil['EpostaAdresi'].' 7-100');
	  }

	  if (!$fonk->eposta_kontrol($eposta))
	  {
		  throw new Exception($dil['EpostaAdresiGecersiz']);
	  }
	  
	  if (strlen($adi) > 50 || strlen($adi) < 2 || strlen($soyadi) > 50 || strlen($soyadi) < 2)
	  {
		  throw new Exception($dil['KarakterSayisiGecersiz'].' : '.$dil['Isim'].'-'.$dil['Soyisim'].' 2-50');
	  }

	  if (!$fonk->turkceharf_kontrol($adi))
	  {
		  throw new Exception($dil['GecersizKarakterGirdiniz'].' : '.$dil['Isim']);
	  }
		if (!$fonk->turkceharf_kontrol($soyadi))
	  {
		  throw new Exception($dil['GecersizKarakterGirdiniz'].' : '.$dil['Soyisim']);
	  }
		
		if ($seviye >= UYE_SEVIYE && UYE_SEVIYE<6)
		{
		  throw new Exception($dil['UyeSeviyeAyarIzin']);
	  }
		
	  if ($vt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE uyeno<>$uyeno AND uyeadi='$kullaniciadi'") > 0)
	  {
		  //Üye Adının Olup Olmadığı Kontrol Ediliyor (kontrol.php deki Sınıf Fonksiyonu Kullanılmıştır)
		  throw new Exception($dil['UyeAdiKullaniliyor']);
	  }

		if ($vt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE uyeno<>$uyeno AND eposta='$eposta'") > 0)
	  {
		  //E-Posta Adresinin Olup Olmadığı Kontrol Ediliyor (kontrol.php deki Sınf Fonksiyonu Kullanılmıştır)
		  throw new Exception($dil['EpostaAdresiKullaniliyor']);
			exit;
	  }
		//Otomatik Ters Çizgi Ekleme Kapalıysa Ters Çizgi (\) Ekliyoruz...
   if (!get_magic_quotes_gpc())
   {
      $adi    = addslashes($adi);
      $soyadi = addslashes($soyadi);
    }
		if ($sifre)
		{
		  $sifre = sha1($sifre);
		  $vt->query2("UPDATE ".TABLO_ONEKI."uyeler SET sifre='$sifre' WHERE uyeno=".$uyeno." AND seviye<".UYE_SEVIYE."");
		}

    //GÜNCELLEME İŞLEMİ 
		if (UYE_SEVIYE == 5)
		{
		  if ($seviye > 5)
			{
			  $seviye == 5;
			}
		} 

    $vt->query2("UPDATE ".TABLO_ONEKI."uyeler SET uyeadi='$kullaniciadi', adi='$adi', soyadi='$soyadi',eposta='$eposta',seviye=$seviye WHERE uyeno=".$uyeno." AND (seviye<".UYE_SEVIYE." OR ".UYE_SEVIYE."=6)");
    echo "<script>alert('".$dil['GuncellemeIslemiTamamlandi']."');location.href='uye_yonet.php?islem=1&uyeno=".$uyeno."';</script>";
	}
	catch (Exception $e)
  {
    echo "<script>alert('".$e->getMessage()."');location.href='uye_yonet.php?sayfa=2&islem=1&uyeno=".$uyeno."';</script>";
  }
} elseif ($islem == 4) {

	try
	{ 
	  @ $uyeno           = trim(intval($_GET['uyeno']));
		@ $onay            = intval($_GET['onay']);
		if (UYE_SEVIYE < 4)
		{
		  throw new Exception($dil['IslemIcinYetkinizYok']);
			exit;
		}

		if ($uyeno)
		{
		  $vt->query("SELECT seviye FROM ".TABLO_ONEKI."uyeler WHERE uyeno=$uyeno");
			$uye_var = $vt->numRows();
			$seviyeal  = $vt->fetchObject();
			$seviye    = $seviyeal->seviye;
			
			if ($uye_var > 0)
			{
			  if ($seviye >= UYE_SEVIYE && UYE_NO != $uyeno)
			  {
			    throw new Exception($dil['UyeIcinIslemYetkinizYok']); 
					exit;
			  } else {
			    if ($vt->query2("UPDATE ".TABLO_ONEKI."uyeler SET yonay=$onay WHERE uyeno=$uyeno"))
					{
				    echo "<script>alert('".$dil['IslemTamamlandi']."');location.href='uye_yonet.php';</script>";
					} else {
					  echo '<div align="center">'.$dil['IslemBasarisiz'].'</div>';
					}
				}
			} else {
			  throw new Exception ($dil['UyeGecerliDegil']);
			}
		}
  }
	catch (Exception $e)
  {
    echo '<div align="center">'.$e->getMessage().'<br /><a href="uye_yonet.php">'.$dil['GeriDon'].'</a></div>';
  }
}
?>
