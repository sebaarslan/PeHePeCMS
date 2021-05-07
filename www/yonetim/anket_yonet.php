<?php
ob_start();
session_start();
require_once ("../icerik/vt.inc.php");
require_once ("../icerik/ayar.inc.php");
require_once ("../icerik/sev.inc.php");
require_once ("../icerik/dil.inc.php");
//SITE DILI AYARLANIYOR
dil_belirle('','yonetimdil');

require_once ("../icerik/fonk.inc.php");

//Yönetici Girişi Yapılmamışsa Yasakla
if (UYE_SEVIYE < 5) 
{
  header('Location: ../index.php');
  exit;
}
$fonk = new Fonksiyon();

@ $islem = intval($_GET['islem']);
@ $sayfa = intval($_GET['sayfa']);
@ $anketno = intval($_GET['anketno']);


$anket_mesaj = '';
//Anket Numarasina Ait Anketin Olup Olmadigini Kontrol Eder
function anketvar($anketno)
{
  global $vt;
	return $vt->kayitSay("SELECT COUNT(anketno) FROM ".TABLO_ONEKI."anketsoru WHERE anketno=$anketno");
}

if ($islem == 2)
{
  // ANKET SORU EKLEME //
  $anketsorusu = $fonk->post_duzen($_POST['anketsoru']);
	@ $bitistarihi = trim($_POST['bitistarihi']);

	if (empty($anketsorusu) || empty($bitistarihi))
	{
	  $anket_mesaj = $dil['BosAlanBirakmayiniz']; 
	} elseif (!$fonk->tarihsaat_kontrol($bitistarihi)) {
	   $anket_mesaj = $dil['TarihFormatiYanlis'];
  } else {
	  $bitistarihi = $fonk->mysql_tarih_saat($bitistarihi,true);

	  if ($anketsorusu)
	  {
	    $anketsoru_ekle = $vt->query("INSERT INTO ".TABLO_ONEKI."anketsoru (`anketsoru`,`tarih`,`bitistarihi`) VALUES ('".$vt->escapeString($anketsorusu)."',NOW(),'$bitistarihi')");
      
		  if ($anketsoru_ekle)
		  {
			  $anketno = $vt->insertId();
			  header('Location: anket_yonet.php?sayfa=2&anketno='.$anketno.'');
        $anket_mesaj = $dil['AnketSorusuEklendiSecenekleriEkle'];

		  } else {
        $anket_mesaj = $dil['IslemBasarisiz'];
		  }
	  } else {
	    $anket_mesaj = $dil['BosAlanBirakmayiniz'];
	  }
	}
//=================================================================================//
} elseif ($islem == 3) {
  // ANKET SEÇENEK EKLEME //
  $anketnosu    = intval($_POST['anketno']);
	$anketsecenek = $fonk->post_duzen($_POST['anketsecenek']);
  
	if (anketvar($anketno) == 0)
	{
	  $anket_mesaj = $dil['IslemGecersiz'];
	} else {
	  if ($anketsecenek)
		{
      $secenek_ekle = $vt->query("INSERT INTO ".TABLO_ONEKI."anketsecenek (`anketno`,`secenek`) VALUES ($anketno,'".$vt->escapeString($anketsecenek)."')");
		  if ($secenek_ekle)
		  {
		    $anket_mesaj = $dil['SecenekEklendi'];
		  } else {
		    $anket_mesaj = $dil['IslemBasarisiz'];
		  }
		} else {
		  $anket_mesaj = $dil['SecenekBos'];
		}
	}
//=====================================================================================//
} elseif ($islem == 4) {
  // ANKET GÖSTERİMİNİ AÇMA/KAPAMA
	@ $goster = trim(htmlspecialchars($_GET['goster']));
	
  if (anketvar($anketno) == 0)
	{
	  $anket_mesaj = $dil['IslemGecersiz'];
	} else {
	  if ($goster)
		{
      $gosterim = $vt->query("UPDATE ".TABLO_ONEKI."anketsoru SET goster='$goster' WHERE anketno=$anketno");
		  if ($gosterim)
		  {
			  if ($goster == 'E')
				{
				  $anket_mesaj = $dil['GosterimeAcildi'];
				} else {
				  $anket_mesaj = $dil['GosterimeKapandi'];
				}
		  } else {
		    $anket_mesaj = $dil['IslemBasarisiz'];
		  }
		} else {
		  $anket_mesaj = $dil['BosAlanBirakmayiniz'];
		}
	}
//========================================================================================//	
} elseif ($islem == 5) {
  // ANKET DÜZENLEME KAYDET
	$anketsoru = $fonk->post_duzen($_POST['anketsoru']);
	@ $bitistarihi = trim($_POST['bitistarihi']);
  try 
	{
    if (empty($anketsoru))
		{
		  throw new Exception($dil['BosAlanBirakmayiniz']);
		}
		if (anketvar($anketno) == 0)
	  {
	    throw new Exception($dil['IslemGecersiz']);
	  } else {
	    //Anket Sorusu Düzenleniyor
			if (!$fonk->tarihsaat_kontrol($bitistarihi))
      {
	      echo $dil['TarihFormatiYanlis'];
		    exit;
      }
	    $bitistarihi = $fonk->mysql_tarih_saat($bitistarihi,true);
			$as_vt = new Baglanti();
			$vt->query("UPDATE ".TABLO_ONEKI."anketsoru SET anketsoru='".$vt->escapeString($anketsoru)."',bitistarihi='$bitistarihi' WHERE anketno=$anketno");
			
			//Anket Seçenekleri Düzenleniyor
			$secenekler_duzenkayit = $vt->query("SELECT `secenekno` FROM ".TABLO_ONEKI."anketsecenek WHERE anketno=$anketno");

		  while ($secenek_duzen = $vt->fetchObject())
		  {
		    $secenek_numara = $secenek_duzen->secenekno;
	      $anket_secenek_duzen = trim(strip_tags(htmlspecialchars($_POST['sec_'.$secenek_numara])));

	      if ($anket_secenek_duzen)
		    {
          $as_vt->query2("UPDATE ".TABLO_ONEKI."anketsecenek SET secenek='".$as_vt->escapeString($anket_secenek_duzen)."' WHERE secenekno=$secenek_numara");
		    } else {
		      throw new Exception($dil['BosAlanBirakmayiniz']);
		    }
				unset($anket_secenek_duzen);
		  }
			throw new Exception($dil['DuzenlemeIslemiTamamlandi']);
		}
  } 
 
	catch (Exception $e)
  {
    $anket_mesaj = $e->getMessage();
  }
//================================================================================//
} elseif ($islem == 6) {
  // ANKET SİLME

  try 
	{
		if (anketvar($anketno) == 0)
	  {
	    throw new Exception($dil['IslemGecersiz']);
	  } else {
		  $cevapsil      = $vt->query("DELETE FROM ".TABLO_ONEKI."anketcevap WHERE anketno=$anketno");
			if ($cevapsil)
			{
		    $seceneklersil = $vt->query("DELETE FROM ".TABLO_ONEKI."anketsecenek WHERE anketno=$anketno");
			  if ($seceneklersil)
			  {
		      $anketsil = $vt->query("DELETE FROM ".TABLO_ONEKI."anketsoru WHERE anketno=$anketno");
			    if ($anketsil)
			    {
			      throw new Exception($dil['SilmeIslemiTamamlandi']);
			    } else {
			      throw new Exception($dil['SilmeIslemiBasarisiz']);
			    }
			  } else {
			    throw new Exception($dil['SilmeIslemiBasarisiz']);
			  }
			} else {
			  throw new Exception($dil['SilmeIslemiBasarisiz']);
			}
		}
	}
	catch (Exception $e)
  {
    $anket_mesaj = $e->getMessage();
  }
} elseif ($islem == 7) {
  // SEÇENEK SİLME
  try 
	{
		if (anketvar($anketno) == 0)
	  {
	    throw new Exception($dil['IslemBasarisiz']);
	  } else {
		  $secenekno = intval($_GET['secenekno']);
		  $seceneksil = $vt->query("DELETE FROM ".TABLO_ONEKI."anketsecenek WHERE anketno=$anketno AND secenekno=$secenekno");
			if ($seceneksil)
			{
			  $s_vt = new Baglanti();
			  $s_vt->query("SELECT cevapno,anketcevap FROM ".TABLO_ONEKI."anketcevap WHERE anketno=$anketno");
				if ($s_vt->numRows()>0)
				{
			    while ($cevap_veri = $s_vt->fetchObject())
			    {
				    $cevaplar_dizi = array();
				    $cevapno  = $cevap_veri->cevapno;
				    $cevaplar = explode(',',$cevap_veri->anketcevap);
					  foreach($cevaplar as $cevapanahtar=>$cevapdeger)
					  {
					    if ($cevapdeger != $secenekno)
						  $cevaplar_dizi[] = $cevapdeger;
					  }
					  $vt->query("UPDATE ".TABLO_ONEKI."anketcevap SET anketcevap='".implode(',',$cevaplar_dizi)."' WHERE anketno=$anketno AND cevapno=$cevapno");
					  unset($cevaplar_dizi);
					}
				}
				$s_vt->freeResult();
				unset($s_vt);
				$vt->query("DELETE FROM ".TABLO_ONEKI."anketcevap WHERE anketcevap=NULL");
			  throw new Exception($dil['SecenekSilindi']);
			} else {
			  throw new Exception($dil['SilmeIslemiBasarisiz']);
			}
		}
		unset($anketvar);
	}
	catch (Exception $e)
  {
    $anket_mesaj = $e->getMessage();
  }
	
//====================================================================================//
} elseif ($islem == 8) {
  // ANKET SEÇENEK İZİNLERİNİN AYARLANMASI //
	
  @ $secenekizin = intval($_GET['secenekizin']);
 
	if (anketvar($anketno) == 0)
	{
	  $anket_mesaj = $dil['IslemGecersiz'];
	} else {
	  if ($vt->kayitSay("SELECT COUNT(cevapno) FROM ".TABLO_ONEKI."anketcevap  WHERE anketno=$anketno")>0)
		{
      $anket_mesaj = $dil['AnketOylandiSecenekIzinDegistirilemez'];
		} else {
			if ($secenekizin)
		  {
        $secenek_izin = $vt->query("UPDATE ".TABLO_ONEKI."anketsoru SET secenekizin=$secenekizin WHERE anketno=$anketno");
		    if ($secenek_izin)
		    {
		      $anket_mesaj = $dil['SecenekIzinAyarlariYapildi'];
		    } else {
		      $anket_mesaj = $dil['IslemBasarisiz'];
		    }
		  } else {
		    $anket_mesaj = $dil['BosAlanBirakmayiniz'];
		  }
		}
	}
//=================================================================================================//
} elseif ($islem == 9) {
// ANKET GÖSTERİMİNİ AÇMA/KAPAMA
	@ $acik = trim(htmlspecialchars($_GET['acik']));
	
  
	if (anketvar($anketno) == 0)
	{
	  $anket_mesaj = $dil['IslemGecersiz'];
	} else {
	  if ($acik)
		{
      $oya_ac = $vt->query("UPDATE ".TABLO_ONEKI."anketsoru SET acik='$acik' WHERE anketno=$anketno");
		  if ($acik)
		  {
			  if ($acik == 'E')
				{
				  $anket_mesaj = '<font color="#008000">'.$dil['OylamayaAcildi'].'</font>';
				} else {
				  $anket_mesaj = $dil['OylamayaKapandi'];
				}
		    
		  } else {
		    $anket_mesaj = $dil['IslemBasarisiz'];
		  }
		} else {
		  $anket_mesaj = $dil['BosAlanBirakmayiniz'];
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo SITE_ADI; ?> : <?php echo $dil['YonetimPaneli']; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="ystil.css" />
</head>
<body background="yonetimresim/bg.gif">
<div align="center">
<table border="1" align="center" cellpadding="0" cellspacing="0" width="95%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['ANKET_YONETIMI']; ?></b></font></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" bgcolor="#efeffa">
		    
				<?php
				if ($anket_mesaj)
				{
				  echo '<tr>
            <td colspan="2" align="center" valign="center" height="30"><font color="#ff0000"><b>'.$anket_mesaj.'</b></font></td>
          </tr>';
				}
				
				if (empty($sayfa))
				{
				?> 
				
				<form name="anket" action="anket_yonet.php?islem=2" method="post">
				<tr bgcolor="#b6c5f2">
		      <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['ANKET_SORUSU_EKLEME_BOLUMU']; ?></b></td>
		    </tr>
        <tr bgcolor="#f7f7fd">
		      <td width="40%" height="25" align="right"><b><?php echo $dil['AnketSorusu']; ?> :</b>&nbsp;&nbsp;</td>
		      <td width="60%" height="25" align="left"><input type="text" id="anketsoru" name="anketsoru" class="input" size="40" maxlength="250" /></td>
		    </tr>
				<tr bgcolor="#f7f7fd">
		      <td width="40%" height="25" align="right"><b><?php echo $dil['AnketBitisTarihi']; ?> :</b>&nbsp;&nbsp;</td>
		      <td width="60%" height="25" align="left"><input type="text" id="bitistarihi" name="bitistarihi" class="input" size="25" maxlength="50" />&nbsp;&nbsp;Örn: gg.aa.yyyy ss:dd - (dd.mm.yyyy hh:ii)</td>
		    </tr>
				<tr>
		      <td colspan="2" align="center" valign="center" height="30"><input type="submit" value="<?php echo $dil['KAYDET']; ?>" class="input"/></td>
		    </tr>
				<tr bgcolor="#ffffff">
		      <td width="100%" height="25" align="center" colspan="2"><?php echo $dil['AnketSorusunuEkleSecenekEkle']; ?></td>
		    </tr>
				</form>
				<?php
				} elseif ($sayfa == 2) {
				
				$anketsoru_sql = $vt->query("SELECT `anketno`,`anketsoru`,`bitistarihi` FROM ".TABLO_ONEKI."anketsoru WHERE anketno=$anketno");
				$anket_kayitli = $vt->numRows();

				if ($anket_kayitli > 0)
				{
				  $anketsoru_veri = $vt->fetchObject();
				  $anket_no   = $anketsoru_veri->anketno;
				  $anket_soru = $anketsoru_veri->anketsoru;
					$anket_bitistarihi = $fonk->duzgun_tarih_saat($anketsoru_veri->bitistarihi,true);
				  ?>
				
				  <form name="anket" action="anket_yonet.php?sayfa=2&anketno=<?php echo $anket_no; ?>&islem=3" method="post" onsubmit="return anketsoru()">
				  <input type="hidden" name="anketno" id="anketno" value="<?php echo $anket_no; ?>" />
					<tr bgcolor="#b6c5f2">
		      <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['ANKET_SECENEK_EKLEME_BOLUMU']; ?></b></td>
		    </tr>
          <tr>
		      <td width="100%" height="25" align="center" colspan="2"><?php echo $anket_soru; ?></td>
		      </tr>
          <tr>
		        <td width="40%" height="25" align="right"><b><?php echo $dil['AnketSecenek']; ?> :</b>&nbsp;&nbsp;</td>
		        <td width="60%" height="25" align="left"><input type="text" id="anketsecenek" name="anketsecenek" class="input" size="40" maxlength="250" /></td>
		      </tr>
				  <tr>
		        <td colspan="2" align="center" valign="center" height="30"><input type="submit" value="<?php echo $dil['KAYDET']; ?>" class="input" tabindex="13" /></td>
		      </tr>
				  </form>
				<?php
				 } else {
				  echo '
				  <tr>
		        <td colspan="2" align="center" valign="center" height="30">'.$dil['KayitBulunamadi'].'</td>
		      </tr>';
			  }				
				
				?>
				<!-- ANKET BİLGİLERİ DÜZENLEME -->
				 <tr bgcolor="#b6c5f2">
		      <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['ANKET_DUZENLE_SIL']; ?></b></td>
		    </tr>
				<form name="anket2" action="anket_yonet.php?sayfa=2&anketno=<?php echo $anket_no; ?>&islem=5" method="post" onsubmit="return anketsoru()">
        <tr>
		      <td align="right" valign="center"><b><?php echo $dil['AnketSorusu']; ?> :</b></td><td align="left"><input type="text" name="anketsoru" id="anketsoru" value="<?php echo $anket_soru; ?>" class="input" size="40" />&nbsp;<a href="anket_yonet.php?islem=6&anketno=<?php echo $anket_no; ?>" onclick="return confirm('<?php echo $dil['SilmekIstiyormusunuz']; ?>');"><b><?php echo $dil['Sil']; ?></b></a></td>
				</tr>
				 <tr>
		      <td align="right" valign="center"><b><?php echo $dil['AnketBitisTarihi']; ?> :</b></td><td align="left"><input type="text" name="bitistarihi" id="bitistarihi" value="<?php echo $anket_bitistarihi; ?>" class="input" size="40" /></td>
				</tr>

				<tr>
		      <td colspan="2" align="center" valign="center"><b><?php echo $dil['SECENEKLER']; ?></b><br />
				<?php
				$anket_secenekler  = $vt->query("SELECT `secenekno`,`secenek` FROM ".TABLO_ONEKI."anketsecenek WHERE anketno=$anket_no ORDER BY secenekno ASC");
        $anketsecenek_sayi = $vt->numRows();

        if ($anketsecenek_sayi > 0)
        {
          while ($anket_secenek = $vt->fetchObject())
          {
					  $secenekno = $anket_secenek->secenekno;
            $secenek   = $anket_secenek->secenek;
						echo '<b>'.$secenekno.' -</b> <input type="text" name="sec_'.$secenekno.'" id="sec_'.$secenekno.'" value="'.$secenek.'" class="input" size="40" /> <a href="anket_yonet.php?islem=7&sayfa=2&anketno='.$anket_no.'&secenekno='.$secenekno.'" onclick="return confirm(\''.$dil['SilmekIstiyormusunuz'].'\');">'.$dil['SecenegiSil'].'</a><br />';
					}
				} else {
				  echo '<font color="#ff0000">'.$dil['AnketIcinSecenekEklenmedi'].'</font>';
				}
				echo '</td></tr>
				<tr>
		      <td colspan="2" align="center" valign="center" height="30"><input type="submit" value="'.$dil['DUZENLE'].'" class="input" tabindex="13" /></td>
		    </tr>';

				}
				
				?>
				</form>
				<!-- ANKET BİLGİLERİ DÜZENLEME SONU -->
		    <tr bgcolor="#b6c5f2">
		      <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['EKLENEN_ANKETLER']; ?></b></td>
		    </tr>
				<tr bgcolor="#ffffff">
		      <td width="100%" height="25" align="center" colspan="2">
					  <table width="100%" bgcolor="#ffffff">
						  <tr bgcolor="#b6c5f2">
							  <td width="5%"><b><?php echo $dil['ANKET_NO']; ?></b></td>
								<td width="35%"><b><?php echo $dil['ANKET_SORUSU']; ?></b></td>
								<td width="15%"><b><?php echo $dil['EKLEME_TARIHI']; ?></b></td>
								<td width="15%"><b><?php echo $dil['BITIS_TARIHI']; ?></b></td>
								<td width="15%"><b><?php echo $dil['GOSTERIM_DURUMU']; ?></b></td>
								<td width="15%"><b><?php echo $dil['ONAY_DURUMU']; ?></b></td>
								<td width="10%"><b><?php echo $dil['SECENEK_IZIN']; ?></b></td>
							</tr>
				      <?php
              $anketler = $vt->query("SELECT `anketno`,`anketsoru`,`tarih`,`goster`,`acik`,`secenekizin`,`bitistarihi` FROM ".TABLO_ONEKI."anketsoru ORDER BY tarih DESC");
							$anketsayi = $vt->numRows();
							if ($anketsayi > 0)
							{
                while ($anket = $vt->fetchObject())
							  {
							    $anketno   = $anket->anketno; 
									$anketsoru = $anket->anketsoru;
									$tarih     = $anket->tarih;
									$gosterim  = $anket->goster;
									$acik      = $anket->acik;
									$secenekizin = $anket->secenekizin;
									$bitistarihi = $anket->bitistarihi;
									if ($gosterim == 'E')
									{
									  $gosterim = '<font color="#008000">'.$dil['Gosterimde'].'</font><br /><a href="anket_yonet.php?islem=4&anketno='.$anketno.'&goster=H">'.$dil['GosterimeKapat'].'</a>';
									} else {
									  $gosterim = '<font color="#ff0000">'.$dil['UyelerGoremez'].'</font><br /><a href="anket_yonet.php?islem=4&anketno='.$anketno.'&goster=E">'.$dil['GosterimeAc'].'</a>';
									}
									if ($acik == 'E')
									{
										if ($bitistarihi > date('Y-m-d H:i:s'))
										{
										  $acik = '<font color="#008000">'.$dil['OylamayaAcik'].'</font><br /><a href="anket_yonet.php?islem=9&anketno='.$anketno.'&acik=H">'.$dil['OylamayaKapat'].'</a>';
										} else {
										  $acik = '<font color="#ff0000">'.$dil['TarihiGecti'].'</font><br /><a href="anket_yonet.php?sayfa=2&anketno='.$anketno.'">'.$dil['TarihiDegistir'].'</a>';
										}
										
									} else {
									  $acik = '<font color="#ff0000">'.$dil['OylamayaKapali'].'</font><br /><a href="anket_yonet.php?islem=9&anketno='.$anketno.'&acik=E">'.$dil['OylamayaAc'].'</a>';
									}
									
									$seceneksayisi = $vt->kayitSay("SELECT COUNT(secenekno) FROM ".TABLO_ONEKI."anketsecenek WHERE anketno=$anketno");
									if (empty($seceneksayisi))
									{
									  $seceneksayisi = $dil['SecenekEklenmemis'];
									}
				          echo '
		              <tr>
							      <td align="center">'.$anketno.'</td>
		                <td align="left"><a href="anket_yonet.php?sayfa=2&anketno='.$anketno.'">'.$anketsoru.'</a></td>
								    <td align="center">'.$fonk->duzgun_tarih_saat($tarih,true).'</td>
										<td align="center">'.$fonk->duzgun_tarih_saat($bitistarihi,true).'</td>
								    <td align="center">'.$gosterim.'</td>
								    <td align="center">'.$acik.'</td>
										<td align="center">
										  <select name="secenekizin" id="secenekizin" onChange="if(options[selectedIndex].value)   window.location.href=(options[selectedIndex].value)">';
											for ($i=1; $i<=$seceneksayisi; $i++)
											{
											  echo '<option value="anket_yonet.php?islem=8&anketno='.$anketno.'&secenekizin='.$i.'"';
												if ($i == $secenekizin) echo ' selected="selected"'; echo '>'.$i.'</option>';
											}
											echo '</select>
                    </td>
							    </tr>';
							  }
							} else {
							  echo '<tr><td width="100%" colspan="6" align="center"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td></tr>';
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