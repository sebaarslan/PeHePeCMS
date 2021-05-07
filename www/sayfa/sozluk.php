<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;
$soz_vt = new Baglanti();

$islem  = isset($_GET['islem'])? $_GET['islem']:'';
$aranan = trim(htmlspecialchars(trim(isset($_REQUEST['aranan'])? $_REQUEST['aranan']:'')));
$harf   = htmlspecialchars(trim(isset($_GET['harf'])? $_GET['harf']:''));
$sirala = intval(isset($_GET['sirala'])? $_GET['sirala']:'');
//===========================
try {
//=============================================================
if (!$islem || $islem == 1) { //ISLEM 1 BASLANGICI
//=============================================================
if (UYE_SEVIYE < SOZLUK_GORME_IZIN)
{
  throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[SOZLUK_GORME_IZIN]));
	exit;
}
  @$sozcukno = intval($_GET['sozcukno']);
  $aranan = preg_replace(" {2,}"," ",$aranan); 
	if ($sozcukno)
	{
	  $arama_alani = " AND sozcukno=$sozcukno";
	} else {
    if (!empty($aranan)) {
    $arama_alani = " AND (turkce LIKE '%$aranan%' OR ingilizce LIKE '%$aranan%'";
	
    $aranan_kelimeler = array();
    $aranan_kelimeler = explode(' ', $aranan);
    while (list($sira, $aranan_kelime) = each($aranan_kelimeler))
    {
	  if (!empty($aranan_kelime))
	  {
	    $aranan_kelime = trim($aranan_kelime);
			if ($aranan_kelime != $aranan)
	    $arama_alani .= " OR turkce LIKE '%$aranan_kelime%' OR ingilizce LIKE '%$aranan_kelime%'";
	  }
    }
    $arama_alani .= ')';
    }else{
	    $arama_alani = "";
    }
	}

  //HARFE GORE ARAMA

  if (!empty($harf))
  {
    if ($sirala == 2)
    {
	  if ($harf == 1)
	  {
        $arama_alani = " AND turkce REGEXP '^[0-9_]'";
	  } else {
	    $arama_alani = " AND turkce LIKE '$harf%'";
	  }
    }else{
	  if ($harf == 1)
	  {
        $arama_alani = " AND ingilizce REGEXP '^[0-9_]'";
	  } else {
	    $arama_alani = " AND ingilizce LIKE '$harf%'";
	  }
    }
  }

$turkce_harfler = array('A','B','C','Ç','D','E','F','G','H','I','İ','J','K','L','M','N','O','Ö','P','R','S','Ş','T','U','Ü','V','Y','Z');
$ingilizce_harfler =   array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

//SIRALAMA ALANI
if (!$sirala || $sirala == 1) 
{
	$siralama_alani = "ingilizce";
	$ilk_sozcuk = "ingilizce";
	$ikinci_sozcuk = "turkce";
	$ilk_baslik = $dil['Ingilizce'];
	$ikinci_baslik = $dil['Turkce'];
	$arama_aciklama = '<b>'.$dil['Ingilizce'].' :</b>';

} elseif ($sirala == 2) {
  $siralama_alani = "turkce";
	$ilk_sozcuk = "turkce";
	$ikinci_sozcuk = "ingilizce";
	$ilk_baslik = $dil['Turkce'];
	$ikinci_baslik = $dil['Ingilizce'];
	$arama_aciklama = '<b>'.$dil['Turkce'].' :</b>';
} else {
	throw new Exception($dil['IslemBasarisiz']);
}
  if (UYE_SEVIYE > 5)
	$kosul = "WHERE sozcukno>0 $arama_alani";
	else
	$kosul = "WHERE onay='E' $arama_alani";
	
  $toplam_sayfa = 0;
  $limit = 30;
  @$sayfano = abs(intval($_REQUEST['s']));
	$toplam_sozcuk  = $soz_vt->kayitSay("SELECT COUNT(*) FROM ".TABLO_ONEKI."sozluk $kosul");
  $bulunan_sozcuk = $soz_vt->kayitSay("SELECT COUNT(*) FROM ".TABLO_ONEKI."sozluk $kosul");

	if(empty($sayfano) || $sayfano>ceil($bulunan_sozcuk/$limit)) 
  {                
    $sayfano = 1;                
    $baslangic = 0;        
  } else {               
    $baslangic = ($sayfano - 1) * $limit;        
  }
  
  $soz_vt->query("SELECT sozcukno,turkce,ingilizce,onay FROM ".TABLO_ONEKI."sozluk $kosul ORDER BY onay DESC,$siralama_alani ASC LIMIT $baslangic,$limit");
  $sozcuk_sayisi = $soz_vt->numRows();
	if (UYE_SEVIYE>5)
	$colspan=3;
	else
	$colspan=2;
?>
<table align="center" border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100%" align="center">
      <table border="0" cellpadding="2" cellspacing="2">
	    <tr>
		  <td align="center" colspan="<?php echo $colspan; ?>"><h1><a href="?sayfa=sozluk"><b><?php echo $dil['SOZLUK']; ?></b></a></h1></td>
		</tr>
		<tr>
		  <td align="center" colspan="<?php echo $colspan; ?>"><b><?php echo $dil['SozlukAdi']; ?></b>&nbsp;&nbsp;<a href="http://www.tdk.org.tr" target="_blank">(Türk Dil Kurumu)</a></td>
		</tr>
		<tr>
		  <td align="center" colspan="2">
	        <?php
			  echo $arama_aciklama; 
			  if ($sirala == 2)
			  {
				$harfler = $turkce_harfler;
			  } else {
				$harfler = $ingilizce_harfler;
			  }
			  foreach ($harfler AS $sira => $harfi)
			  {
			    echo '&nbsp;<a href="?sayfa=sozluk&harf='.$harfi.'&sirala='.$sirala.'"><b>'.$harfi.'</b></a>&nbsp;';
			  }
			  echo '&nbsp;<a href="?sayfa=sozluk&harf=1&sirala='.$sirala.'"><b>'.$dil['Diger'].'</b></a>';
		    ?>
		  </td>
		</tr>
        <tr bgcolor="#FFFFFF">
          <td colspan="<?php echo $colspan; ?>" width="40%" align="center"><a href="?sayfa=sozluk&sirala=2">[ <?php echo $dil['TurkceAZSirala']; ?> ]</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?sayfa=sozluk&sirala=1">[ <?php echo $dil['IngilizceAZSirala']; ?> ]</a>
		  </td>
        </tr>
		<tr>
		  <td colspan="<?php echo $colspan; ?>" align="center"><form  name="sozcukarama" action="?sayfa=sozluk" method="post"><input type="text" name="aranan" size="30">&nbsp;&nbsp;<input type="submit" value="::: <?php echo $dil['ARA']; ?> :::"></FORM></td>
		</tr>
    <tr>
      <td align="left" colspan="<?php echo $colspan; ?>"><?php echo $dil['ToplamSozcukSayisi']; ?> :  <b><?php
			echo "$toplam_sozcuk</b><br>";
			$ilk = 1+$baslangic;
      $son = $limit+$baslangic;

			if ($bulunan_sozcuk != 0)
			{
			  if ($son > $bulunan_sozcuk)
			  {
			    $kalan = $bulunan_sozcuk-($son-$limit);
				  $son = ($son-$limit)+$kalan;
			  }
			  echo $fonk->yerine_koy($dil['BulunanSozcukGosterilenSozcuk'],array($bulunan_sozcuk,$ilk,$son));
			}
			if (UYE_SEVIYE >= SOZCUK_EKLEME_IZIN)
			{
		  ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href="?sayfa=sozluk&islem=2"><?php echo $dil['SozcukEkle']; ?></a>&nbsp;]
			<?php
			}
			?>
		  </td>
		 
    </tr>
		<tr class="tablobaslik">
		  <td width="50%" align="center"><b><?php echo $ilk_baslik?></b></td>
		  <td width="50%" align="center"><b><?php echo $ikinci_baslik?></b></td>
			<?php 
			if (UYE_SEVIYE>5)
			echo '<td align="center" nowrap="nowrap"><b>'.$dil['Onay'].'</b></td>';
			?>	
		</tr>
		<?php
		 
		  if ($sozcuk_sayisi == 0) {
			echo '<tr><td align=center colspan=2><font color=#FF0000>'.$dil['KayitBulunamadi'].'</td></tr>';
		  }else{
			$renk = 0;
		    while ($sozlukveri = $soz_vt->fetchArray ()) {
			  $sozcukno   = $sozlukveri["sozcukno"];
			  $turkce     = $fonk->yazdir_duzen($sozlukveri["turkce"]);
			  $ingilizce  = $fonk->yazdir_duzen($sozlukveri["ingilizce"]);
				$onay       = $sozlukveri['onay'];
				if ($onay=='E')
				$onay_mesaj = '<font color="#008000">'.$dil['Onayli'].'</font>&nbsp;&nbsp;<a href="?sayfa=sozluk&islem=5&sozcukno='.$sozcukno.'">'.$dil['Kaldir'].'</a>';
				else
				$onay_mesaj = '<font color="#ff0000">'.$dil['Onaysiz'].'</font>&nbsp;&nbsp;<a href="?sayfa=sozluk&islem=5&sozcukno='.$sozcukno.'">'.$dil['Onayla'].'</a>';

			  $renk = $renk + 1;

			  if (($renk % 2) == 0) {
				  $sozluk_class = "renk1";
			  }else{
				  $sozluk_class = "renk2";
			  }
				echo '<tr class="'.$sozluk_class.'">';
			  if (UYE_SEVIYE >= SOZCUK_DUZENLEME_IZIN) {
				echo '<td width="50%" align="left" style="padding-left:5px"><a href="?sayfa=sozluk&islem=4&sozcukno='.$sozcukno.'" title="'.$dil['Duzenle'].'">'.${$ilk_sozcuk}.'</a></td>';
				echo '<td width="50%" align="left" style="padding-left:5px"><a href="?sayfa=sozluk&islem=4&sozcukno='.$sozcukno.'" title="'.$dil['Duzenle'].'">'.${$ikinci_sozcuk}.'</a></td>';
			  }else{
				echo "<tr class=\"$sozluk_class\">
				<td width=\"50%\" align=\"left\" style=\"padding-left:5px\">${$ilk_sozcuk}</td>";
				echo "<td width=\"50%\" align=\"left\" style=\"padding-left:5px\">${$ikinci_sozcuk}</td>";
			  }
				if (UYE_SEVIYE>5)
				echo '<td align="center" nowrap="nowrap">'.$onay_mesaj.'</td>';
        echo '</tr>';
		    }
		  }
		?>
		<tr>
		  <td colspan="<?php echo $colspan; ?>"><hr />&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="<?php echo $colspan; ?>" align="center">
		<?php
		/*SAYFA NUMARALARI*/
		 echo $fonk->sayfalama($limit,$bulunan_sozcuk,$sayfano,'?sayfa=sozluk&s=[sn]&sirala='.$sirala.'&harf='.$harf.'&aranan='.$aranan);
		/*SAYFA NUMARALARI BT*/
		?>
		  </td>
		</tr>
	  </table>
	</td>
  </tr>
</table>
<?php
//==============================================================
} elseif ($islem == 2) { //SOZCUK EKLEME BOLUMU
//==============================================================
if (UYE_SEVIYE < SOZCUK_EKLEME_IZIN)
{
  throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[SOZCUK_EKLEME_IZIN]),1);
  exit;
}
@ $k = intval($_GET['k']); 
@ $ingilizce = $fonk->yazdir_duzen(unserialize($_SESSION['sozcuk']['ingilizce']));
@ $turkce    = $fonk->yazdir_duzen(unserialize($_SESSION['sozcuk']['turkce']));
?>
<table align="center" border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100%">
      <table border="0" cellpadding="2" cellspacing="2">
	      <tr>
		      <td align="center" colspan="2"><h1><a href="?sayfa=sozluk"><b><?php echo $dil['SOZCUK_EKLEME']; ?></b></a></h1></td>
		    </tr>
				<?php
				if ($k == 1)
				{
				?>
				<tr>
          <td colspan="2" align="center"><font color="#008000"><b><?php echo $dil['KayitIslemiTamamlandi']; ?></b></font></td>
        </tr>
        <?php
				}
				?>
        <form name="sozcukekleme" action="?sayfa=sozluk&islem=3" method="post">
		    <tr>
		      <td align="right"><?php echo $dil['IngilizceSozcuk']; ?> : </td>
		      <td align="left"><input type="text" name="ingilizce" size="30" maxlength="100" value="<?php echo $ingilizce; ?>"></td>
		    </tr>
        <tr>
          <td align="right"><?php echo $dil['TurkceSozcuk']; ?> : </td>
          <td align="left"><input type="text" name="turkce" size="30" maxlength="100" value="<?php echo $turkce; ?>"></td>
        </tr>
        <tr>
          <td colspan="2" align="center"><input type="submit" class="input" value="::: <?php echo $dil['KAYDET']; ?> :::"></td>
        </tr>
        </form>
        <tr>
          <td colspan="2" align="center"><a href="?sayfa=sozluk"><b><?php echo $dil['Sozluk']; ?></b></a></td>
        </tr>
	  </table>
	</td>
  </tr>
</table>
<?php
unset($_SESSION['sozcuk'],$ingilizce,$turkce);
//======================================================
} elseif ($islem == 3) { //SOZCUK KAYIT BOLUMU
//======================================================
  @ $sozcukno  = intval($_POST['sozcukno']);
  @ $turkce    = $fonk->post_duzen($_POST['turkce']);
  @ $ingilizce = $fonk->post_duzen($_POST['ingilizce']);
  @ $t = intval($_GET['t']);
	if (empty($t))
	{
	  //Ilk Kez Kayit Yapiliyorsa
	  $_SESSION['sozcuk']['ingilizce'] = serialize($ingilizce);
	  $_SESSION['sozcuk']['turkce']    = serialize($turkce);
	} else {
	  //Var Olan Bir Kayit Yine de Eklenmek Isteniyorsa
		@ $tekrar_ingilizce = $fonk->yazdir_duzen(unserialize($_SESSION['sozcuk']['ingilizce']));
		if ($ingilizce != $tekrar_ingilizce)
		{
		  throw new Exception($dil['IslemBasarisiz'],2);
			exit;
		}
	}
  if (UYE_SEVIYE == 0)
	{
	  $_SESSION['sayfaadi'] = serialize($sayfa);
    header('Location: ?sayfa=giris&hata=15');
		exit;
  } 
	if (empty($sozcukno))
	{
	  //KAYIT KONTROL
    if (UYE_SEVIYE < SOZCUK_EKLEME_IZIN)
	  {
	    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[SOZCUK_EKLEME_IZIN]),1);
		  exit;
	  }
		$buton_adi = $dil['KAYDET'];
	} else {
	  //DUZEN KONTROL
    if (UYE_SEVIYE < SOZCUK_DUZENLEME_IZIN)
	  {
	    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[SOZCUK_DUZENLEME_IZIN]),1);
		  exit;
	  }
		$buton_adi = $dil['Duzenle'];
	}
  $mesaj = '';
	if (UYE_SEVIYE >= SOZCUK_ONAY) 
  {
	  $sozcukonay = 'E';
	} else {
	  $sozcukonay = 'H';
	  $mesaj = '<br />'.$dil['YoneticiOnayiGerekiyor'];
  }

  if (!$turkce || !$ingilizce) 
    {
      throw new Exception($dil['BosAlanBirakmayiniz'],2);
			exit;
    } else {
      $sozvt = new Baglanti();
			if (empty($sozcukno))
      {
			  $sozvt->query("SELECT sozcukno,ingilizce,turkce FROM ".TABLO_ONEKI."sozluk WHERE ingilizce='$ingilizce' AND turkce='$turkce'");
			} else {
			  $sozvt->query("SELECT sozcukno,ingilizce,turkce FROM ".TABLO_ONEKI."sozluk WHERE ingilizce='$ingilizce' AND turkce='$turkce' AND sozcukno<>$sozcukno");
			}
			
			$sozcuk_tam_var = $sozvt->numRows();
      if ($sozcuk_tam_var > 0)
      {
        echo '<table align="center" border="0" cellpadding="2" cellspacing="0" width="100%">
        <tr>
        <td width="100%">
        <table border="0" cellpadding="2" cellspacing="2">
				<tr>
				  <td align="center" colspan="2"><font color=#FF0000>'.$fonk->hata_mesaj($dil['SozcukSistemdeKayitli'],false,'<a href="?sayfa=sozluk&islem=2">'.$dil['GeriDon'].'</a>').'</td>
				</tr>
				<tr class="tablobaslik">
		    <td width="45%" align="left"><b>'.$dil['Ingilizce'].'</b></td>
		    <td width="45%" align="left"><b>'.$dil['Turkce'].'</b></td>';
				if (UYE_SEVIYE >= SOZCUK_DUZENLEME_IZIN)
				  echo '<td width="5%" align="left"><b>'.$dil['Duzenle'].'</b></td>';
		    echo '</tr>';
          while ($sozcuk_tam_veri = $sozvt->fetchArray())
          { 
            $vingilizce = $sozcuk_tam_veri["ingilizce"];
            $vturkce = $sozcuk_tam_veri["turkce"];
            $vsozcukno = $sozcuk_tam_veri["sozcukno"];
            echo '<tr><td align="left">'.$vingilizce.'</td><td align="left">'.$vturkce.'</td>';
						if (UYE_SEVIYE >= SOZCUK_DUZENLEME_IZIN)
						echo '<td align="center"><a href="?sayfa=sozluk&islem=4&sozcukno='.$vsozcukno.'">'.$dil['Duzenle'].'</a></td>';
						echo '</tr>';
          }
          echo "
          </table></td></tr></table>";
      } else {
				$sozcuk_var = 0;
				//Kayit Formundan Gelen Sozcukler Once Kontrol Ediliyor Eger Ayni Kayittan Varsa ve Yinede Eklenmek Istenirse Kaydediliyor
				if ($t!=1)
				{
				  if (empty($sozcukno))
          $sozvt->query("SELECT sozcukno,turkce,ingilizce FROM ".TABLO_ONEKI."sozluk WHERE ingilizce LIKE '$ingilizce'");
					else
					$sozvt->query("SELECT sozcukno,turkce,ingilizce FROM ".TABLO_ONEKI."sozluk WHERE ingilizce LIKE '$ingilizce' AND sozcukno<>$sozcukno");
          $sozcuk_var = $sozvt->numRows();
				}
        if ($sozcuk_var > 0)
        {
        ?>
				  <table align="center" border="0" cellpadding="2" cellspacing="0" width="100%">
          <tr>
          <td width="100%">
          <table width="100%" border="0" cellpadding="2" cellspacing="2">
				  <tr>
				  <td align="center" colspan="2"><?php echo $fonk->hata_mesaj($dil['SozcukSistemdeKayitli'].'<br />'.$dil['SozcuguTekrarKaydetmekIstiyormusunuz'],false,'<a href="?sayfa=sozluk&islem=2">'.$dil['GeriDon'].'</a>'); ?></td>
				  </tr>
					<tr>
					<td align="center" colspan="2"><b><?php echo $dil['KayitliSozcuk']; ?></b></td>
					</tr>
					</table>
					<table width="100%">
				  <tr class="tablobaslik">
		      <td width="45%" align="left"><b><?php echo $dil['Ingilizce']; ?></b></td>
		      <td width="45%" align="left"><b><?php echo $dil['Turkce']; ?></b></td>
					<?php
				  if (UYE_SEVIYE >= SOZCUK_DUZENLEME_IZIN)
				  echo '<td width="5%" align="left"><b>'.$dil['Duzenle'].'</b></td>';
					?>
		      </tr>
					<?php
          while ($sozcuk_veri = $sozvt->fetchArray())
					{
            $eingilizce = $sozcuk_veri["ingilizce"];
            $eturkce = $sozcuk_veri["turkce"];
            $esozcukno = $sozcuk_veri["sozcukno"];
            echo '<tr><td align="left">'.$eingilizce.'</td><td align="left">'.$eturkce.'</td>';
						if (UYE_SEVIYE >= SOZCUK_DUZENLEME_IZIN)
						echo '<td align="center"><a href="?sayfa=sozluk&islem=4&sozcukno='.$esozcukno.'">'.$dil['Duzenle'].'</a></td>';
						echo '</tr>';
          }
					?>
         </table>
				 <table align="center" width="100%">
				   <form name="sozcukekleme" action="?sayfa=sozluk&islem=3&t=1" method="post">
					 <input type="hidden" name="ingilizce" value="<?php echo $ingilizce; ?>" />
           <input type="hidden" name="turkce" value="<?php echo $turkce; ?>" />
						<tr>
				      <td align="center" colspan="2"><b><?php echo $dil['EklemekIstediginizSozcuk']; ?></b></td>
						</tr>
						<tr class="tablobaslik">
		        <td width="40%" align="left"><b><?php echo $dil['Ingilizce']; ?></b></td>
		        <td width="40%" align="left"><b><?php echo $dil['Turkce']; ?></b></td>
						<td width="10%" align="center"></td>
						</tr>
            <tr>
				      <td align="left"><?php echo $ingilizce; ?></td>
							<td align="left"><?php echo $turkce; ?></td>
							<td align="center" nowrap="nowrap"><a href="?sayfa=sozluk&islem=2"><?php echo $dil['GeriDon']; ?></a></td>
						</tr>
            <tr>
						  <td align="center" colspan="2"><input type="submit" value="::: <?php echo $buton_adi; ?> :::"></td>
						</tr>
            </form>
						</table>
              </td>
						</tr>
					</table>
				<?php
      } else {
			  if (empty($sozcukno))
				{
          $sozvt->query2("INSERT INTO ".TABLO_ONEKI."sozluk (turkce, ingilizce, uyeno, tarih, onay) VALUES ('$turkce', '$ingilizce', ".UYE_NO.", NOW(), '$sozcukonay')");
				  
          throw new Exception($dil['KayitIslemiTamamlandi'].$mesaj,3);
				} else {
				  $soz_vt->query2("UPDATE ".TABLO_ONEKI."sozluk SET turkce='$turkce', ingilizce='$ingilizce' WHERE sozcukno=$sozcukno");
					unset($_SESSION['sozcuk']);
          throw new Exception($dil['DuzenlemeIslemiTamamlandi'],5);
				}
      }
    }
		}

//========================================================
} elseif ($islem == 4) { //SOZCUK DUZENLEME BOLUMU
//========================================================
  @ $sozcukno = intval($_GET['sozcukno']);
  if (UYE_SEVIYE >= SOZCUK_DUZENLEME_IZIN) 
  {
    if (!$sozcukno) 
    {
      throw new Exception($dil['IslemBasarisiz']);
    } else {
      $soz_vt = new Baglanti();
      $soz_vt->query("SELECT sozcukno, turkce, ingilizce, uyeno, tarih FROM ".TABLO_ONEKI."sozluk WHERE sozcukno=$sozcukno");
      $sozcuk_veri = $soz_vt->fetchArray();
      $turkce = $sozcuk_veri["turkce"];
      $ingilizce = $sozcuk_veri["ingilizce"];
      $sozcukekleyen = $fonk->uye_adi($sozcuk_veri["uyeno"]);
      $sozcukeklemetarihi = $sozcuk_veri["tarih"];
      $turkce = $fonk->yazdir_duzen($turkce);
      $ingilizce = $fonk->yazdir_duzen($ingilizce);
?>
<table cellspacing="0" cellpadding="0" width="98%" align="center">
  <tr>
    <td width="100%" align="center">
      <table width="100%" align="center">
        <tr>
          <td width="100%" colspan="8" align="center"><h1><?php echo $dil['SOZCUK_DUZENLEME']; ?></h1></td>
        </tr>
        <form class="ara" name="sozcukduzen" action="?sayfa=sozluk&islem=3" method="post">
        <input type="hidden" name="sozcukno" value="<?php echo $sozcukno; ?>">
        </tr>
          <td align="right"><?php echo $dil['IngilizceSozcuk']; ?> : </td>
          <td align="left"><input type="text" name="ingilizce" size="30" value="<?php echo $ingilizce; ?>"></td>
        </tr>
        <tr>
          <td align="right"><?php echo $dil['TurkceSozcuk']; ?> : </td>
          <td align="left"><input type="text" name="turkce" size="30" value="<?php echo $turkce; ?>"></td>
        <tr>
          <td colspan=2 align=center><b><?php echo $dil['Ekleyen']; ?> :</b> <?php echo $sozcukekleyen." - ".$fonk->duzgun_tarih_saat($sozcukeklemetarihi); ?></td>
        </tr>
        <tr>
          <td colspan="2" align="center"><input type="submit" class="input" value="::: <?php echo $dil['Duzenle']; ?> :::"></td>
        </tr>
				</form>
      </table>
    </td>
  </tr>
</table>
<?php
}
}else{
	throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler['SOZCUK_DUZENLEME_IZIN']));
  exit;
}


//==============================================================
}elseif ($islem == 5) { //SOZCUK ONAYLAMA/ONAY KALDIRMA
//==============================================================
	@ $sozcukno = intval($_GET['sozcukno']);

  if (UYE_SEVIYE > 5)  
  {
    if (empty($sozcukno)) 
    {
      throw new Exception($dil['IslemBasarisiz']);
			exit;
    } else {
      $soz_vt = new Baglanti();
			$soz_vt->query("SELECT onay FROM ".TABLO_ONEKI."sozluk  WHERE sozcukno=".$sozcukno."");
			$sozcuk_onay_veri = $soz_vt->fetchObject();
			$sozcuk_onay = $sozcuk_onay_veri->onay;

			if ($sozcuk_onay=='H')
			$onay = 'E';
			else
			$onay = 'H';

			$soz2_vt = new Baglanti();
			$soz2_vt->query("UPDATE ".TABLO_ONEKI."sozluk SET onay='".$onay."' WHERE sozcukno=$sozcukno");
      throw new Exception($dil['IslemTamamlandi'],5);
    }
  } else {
    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler['SOZCUK_DUZENLEME_IZIN']));
    exit;
  }
//==============================================================
}  //ISLEM 5 SONU
//==============================================================
} //try Sonu
catch (Exception $e)
{
$hatakod = $e->getCode();
if (empty($hatakod))
{
  $hata  = false;
  $adres = '<a href="index.php">'.$dil['Tamam'].'</a>';
} elseif ($hatakod==1) {
  $hata  = false;
  $adres = '<a href="index.php?sayfa=sozluk">'.$dil['Tamam'].'</a>';
} elseif ($hatakod==2) {
  $hata  = false;
  $adres = '<a href="index.php?sayfa=sozluk&islem=2">'.$dil['Tamam'].'</a>';
} elseif ($hatakod==3) {
  $hata  = true;
  $adres = '<a href="index.php?sayfa=sozluk">'.$dil['Tamam'].'</a>';
} elseif ($hatakod==5) {
  $hata  = true;
  $adres = '<a href="index.php?sayfa=sozluk&sozcukno='.$sozcukno.'">'.$dil['Tamam'].'</a>';
} else {
	$adres = '<a href="index.php?sayfa=yaziekle">'.$dil['Tamam'].'</a>';
	$hata = true;
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
}//catch Sonu
?>
</body>
</html>