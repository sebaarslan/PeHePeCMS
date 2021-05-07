<?php
ob_start();
session_start();
require_once ("../icerik/vt.inc.php");
require_once ("../icerik/ayar.inc.php");
require_once ("../icerik/dil.inc.php");
@ dil_belirle('','yonetimdil');
require_once ("../icerik/sev.inc.php");
require_once ("../icerik/fonk.inc.php");


//Yönetici Girişi Yapılmamışsa Yasakla
if (UYE_SEVIYE < 5) 
{
  header('Location: ../index.php');
  exit;
}
$fonk         = new Fonksiyon();
@ $islem      = intval($_GET['islem']);
@ $sayfa      = intval($_GET['sayfa']);
@ $kategorino = intval($_GET['kategorino']);
$ksk           = YAZI_KATEGORI_SIRA; //Kategori Siralama Kriteri Aliniyor
$kategori_mesaj = '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo SITE_ADI; ?> : <?php echo $dil['YonetimPaneli']; ?></title>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="ystil.css" />
</head>
<?php

//Kategori Numarasina Ait Kategorinin Olup Olmadigini Kontrol Eder
function kategorivar($kategorino)
{
  global $vt;
	return $vt->kayitSay("SELECT COUNT(kategorino) FROM ".TABLO_ONEKI."yazikategori WHERE kategorino=$kategorino");
}
function kategori_ust_sira()
{
  global $vt;
  if ($vt->query("SELECT MAX(kategorisira) AS ustsira FROM ".TABLO_ONEKI."yazikategori WHERE altkategorino=0"))
  {
    $kategoriustsira = $vt->fetchObject();
    return intval($kategoriustsira->ustsira);
	} else {
	  return false;
	}
}
$hata_kod = false;
if ($islem == 1)
{
  try
	{
    // KATEGORI EKLEME
    $kategorino        = intval($_POST['kategorino']);
    $kategoriadi       = $fonk->post_duzen($_POST['kategoriadi']);
		$kategoriaciklama  = $fonk->post_duzen($_POST['kategoriaciklama']);
    $kategori_ust_sira = kategori_ust_sira();
		$kategorisira      = intval($kategori_ust_sira+1);
	  if ($kategoriadi)
    {
	    if ($vt->kayitSay("SELECT COUNT(kategorino) FROM ".TABLO_ONEKI."yazikategori WHERE TRIM(kategoriadi)='$kategoriadi'") > 0)
		  {
		    throw new Exception($dil['KategoriKayitli']);
		  } else {
        if ($vt->query("INSERT INTO ".TABLO_ONEKI."yazikategori (`kategoriadi`,`altkategorino`,`kategoriaciklama`,`kategorisira`) VALUES ('$kategoriadi',$kategorino,'$kategoriaciklama',$kategorisira)"))
        {
				  $kategorino = $vt->insertId();
          header('Location: kategori_yonet.php?sayfa=2&kategorino='.$kategorino.'');
          throw new Exception($dil['KayitIslemiTamamlandi']);
        } else {
          throw new Exception($dil['IslemBasarisiz']);
        }
		  }
    } else {
      throw new Exception($dil['BosAlanBirakmayiniz']);
    }
  }
  catch (Exception $e)
  {
    $kategori_mesaj = $e->getMessage();
  }
//=================================================================================//
}  elseif ($islem == 2) {
  // KATEGORİ DÜZENLEME KAYDET
  $kategori_no  = intval($_POST['kategorino']);
	$kategoriadi = $fonk->post_duzen($_POST['kategoriadi']);
	$kategoriaciklama = $fonk->post_duzen($_POST['kategoriaciklama']);
  try 
	{
    if (empty($kategoriadi))
		{
		  throw new Exception($dil['BosAlanBirakmayiniz']);
		}
		if ($kategori_no>0 && kategorivar($kategori_no) == 0)
	  {
	    throw new Exception($dil['IslemBasarisiz']);
	  } else {
	    //Kategori Düzenleniyor
			$vt->query2("UPDATE ".TABLO_ONEKI."yazikategori SET altkategorino=$kategori_no, kategoriadi='$kategoriadi',kategoriaciklama='$kategoriaciklama' WHERE kategorino=$kategorino");


			throw new Exception('<font color="#008000">'.$dil['DuzenlemeIslemiTamamlandi'].'</font>');
		}
  } 
 
	catch (Exception $e)
  {
    $kategori_mesaj = $e->getMessage();
  }
//================================================================================
//KATEGORI DUZENLEME SONU
//================================================================================
} elseif ($islem == 3) {
  // KATEGORI SILME

  try 
	{
	  $sil_vt  = new Baglanti();
		$yazi_vt = new Baglanti();
	  @ $sil     = $fonk->post_duzen($_GET['sil']);
	  @ $sil_kod = $_SESSION['sil_kod'];
		if (kategorivar($kategorino) == 0 || $sil_kod != $sil || !$sil || !$sil_kod)
	  {
	    throw new Exception($dil['IslemBasarisiz']);
	  } else {
		  unset($_SESSION['sil_kod']);
      
				
				//Silinen Ana Kategorinin Sirasi Aliniyor
					$ks_vt = new Baglanti();
					$ks_vt->query("SELECT kategorisira FROM ".TABLO_ONEKI."yazikategori WHERE kategorino=$kategorino AND altkategorino=0");
					if ($ks_vt->numRows()>0)
					{
					  $ks_veri = $ks_vt->fetchObject();
						$kategori_sira = $ks_veri->kategorisira;
					}
					
					$kategorisil_dizi = $fonk->kategoriIdListe($kategorino);
				$kategorisil_dizi[] = $kategorino;
		    if ($sil_vt->query("DELETE FROM ".TABLO_ONEKI."yazikategori WHERE kategorino IN (".implode(',',$kategorisil_dizi).")"))
			  {
					//Once Yorumlar Siliniyor
				  $sil_vt->query("DELETE FROM ".TABLO_ONEKI."yorumlar WHERE yazino IN (SELECT yazino FROM ".TABLO_ONEKI."yazilar WHERE kategorino IN (".implode(',',$kategorisil_dizi)."))");
					//Yorumlar Silindi
					//Yazi Numaralari Aliniyor
					$yazi_vt->query("SELECT yazino FROM ".TABLO_ONEKI."yazilar WHERE kategorino IN (".implode(',',$kategorisil_dizi).")");
					while ($yazi_veri = $yazi_vt->fetchObject())
					{
					  $yazino = $yazi_veri->yazino;
					  //Resimler Siliniyor
					  $resim_dizi = glob('../'.RESIM_DIZIN.'/'."r_".$yazino."*");
					  for ($i=0; $i<count($resim_dizi); $i++)
					  @unlink($resim_dizi[$i]);
						//Resimler Silindi
          }
					//Yazilar Siliniyor
					$sil_vt->query2("DELETE FROM ".TABLO_ONEKI."yazilar WHERE kategorino IN (".implode(',',$kategorisil_dizi).")");
					//Yazilar Silindi
					
					//Kategori Siliniyor
					
					$sil_vt->query2("DELETE FROM ".TABLO_ONEKI."yazikategori WHERE kategorino=$kategorino");

					//Kategori Sirasi Degistiriliyor
					if ($kategori_sira>0)
				  $sil_vt->query2("UPDATE ".TABLO_ONEKI."yazikategori SET kategorisira=kategorisira-1 WHERE kategorisira > ".$kategori_sira." AND altkategorino=0");

					unset($sil_vt,$yazi_vt,$ks_vt);
					$hata_kod = true;
			    throw new Exception($dil['SilmeIslemiTamamlandi']);
			  } else {
			    throw new Exception($dil['IslemBasarisiz']);
			  }
			}
		
	}
	catch (Exception $e)
  {
    $kategori_mesaj = $e->getMessage();
  }
} elseif ($islem == 4) {
  // KATEGORI SIRALAMA KRITERININ KAYDEDILMESI
	$ksk = intval($_GET['ksk']);
	if ($ksk==1)
	$ksk = 1;
	elseif ($ksk==2)
	$ksk = 2;
	elseif ($ksk==3)
	$ksk = 3;
	else
	$ksk = 1;
  try 
	{
	  $svt = new Baglanti();
		if ($svt->query("UPDATE ".TABLO_ONEKI."yonetim SET yazikategorisira=$ksk WHERE 1"))
		{
		  $hata_kod = true;
			throw new Exception($dil['IslemTamamlandi']);
		} else {
		  throw new Exception($dil['IslemBasarisiz']);
		}
  }
	catch (Exception $e)
  {
	  unset($svt);
    $kategori_mesaj = $e->getMessage();
  }
} elseif ($islem == 5) {
  //KATEGORI SIRA DEGISTIRME
	$yon = intval($_GET['yon']);

  try 
	{
	  $svt = new Baglanti();
		//Kategori Sirasi Aliniyor
		$svt->query("SELECT kategorisira FROM ".TABLO_ONEKI."yazikategori WHERE kategorino=$kategorino");
		$kategori_sira_veri = $svt->fetchObject();
		$kategori_sira = $kategori_sira_veri->kategorisira;
		$kategori_ustsayi = kategori_ust_sira();

		$islem_tamam = 0;
		
		if ($yon==1 && $kategori_sira>1)
		{
		  $islem_tamam++;
			$yeni_kategori_sira = $kategori_sira-1;
		} elseif ($yon==2 && $kategori_sira<$kategori_ustsayi) {
		  $islem_tamam++;
		  $yeni_kategori_sira = $kategori_sira+1;
    } else { 
		  $islem_tamam = 0;
		}
		
		if ($islem_tamam>0)
		{
		  //Yeni Kategori Sirasi Eski Kategoriye Ekleniyor
		  if ($svt->query2("UPDATE ".TABLO_ONEKI."yazikategori SET kategorisira=$kategori_sira WHERE kategorisira=$yeni_kategori_sira"))
		  //Kategori Sirasi Degistiriliyor
		  if ($svt->query2("UPDATE ".TABLO_ONEKI."yazikategori SET kategorisira=$yeni_kategori_sira WHERE kategorino=$kategorino"))
		  $islem_tamam++;
		
		  
		  $islem_tamam++;
    }
		if ($islem_tamam>2)
		{
		  $hata_kod = true;
		  throw new Exception($dil['IslemTamamlandi']);
		} else {
		  $hata_kod = false;
			throw new Exception($dil['IslemBasarisiz']);
		}

  }
	catch (Exception $e)
  {
    $kategori_mesaj = $e->getMessage();
  }
}

?>


<body background="yonetimresim/bg.gif">
<div align="center">
<table border="1" align="center" cellpadding="0" cellspacing="0" width="80%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><a href="kategori_yonet.php"><font color="#ffffff"><b><?php echo $dil['KATEGORI_YONETIMI']; ?></b></font></a></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
		    
				<?php
				if ($kategori_mesaj)
				{
				  echo '<tr>
            <td colspan="2" align="center" valign="center" height="30">';
						if ($hata_kod)
						echo '<font color="#008000">'.$kategori_mesaj.'</font>';
						else
						echo '<font color="#ff0000">'.$kategori_mesaj.'</font>';
						echo '</td>
          </tr>';
				}
				
				if (empty($sayfa))
				{
				?> 
				<form name="kategori" action="kategori_yonet.php?islem=1" method="post" onsubmit="return kategori()">
				
				<tr bgcolor="#b6c5f2">
		      <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['KATEGORI_EKLEME_BOLUMU']; ?></b></td>
		    </tr>
				<tr bgcolor="#f7f7fd">
		      <td width="100%" height="25" align="center" colspan="2"><?php echo $fonk->yerine_koy($dil['KategoriEkleAciklama'],'<b>'.$dil['ANA_KATEGORI'].'</b>'); ?></td>
				</tr>
				<tr bgcolor="#f7f7fd">
		      <td width="40%" height="25" align="right"><b><?php echo $dil['Kategori']; ?>&nbsp;:</b>&nbsp;&nbsp;</td>
				  <td width="60%" height="25" align="left">
          <?php
					echo '<select name="kategorino" id="kategorino" class="input">'; 
					echo '<option value="0">----- '.$dil['ANA_KATEGORI'].' -----</option>';
          $kategoriler_dizi = $fonk->kategoriListe(0,0);
          for ($k=0; $k<count($kategoriler_dizi); $k++) 
          { 
            $kategorino = $kategoriler_dizi[$k][0];
            $kategoriadi = $kategoriler_dizi[$k][1];
            echo '<option value="'.$kategorino.'">';
            $level = $kategoriler_dizi[$k][2];
            for ($j=0;$j<$level;$j++) echo '&nbsp;&nbsp;'; //Alt Kategorileri Iceri Kaydirma Bosluklari 
            echo $kategoriadi.'</option>';
          } 
          echo '</select>';
					?></td>
				</tr>
        <tr bgcolor="#f7f7fd">
		      <td width="40%" height="25" align="right"><b><?php echo $dil['KategoriAdi']; ?> :</b>&nbsp;&nbsp;</td>
		      <td width="60%" height="25" align="left"><input type="text" id="kategoriadi" name="kategoriadi" class="input" size="40" maxlength="100" /></td>
		    </tr>
				<tr bgcolor="#f7f7fd">
		      <td width="40%" height="25" align="right"><b><?php echo $dil['KategoriAciklama']; ?> :</b>&nbsp;&nbsp;</td>
		      <td width="60%" height="25" align="left"><input type="text" id="kategoriaciklama" name="kategoriaciklama" class="input" size="40" maxlength="200" /></td>
		    </tr>
				<tr>
		      <td colspan="2" align="center" valign="center" height="30"><input type="submit" value="<?php echo $dil['KATEGORI_EKLE']; ?>" class="input"/></td>
		    </tr>
				
				</form>
				<?php
				} elseif ($sayfa == 2) {
          $vt->query("SELECT `kategorino`,`altkategorino`,`kategoriadi`,`kategoriaciklama` FROM ".TABLO_ONEKI."yazikategori WHERE kategorino=$kategorino");
				  $kategorikayitli = $vt->numRows();

				  if ($kategorikayitli > 0)
				  {
				    $kategori_veri  = $vt->fetchObject();
				    $kategori_no    = $kategori_veri->kategorino;
						$altkategori_no = $kategori_veri->altkategorino;
				    $kategori_adi   = $kategori_veri->kategoriadi;
						$kategori_aciklama = $kategori_veri->kategoriaciklama;
				  ?>
				  <!-- KATEGORİ BİLGİLERİ DÜZENLEME -->
				  <tr bgcolor="#b6c5f2">
		        <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['KATEGORI_DUZENLE']; ?></b></td>
		      </tr>
				  <form name="kategori" action="kategori_yonet.php?sayfa=2&islem=2&kategorino=<?php echo $kategori_no; ?>" method="post" onsubmit="return kategori()">
					<tr bgcolor="#f7f7fd">
		      <td width="40%" height="25" align="right"><b><?php echo $dil['Kategori']; ?>&nbsp;:</b>&nbsp;&nbsp;</td>
				  <td width="60%" height="25" align="left">
          <?php
					echo '<select name="kategorino" id="kategorino" class="input">'; 
					echo '<option value="0">----- '.$dil['ANA_KATEGORI'].' -----</option>';
          $kategoriler_dizi = $fonk->kategoriListe(0,0);
          for ($k=0; $k<count($kategoriler_dizi); $k++) 
          { 
            $kategorino = $kategoriler_dizi[$k][0];
            $kategoriadi = $kategoriler_dizi[$k][1];
            echo '<option value="'.$kategorino.'"';
						if ($kategorino == $altkategori_no) echo ' selected="selected"'; echo '>';
            $level = $kategoriler_dizi[$k][2];
            for ($j=0;$j<$level;$j++) echo '&nbsp;&nbsp;'; //Alt Kategorileri Iceri Kaydirma Bosluklari 
            echo $kategoriadi.'</option>';
          } 
          echo '</select>';
					?></td>
				</tr>
          <tr>
		        <td align="right" valign="center"><b><?php echo $dil['KategoriAdi']; ?> :</b></td>
						<td align="left"><input type="text" name="kategoriadi" id="kategoriadi" value="<?php echo $kategori_adi; ?>" class="input" size="40" maxlength="100" /></td>
				  </tr>
           <tr>
		        <td align="right" valign="center"><b><?php echo $dil['KategoriAciklama']; ?> :</b></td>
						<td align="left"><input type="text" name="kategoriaciklama" id="kategoriaciklama" value="<?php echo $kategori_aciklama; ?>" class="input" size="50" maxlength="200" /></td>
				  </tr>
					
				  <tr>
		        <td colspan="2" align="center" valign="center" height="30"><input type="submit" value="<?php echo $dil['DUZENLE']; ?>" class="input" tabindex="13" /><br /><a href="kategori_yonet.php"><?php echo $dil['YeniKategoriEkle']; ?></a></td>
		      </tr>
					</form>
				  <?php
				  } else {
				    echo '
				    <tr>
		          <td colspan="2" align="center" valign="center" height="30">'.$dil['KayitBulunamadi'].'</td>
		        </tr>';
			    }				
				} elseif ($sayfa == 4) {
				  $silinecekkategorino = intval($_GET['kategorino']);
					$sil_kod             = $fonk->kod(5);
					$_SESSION['sil_kod'] = $sil_kod;
				?>
				<tr>
		      <td colspan="2" align="center" height="20">
					<font color="#ff0000">
					<?php
					$ys_vt = new Baglanti();
					$altkategoridizi = $fonk->kategoriIdListe($silinecekkategorino);
					$altkategorisayi = count($altkategoridizi); 
					if ($altkategorisayi>0)
					$sil_kosul = " OR kategorino IN (".implode(',',$altkategoridizi).")";
					@ $yazi_sayi = $ys_vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE kategorino=$silinecekkategorino ".$sil_kosul.""); 
					unset($ys_vt);
					echo $fonk->yerine_koy($dil['KategoriAltKategoriYaziSilinsinmi'],array($fonk->kategoriAdi($silinecekkategorino),intval($altkategorisayi),intval($yazi_sayi))); ?>
					</font>
					</td>
				</tr>
				<tr>
					<td align="center"><a href="kategori_yonet.php?islem=3&kategorino=<?php echo $silinecekkategorino; ?>&sil=<?php echo $sil_kod; ?>" onclick="return confirm('<?php echo $dil['SilmekIstiyormusunuz']; ?>');"><?php echo $dil['Sil']; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;<a href="kategori_yonet.php"><?php echo $dil['Iptal']; ?></td>
		    </tr>
				<?php 
				} //Sayfa 4 Sonu
				?>
				<!-- KATEGORI BİLGİLERİ DÜZENLEME SONU -->
		    <tr bgcolor="#b6c5f2">
		      <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['EKLENEN_KATEGORILER']; ?></b></td>
		    </tr>
				<tr bgcolor="#ffffff">
		      <td width="100%" height="25" align="center" colspan="2">
					  <table width="100%" bgcolor="#ffffff">
						  <tr>
							  <td colspan="4" align="left">
								<?php
								$kategoriler = $vt->query("SELECT `kategorino`,`kategoriadi` FROM ".TABLO_ONEKI."yazikategori ORDER BY kategoriadi ASC");
							  $kategorisayi = $vt->numRows();
								echo $dil['KATEGORI'].' : <b>'.$kategorisayi.'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
								?>
								<input type="radio" name="sira" value="1" onclick="location.href='kategori_yonet.php?ksk=1&islem=4'"<?php if ($ksk==1) echo ' checked="checked"'; ?> />&nbsp;<?php echo $dil['HarfeGoreSirala']; ?>
								<input type="radio" name="sira" value="2" onclick="location.href='kategori_yonet.php?ksk=2&islem=4'"<?php if ($ksk==2) echo ' checked="checked"'; ?> />&nbsp;<?php echo $dil['EklemeTarihineGoreSirala']; ?>
								<input type="radio" name="sira" value="3" onclick="location.href='kategori_yonet.php?ksk=3&islem=4'"<?php if ($ksk==3) echo ' checked="checked"'; ?> />&nbsp;<?php echo $dil['KullaniciTanimliSirala']; ?>
								</td>
							</tr>
						  <tr bgcolor="#b6c5f2">
							  <td align="center"><b><?php echo $dil['KATEGORI_NO']; ?></b></td>
								<td align="center"><b><?php echo $dil['KATEGORI_ADI']; ?></b></td>
								<?php
								if ($ksk==3)
								echo '<td align="center"><b>'.$dil['KATEGORI_SIRA'].'</b></td>';
								?>
								<td align="center"><b><?php echo $dil['KATEGORI_YAZI_SAYISI']; ?></b></td>
								<td align="center"><b><?php echo $dil['Sil']; ?></b></td>
							</tr>
				      <?php
              //Kategori Siralari
							$vt->query("SELECT kategorino,kategorisira FROM ".TABLO_ONEKI."yazikategori WHERE altkategorino=0");
							$sira_dizi = array();
							while ($sira_veri = $vt->fetchObject())
							{
							  $sira_dizi[$sira_veri->kategorino] = $sira_veri->kategorisira;
							}
							if ($kategorisayi > 0)
							{
                $kategorilistedizi = $fonk->kategoriListe(0,0,$ksk);
								for ($i=0; $i<count($kategorilistedizi); $i++) 
                { 
							    $kategorino   = $kategorilistedizi[$i][0]; 
									$kategoriadi = $kategorilistedizi[$i][1];
									
									$yazisayi = $vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE kategorino=$kategorino");
									if (empty($yazisayi))
									{
									  $yazisayi = $dil['KategorideYaziYok'];
									}
				          echo '
		              <tr>
							      <td align="center">'.$kategorino.'</td>
		                <td align="left"><a href="kategori_yonet.php?sayfa=2&kategorino='.$kategorino.'">';
										for ($j=0;$j<$kategorilistedizi[$i][2];$j++) echo '&nbsp;&nbsp;';
										echo $kategoriadi.'</a></td>';
										if ($ksk==3)
										{
										  $kategori_sira = $sira_dizi[$kategorino];
											$kategori_ust_sira = kategori_ust_sira();
											echo '<td align="center">';
											if ($kategori_sira>0)
											{
											  
											  if ($kategori_sira>1) 
											  echo '<a href="kategori_yonet.php?islem=5&kategorino='.$kategorino.'&yon=1">'.$dil['Yukari'].'</a>&nbsp;&nbsp;';
																			
											  echo '<b>'.$kategori_sira.'</b>';

											  if ($kategori_sira<$kategori_ust_sira)
											  echo '&nbsp;&nbsp;<a href="kategori_yonet.php?islem=5&kategorino='.$kategorino.'&yon=2">'.$dil['Asagi'].'</a>';

											} else {
											  echo '---';
											}
                      echo '</td>';
										}
										echo '
								    <td align="center">'.$yazisayi.'</td>
										<td align="center"><a href="kategori_yonet.php?sayfa=4&kategorino='.$kategorino.'">'.$dil['Sil'].'</a></td>
							    </tr>';
							  }
							} else {
							  echo '<tr><td width="100%" colspan="5" align="center"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td></tr>';
							}
							
							?>
						</table>
					</td>
		    </tr>
	    </table>
	  </td>
  </tr>
</table>
</div>