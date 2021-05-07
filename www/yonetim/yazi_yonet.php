<?php
ob_start();
session_start();
require_once ("../icerik/vt.inc.php");
require_once ("../icerik/ayar.inc.php");
require_once ("../icerik/sev.inc.php");
require_once ("../icerik/dil.inc.php");
dil_belirle('','yonetimdil');
require_once ("../icerik/fonk.inc.php");
//Yönetici Girişi Yapılmamışsa Yasakla
if (UYE_SEVIYE < 5) 
{
  header('Location: ../index.php');
  exit;
}
$fonk = new Fonksiyon();
$yyvt = new Baglanti();
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

function islemler(mesaj) 
{
  var total = 0;
  var max   = 0;
  max       = yazilar.yazilar.length;

  for (var idx = 0; idx < max; idx++) 
  {
    if (eval("document.yazilar.yazilar[" + idx + "].checked") == true) 
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
//-->
</script>
<script language="JavaScript" type="text/javascript" src="../icerik/script.js"></script>
</head>
<body background="yonetimresim/bg.gif">
<?php
$islem   = false; 
@ $islem = intval($_GET['islem']);
@ $aranan_yazi = trim(strip_tags(htmlspecialchars($_REQUEST['yaziara'])));
@ $kategorino  = intval($_REQUEST['kategorino']);
@ $y = abs(intval($_REQUEST['y']));
if (empty($islem))
{
	$arama_kosul           = 'WHERE yazino>0';
  if (empty($aranan_yazi) && empty($kategorino))
  {
    $aranan               = '';
    $arama_mesaj          = '<font color="#ff6600"><b>'.$dil['TumYazilarGosteriliyor'].'</b></font>';
  }
	if ($aranan_yazi)
	{
    $aranan_yazi          = eregi_replace(" {1,}","+",$aranan_yazi);
    $aranan_kelimeler     = explode('+',$aranan_yazi);
    $aranan_kelime_sayisi = count($aranan_kelimeler);
    $arama_kosul          .= " AND (baslik LIKE '%$aranan_kelimeler[0]%'";
    for ($i=0; $i<$aranan_kelime_sayisi; $i++)
    {
      $aranan_kelime = $aranan_kelimeler[$i];
      $arama_kosul .= " OR baslik LIKE '%$aranan_kelime%' OR yazi LIKE '%$aranan_kelime%'";
    }
    $arama_kosul .= ")";
    $aranan      = '&yaziara='.implode('+',$aranan_kelimeler);
    $aranan      = '&yaziara='.$aranan_yazi;
    $arama_mesaj = '<font color="#ff6600"><b>'.$dil['ArananSozcukler'].' :</b></font> '.$aranan_yazi;
  }
	if (KATEGORI_SAYI > 0)
  {
    if ($kategorino>0)
    {
      //Alt Kategoriler Diziye Aliniyor
      $altkategori_dizi = $fonk->kategoriIdListe($kategorino);
      if (count($altkategori_dizi)>0)
      $arama_kosul .= " AND kategorino IN (".$kategorino.",".implode(',',$altkategori_dizi).")";  //Alt Kategori Varsa Ana Kategori Ile Birlikte Sorgulaniyor
      else
      $arama_kosul .= ' AND kategorino='.$kategorino;  //Alt Kategori Yoksa Sadece Ana Kategori Sorgulaniyor
    }
		$aranan        .= "&kategorino=$kategorino";
  } else {
    $arama_kosul .= ' AND kategorino=0';
  }

  $siralama_dizi = array(1=>$dil['KayitTarihiYeni'], 2=>$dil['KayitTarihiEski'], 3=>$dil['UyeAdiAZ'], 4=>$dil['UyeAdiZA']);

  $toplamyazi = $yyvt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar $arama_kosul");
  ?>
  <table border="1" align="center" cellpadding="0" cellspacing="0" width="95%" bgcolor="#efeffa" bordercolor="#b6c5f2">
    <tr>
      <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['YAZI_YORUM_YONETIMI']; ?></b></font></td>
    </tr>
    <tr>
      <td width="100%" align="center"><form method="post" action="yazi_yonet.php"><b><?php echo $dil['YAZI_ARA']; ?> : </b> 
			<?php
          if (KATEGORI_SAYI > 0)
          {
            echo '<select name="kategorino" class="input">';
						echo '<option value="0">'.$dil['ButunKonular'].'</option>';
						$kategoriler_dizi = $fonk->kategoriListe(0,0);
            for ($i=0; $i<count($kategoriler_dizi); $i++) 
            { 
              $kategorinosu = $kategoriler_dizi[$i][0];
              $kategoriismi = $kategoriler_dizi[$i][1];
              echo '<option value="'.$kategorinosu.'"'; if ($kategorinosu==$kategorino) echo ' selected="selected"'; echo '>';
              $level = $kategoriler_dizi[$i][2];
              for ($j=0;$j<$level;$j++) echo '&nbsp;&nbsp;'; //Alt Kategorileri Iceri Kaydirma Bosluklari 
              echo $kategoriismi.'</option>';
            } 
            unset($kategoriler_dizi,$kategorinosu,$kategoriismi);
            echo '</select>';
          }
          ?>
					<input type="text" name="yaziara" size="20" class="input" /> <input type="submit" value="<?php echo $dil['Ara']; ?>" class="input" /></form></td>
    </tr>
    <tr bgcolor="#efeffa">
      <td align="center" width="100%" bgcolor="#efeffa">
			  <form action="yazi_yonet.php?islem=3&aranan=<?php echo $aranan; ?>&kategorino=<?php echo $kategorino; ?>&y=<?php echo $y; ?>" method="post" name="yazilar">
        <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
          <tr>
            <td width=100% colspan="5" align="left"><?php echo $dil['ToplamYaziSayisi']; ?> : <b><?php echo $toplamyazi; ?></b></td>
          </tr>
          <tr bgcolor="#b6c5f2">
            <td width="2%" align="center"><input type="checkbox" onclick="this.value=sec(this.form.yazilar)" class="input" /></td>
            <td width="5%" align="center"><b>SN</b></td>
            <td width="30%" align="center"><b><?php echo $dil['BASLIK']; ?></b></td>
            <td width="15%" align="center"><b><?php echo $dil['EKLEYEN']; ?></b></td>
            <td width="10%" align="center"><b><?php echo $dil['TARIH']; ?></b></td>
            <td width="10%" align="center"><b><?php echo $dil['YORUM']; ?></b></td>
            <td width="10%" align="center"><b><?php echo $dil['ONAY_DURUMU']; ?></b></td>
            <td width="18%" align="center"><b><?php echo $dil['KATEGORI']; ?></b></td>
          </tr>
          <?php
          $limit = 30;
          if(empty($y)) 
          {                
            $y = 1;                
            $baslangic = 0;        
          } else {               
            $baslangic = ($y - 1) * $limit;        
          }
          $yyvt->query("SELECT yazino,kategorino,uyeno,baslik,eklemetarihi,okunma,onay FROM ".TABLO_ONEKI."yazilar $arama_kosul ORDER BY onay DESC, eklemetarihi DESC LIMIT $baslangic,$limit");
          $yazi_sayi = $yyvt->numRows();

          if ($yazi_sayi == 0)
          {
            echo '
            <tr>
              <td  width="100%" bgcolor="#f0f8ff" align="center" height="30" colspan="8"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td>
            </tr>';
          } else {
            $ysayi = 0;
            $yazi_no = 0;
            for ($i=0; $i<$yazi_sayi; $i++) //for baslangici-(a)
            {
              $yazi_bilgi  = $yyvt->fetchArray();
              $yazi_no          = intval($yazi_bilgi["yazino"]);
              $yazi_baslik      = $fonk->yazdir_duzen($yazi_bilgi["baslik"]);
              $yazi_okunma      = $yazi_bilgi["okunma"];
              $yazi_tarih       = $yazi_bilgi["eklemetarihi"];
              $yazi_yazar       = intval($yazi_bilgi["uyeno"]);
              $yazi_onay        = $yazi_bilgi["onay"];
              $yazi_kategori    = $yazi_bilgi['kategorino'];
              $ysayi++;
              $y_sira           = $baslangic+$ysayi;

              $yazi_baslik      = substr($yazi_baslik,0,50).'...';
              if (($ysayi % 2) == 0)
              {
                $bg_color = '#dae1f9';
              } else {
                $bg_color = '#f7f7fd';
              }
              if ($yazi_onay == 'E')
              {
                $onay = '<font color="#008000">'.$dil['Onayli'].'</font><br /><a href="yazi_yonet.php?islem=3&yazilar_'.$yazi_no.'='.$yazi_no.'&islemonaykaldir=1"><font color="#008000">'.$dil['OnayiKaldir'].'</font></a>';
              } else {
                $onay = '<font color="#ff0000">'.$dil['Onaysiz'].'</font><br /><a href="yazi_yonet.php?islem=3&yazilar_'.$yazi_no.'='.$yazi_no.'&islemonay=1"><font color="#ff0000">'.$dil['Onayla'].'</font></a>';
              }
              //YORUM SAYISI
              $toplam_onayli_yorum          = $yyvt->kayitSay("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE onay='E' AND yazino=$yazi_no");
              $toplam_onaysiz_yorum        = $yyvt->kayitSay("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE onay='H' AND yazino=$yazi_no");
              $toplam_yorum = intval($toplam_onayli_yorum+$toplam_onaysiz_yorum);
              ?>
              <tr bgcolor="<?php echo $bg_color; ?>">
                <td width="2%" align="center"><input type="checkbox" id="yazilar" name="<?php echo 'yazilar_'.$yazi_no; ?>" value="<?php echo $yazi_no; ?>" class="input" /></td>
                <td width="5%" height="20" align="center"><?php echo $y_sira; ?></td>
                <td width="30%" height="20" align="left">&nbsp;&nbsp;<a href="?islem=2&yno=<?php echo $yazi_no.$aranan; ?>"><font color="#3366ff"><strong><?php echo $yazi_baslik; ?></strong></font></a></td>
                <td width="15%" align="center"><?php echo $fonk->uye_adi($yazi_yazar); ?></td>
                <td width="10%" align="center"><?php echo $fonk->duzgun_tarih_saat($yazi_tarih,true); ?></td>
                <td width="10%" align="left"><?php echo $dil['Toplam']; ?>: <?php echo $toplam_yorum; ?><br /><font color="#008000"><?php echo $dil['Onayli']; ?>: <?php echo $toplam_onayli_yorum; ?></font><br /><font color="#ff0000"><?php echo $dil['Onaysiz']; ?>: <?php echo $toplam_onaysiz_yorum; ?></font></td>
                <td width="10%" align="center"><?php echo $onay; ?></td>
								<td width="18%" align="left" style="padding-left:1px">
								<?php
								if (KATEGORI_SAYI > 0)
								{
									echo '&nbsp;<b>';
									if (!$kategoriadi = $fonk->kategoriAdi($yazi_kategori))
									$kategoriadi = $dil['ButunKonular'];
									echo $kategoriadi.'</b><br />';
								  echo '<select name="kategorino" id="kategorino" class="input" onchange="atla(\'self\',this,0);">'; 
									echo '<option value="0">'.$dil['ButunKonular'].'</option>';
                  $kategoriler_dizi = $fonk->kategoriListe(0,0);
                  for ($k=0; $k<count($kategoriler_dizi); $k++) 
                  { 
                    $kategorino = $kategoriler_dizi[$k][0];
                    $kategoriadi = $kategoriler_dizi[$k][1];
                    echo '<option value="yazi_yonet.php?islem=9&yazino='.$yazi_no.'&kategorino='.$kategorino.'&y='.$y.'"';
										if ($kategorino == $yazi_kategori) echo ' selected="selected"'; echo '>';
                    $level = $kategoriler_dizi[$k][2];
                    for ($j=0;$j<$level;$j++) echo '&nbsp;&nbsp;'; //Alt Kategorileri Iceri Kaydirma Bosluklari 
                    echo $kategoriadi.'</option>';
                  } 
									echo '</select>';
								} else {
								  echo $dil['KategoriBulunamadi'];
								}
								?>
								</td>
							</tr>
            <?php
            } //for sonu - (a)
            ?>
            <tr>
              <td colspan="8" width="100%" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dil['Secilileri']; ?> :
                <input type="submit" value="<?php echo $dil['Onayla']; ?>" name="islemonay" onclick="return islemler('<?php echo $dil['YaziyiOnaylamakIstiyormusunuz']; ?>')" class="input" />&nbsp;
                <input type="submit" value="<?php echo $dil['OnayiKaldir']; ?>" name="islemonaykaldir" onclick="return islemler('<?php echo $dil['YazininOnayiniKaldirmakIstiyormusunuz']; ?>')" class="input" />&nbsp;
                <input type="submit" value="<?php echo $dil['Sil']; ?>" name="islemsil" onclick="return islemler('<?php echo $dil['SilmekIstiyormusunuz']; ?>')" class="input" />&nbsp;</td>
          </tr>
          <?php
          } // Yazi Sayi if Kontrol Sonu
          ?>
          <tr bgcolor="#e5e5f8">
            <td align="center" width="100%" colspan="8">
              <table width="100%" align="center">
                <tr>
                  <td width="50%" align="left">&nbsp;&nbsp;
                  <?php
                  if ($y > 1)
                  {
                    $onceki_yazi = $y-1;
                    echo '<a href="?y='.$onceki_yazi.$aranan.'">«&nbsp;'.$dil['Onceki'].'</a>';
                   }
                   ?>
                   </td>
                   <td width="50%" align="right">
                   <?php
                   if ($toplamyazi > ($y*$limit))
                   {
                     $sonraki_yazi = $y+1;
                     echo '<a href="?y='.$sonraki_yazi.$aranan.'">'.$dil['Sonraki'].'&nbsp;»</a>';
                    }
                    ?>
                    &nbsp;&nbsp;
                   </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
				</form>
      </td>
    </tr>         
  </table>
<?php
unset($toplam_yazi,$toplamyazi,$onceki_yazi,$sonraki_yazi,$yazi_no,$yazi_baslik,$yazi_okunma,$yazi_tarih,$yazi_yazar,$ysayi,$y_sira,$bg_color,$arama_kosul,$aranan,$aranan_yazi,$arama_mesaj);
//=====================================
} elseif ($islem == 2) { // 2. ADIM
//=====================================
  //////////////////////////
  //YAZI AYRINTI
  //////////////////////////
  @ $yno = abs(intval($_GET['yno']));
  
  $yyvt->query("SELECT yazino,kategorino,uyeno,resim,baslik,yazi,eklemetarihi,okunma,onay FROM ".TABLO_ONEKI."yazilar WHERE yazino=$yno");

  $yazi_sayi   = $yyvt->numRows();

  if ($yazi_sayi > 0)
  {
    $yazi_veri     = $yyvt->fetchObject();
		$yazi_no       = $yazi_veri->yazino;
    $yazi_baslik   = $fonk->yazdir_duzen($yazi_veri->baslik);
    $yazi_yazar    = $yazi_veri->uyeno;
		$yazi_icerik   = $fonk->yazdir_duzen($yazi_veri->yazi);
		$yazi_okunma   = $yazi_veri->okunma;
		$yazi_tarih    = $yazi_veri->eklemetarihi;
		$yazi_resim    = $yazi_veri->resim;
		$yazi_onay     = $yazi_veri->onay;
		$yazi_resim    = $yazi_veri->resim;
		$yazi_kategori = $yazi_veri->kategorino;
    
		
		$resim = '../'.RESIM_DIZIN.'/'.$yazi_resim;
		
		
    if (!file_exists($resim) || empty($yazi_resim))
    {
		  $resim = RESIM_DIZIN.'/bos.gif';
		} else {
		  $resim = RESIM_DIZIN.'/'.$yazi_resim;
		}

		
		if ($yazi_onay == 'E')
    {
      $onay = '<a href="yazi_yonet.php?islem=3&yazilar_'.$yazi_no.'='.$yazi_no.'&islemonaykaldir=1"><font color="#008000">'.$dil['OnayiKaldir'].'</font></a>';
    } else {
      $onay = '<a href="yazi_yonet.php?islem=3&yazilar_'.$yazi_no.'='.$yazi_no.'&islemonay=1"><font color="#ff0000">'.$dil['Onayla'].'</font></a>';
    }
    ?>
		<form name="yazi_yaz" action="yazi_yonet.php?islem=7" method="post" enctype="multipart/form-data">
		<input type="hidden" name="yazino" id="yazino" value="<?php echo $yazi_no; ?>" />
    <table border="1" align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="#efeffa" bordercolor="#b6c5f2">
      <tr>
        <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['YAZI_YORUM_YONETIMI']; ?></b></font></td>
      </tr>
      <tr>
        <td colspan="2" align="right" style="padding-right:25px"><br /><a href="yazi_yonet.php?aranan=<?php echo $aranan; ?>&kategorino=<?php echo $kategorino; ?>&y=<?php echo $y; ?>"><b><?php echo $dil['GeriDon']; ?></b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="yazi_yonet.php"><b><?php echo $dil['TumYazilar']; ?></b></a><br />&nbsp;</td>
      </tr>
			<tr>
				<td  align="left" width="100%" class="main4">
				  <table width="90%" align="center">
            <tr>
						  <td><input type="text" name="baslik" value="<?php echo $yazi_baslik; ?>" class="input" size="60" /><br /><br />
							<?php
				      echo $fonk->html_duzen('yazi_yaz','metin',500,'../editor',false);
              ?>
	            <textarea name="metin" id="metin"  style="background: transparent url() repeat scroll 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; height: 170px; width: 400px;" tabindex="2"><?php echo $yazi_icerik; ?></textarea></td>
							  <td align="center"><img alt="<?php echo $yazi_baslik; ?>" src="../resim.php?resim=<?php echo $resim; ?>&en=300&boy=200" border="1" /><br /><br />
								<input type="file" id="resim" name="resim" class="input" value="<?php echo $dil['Gozat']; ?>" /></td>
            </tr>
            <tr>
              <td height="25" colspan="2" align="left">
							<?php
							if (KATEGORI_SAYI > 0)
							{
								  echo 'Kategori : <select name="kategorino" id="kategorino" class="input">';
                  $kategoriler_dizi = $fonk->kategoriListe(0,0);
                  for ($i=0; $i<count($kategoriler_dizi); $i++) 
                  { 
                    $kategorino = $kategoriler_dizi[$i][0];
                    $kategoriadi = $kategoriler_dizi[$i][1];
                    echo '<option value="'.$kategorino.'"';
										if ($kategorino == $yazi_kategori) echo ' selected="selected"'; echo '>';
                    $level = $kategoriler_dizi[$i][2];
                    for ($j=0;$j<$level;$j++) echo '&nbsp;&nbsp;'; //Alt Kategorileri Iceri Kaydirma Bosluklari 
                    echo $kategoriadi.'</option>';
                  } 
									unset($kategoriler_dizi,$kategorino,$kategoriadi);
									echo '</select>';
								}
								?>
							</td>
            </tr>
						<tr>
              <td colspan="2" align="center"><input type="submit" value="<?php echo $dil['DUZENLE']; ?>" class="input" /></td>
            </tr>
            <tr>
              <td height="25" colspan="2"></td>
            </tr>
            <tr>
              <td align="center" colspan="2"><a href="yazi_yonet.php?islem=3&yazilar_<?php echo $yazi_no; ?>=<?php echo $yazi_no; ?>&islemsil=1" onclick="return confirm('<?php echo $dil['SilmekIstiyormusunuz']; ?>');"><font color="#008000"><?php echo $dil['Sil']; ?></font></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<?php echo $onay; ?><font color="#ff6600">&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<font color="#3366ff"><?php echo $dil['Okunma']; ?> :</font>&nbsp;<?php echo $yazi_okunma; ?>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<font color="#3366ff"><?php echo $dil['Yazan']; ?> :</font>&nbsp;<?php echo $fonk->uye_adi($yazi_yazar); ?>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<font color="#3366ff"><?php echo $dil['Tarih']; ?> :</font>&nbsp;<?php echo $fonk->duzgun_tarih_saat($yazi_tarih,true); 
					    unset($yazi_veri,$yazi_baslik,$yazi_yazar,$yazi_icerik,$yazi_tarih,$yazi_okunma,$yazi_resim);
				      ?></font>
				    </td>
          </tr>
				</table>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="right" style="padding-right:25px"><br /><a href="yazi_yonet.php"><b><?php echo $dil['TumYazilar']; ?></b></a></td>
    </tr>
  </table>
    </form>
    <br />
    <?php
		$vt3 = new Baglanti();
    $vt3->query("SELECT k.yorumno,k.uyeno,k.baslik,k.yorum,k.uyeno,k.tarih,k.onay FROM ".TABLO_ONEKI."yazilar AS y, ".TABLO_ONEKI."yorumlar AS k WHERE k.yazino=$yazi_no AND k.yazino=y.yazino ORDER BY k.tarih DESC");

    $yorum_sayi   = $vt3->numRows();
		?>
    <table align="center" cellpadding="0" cellspacing="0" width="80%" bgcolor="#837eff">
      <tr bgcolor="#a0aff1">
        <td align="center" valign="center" colspan="2" height="20"><span class="baslikm"><?php echo $dil['YORUMLAR']; ?> (<?php echo $yorum_sayi; ?>)</span></td>
      </tr>
      <tr>
        <td align="center">
				  <table width="100%">
					<?php
          if ($yorum_sayi > 0)
          {
					  for ($y=0; $y<$yorum_sayi; $y++)
						{
              $yorum_veri   = $vt3->fetchObject();
							$yorum_no     = $yorum_veri->yorumno;
						  $yorum_baslik = $yorum_veri->baslik;
						  $yorum_mesaj  = $yorum_veri->yorum;
						  $yorum_yazan  = $yorum_veri->uyeno;
							$yorum_tarih  = $yorum_veri->tarih;
							$yorum_onay   = $yorum_veri->onay;
							if ($yorum_onay == 'E')
							{
							  $onaydurum = 'Onaylı';
							  $yonay = '<a href="yazi_yonet.php?islem=5&yazino='.$yazi_no.'&yorumno='.$yorum_no.'"><font color="#008000">'.$dil['OnayiKaldir'].'</font></a>';
							} else {
							  $onaydurum = 'Onaysız';
							  $yonay = '<a href="yazi_yonet.php?islem=6&yazino='.$yazi_no.'&yorumno='.$yorum_no.'"><font color="#ff0000">'.$dil['Onayla'].'</font></a>';
							}
						  ?>
					    <tr bgcolor="#f7f7fd">
						    <td align="left" style="border:1px solid #000000">
								  <form name="yorumkayit" action="yazi_yonet.php?islem=8" method="post">
									<input type="hidden" name="yazino" id="yazino" value="<?php echo $yazi_no; ?>" />
									<input type="hidden" name="yorumyazino" id="yorumyazino" value="<?php echo $yorum_no; ?>" />
								  <table width="100%">
									  <tr bgcolor="#e5e5f8">
										  <td width="100%" height="20" valign="bottom" colspan="2"><input type="input" name="yorumbaslik" id="yorumbaslik" value="<?php echo $yorum_baslik; ?>" size="40" class="input" /></td>
					          </tr>
						        <tr>
						          <td align="left"><font color="#000000"><textarea name="yorummesaj" cols="50" rows="4"><?php echo $yorum_mesaj; ?></textarea></font></td>
											<td align="left" valign="top"><?php echo $dil['OnayDurumu']; ?> : <font color="#ff6600"><?php echo $onaydurum; ?></font><br /><?php echo $yonay; ?><br /><a href="yazi_yonet.php?islem=10&yazino=<?php echo $yazi_no; ?>&yorumno=<?php echo $yorum_no; ?>" onclick="return confirm('<?php echo $dil['SilmekIstiyormusunuz']; ?>');"><font color="#ff0000"><?php echo $dil['Sil']; ?></font></a><br />
											<?php echo $dil['Yazan']; ?> : <font color="#ff6600"><?php echo $fonk->uye_adi($yorum_yazan); ?></font><br />
											<?php echo $dil['Tarih']; ?> : <font color="#ff6600"><?php echo $fonk->duzgun_tarih_saat($yorum_tarih,true); ?></font></td>
					          </tr>
										<tr>
										  <td align="center"><input type="submit" value="<?php echo $dil['DUZENLE']; ?>" class="input" /></td>
										  <td align="right">&nbsp;</td>
										</tr>
									</table>
									</form>
								</td>
							</tr>
						<?php
						}
					} else {
					  echo '<tr bgcolor="#e5e5f8"><td align="center" colspan="2" height="35"><span class="fonthata">'.$dil['YorumYapilmadi'].'</td></tr>';
					}
					?>
					</table>
				</td>
	    </tr>
		</table>
  <?php
  } else {
    echo "<script>alert('".$dil['KayitBulunamadi']."');location.href='yazi_yonet.php';</script>";
	}
	@$vt3->freeResult();
	unset($yazi_sayi,$yno);

} elseif ($islem == 3) {
 /* ============================================== */
/* YAZI ONAYLAMA, SİLME, ONAY KALDIRMA İŞLEMLERİ
/* ============================================== */
/* YAZI ISLEMLERI */
  @ $islemsil        = $_REQUEST['islemsil'];
  @ $islemonay       = $_REQUEST['islemonay'];
  @ $islemonaykaldir = $_REQUEST['islemonaykaldir'];
  @ $form_bilgisi    = $_REQUEST;
  
  if ($islemsil)
  {
    //SILME ISLEMLERI
    foreach ( $form_bilgisi as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
        if ($anahtar == "yazilar_$deger")
        {
          $yyvt->query2("DELETE FROM ".TABLO_ONEKI."yazilar WHERE yazino=$deger");
					$yyvt->query2("DELETE FROM ".TABLO_ONEKI."yorumlar WHERE yazino=$deger");
					$resim_dizi = glob('../'.RESIM_DIZIN.'/'."r_".$deger."*");
					for ($i=0; $i<count($resim_dizi); $i++)
					{
					  unlink($resim_dizi[$i]);
					}
        }
      }
    }
		 echo "<script>alert('".$dil['YazilarSilindi']."');location.href='yazi_yonet.php';</script>";
  } elseif ($islemonay) {
    
    //ONAYLAMA ISLEMLERI
    foreach ( $form_bilgisi as $anahtar=>$deger ) 
    {
		 
      if ( gettype ($deger ) != "array" ) 
      {
        if ($anahtar == "yazilar_$deger")
        {
          if (!$yyvt->query("UPDATE ".TABLO_ONEKI."yazilar SET onay='E' WHERE yazino=$deger"))
					{
					  echo $dil['OnaylamaIslemiBasarisiz'];
						exit;
					}
        }
      }
    }
    echo "<script>alert('".$dil['YazilarOnaylandi']."');location.href='?sayfa=2';</script>";
  } elseif ($islemonaykaldir) {
    //ONAY KALDIRMA ISLEMLERI
		
    foreach ( $form_bilgisi as $anahtar=>$deger ) 
    {
      if ( gettype ($deger ) != "array" ) 
      {
        if ($anahtar == "yazilar_$deger")
        {
          if (!$yyvt->query("UPDATE ".TABLO_ONEKI."yazilar SET onay='H' WHERE yazino=$deger"))
					{
					  echo $dil['IslemBasarisiz'];
					  exit;
					}
        }
      }
    }
    echo "<script>alert('".$dil['OnayKaldirildi']."');location.href='?sayfa=2';</script>";
  }
} elseif ($islem == 5) {
  //YORUM ONAYI KALDIRMA BÖLÜMÜ
	$yorumno = intval($_GET['yorumno']);
	$yazino  = intval($_GET['yazino']);
  $yyvt->query("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE yorumno=$yorumno");
	$yorumno_tamam = $yyvt->numRows();
	if ($yorumno_tamam > 0)
	{
	  $yazionay = $yyvt->query("UPDATE ".TABLO_ONEKI."yorumlar SET onay='H' WHERE yorumno=$yorumno");
		if ($yazionay)
		{
		 echo '<script>alert(\''.$dil['YorumOnayiKaldirildi'].'\');location.href=\'yazi_yonet.php?islem=2&yno='.$yazino.'\';</script>';
		} else {
		  echo '<script>alert(\''.$dil['IslemBasarisiz'].'\');location.href=\'yazi_yonet.php?islem=2&yno='.$yazino.'\';</script>';
		}
	} else {
	  echo "<script>alert('".$dil['IslemBasarisiz']."');location.href='yazi_yonet.php?islem=2&yno=".$yazino."';</script>";
	}
} elseif ($islem == 6) {
  //YORUM ONAYLAMA BÖLÜMÜ
	$yorumno = intval($_GET['yorumno']);
	$yazino  = intval($_GET['yazino']);
  $yyvt->query("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE yorumno=$yorumno");
	$yorumno_tamam = $yyvt->numRows();
	if ($yorumno_tamam > 0)
	{
	  $yazionay = $yyvt->query2("UPDATE ".TABLO_ONEKI."yorumlar SET onay='E' WHERE yorumno=$yorumno");
		if ($yazionay)
		{
		  //YORUM ONAYLANDIKTAN SONRA İLİŞKİLİ KİŞİLERE E-POSTA BİLGİSİ GÖNDERİLİYOR
		  $yyvt->query("SELECT uyeno FROM ".TABLO_ONEKI."yorumlar WHERE yorumno=$yorumno");
			$yorum_uyeno_bilgi = $yyvt->fetchObject();
			$yorum_uyeno       = $yorum_uyeno_bilgi->uyeno;
		  $fonk->yorum_eposta_bilgi($yazino,$yorum_uyeno);
			
			//YORUM ONAY MESAJI EKRANA BASTIRILIYOR
		  echo "<script>alert('".$dil['YorumOnaylandi']."');location.href='yazi_yonet.php?islem=2&yno=".$yazino."';</script>";
		} else {
		  echo "<script>alert('".$dil['IslemBasarisiz']."');location.href='yazi_yonet.php?islem=2&yno=".$yazino."';</script>";
		}
	} else {
	  echo "<script>alert('".$dil['IslemBasarisiz']."');location.href='yazi_yonet.php?islem=2&yno=".$yazino."';</script>";
	}

} elseif ($islem == 7) {
  ///////////////////////////////////
  // YAZI DUZENLEME / KAYIT ////////
	//////////////////////////////////
	
  @ $yazino    = intval($_POST['yazino']);
	@ $kategori  = intval($_POST['kategori']);

	try 
  {
    
    // Veriler Kontrol Ediliyor
    @ $baslik    = $fonk->post_duzen($_POST['baslik']);
    @ $yazi      = $fonk->post_duzen($_POST['metin']);
		@ $resim     = trim(strip_tags(htmlspecialchars($_FILES['resim']['name'])));
		@ $kategorino = intval($_POST['kategorino']);



    if (!$yazi || !$baslik)
    {
      throw new Exception($dil['IsaretliAlanlariBosBirakmayiniz']);
			exit;
		} elseif (KATEGORI_SAYI > 0) {
		  if (!$kategorino)
			{
			  throw new Exception($dil['IsaretliAlanlariBosBırakmayiniz']);
				exit;
			}
			if($yyvt->kayitSay("SELECT COUNT(kategorino) FROM ".TABLO_ONEKI."yazikategori WHERE kategorino=$kategorino") == 0)
			{
			  throw new Exception($dil['KategoriGecersiz']);
				exit;
			}
    } elseif (strlen($baslik) > 100) {
      throw new Exception($fonk->yerine_koy($dil['BaslikKarakterIzin'],100));
      exit;
			
    } elseif (strlen($yazi) > YAZI_KARAKTER) {
      throw new Exception($fonk->yerine_koy($dil['YaziKarakterIzin'],YAZI_KARAKTER));
			exit;
			
    } elseif ($resim != '') {
		  if (!$fonk->resim_adi_kontrol($resim))
			{
			  throw new Exception($dil['ResimAdiGecersiz']);
				exit;
			}
		}
      $resim_mesaji = '';
     if (KATEGORI_SAYI == 0)
		 {
		   $kategori=0;
		 }
      
			if ($yazino)
			{
        //RESIM EKLEME KONTROLLERI
        //Resim Ekleme Hatalari
        if ($resim != '')
				{
				  if ($_FILES['resim']['error'] > 0)
          {
            switch ($_FILES['resim']['error'])
           {
             case 1: throw new Exception($dil['ResimYuklemeBasarisiz']);  break;
             case 2: throw new Exception($dil['ResimYuklemeBasarisiz']); break;
             case 3: throw new Exception($dil['ResimYuklemeBasarisiz']); break;
             case 4: throw new Exception($dil['ResimYuklemeBasarisiz']); break;
           }
         }
	
         //Resim MIME tipi kontrolu
         if (!array_key_exists($_FILES['resim']['type'],$yazi_resim_uzanti))
         {
           throw new Exception($_FILES['resim']['type'].$dil['ResimUzantisiGecersiz']);
           exit;
         }

         if ($_FILES['resim']['size'] > BOYUT_IZIN)
         {
           throw new Exception($dil['ResimBoyutuBuyuk']);
           exit;
         } 
  
         $uzanti       = '';

         //RESİM YUKLENIYOR
         $uzanti = strtr($_FILES['resim']['type'],$yazi_resim_uzanti);
         if(!is_dir('../'.RESIM_DIZIN))
         {
           throw new Exception($dil['KlasorBulunamadi']);
           exit;
         }

         $resim_dizin  = '../'.RESIM_DIZIN.'/r_'.$yazino.'.'.$uzanti;
		  
         if (is_uploaded_file($_FILES['resim']['tmp_name']))
         {
           if (!move_uploaded_file($_FILES['resim']['tmp_name'], $resim_dizin))
           {
             $resim_mesaji = $dil['ResimYuklemeBasarisiz'];
           } else {
				     /*$icerik = boyutlandir($resim_dizin,$max_en,$max_boy); 
             $dosya  = fopen ($resim_dizin,"w+"); 
             fwrite($dosya,$icerik); 
             fclose($dosya); 
					   */
             
						 //Eski Resmin Adi Aliniyor
		         $yyvt->query("SELECT resim FROM ".TABLO_ONEKI."yazilar WHERE yazino=$yazino");
		         $eski = $yyvt->fetchObject();
		         $eski_resim = $eski->resim;
		         $yeni_resim = 'r_'.$yazino.'.'.$uzanti;

		         if ($yyvt->query2("UPDATE ".TABLO_ONEKI."yazilar SET resim='$yeni_resim' WHERE yazino=$yazino"))
		         {
		           if ($eski_resim !== $yeni_resim)
			         {
			           $sil = '../'.RESIM_DIZIN.'/'.$eski_resim;
			           @unlink($sil);
			         }
             }
						 $resim_mesaji = $dil['ResimYuklendi'];
          }
        } else {
				  unlink($resim_dizin);
          $resim_mesaji = $dil['ResimYuklemeBasarisiz'];
        }
      }

      //Yazi Guncelleniyor
      if (UYE_SEVIYE > 5)
      {
        $duzenleme_kosul = "yazino=$yazino";
      } else {
        $duzenleme_kosul = "uyeno=".UYE_NO." AND yazino=$yazino";
      }
      $yazino_no = $yyvt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE $duzenleme_kosul");

      if ($yazino_no == 0)
      {
        throw new Exception($dil['IslemGecersiz']);
        exit;
      }
		
      $yyvt->query2("UPDATE ".TABLO_ONEKI."yazilar SET kategorino=$kategorino,baslik='$baslik', yazi='$yazi', duzenlemetarihi=NOW() WHERE $duzenleme_kosul");
			echo "<script>alert(\"".$dil['DuzenlemeIslemiTamamlandi']." - ".$resim_mesaji."\");location.href='yazi_yonet.php?islem=2&yno=".$yazino."';</script>";
		}
	}
	catch (Exception $e)
  {
     echo "<script>alert('".$e->getMessage()."');location.href='yazi_yonet.php?islem=2&yno=".$yazino."';</script>";
  }
} elseif ($islem == 8) {
  try 
  {
		@ $yorumyazino = intval($_POST['yorumyazino']);
		@ $yazino = intval($_POST['yazino']);
    @ $yorumbaslik = $fonk->post_duzen($_POST['yorumbaslik']);
    @ $yorummesaj  = $fonk->post_duzen($_POST['yorummesaj']);

	  if (empty($yorummesaj))
	  {
      throw new Exception($dil['YorumuBosBirakmayin']);
      exit;
    }
    if (strlen($yorumbaslik) > 100)
    {
      throw new Exception($fonk->yerine_koy($dil['BaslikKarakterIzin'],100));
      exit;
    }
    if (strlen($yorummesaj) > YORUM_KARAKTER)
    {
      throw new Exception($fonk->yerine_koy($dil['YaziKarakterIzin'],YORUM_KARAKTER));
      exit;
    }
			
    if ($yyvt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE yazino=$yorumyazino") == 0)
    {
      throw new Exception($dil['IslemGecersiz']);
      exit;
    }

    if ($yyvt->query2("UPDATE ".TABLO_ONEKI."yorumlar SET baslik='$yorumbaslik',yorum='$yorummesaj' WHERE yorumno=$yorumyazino"))
    {
      echo "<script>alert('Güncelleme İşlemi Yapıldı');location.href='yazi_yonet.php?islem=2&yno=".$yazino."';</script>";
    } else {
      throw new Exception($dil['IslemBasarisiz']);
       exit;
    }
  }
  catch (Exception $e)
  {
    echo "<script>alert('".$e->getMessage()."');location.href='yazi_yonet.php?islem=2&yno=".$yorumyazino."';</script>";
  }
} elseif ($islem == 9) {
  try 
  {
		@ $yazino     = intval($_GET['yazino']);
		@ $kategorino = intval($_GET['kategorino']);
		@ $y          = intval($_GET['y']);
		if ($yyvt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE yazino=$yazino")==0)
		{
		  throw new Exception($dil['IslemGecersiz']);
			exit;
		}
		if ($yyvt->kayitSay("SELECT COUNT(kategorino) FROM ".TABLO_ONEKI."yazikategori WHERE kategorino=$kategorino")==0)
		{
		  throw new Exception($dil['IslemGecersiz']);
			exit;
		}
    if ($yyvt->query2("UPDATE ".TABLO_ONEKI."yazilar SET kategorino=$kategorino WHERE yazino=$yazino"))
		{
		  throw new Exception($dil['YaziYeniKategoriyeTasindi']);
			exit;
		} else {
		  throw new Exception($dil['KategoriDegistirmeBasarisiz']);
		}
	}
	catch (Exception $e)
  {
    echo "<script>alert('".$e->getMessage()."');location.href='yazi_yonet.php?y=".$y."';</script>";
  }
} elseif ($islem == 10) {
  //YORUM ONAYI KALDIRMA BÖLÜMÜ
	$yorumno = intval($_GET['yorumno']);
	$yazino  = intval($_GET['yazino']);

  $yyvt->query("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE yorumno=$yorumno");
	$yorumno_tamam = $yyvt->numRows();
	if ($yorumno_tamam > 0)
	{
	  $yazionay = $yyvt->query("DELETE FROM ".TABLO_ONEKI."yorumlar WHERE yorumno=$yorumno");
		if ($yazionay)
		{
		 echo '<script>alert(\''.$dil['SilmeIslemiTamamlandi'].'\');location.href=\'yazi_yonet.php?islem=2&yno='.$yazino.'\';</script>';
		} else {
		  echo '<script>alert(\''.$dil['IslemBasarisiz'].'\');location.href=\'yazi_yonet.php?islem=2&yno='.$yazino.'\';</script>';
		}
	} else {
	  echo "<script>alert('".$dil['IslemBasarisiz']."');location.href='yazi_yonet.php?islem=2&yno=".$yazino."';</script>";
	}
} 
unset($yyvt);
?>
