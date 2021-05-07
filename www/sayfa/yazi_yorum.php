<?php 
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;
//=======================================================
try
{ // try Başlangıcı
//=======================================================
@ $sayfano     = abs(intval($_REQUEST['s']));
@ $kategorino  = intval($_REQUEST['kategori']);
@ $yazino      = intval($_REQUEST['yazino']);
@ $aranan_yazi = $fonk->post_duzen($_REQUEST['yaziara']);
@ $uyeno       = intval($_REQUEST['uyeno']);
//=====================================================
if (empty($islem) || $islem == 1) { //Yazi Listesi
//=====================================================
$arama_kosul = '';
$aranan      = '';
if (UYE_SEVIYE >= YAZI_OKUMA_IZIN)
{
  if (empty($aranan_yazi) && empty($uyeno) && empty($kategorino))
  {
    $arama_mesaj = '<b>'.$dil['TumYazilarGosteriliyor'].'</b>';
	}
  if (empty($aranan_yazi))
  {
    $arama_mesaj = '<b>'.$dil['TumYazilarGosteriliyor'].'</b>';
  } else {
    $aranan_yazi = eregi_replace(" {1,}","+",$aranan_yazi);
    $aranan_kelimeler = explode('+',$aranan_yazi);
    $aranan_kelime_sayisi = count($aranan_kelimeler);
    $arama_kosul = "AND (y.baslik LIKE '%$aranan_kelimeler[0]%'";
    for ($i=0; $i<$aranan_kelime_sayisi; $i++)
    {
      $aranan_kelime = $aranan_kelimeler[$i];
      $arama_kosul .= " OR y.baslik LIKE '%$aranan_kelime%' OR y.yazi LIKE '%$aranan_kelime%'";
    }
    $arama_kosul .= ")";
    $aranan      = '&yaziara='.implode('+',$aranan_kelimeler);
    $arama_mesaj = '<b>'.$dil['ArananSozcukler'].' :</b> '.$aranan_yazi;
    unset($aranan_kelimeler,$aranan_kelime_sayisi,$aranan_kelime);
  }
	if ($uyeno>0)
	{
	  $arama_kosul .= " AND y.uyeno=$uyeno";
		$aranan      .= "&uyeno=$uyeno";
	}

  if (KATEGORI_SAYI > 0)
  {
    if ($kategorino>0)
    {
      //Alt Kategoriler Diziye Aliniyor
      $altkategori_dizi = $fonk->kategoriIdListe($kategorino);
      if (count($altkategori_dizi)>0)
      $arama_kosul .= " AND y.kategorino IN (".$kategorino.",".implode(',',$altkategori_dizi).")";  //Alt Kategori Varsa Ana Kategori Ile Birlikte Sorgulaniyor
      else
      $arama_kosul .= ' AND y.kategorino='.$kategorino;  //Alt Kategori Yoksa Sadece Ana Kategori Sorgulaniyor
    }
  } else {
    $arama_kosul .= ' AND y.kategorino=0';
  }
?>
<table cellspacing="0" cellpadding="0" width="98%" align="center">
  <tr>
    <td width="100%" align="center">
      <table width="100%" align="center">
        <tr>
          <td width="100%" colspan="8" align="center"><h1><?php echo $dil['YAZI_YORUM']; ?></h1><?php echo $arama_mesaj; ?></td>
        </tr>
        <form name="yazi_ara" id="yazi_ara" action="?sayfa=yazi" method="post">
        <tr>
          <td width="100%" align="right">
          <?php
          if (KATEGORI_SAYI > 0)
          {
            echo '<select name="kategori" class="input">';
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
          &nbsp;&nbsp;
          <input type="text" name="yaziara" id="yaziara" class="input" maxlength="50" />
          <input type="submit" value="<?php echo $dil['ARA']; ?>" class="input" id="araButon" name="araButon" />
          <div>
          <b><a href="index.php">Ana Sayfa</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="?sayfa=yaziekle&kategori=<?php echo $kategorino; ?>"><?php echo $dil['YaziEkle']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="?sayfa=yazi"><?php echo $dil['ButunKonular']; ?></a></b></div></td>
        </tr>
        </form>
      </table>
      <form name="yazi_yorum" id="yazi_yorum" action="xml.php" method="post">
      <table width="99%" align="center" cellpadding="0">
        <tr>
          <td align="left" colspan="4">
            <?php
            if (KATEGORI_SAYI > 0)
            {
							echo '<b>'.$dil['Kategori'].' : </b>';
							if ($kategorino>0)
							{
							  echo '<a href="?sayfa=yazi&kategori='.$kategorino.'">'.$fonk->kategoriAdi($kategorino).'</a>';
								$altkategori_dizi = $fonk->kategoriSecListe($kategorino);
								foreach($altkategori_dizi as $altkategorino=>$altkategoriadi)
								{
									echo '&nbsp;&nbsp; <a href="?sayfa=yazi&kategori='.$altkategorino.'">»&nbsp;'.$altkategoriadi.'</a>';
								}
								$k_vt = new Baglanti();
                $k_vt->query("SELECT kategoriaciklama FROM ".TABLO_ONEKI."yazikategori WHERE kategorino=$kategorino");
								if ($kategori_aciklama = $k_vt->fetchObject()->kategoriaciklama)
								echo '<br />'.$kategori_aciklama;
								unset($k_vt);
							} else {
							  echo $dil['ButunKonular'];
							}
						} 
            ?>
          </td>
        </tr>
        <tr class="tablobaslik">
          <td align="center" width="5%" height="25"><b>SN</b></td>
          <td align="center" width="25%" height="25"><b><?php echo $dil['RESIM']; ?></b></td>
          <td align="center" width="50%" height="25"><b><?php echo $dil['YAZI']; ?></b></td>
          <td align="center" width="20%" height="25"><b><?php echo $dil['YAZAR'].' - '.$dil['TARIH']; ?></b></td>
        </tr>
        <?php
        $limit = 10;
				
        $toplamyazi = $vt->kayitSay("SELECT COUNT(y.yazino) FROM ".TABLO_ONEKI."yazilar AS y, ".TABLO_ONEKI."uyeler AS u WHERE  y.uyeno=u.uyeno AND y.onay='E' $arama_kosul");

        if(empty($sayfano) || $sayfano>ceil($toplamyazi/$limit)) 
        {                
          $sayfano = 1;                
          $baslangic = 0;        
        } else {               
          $baslangic = ($sayfano - 1) * $limit;        
        }

        $vt->query("SELECT u.uyeadi,y.yazino,y.resim,SUBSTRING(y.yazi,1,500) AS yazi,y.kategorino,y.uyeno,y.baslik,y.eklemetarihi,y.okunma,y.puan FROM ".TABLO_ONEKI."yazilar AS y, ".TABLO_ONEKI."uyeler AS u WHERE y.uyeno=u.uyeno AND y.onay='E' $arama_kosul ORDER BY y.eklemetarihi DESC LIMIT $baslangic,$limit");

        $toplam_yazi = $vt->numRows();  
        if ($toplam_yazi == 0)
        {
          echo '
          <tr>
            <td colspan="4" width="100%" align="center" height="30"><font color="#ff0000">'.$dil["KayitBulunamadi"].'</font></td>
          </tr>';
        } else {
          $ysayi = 0;
					$y_sira = 0;
          while ($yazi_bilgi  = $vt->fetchArray())
          {
            $yazi_no          = $yazi_bilgi["yazino"];
            $yazi_baslik      = $fonk->yazdir_duzen($yazi_bilgi["baslik"]);
            $yazi_okunma      = $yazi_bilgi["okunma"];
            $yazi_tarih       = $yazi_bilgi["eklemetarihi"];
            $yazi_yazar       = $yazi_bilgi["uyeadi"];
            $yazi_kategori    = $yazi_bilgi['kategorino'];
            $yazi_icerik      = $fonk->yazdir_duzen($yazi_bilgi['yazi']);
            $yazi_resim       = $yazi_bilgi['resim'];
						$yazi_puan        = $yazi_bilgi['puan'];
						$yazi_uyeno       = $yazi_bilgi['uyeno'];
						if (UYE_SEVIYE >= UYE_GORME_IZIN)
            $yazi_yazar = '<a href="?sayfa=uye&uye='.$yazi_uyeno.'">'.$yazi_yazar.'</a>';

            //Sadece Yazılari Aliniyor... BB Kodlari Atiliyor
            $yazi_icerik = preg_replace('(\[/?[^\]]+\])is',"",$yazi_icerik);
            $ysayi++;
            $y_sira           = $baslangic+$ysayi;

            $yazi_baslik      = substr($yazi_baslik,0,50).'...';
            $yazi_icerik      = substr($yazi_icerik,0,200).'...';
            $yazi_baslik      = wordwrap($yazi_baslik, 45, "\n",1);
            $yazi_icerik      = wordwrap($yazi_icerik, 60, "\n",1);
							
            $yorumlar         = $vt->kayitSay("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE yazino=$yazi_no AND onay='E'");

            if (($ysayi % 2) == 0)
            {
              $renk = ' class="renk1"';
            } else {
              $renk = ' class="renk2"';
            }
            $resim = RESIM_DIZIN.'/'.$yazi_resim;
            if (!file_exists($resim) || empty($yazi_resim))
            $resim = RESIM_DIZIN.'/bos.gif';
						
            ?>
						<input type="hidden" name="yazino[]" value="<?php echo $yazi_no; ?>" />
            <tr<?php echo $renk; ?>>
              <td width="5%" height="20" align="center"><?php echo $y_sira; ?></td>
              <td width="25%" align="center"><a href="?sayfa=yazi&yazino=<?php echo $yazi_no; ?>&islem=2"><img src="resim.php?resim=<?php echo $resim; ?>&en=130&boy=80" border="0" alt="<?php echo $yazi_baslik; ?>" title="<?php echo $yazi_baslik; ?>" align="absmiddle" /></a></td>
              <td width="50%" height="20" valign="top" align="left" style="padding-left:5px">&nbsp;<a href="?sayfa=yazi&yazino=<?php echo $yazi_no; ?>&islem=2"><font color="#3366ff"><strong><?php echo $yazi_baslik; ?></strong></font></a><br /><?php echo $yazi_icerik.'<br /><a href="?sayfa=yazi&yazino='.$yazi_no.'&islem=2"><b>'.$dil['TamaminiOku'].'</b></a>&nbsp;&raquo;&raquo;'; 
              if (KATEGORI_SAYI > 0)
							{
							  if (!$kategoriadi = $fonk->kategoriAdi($yazi_kategori))
								$kategoriadi = $dil['ButunKonular'];
							  echo '<br /><b>'.$dil['Kategori'].' : </b><a href="?sayfa=yazi&kategori='.$yazi_kategori.'">'.$kategoriadi.'</a>';
							}
              ?>
              </td>
              <td width="20%" align="center"><b><?php echo $yazi_yazar; ?></b><br /><?php echo str_replace(' ','<br />',$fonk->duzgun_tarih_saat($yazi_tarih,true)); ?><br />
              <b><?php echo $dil['Yorum']; ?> : </b><?php echo $yorumlar; ?><br />
              <b><?php echo $dil['Okunma']; ?> : </b><?php echo $yazi_okunma; 
							if (YAZI_OYLAMA=='E')
							echo '<br /><b>'.$dil['Puan'].' : </b>'.$yazi_puan; ?></td>
            </tr>
            <?php
          }
          ?>
          <tr>
            <td align="center" width="100%" colspan="4">
              <table width="100%" align="center">
                <tr>
                  <td colspan="4" width="100%" align="center"><?php echo $fonk->sayfalama($limit,$toplamyazi,$sayfano,'?sayfa=yazi&kategori='.$kategorino.'&s=[sn]'.$aranan); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <?php
          unset($toplam_yazi,$toplamyazi,$onceki_yazi,$sonraki_yazi,$yazi_no,$yazi_baslik,$yazi_okunma,$yazi_tarih,$yazi_yazar,$ysayi,$y_sira,$bg_color,$arama_kosul,$aranan,$aranan_yazi,$arama_mesaj);
        }
        $vt->freeResult();
				
        ?>
        </table>
				
        <table align="center" width="95%">
          <tr>
            <td align="right"><br /><img src="resim/rss.jpg" width="20" height="20" align="absmiddle" border="0" title="RSS" onclick="document.yazi_yorum.submit()" style="cursor:hand" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php"><b><?php echo $dil['AnaSayfa']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=yaziekle&kategori=<?php echo $kategorino; ?>"><b><?php echo $dil['YaziEkle']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=yazi"><b><?php echo $dil['TumYazilar']; ?></b></a><br />&nbsp;</td>
          </tr>
        </table>
				</form>
				<?php
				$yvt = new Baglanti();
				$yvt->query("SELECT y.uyeno,y.yazino,SUBSTRING(y.yorum,1,50) AS yorum,y.baslik,y.tarih FROM ".TABLO_ONEKI."yorumlar AS y, ".TABLO_ONEKI."yazilar AS yz WHERE y.yazino=yz.yazino AND y.onay='E' AND y.yazino>0 AND yz.onay='E' ORDER BY y.tarih DESC LIMIT 0,10");
				$yorum_sayi = $yvt->numRows();
				if ($yorum_sayi>0)
				{
				?>
				<table align="center" width="95%">
				<tr>
				  <td align="center" colspan="4"><h1><?php echo $dil['SON_YORUMLAR']; ?></h1></td>
				</tr>
				<tr class="tablobaslik">
          <td align="center" width="5%" height="25"><b>SN</b></td>
          <td align="center" width="15%" height="25"><b><?php echo $dil['YAZINO']; ?></b></td>
          <td align="center" width="50%" height="25"><b><?php echo $dil['YORUM']; ?></b></td>
          <td align="center" width="30%" height="25"><b><?php echo $dil['YAZAR'].' - '.$dil['TARIH']; ?></b></td>
        </tr>
				<?php
				$ysira = 0;
				while ($yorum_veri = $yvt->fetchObject())
				{
				  $ysira++;
				  $yorum_icerik = $fonk->yazdir_duzen($yorum_veri->yorum);
					$yorum_tarih  = $yorum_veri->tarih;
					$yorum_uyeno  = $yorum_veri->uyeno;
					$yorum_yazan  = $fonk->uye_adi($yorum_uyeno);
					$yorum_yazino = $yorum_veri->yazino;
					if (UYE_SEVIYE >= UYE_GORME_IZIN)
          $yorum_yazan = '<a href="?sayfa=uye&uye='.$yorum_uyeno.'">'.$yorum_yazan.'</a>, ';
					if (($ysira % 2) == 0)
          {
            $renk = 'renk1';
          } else {
            $renk = 'renk2';
          }
					echo '
					<tr class="'.$renk.'">
          <td align="center" width="5%" height="25">'.$ysira.'</td>
					<td align="center" width="15%" height="25"><a href="?sayfa=yazi&yazino='.$yorum_yazino.'&islem=2">'.$yorum_yazino.'</a></td>
          <td align="left" width="50%" height="25"><a href="?sayfa=yazi&yazino='.$yorum_yazino.'&islem=2">'.$yorum_icerik.'</a></td>
          <td align="center" width="30%" height="25" nowrap="nowrap">'.$yorum_yazan.'<br />'.$fonk->duzgun_tarih_saat($yorum_tarih,true).'</td>
        </tr> ';
				}
				?>
				<tr><td align="center" colspan="4"><a href="?sayfa=yorum"><?php echo $dil['TumYorumlar']; ?></a></td></tr>
				
			  </table>
				<?php
				} //Yorum if Kontrol Sonu
				$yvt->freeResult();
				unset($yvt);
				?>
      </td>
    </tr>
  </table>
	<table>
	<tr>
	<td align="center" width="50%" valign="top">
	<?php
	//EN COK OKUNAN YAZILAR
  $eyvt = new Baglanti();
  $eyvt->query("SELECT u.uyeadi,y.yazino,y.resim,SUBSTRING(y.yazi,1,500) AS yazi,y.kategorino,y.uyeno,y.baslik,y.eklemetarihi,y.okunma,y.puan FROM ".TABLO_ONEKI."yazilar AS y, ".TABLO_ONEKI."uyeler AS u WHERE y.uyeno=u.uyeno AND y.onay='E' ORDER BY okunma DESC LIMIT 0,10");
  $encok_okunan_yazi_sayi = $eyvt->numRows();
  if ($encok_okunan_yazi_sayi>0)
  {
  ?>
  <table align="center" width="95%">
    <tr>
      <td align="center" colspan="4"><h1><?php echo $dil['EnCokOkunanlar']; ?></h1></td>
    </tr>
    <tr class="tablobaslik">
      <td align="center" width="5%" height="25"><b>SN</b></td>
      <td align="center" width="15%" height="25"><b><?php echo $dil['YAZINO']; ?></b></td>
      <td align="center" width="50%" height="25"><b><?php echo $dil['YAZI']; ?></b></td>
      <td align="center" width="30%" height="25"><b><?php echo $dil['YAZAR'].' - '.$dil['TARIH']; ?></b></td>
    </tr>
    <?php
    $ysayi = 0;
    while ($yazi_bilgi  = $eyvt->fetchArray())
    {
      $yazi_no          = $yazi_bilgi["yazino"];
      $yazi_baslik      = $fonk->yazdir_duzen($yazi_bilgi["baslik"]);
      $yazi_okunma      = $yazi_bilgi["okunma"];
      $yazi_tarih       = $yazi_bilgi["eklemetarihi"];
      $yazi_yazar       = $yazi_bilgi["uyeadi"];
      $yazi_kategori    = $yazi_bilgi['kategorino'];
      $yazi_icerik      = $fonk->yazdir_duzen($yazi_bilgi['yazi']);

      $yazi_uyeno       = $yazi_bilgi['uyeno'];
      if (UYE_SEVIYE >= UYE_GORME_IZIN)
      $yazi_yazar = '<a href="?sayfa=uye&uye='.$yazi_uyeno.'">'.$yazi_yazar.'</a>';

      //Sadece Yazılari Aliniyor... BB Kodlari Atiliyor
      $yazi_icerik = preg_replace('(\[/?[^\]]+\])is',"",$yazi_icerik);
      $ysayi++;

      $yazi_baslik      = substr($yazi_baslik,0,50).'...';
      $yazi_baslik      = wordwrap($yazi_baslik, 45, "\n",1);

      if (($ysayi % 2) == 0)
      {
        $renk = 'renk1';
      } else {
        $renk = 'renk2';
      }
      echo '
      <tr class="'.$renk.'">
        <td align="center" width="5%" height="25">'.$ysayi.'</td>
        <td align="center" width="15%" height="25"><a href="?sayfa=yazi&yazino='.$yazi_no.'&islem=2">'.$yazi_no.'</a></td>
        <td align="left" width="15%" height="25" style="padding-left:5px"><a href="?sayfa=yazi&yazino='.$yazi_no.'&islem=2">'.$yazi_baslik.'</a></td>
        <td align="center" width="30%" height="25">'.$yazi_yazar.'<br />'.$fonk->duzgun_tarih_saat($yazi_tarih,true).'<br />'.$dil['Okunma'].':'.$yazi_okunma.'</td>
      </tr> ';
      }
      ?>
  </table>
  <?php
  } //if Kontrol Sonu
  $eyvt->freeResult();
  unset($eyvt);
	//================================================================================================================
  //EN COK OKUNAN YAZILAR SONU
	//================================================================================================================
	?>
	</td>
	<td align="center" width="50%" valign="top">
	<?php
	//================================================================================================================
	//EN COK PUAN ALAN YAZILAR
	//================================================================================================================

  $pyvt = new Baglanti();
  $pyvt->query("SELECT u.uyeadi,y.yazino,y.resim,SUBSTRING(y.yazi,1,500) AS yazi,y.kategorino,y.uyeno,y.baslik,y.eklemetarihi,y.okunma,y.puan FROM ".TABLO_ONEKI."yazilar AS y, ".TABLO_ONEKI."uyeler AS u WHERE y.uyeno=u.uyeno AND y.onay='E' ORDER BY puan DESC LIMIT 0,10");
  $encok_puanlanan_yazi_sayi = $pyvt->numRows();
  if ($encok_puanlanan_yazi_sayi>0)
  {
  ?>
  <table align="center" width="95%">
    <tr>
      <td align="center" colspan="4"><h1><?php echo $dil['EnCokPuanlananlar']; ?></h1></td>
    </tr>
    <tr class="tablobaslik">
      <td align="center" width="5%" height="25"><b>SN</b></td>
      <td align="center" width="15%" height="25"><b><?php echo $dil['YAZINO']; ?></b></td>
      <td align="center" width="50%" height="25"><b><?php echo $dil['YAZI']; ?></b></td>
      <td align="center" width="30%" height="25"><b><?php echo $dil['YAZAR'].' - '.$dil['TARIH']; ?></b></td>
    </tr>
    <?php
    $ysayi = 0;
    while ($yazi_bilgi  = $pyvt->fetchArray())
    {
      $yazi_no          = $yazi_bilgi["yazino"];
      $yazi_baslik      = $fonk->yazdir_duzen($yazi_bilgi["baslik"]);
      $yazi_tarih       = $yazi_bilgi["eklemetarihi"];
      $yazi_yazar       = $yazi_bilgi["uyeadi"];
      $yazi_kategori    = $yazi_bilgi['kategorino'];
      $yazi_puan        = $yazi_bilgi['puan'];
      $yazi_uyeno       = $yazi_bilgi['uyeno'];
      if (UYE_SEVIYE >= UYE_GORME_IZIN)
      $yazi_yazar = '<a href="?sayfa=uye&uye='.$yazi_uyeno.'">'.$yazi_yazar.'</a>';

      $ysayi++;

      $yazi_baslik      = substr($yazi_baslik,0,50).'...';
      $yazi_baslik      = wordwrap($yazi_baslik, 45, "\n",1);
      if (($ysayi % 2) == 0)
      {
        $renk = 'renk1';
      } else {
        $renk = 'renk2';
      }
      echo '
      <tr class="'.$renk.'">
        <td align="center" width="5%" height="25">'.$ysayi.'</td>
        <td align="center" width="15%" height="25"><a href="?sayfa=yazi&yazino='.$yazi_no.'&islem=2">'.$yazi_no.'</a></td>
        <td align="left" width="15%" height="25" style="padding-left:5px"><a href="?sayfa=yazi&yazino='.$yazi_no.'&islem=2">'.$yazi_baslik.'</a></td>
        <td align="center" width="30%" height="25">'.$yazi_yazar.'<br />'.$fonk->duzgun_tarih_saat($yazi_tarih,true).'<br />'.$dil['Puan'].':'.$yazi_puan.'</td>
      </tr> ';
      }
      ?>
  </table>
  <?php
  } //if Kontrol Sonu
  $pyvt->freeResult();
  unset($pyvt);
  //EN COK PUAN ALAN YAZILAR SONU
  ?>
	</td>
	</tr>
	</table>
	<?php
	} else {
    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[YAZI_OKUMA_IZIN]));
		exit;
  }
//==========================================
} elseif ($islem == 2) { // Yazi Ayrinti
//==========================================
  $vt = new Baglanti();
  if ($vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE yazino=$yazino") == 0)
  {
    throw new Exception($dil['IslemGecersiz']);
    exit;
  }
  if (UYE_SEVIYE >= YAZI_AYRINTI_OKUMA_IZIN)
  {
    @ $okundu = $_SESSION['okundu'];
    if ($okundu != $yazino)
    {
      //Okunma Sayisi Artiriliyor
      if (!$vt->query2("UPDATE ".TABLO_ONEKI."yazilar SET okunma=okunma+1 WHERE yazino=$yazino"))
      {
        throw new Exception($dil['IslemGecersiz']);
        exit;
      }
      $_SESSION['okundu'] = $yazino;
    }

    $vt->query("SELECT u.uyeno,u.uyeadi,u.resim AS uyeresim,y.yazino,y.kategorino,y.resim,y.baslik,y.yazi,y.eklemetarihi,y.okunma,y.puan FROM ".TABLO_ONEKI."yazilar AS y, ".TABLO_ONEKI."uyeler AS u WHERE y.onay='E' AND y.yazino=$yazino AND y.uyeno=u.uyeno");

    $yazi_sayi   = $vt->numRows();

    if ($yazi_sayi > 0)
    {
      $yazi_veri       = $vt->fetchObject();
      $yazi_uyeno      = $yazi_veri->uyeno;
      $yazi_no         = $yazi_veri->yazino;
      $yazi_kategorino = $yazi_veri->kategorino;
      $yazi_baslik     = $fonk->yazdir_duzen($yazi_veri->baslik);
      $yazi_yazar      = $yazi_veri->uyeadi;
      $yazi_icerik     = $fonk->yazdir_duzen($yazi_veri->yazi);
      $yazi_yazan      = $yazi_veri->uyeadi;
      $yazi_okunma     = $yazi_veri->okunma;
      $yazi_tarih      = $yazi_veri->eklemetarihi;
      $yazi_resim      = $yazi_veri->resim;
      $uye_resim       = $yazi_veri->uyeresim;
			$yazi_puan       = $yazi_veri->puan;
				
      $uyeresim = UYE_RESIM_DIZIN.'/'.$uye_resim;
      if (!file_exists($uyeresim) || empty($uye_resim))
      {
        $uyeresim = UYE_RESIM_DIZIN.'/bos.gif';
      }

      $resim = RESIM_DIZIN.'/'.$yazi_resim;
      if (!file_exists($resim) || empty($yazi_resim))
      {
        $resim = 0;
      }

      $yazi_baslik = wordwrap($yazi_baslik, 50, "\n",1);

      $yazi_icerik = $fonk->textWrap($yazi_icerik);
      //$yazi_icerik = wordwrap($yazi_icerik, 200, "\n",1);
      ?>
      <table width="98%" cellspacing="0" cellpadding="0">
        <tr>
          <td  align="center" valign="center"><h1><?php echo $dil['YAZI_YORUM']; ?></h1></td>
        </tr>
          <tr>
            <td align="right" style="padding-right:25px"><br /><a href="?sayfa=yazi"><b><?php echo $dil['TumYazilar']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=yaziekle&kategori=<?php echo $yazi_kategorino; ?>"><b><?php echo $dil['YaziEkle']; ?></b></a><br />&nbsp;</td>
          </tr>
					</table>
					
					<table width="98%" cellspacing="0" cellpadding="0" class="mesaj1">
          <tr>
            <td  align="left" width="100%" height="100%">
						<script language="javascript">
						function resimAc(yazino,en,boy)
            {
              window.open("resimac.php?yazino="+yazino,"","width="+en+",height="+boy);
            }
						</script>
						<?php
						if (!empty($resim))
						{
						  $resim_boyut = getimagesize($resim);
							$resim_en    = $resim_boyut[0];
							$resim_boy   = $resim_boyut[1];
							?>
						  <a href="javascript:resimAc('<?php echo $yazi_no; ?>','<?php echo $resim_en; ?>','<?php echo $resim_boy; ?>')"><img alt="<?php echo $yazi_baslik; ?>" src="resim.php?resim=<?php echo $resim; ?>&en=<?php echo YAZI_RESIM_EN; ?>&boy=<?php echo YAZI_RESIM_BOY; ?>" align="right" vspace="10px" hspace="5px" class="yaziresim" border="0" /></a>
						<?php
						}
						echo '<span style="font-size:12px"><b>'.$yazi_baslik.'</b></span><br /><br />';
						?>
						<?php echo nl2br($fonk->bb_html($yazi_icerik)); ?>
						</td>
          </tr>
          <tr>
            <td height="25">&nbsp;</td>
          </tr>
          <tr>
            <td align="center" colspan="2"><hr />
						<table>
						<tr>
						<td align="center"><img src="resim.php?resim=<?php echo $uyeresim; ?>&en=<?php echo UYE_RESIM_EN; ?>&boy=<?php echo UYE_RESIM_BOY; ?>" hspace="10"></td>
						<td align="left" valign="center" nowrap="nowrap">
						<?php 
						if (KATEGORI_SAYI > 0)
						{
							if (!$kategoriadi = $fonk->kategoriAdi($yazi_kategorino))
							$kategoriadi = $dil['ButunKonular'];
							echo '<b>'.$dil['Kategori'].' : </b><a href="?sayfa=yazi&kategori='.$yazi_kategorino.'">'.$kategoriadi.'</a><br />';
						}
						echo '<b>'.$dil['Yazar'].' :</b>&nbsp;'.$yazi_yazan.'<br /><b>'.$dil['Okunma'].' :</b>&nbsp;'.$yazi_okunma.'<br /><b>'.$dil['Tarih'].' :</b>&nbsp;'.str_replace(' ','<br />',$fonk->duzgun_tarih_saat($yazi_tarih,1));
            if (UYE_SEVIYE > 0)
            {
              $sure_asimi = $vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE eklemetarihi > DATE_SUB(NOW(), INTERVAL ".YAZI_DUZENLEME_SURESI." HOUR) AND uyeno=".UYE_NO." AND yazino=$yazi_no AND onay='E'");
           
              if ($sure_asimi > 0 || UYE_SEVIYE > 5)
              {
                echo '<br /><a href="?sayfa=yaziekle&yazino='.$yazi_no.'"><b>'.$dil['Duzenle'].'</b></a>';
              }
            }
            unset($yazi_veri,$yazi_baslik,$yazi_yazar,$yazi_icerik,$yazi_tarih,$yazi_okunma,$yazi_resim);
            echo '
            </font>';
						?>
						</td>
					  </tr>
						<?php
						if (YAZI_OYLAMA=='E')
						{
						?>
						<tr>
						  <td align="center" colspan="2">
							  <table width="100%" align="center">
									<tr>
									<?php
									$oys_vt = new Baglanti();
                  $oys_vt->query("SELECT uyeno FROM ".TABLO_ONEKI."yazipuan WHERE yazino=$yazino");
                  $oy_veri = $oys_vt->fetchObject();
                  @$oy_sayi_dizi = explode(',',$oy_veri->uyeno);
									$oy_sayisi    = count($oy_sayi_dizi);
									if ($oy_sayisi==1 && empty($oy_sayi_dizi[0]))
									$oy_sayisi = 0;
									@ $ortalama     = number_format(($yazi_puan/$oy_sayisi),2,',','');
									echo '<td colspan="2" align="center"><b>'.$dil['Puan'].' :</b> '.$yazi_puan.' &nbsp;&nbsp;<b>'.$dil['OylayanKisi'].' :</b> '.$oy_sayisi.'&nbsp;&nbsp;<b>'.$dil['Ortalama'].' :</b> '.$ortalama.'</td>';
									$oys_vt->freeResult();
									?>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
						  <td align="center" colspan="2">
							  <table>
								  <?php
									if (UYE_SEVIYE < 1)
									{
									?>
								  <tr>
									  <td align="center"><font color="#ff000"><?php echo $dil['OylamaIcinGirisGerekli']; ?></font></td>
									</tr>
									<?php
									} else {
									  if (in_array(UYE_NO,$oy_sayi_dizi))
									  {
										?>
										<tr>
									    <td align="center"><font color="#ff000"><?php echo $dil['DahaOnceOyKullandiniz']; ?></font></td>
									  </tr>
										<?php
									  } else {
									  ?>
                    <tr>
										<?php	
										for ($i=1; $i<11; $i++)
							      {
										  echo '<td align="center">'.$i.'</td>';
										}
										?>
									  </tr>
									  <tr>
									  <?php
										for ($i=1; $i<11; $i++)
							        {
							          echo '<td align="center"><input type="radio" name="oy" value="'.$i.'" onclick="location.href=\'?sayfa=yazi&yazino='.$yazino.'&islem=4&oy='.$i.'\';" /></td>';
							        }
										?>
									  </tr>
									<?php
									  }
									}
									unset($oys_vt,$oy_sayi_dizi,$oy_sayisi,$ortalama);
									?>
								</table>
							</td>
						</tr>
						<?php
						}
						?>
				    </table>
            </td>
          </tr>
					</table>
					
          <table width="98%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2" align="right" style="padding-right:25px"><br /><a href="?sayfa=yazi"><b><?php echo $dil['TumYazilar']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=yaziekle&kategori=<?php echo $yazi_kategorino; ?>"><b><?php echo $dil['YaziEkle']; ?></b></a></td>
          </tr>
        </table>
        <br />
				<?php
        $vt->query("SELECT u.uyeadi,k.baslik,k.yorum,k.uyeno,k.tarih FROM ".TABLO_ONEKI."yazilar AS y, ".TABLO_ONEKI."yorumlar AS k, ".TABLO_ONEKI."uyeler AS u WHERE k.yazino=$yazi_no AND k.onay='E' AND k.yazino=y.yazino AND k.uyeno=u.uyeno ORDER BY tarih DESC");

        $yorum_sayi   = $vt->numRows();
        
				?>
        <table align="center" cellpadding="0" cellspacing="0" width="95%">
          <tr>
            <td align="center" valign="center" colspan="2" height="20"><?php echo $dil['Yorum'].' : ('.$yorum_sayi.')'; ?></td>
          </tr>
          <tr>
            <td align="center">
              <table width="100%" class="tablolar">
              <?php
              if ($yorum_sayi > 0)
              {
                for ($y=0; $y<$yorum_sayi; $y++)
                {
                  $yorum_veri   = $vt->fetchObject();
                  $yorum_baslik = $fonk->yazdir_duzen($yorum_veri->baslik);
                  $yorum_mesaj  = $fonk->yazdir_duzen($yorum_veri->yorum);
                  $yorum_yazan  = $yorum_veri->uyeadi;
                  $yorum_tarih  = $yorum_veri->tarih;

									$yorum_baslik = wordwrap($yorum_baslik, 40, "<br />",1);
                  //$yorum_mesaj = wordwrap(nl2br($yorum_mesaj), 80, "<br />",1);

                  ?>
									<tr>
                  <td align="left" style="border:1px solid #000000">
                    <table width="100%">
                      <tr class="tablobaslik">
                        <td width="100%" height="20" valign="bottom"><?php echo $yorum_baslik; ?></td>
                      </tr>
                      <tr>
                        <td align="left" colspan="2"><?php echo nl2br($yorum_mesaj); ?></td>
                      </tr>
                      <tr>
                        <td align="right"><?php echo $yorum_yazan.' <b>|</b> '.$fonk->duzgun_tarih_saat($yorum_tarih,true); ?></td>
                      </tr>
                    </table>
                  </td>
                  </tr>
								<?php
                }
              } else {
					      echo '<tr><td align="center" colspan="2" height="35"><span class="fonthata">'.$dil['KatilimYapilmamis'];
						if (YORUM_EKLEME_IZIN == 'E') echo '<br />'.$dil['IlkKatilimiYapiniz'];
						echo '</span></td></tr>';
					}
					
					?>
          </table>
        </td>
      </tr>
    </table>
		<?php
		if (UYE_SEVIYE > 0)
		{
		  if (UYE_SEVIYE >= YORUM_EKLEME_IZIN)
		  {
			@ $yorumbaslik = unserialize($_SESSION['yorum']['baslik']);
			@ $yorummesaj  = unserialize($_SESSION['yorum']['mesaj']);
      ?>
        <br />
        <form name="yorum_yaz" id="yorum_yaz"  action="?sayfa=yazi&islem=3&yazino=<?php echo $yazino; ?>" method="post">
        <input type="hidden" name="yorumyazino" id="yorumyazino" value="<?php echo $yazi_no; ?>" />
        <table align="center" cellpadding="0" cellspacing="0" width="500">

          <tr>
            <td width="35%" height="20" align="right" valign="middle"><b><?php echo $dil['KullaniciAdiniz']; ?>&nbsp;&nbsp;:</b></td>
            <td width="65%" height="20" valign="middle" align="left">&nbsp;<?php echo UYE_KULLADI; ?></td>
          </tr>
          <tr>
            <td width="35%" height="20" align="right" valign="middle"><b><?php echo $dil['Baslik']; ?>&nbsp;&nbsp;:</b></td>
            <td width="65%" height="20" valign="middle" align="left">&nbsp;<input type="text" class="input" id="yorumbaslik" style="width: 230px" tabindex="1" name="yorumbaslik" maxlength="100" value="<?php echo $yorumbaslik; ?>" /></td>
          </tr>
          <tr>
            <td width="35%" align="right" valign="top"><b>* <?php echo $dil['Yorum']; ?>&nbsp;&nbsp;:</b></td>
            <td width="65%" align="left" valign="top">&nbsp;<textarea name="yorummesaj" id="yorummesaj" cols="40" rows="7" onkeyup="karakter_sayi_kontrol('yorummesaj',<?php echo YORUM_KARAKTER; ?>);" tabindex="3"><?php echo $yorummesaj; ?></textarea></td>
          </tr>
					<tr>
             <td width="35%" align="right" valign="top">&nbsp;</td>
             <td width="65%" height="25" align="left"><input type="text" name="yorummesaj_sayac" id="yorummesaj_sayac" size="5" value="<?php echo YORUM_KARAKTER; ?>" />
           </tr>
          <tr>
            <td align="center" colspan="2" width="100%"  height="20"><input type="submit" name="yorumGonder" id="yorumGonder" tabindex="3" value=" <?php echo $dil['KAYDET']; ?> " border="0" class="input" /></td>
          </tr>
          <tr>
            <td align="center" colspan="2" width="100%"  height="20"><br />
            <span class="fontmavi">
            <?php
            if (YORUM_ONAY == 1)
            {
              echo $dil['YorumHemenYayinlanacak'];
            } elseif (YORUM_ONAY == 2) {
              echo $dil['YorumOnaydanSonraYayinlanacak'];
            }
            ?>
            </span></td>
          </tr>
        </table>
      </form>
			<?php
      unset($_SESSION['yorum']);
		  } else {
		    throw new Exception($dil['YorumEklemeIzninizYok']);
		  }
		} else {
		  $_SESSION['sayfaadi'] = serialize($sayfa.'&yazino='.$yazi_no.'&islem=2');
		  throw new Exception($dil['YorumIcinGirisGerekli'],3);
		}
		echo '<br />';
  } else {
    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],@$seviyeler[YAZI_AYRINTI_OKUMA_IZIN]));
		exit;
	}
  $vt->freeResult();
	unset($yazi_sayi,$yno,$yorum_mesaj,$yorum_baslik,$yorum_veri,$yorum_yazan,$yorum_tarih);
	} else {
	  throw new Exception($dil['UyeSeviyeYetersiz']);
		exit;
	}
	unset($vt);
//==============================================
// 2. ADIM SONU
//==============================================
} elseif ($islem == 3) { // 3. ADIM BASLANGICI
//==============================================
@ $yorumyazino = intval($_POST['yorumyazino']);
@ $yorumbaslik = $fonk->post_duzen($_POST['yorumbaslik']);
@ $yorummesaj  = $fonk->post_duzen($_POST['yorummesaj']);
$_SESSION['yorum']['baslik'] = serialize($yorumbaslik);
$_SESSION['yorum']['mesaj']  = serialize($yorummesaj);

if (UYE_SEVIYE >= YORUM_EKLEME_IZIN)
{
  if (empty($yorummesaj))
  {
    throw new Exception($dil['IsaretliAlanlariBosBirakmayiniz'],1);
    exit;
  }
  if (strlen($fonk->yazdir_duzen($_POST['yorumbaslik'])) > 100)
  {
    throw new Exception($fonk->yerine_koy($dil['BaslikKarakterIzin'],100),1);
    exit;
  }
  if (strlen($fonk->yazdir_duzen($_POST['yorummesaj'])) > YORUM_KARAKTER)
  {
    throw new Exception($fonk->yerine_koy($dil['YaziKarakterIzin'],YORUM_KARAKTER),1);
    exit;
  }
  if ($vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE yazino=$yorumyazino") == 0)
  {
    throw new Exception($dil['IslemGecersiz']);
    exit;
  }
  if ($vt->kayitSay("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE tarih>DATE_SUB(NOW(),INTERVAL ".YORUM_ARASI_SURE." MINUTE) AND yazino=$yorumyazino AND uyeno=".UYE_NO."") > 0)
  {
    throw new Exception($fonk->yerine_koy($dil['IslemIcinBeklemenizGerekiyor'],YORUM_ARASI_SURE));
    exit;
  } else {
	  unset($_SESSION['yorum']);
    if (UYE_SEVIYE >= YORUM_ONAY)
    {
      $yorumonay = 'E';
      $kayit_mesaj = $dil['KayitIslemiTamamlandi'];
    } else {
      $yorumonay = 'H';
      $kayit_mesaj = $dil['KayitIslemiTamamlandi'].'\n'.$dil['YoneticiOnayiGerekiyor'];
    }
    
    if ($vt->query2("INSERT INTO ".TABLO_ONEKI."yorumlar (`yazino`,`uyeno`,`baslik`,`yorum`,`tarih`,`onay`) VALUES ($yorumyazino,".UYE_NO.",'".$vt->escapeString($yorumbaslik)."','".$vt->escapeString($yorummesaj)."',NOW(),'$yorumonay')"))
    {
      if ($yorumonay == 'E')
      {
        $fonk->yorum_eposta_bilgi($yorumyazino,UYE_NO);
      }
      throw new Exception($kayit_mesaj,2);
    } else {
      throw new Exception($dil['IslemBasarisiz']);
      exit;
    }
  }
} else {
  throw new Exception ($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],@$seviyeler[YORUM_EKLEME_IZIN]));
}
unset($vt,$yorumyazino,$yorummesaj,$yorumbaslik,$yorumonay);
//=============================================================
} elseif ($islem == 4) {// 3. ADIM SONU 4. ADIM BASLANGICI
//=============================================================
@ $yazino = intval($_GET['yazino']);
@ $oy     = intval($_GET['oy']);

if (UYE_SEVIYE<1)
{
  throw new Exception($dil['OylamaIcinGirisGerekli']);
	exit;
}
if (UYE_SEVIYE >= YAZI_AYRINTI_OKUMA_IZIN)
{
$yazi_vt = new Baglanti();
$oy_vt   = new Baglanti();
$oy_vt2  = new baglanti();
if ($yazi_vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE yazino=$yazino") == 0)
{
  throw new Exception($dil['IslemGecersiz']);
  exit;
}
if ($oy>10 || $oy<1)
{
  throw new Exception($dil['IslemGecersiz'],1);
	exit;
}
if (YAZI_OYLAMA!='E')
{
  throw new Exception($dil['IslemGecersiz'],1);
	exit;
}
$yazi_vt->query("SELECT puan FROM ".TABLO_ONEKI."yazilar WHERE yazino=$yazino");
$yazi_veri = $yazi_vt->fetchObject();
$yazi_puan = $yazi_veri->puan;
unset($yazi_veri);
$yazi_vt->freeResult();
if ($yazi_vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazipuan WHERE yazino=$yazino") == 0)
{
  $oy_vt->query2("INSERT INTO ".TABLO_ONEKI."yazipuan (yazino,uyeno) VALUES($yazino,".UYE_NO.")");
	
  $oy_vt2->query2("UPDATE ".TABLO_ONEKI."yazilar SET puan=($yazi_puan+$oy) WHERE yazino=$yazino");
  throw new Exception($dil['IslemTamamlandi'],2);
  exit;
} else {
  if ($yazi_vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazipuan WHERE ".UYE_NO." IN (uyeno)")>0)
  {
    throw new Exception($dil['DahaOnceOyKullandiniz']);
    exit;
  } else {
    $oy_vt->query2("UPDATE ".TABLO_ONEKI."yazipuan AS yp SET yp.uyeno=CONCAT(yp.uyeno,',".UYE_NO."') WHERE yp.yazino=$yazino");
    $oy_vt2->query2("UPDATE ".TABLO_ONEKI."yazilar SET puan=($yazi_puan+$oy) WHERE yazino=$yazino");
    throw new Exception($dil['IslemTamamlandi'],2);
    exit;
  }
}
} else {
  throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[YAZI_AYRINTI_OKUMA_IZIN]));
  exit;
}
//=============================================================
} // 4. ADIM SONU
//=============================================================

} // try Sonu
//=========================================
catch (Exception $e)
{
  $hatakod = $e->getCode();
  if ($hatakod == 1)
  {
    $adres = '<a href="index.php?sayfa=yazi&islem=2&yazino='.$yazino.'">'.$dil['Tamam'].'</a>';
    $hata  = false;
  } elseif ($hatakod == 2) {
    $adres = '<a href="index.php?sayfa=yazi&islem=2&yazino='.$yazino.'">'.$dil['Tamam'].'</a>';
    $hata = true;
	} elseif ($hatakod == 3) {
	  $adres = '<a href="index.php?sayfa=giris">'.$dil['Tamam'].'</a>';
		$hata = false;
  } else {
	  unset($_SESSION['yorum']);
    $adres = '<a href="index.php?sayfa=yazi">'.$dil['Tamam'].'</a>';
    $hata = false;
  }
	unset($oy_vt,$yazi_vt,$oy_vt2);
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
?>