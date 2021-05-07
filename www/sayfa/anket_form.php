<?php 
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
{
  echo 'Tek Kullanilmaz';
  exit;
}
$anketkontrol = new Fonksiyon();
//========================================================
if (empty($islem) || $islem == 1) { // 1. ADIM BASLANGICI
//========================================================
?>
<form name="anketForm" id="anketForm" action="?sayfa=anket&islem=3" autocomplete="off" method="post">
<p>&nbsp;</p>
<table width="98%" align="center">
  <tr>
    <td width="100%" height="20" colspan="2" align="center"><h1><?php echo $dil['ANKET']; ?></h1></td>
  </tr>
	<tr>
    <td width="100%" height="20" colspan="2" align="right"><a href="?sayfa=anket&islem=2"><?php echo $dil['TumAnketler']; ?></a></td>
  </tr>
  <?php
	@ $anketno = intval($_GET['anketno']);
	$anket_kosul = '';
	if ($anketno)
  {
    $anket_kosul = "a.anketno=$anketno AND ";
  } else {
    $anket_kosul = '';
  }

  $vt->query("SELECT a.anketno,a.anketsoru,a.secenekizin,a.acik,a.bitistarihi,s.secenekno,s.secenek FROM ".TABLO_ONEKI."anketsoru AS a, ".TABLO_ONEKI."anketsecenek AS s WHERE $anket_kosul a.goster='E' AND a.anketno=s.anketno ORDER BY a.acik ASC,a.tarih DESC LIMIT 1");
  $toplam_anket  = $vt->numRows();
  $anket_acik    = 'H';
  $anket_oylandi = 0;
  if ($toplam_anket == 0) //1. if Baslangici
  {
    echo '
    <tr>
      <td  width="100%" align="center" height="30">';
			echo $fonk->hata_mesaj($dil['AnketBulunamadi'],false);
			echo '</td>
    </tr>';
  } else {
    $anket_bilgi       = $vt->fetchArray();
    $anket_no          = $anket_bilgi["anketno"];
    $anket_soru        = $fonk->yazdir_duzen($anket_bilgi["anketsoru"]);
    $anket_secenekizin = $anket_bilgi["secenekizin"];
    $anket_acik        = $anket_bilgi["acik"];
    $anket_bitistarihi = $anket_bilgi["bitistarihi"];
				
    $anket_oylama_kontrol = $vt->query("SELECT anketcevap FROM ".TABLO_ONEKI."anketcevap WHERE anketno=$anket_no AND uyeno=".UYE_NO."");
    $anket_oylandi        = $vt->numRows();
    $anket_cevap          = array();
    if ($anket_oylandi > 0) //2. if Baslangici 
    {
      $anket_oylama = $vt->fetchObject();
      $anket_oylar     = explode(',',$anket_oylama->anketcevap);
      $anket_secim_sayi = count($anket_oylar);
      for($a=0; $a<$anket_secim_sayi; $a++) //1. for Baslangc 
      {
        @ $anket_oy = $anket_oylar[$a];
        if ($anket_oy)
        {
          array_push($anket_cevap,$anket_oy);
        }
      } //1. for Sonu
      unset($anket_oylama,$anket_oylar,$anket_secim_sayi,$anket_oy);
    } //2. if Sonu

    $vt->freeResult();
        
    
    $anket_soru      = wordwrap($anket_soru, 70, "<br />",1);
    ?>
    <tr class="tablobaslik">
      <td height="25" valign="center" align="center"><strong><?php echo $anket_soru; ?></strong></td>
    </tr>
    <tr>
      <td height="20" align="left">
        <table width="100%">
          <input type="hidden" name="anketno" id="anketno" value="<?php echo $anket_no; ?>" />
					<?php
          //SECENEKLERIN OY SAYISI VE YUZDESI  HESAPLANIYOR
          $vt->query("SELECT anketcevap FROM ".TABLO_ONEKI."anketcevap WHERE anketno=$anket_no");
          $oylanan_secenek_sayi = $vt->numRows();
          $oylanan_secenek_dizi = false;
          $oylanan_secenek_dizi = array();
          
          if ($oylanan_secenek_sayi > 0) //4. if Balang c
          {
            for ($o=0; $o<$oylanan_secenek_sayi; $o++) //2. for Ba langc 
            {
              $oylanan_secenek = $vt->fetchObject();
              $oy_secenek      = explode(',',$oylanan_secenek->anketcevap);
              $oy_secim_sayi   = count($oy_secenek);

              for($oy=0; $oy<$oy_secim_sayi; $oy++) //3. for Balang c
              {
                @ $oylar = intval($oy_secenek[$oy]);
                if ($oylar)
                {
                  array_push($oylanan_secenek_dizi,$oylar);
                }
              } //3. for Sonu
              unset($oy,$oylar);
            } // 2. for Sonu
            unset($oylanan_secenek,$oy_secenek,$oy_secim_sayi);
          } // 4. if Sonu

          $vt->freeResult();
          //Her Se enek  in Oy Says  Belirleniyor
          $toplam_verilen_oylar = array_count_values($oylanan_secenek_dizi);
          $toplam_oy = array_sum($toplam_verilen_oylar);
						
          //SEENEKLER YAZDIRILIYOR
          $secenek_sql = $vt->query("SELECT secenekno,secenek FROM ".TABLO_ONEKI."anketsecenek WHERE anketno=$anket_no");
          $secenek_sayi = $vt->numRows();
          if ($secenek_sayi > 0) // 5. if Baslangici 
          {
            for ($s=0; $s<$secenek_sayi; $s++) // 4. for Baslangici
            {
              $secenek_bilgi = $vt->fetchObject();
              $secenek_no    = $secenek_bilgi->secenekno;
              $secenek       = $fonk->yazdir_duzen($secenek_bilgi->secenek);
								
              @ $toplam_aldigi_oy = $toplam_verilen_oylar[$secenek_no];
								
              @ $toplam_oy_yuzde    = number_format($toplam_aldigi_oy * 100 / $toplam_oy,2,'.','');

              if (($s % 2) == 0)
              {
                $bgrenk = 'renk1';
              } else {
                $bgrenk = 'renk2';
              }
              ?>
							<tr class="<?php echo $bgrenk; ?>"><td width="5%" align="center" nowrap="nowrap">
							<?php
              if (UYE_SEVIYE > 0 && $anket_acik == 'E' && $anket_oylandi == 0 && $anket_bitistarihi>date('Y-m-d H:i:s')) 
              { 
								echo '<input type="checkbox" name="sec['.$secenek_no.']" id="secenek" onclick="secim_kontrol('.$anket_secenekizin.','.$s.');" value="1" />'; 
              } else {
                echo '<input type="checkbox" disabled="disabled"'; if (in_array($secenek_no,$anket_cevap)) echo ' checked="checked"'; echo '/>'; 
              }
              ?>
              </td><td width="55%" class="main">
              <?php
              if (UYE_SEVIYE > 0 && $anket_acik == 'E' && $anket_oylandi == 0 && $anket_bitistarihi>date('Y-m-d H:i:s')) 
              {
                echo '<label for="sec_'.$secenek_no.'">'.$secenek.' </label>';
              } else {
                echo $secenek;
              }
              ?>
              &nbsp;&nbsp;&nbsp;&nbsp;<span class="fontmavi">(%<?php echo $toplam_oy_yuzde; ?>)</font></td>
              <td width="40%" align="left" nowrap="nowrap">
                <table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="15%" nowrap="nowrap" align="right"><b><?php echo intval($toplam_aldigi_oy); ?> - </b></td>
                    <td width="85%" nowrap="nowrap" height="20" valign="center" align="left"><div style="color:#000000;height:15px; width:<?php echo $toplam_oy_yuzde; ?>%; text-align:right;background-image:url(resim/bar2.jpg)"></div></td>
                  </tr>
                </table>
              </td>
            </tr>
						<?php
            } // 4. for Bitti
              unset($secenek_bilgi,$secenek_no,$secenek,$bgrenk,$toplam_aldigi_oy,$toplam_oy_yuzde);
            } else {
              echo '<tr><td align="center">'.hata_goster("Bu Anket İçin Seçenek Bulunamadı").'</td></tr>';
            }  // 5. if Bitti
            ?>
          </table>
        </td>
      </tr>
    <?php
		unset($secenek_sayi,$toplam_verilen_oylar,$toplam_oy);
    echo '<tr><td align="center"><span class="fontmavi">'.$anketkontrol->yerine_koy($dil['AnketKatilimSayisi'],$oylanan_secenek_sayi).'</span></td></tr>';

    if (UYE_SEVIYE == 0)
    {
      if ($anket_acik == 'H' || $anket_bitistarihi<date('Y-m-d H:i:s'))
      {
        echo '<tr><td align="center"><span class="fonthata">( '.$dil['AnketOylamayaKapali'].' )</span></td></tr>';
      } else {
        echo '<tr><td align="center"><span class="fonthata">( '.$dil['IslemIcinGirisGerekli'].' )</span></td></tr>';
      }
    } else {
      if ($anket_acik == 'E' && $anket_oylandi == 0 && $anket_bitistarihi>date('Y-m-d H:i:s'))
      {
        echo '<tr><td align="center"><span class="fontmavi">( '.$anketkontrol->yerine_koy($dil['SecenekOyIzin'],$anket_secenekizin).' )</b></span></td></tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="center"><input type="submit" name="anketKayit" id="anketKayit" value="'.$dil['OYLA'].'" class="input" /></td>
        </tr>';
      } else {
        if ($anket_oylandi > 0)
        {
          echo '<tr><td align="center"><span class="fonthata">( '.$dil['AnketiOyladiniz'].' )</span></td></tr>';
        } else {
          echo '<tr><td align="center"><span class="fonthata">( '.$dil['AnketOylamayaKapali'].' )</span></td></tr>';
        }
      }
    }
  } // 1. if Sonu
  $vt->freeResult();
  unset($toplam_anket,$anket_cevap,$anket_bilgi,$anket_no,$anket_soru,$oylanan_secenek_dizi,$toplam_verilen_oylar);
  ?>
</table>
</form>
<?php                                     
// =====================================================
} elseif ($islem == 2) { // TUM ANKETLER 
//======================================================
?>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="0" width="98%" align="center">
  <tr>
    <td width="100%" align="center">
      <table width="100%" align="center">
        <tr>
          <td width="100%" height="20" colspan="8" align="center"><h1><?php echo $dil['ANKET']; ?></h1></td>
        </tr>
			</table>
		</td>
	</tr>
</table>
<table align="center" cellpadding="0" cellspacing="1" width="98%" class="tablolar">
	<tr class="tablobaslik">
    <td align="center" width="5%" height="25"><b>SN</b></td>
		<td align="center" width="40%" height="25"><b><?php echo $dil['ANKET']; ?></b></td>
    <td align="center" width="15%" height="25"><b><?php echo $dil['BASLANGIC_TARIHI']; ?></b></td>
		<td align="center" width="15%" height="25"><b><?php echo $dil['BITIS_TARIHI']; ?></b></td>
		<td align="center" width="15%" height="25"><b><?php echo $dil['DURUMU']; ?></b></td>
    <td align="center" width="10%" height="25"><b><?php echo $dil['KATILIM']; ?></b></td>
	</tr>
		
  <?php
  $limit = 10;
  @ $a = abs(intval($_GET['a']));
  $toplamanket = $vt->kayitSay("SELECT COUNT(anketno) FROM ".TABLO_ONEKI."anketsoru WHERE goster='E'");

  if(empty($a)) 
  {                
    $a = 1;                
    $baslangic = 0;        
  } else {               
    $baslangic = ($a - 1) * $limit;        
  }
      
  $vt->query("SELECT anketno,anketsoru,tarih,acik,bitistarihi FROM ".TABLO_ONEKI."anketsoru WHERE goster='E' ORDER BY acik ASC, tarih DESC LIMIT $baslangic,$limit");
 
  if ($toplamanket == 0)
  {
    echo '
    <tr>
      <td  width="100%" bgcolor="#f0f8ff" align="center" height="30" colspan="6"><font color="#ff0000">'.$fonk->hata_mesaj($dil['KayitBulunamadi'],false,'<a href="?">'.$dil['Tamam'].'</a>').'</font></td>
    </tr>';
  } else {
	  $asayi = 0;
    while ($anket_bilgi = $vt->fetchArray())
    {
      $anket_no         = $anket_bilgi["anketno"];
      $anket_soru       = $fonk->yazdir_duzen($anket_bilgi["anketsoru"]);
      $anket_tarih      = $anket_bilgi["tarih"];
      $anket_acik       = $anket_bilgi["acik"];
			$anket_bitistarihi = $anket_bilgi["bitistarihi"];
			
			$asayi++;
			$a_sira           = $baslangic+$asayi;


      $anket_soru      = substr($anket_soru,0,50).'...';

      if (($asayi % 2) == 0)
      {
        $bg_color = 'renk1';
      } else {
        $bg_color = 'renk2';
      }
			if ($anket_acik == 'E' && $anket_bitistarihi>date('Y-m-d H:i:s'))
			{
			  $anket_acik_mesaj = $dil['OylamayaAcik'];
			} else {
			  $anket_acik_mesaj = $dil['OylamayaKapali'];
			}
			$anket_katilim = $vt->kayitSay("SELECT COUNT(anketno) FROM ".TABLO_ONEKI."anketcevap WHERE anketno=$anket_no");
			
      echo '<tr class="'.$bg_color.'">
      <td width="5%" height="20" align="center">'.$a_sira.'</td>
      <td width="40%" height="20">&nbsp;<a href="?sayfa=anket&anketno='.$anket_no.'"><font color="#3366ff"><strong>'.$anket_soru.'</strong></font></font></td>
      <td width="15%" align="center">'.$fonk->duzgun_tarih_saat($anket_tarih,true).'</td>
      <td width="15%" align="center">'.$fonk->duzgun_tarih_saat($anket_bitistarihi,true).'</td>
      <td width="15%" align="center">'.$anket_acik_mesaj.'</td>
      <td width="10%" align="center">'.$anket_katilim.'</td>
      </tr>';
    }
		echo '
		<tr bgcolor="#f5f5f5">
      <td align="center" width="100%" colspan="6">'.$fonk->sayfalama($limit, $toplamanket, $a, '?sayfa=anket&islem=1&a=[sn]').'</td>
    </tr>';
    unset($toplam_anket);
  }
?>
</table>
<?php
//================================================
} elseif ($islem == 3) { //ANKET KAYIT BASLANGICI
//================================================
  $vt = new Baglanti();
	global $fonk;
	global $dil;
  $ganket_no = intval($_POST['anketno']);
	
	try
	{
	  if (UYE_SEVIYE == 0)
    { 
      $_SESSION['sayfaadi'] = serialize($sayfa);
      header('Location: ?sayfa=giris&hata=15');
      exit;
    }
    //Anket Dogrulama
		$vt->query("SELECT anketno FROM ".TABLO_ONEKI."anketsoru WHERE anketno=$ganket_no AND goster='E' AND acik='E'");
		$anket_var = $vt->numRows();
		$vt->freeResult();
    if ($anket_var == 0)
    {
      throw new Exception($dil['AnketGecersiz']);
    } elseif ($vt->kayitSay("SELECT cevapno FROM ".TABLO_ONEKI."anketcevap WHERE anketno=$ganket_no AND uyeno=".UYE_NO."") > 0) {
      throw new Exception($dil['AnketiOyladiniz']);
		}
    //Anket Genel Kontrol
	  $anket_genel_bilgi = $vt->query("SELECT goster,acik,secenekizin,bitistarihi FROM ".TABLO_ONEKI."anketsoru WHERE anketno=$ganket_no");
	  $anket_gbilgi      = $vt->fetchArray();
    $anket_secizin     = $anket_gbilgi["secenekizin"];
    $anket_gacik       = $anket_gbilgi["acik"];
	  $anket_ggoster     = $anket_gbilgi["goster"];
		$anket_bitistarihi = $anket_gbilgi["bitistarihi"];
		
		$vt->freeResult();
		
		if ($anket_gacik == 'H' || $anket_bitistarihi < date('Y-m-d H:i:s'))
		{
      throw new Exception($dil['AnketOylamayaKapali']);
		} elseif ($anket_ggoster == 'H') {
		  throw new Exception($dil['AnketGosterimeKapali']);
		}
		
		//Anket Seenek Kontrol
		$gelen_secenekler   = $vt->query("SELECT secenekno FROM ".TABLO_ONEKI."anketsecenek WHERE anketno=$ganket_no");
		$gelen_secenek_sayi = $vt->numRows();
		$gelen_cevaplar = false;
		$gelen_cevaplar = array();

		$gelen_cevap_sayisi = 0;
		if ($gelen_secenek_sayi > 0)
		{
		  for ($s=0; $s<$gelen_secenek_sayi; $s++)
			{
			  $gelen_secenek_veri = $vt->fetchObject();
				$gelen_secenek_no   = $gelen_secenek_veri->secenekno;
				@ $gelen_cevap      = intval($_POST['sec'][$gelen_secenek_no]);
				if ($gelen_cevap)
				{
				  array_push($gelen_cevaplar,$gelen_secenek_no);
				  $gelen_cevap_sayisi++;
				}
			}
		} else {
      throw new Exception($dil['AnketeAitSecenekBulunamadi']);
			exit;
		}
		
		if ($gelen_cevap_sayisi <= 0)
		{
		  throw new Exception($dil['SecimYapmadiniz'],1);
			exit;
		} elseif ($gelen_cevap_sayisi > $anket_secizin) {
		  
			throw new Exception($fonk->yerine_koy($dil['SecenekOyIzin'],$anket_secizin),1);
			exit;
		}
    $anket_kayit = $vt->query("INSERT INTO ".TABLO_ONEKI."anketcevap (anketno,uyeno,anketcevap) VALUES ($ganket_no,".UYE_NO.",'".implode(',',$gelen_cevaplar)."')");
	
    if ($anket_kayit)
    {
      throw new Exception($dil['AnketOylamaTamam'],2);
    } else {
      throw new Exception($dil['IslemBasarisiz']);
    }
	}
  catch (Exception $e)
  {
    $hatakod = $e->getCode();
    if ($hatakod == 1)
    {
      $adres = '<a href="index.php?sayfa=anket&islem=1">'.$dil['Tamam'].'</a>';
	    $hata  = false;
    } elseif ($hatakod == 2) {
      $adres = '<a href="index.php?sayfa=anket&islem=1">'.$dil['Tamam'].'</a>';
	    $hata = true;
    } else {
      $adres = '<a href="index.php?sayfa=anket&islem=2">'.$dil['Tamam'].'</a>';
	    $hata = false;
    }
    ?>
    <table align="center" cellpadding="0" cellspacing="0" width="85%">
      <tr>
	      <td align="center">
        <?php echo $fonk->hata_mesaj($e->getMessage(),$hata,$adres); ?>
		    </td>
	    </tr>
    </table>
	<?php
  }
	unset($vt,$gelen_cevaplar,$anket_gbilgi,$anket_secizin,$anket_gacik,$anket_ggoster,$anket_genel_bilgi);
}
?>