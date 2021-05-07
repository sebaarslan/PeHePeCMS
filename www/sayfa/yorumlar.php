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
    $arama_kosul .= " AND (y.baslik LIKE '%$aranan_kelimeler[0]%'";
    for ($i=0; $i<$aranan_kelime_sayisi; $i++)
    {
      $aranan_kelime = $aranan_kelimeler[$i];
      $arama_kosul .= " OR y.baslik LIKE '%$aranan_kelime%' OR y.yorum LIKE '%$aranan_kelime%'";
    }
    $arama_kosul .= ")";
    $aranan      = '&yaziara='.implode('+',$aranan_kelimeler);
    $arama_mesaj = '<b>'.$dil['ArananSozcukler'].' :</b> '.$aranan_yazi;
    unset($aranan_kelimeler,$aranan_kelime_sayisi,$aranan_kelime);
  }
  if ($uyeno>0)
	{
	  $arama_kosul .= " AND yz.uyeno=$uyeno";
		$aranan      .= "&uyeno=$uyeno";
	}
  if (KATEGORI_SAYI > 0)
  {
    if ($kategorino == 0)
    {
      $kategorikosul = '';
    } else {
      //Alt Kategoriler Diziye Aliniyor
      $altkategori_dizi = $fonk->kategoriIdListe($kategorino);
      if (count($altkategori_dizi)>0)
      $arama_kosul  .= " AND yz.kategorino IN (".$kategorino.",".implode(',',$altkategori_dizi).")";  //Alt Kategori Varsa Ana Kategori Ile Birlikte Sorgulaniyor
      else
      $arama_kosul .= 'AND yz.kategorino='.$kategorino;  //Alt Kategori Yoksa Sadece Ana Kategori Sorgulaniyor
    }
  } else {
    $arama_kosul .= 'AND yz.kategorino=0';
  }
?>
<table cellspacing="0" cellpadding="0" width="98%" align="center">
  <tr>
    <td width="100%" align="center">
      <table width="100%" align="center">
        <tr>
          <td width="100%" colspan="8" align="center"><h1><?php echo $dil['YORUM']; ?></h1><?php echo $arama_mesaj; ?></td>
        </tr>
        <form name="yazi_ara" id="yazi_ara" action="?sayfa=yorum" method="post">
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
        <?php
        $limit = 25;
				
        $toplamyorum = $vt->kayitSay("SELECT COUNT(y.yorumno) FROM ".TABLO_ONEKI."yazilar AS yz, ".TABLO_ONEKI."yorumlar AS y WHERE y.yazino=yz.yazino AND yz.onay='E' ".$arama_kosul."");

        if(empty($sayfano) || $sayfano>ceil($toplamyorum/$limit)) 
        {                
          $sayfano = 1;                
          $baslangic = 0;        
        } else {               
          $baslangic = ($sayfano - 1) * $limit;        
        }

				$yvt = new Baglanti();
				$yvt->query("SELECT y.uyeno,y.yazino,SUBSTRING(y.yorum,1,50) AS yorum,y.baslik,y.tarih FROM ".TABLO_ONEKI."yorumlar AS y, ".TABLO_ONEKI."yazilar AS yz WHERE y.yazino=yz.yazino AND y.onay='E' AND y.yazino>0 AND yz.onay='E' ".$arama_kosul." ORDER BY y.tarih DESC LIMIT $baslangic,$limit");
				$yorum_sayi = $yvt->numRows();
				if ($yorum_sayi>0)
				{
				?>
				<tr class="tablobaslik">
          <td align="center" width="5%" height="25"><b>SN</b></td>
          <td align="center" width="15%" height="25"><b><?php echo $dil['YAZINO']; ?></b></td>
          <td align="center" width="50%" height="25"><b><?php echo $dil['YORUM']; ?></b></td>
          <td align="center" width="30%" height="25"><b><?php echo $dil['YAZAR'].' - '.$dil['TARIH']; ?></b></td>
        </tr>
				<?php
				$ysira = 0;
				$y_sira = 0;
				while ($yorum_veri = $yvt->fetchObject())
				{
				  $ysira++;
				  $yorum_icerik = $fonk->yazdir_duzen($yorum_veri->yorum);
					$yorum_tarih  = $yorum_veri->tarih;
					$yorum_uyeno  = $yorum_veri->uyeno;
					$yorum_yazan  = $fonk->uye_adi($yorum_uyeno);
					$yorum_yazino = $yorum_veri->yazino;
					if (UYE_SEVIYE >= UYE_GORME_IZIN)
          $yorum_yazan = '<a href="?sayfa=uye&uye='.$yorum_uyeno.'">'.$yorum_yazan.'</a>';
					if (($ysira % 2) == 0)
          {
            $renk = 'renk1';
          } else {
            $renk = 'renk2';
          }
          $y_sira           = $baslangic+$ysira;
					echo '
					<tr class="'.$renk.'">
          <td align="center" width="5%" height="25">'.$y_sira.'</td>
					<td align="center" width="15%" height="25"><a href="?sayfa=yazi&yazino='.$yorum_yazino.'&islem=2">'.$yorum_yazino.'</a></td>
          <td align="left" width="50%" height="25"><a href="?sayfa=yazi&yazino='.$yorum_yazino.'&islem=2">'.$yorum_icerik.'</a></td>
          <td align="center" width="30%" height="25" nowrap="nowrap">'.$yorum_yazan.'<br />'.$fonk->duzgun_tarih_saat($yorum_tarih,true).'</td>
        </tr> ';
				}
				?>
				<?php
				} //Yorum if Kontrol Sonu
				?>
		  <tr>
        <td align="center" width="100%" colspan="4">
          <table width="100%" align="center">
            <tr>
              <td colspan="4" width="100%" align="center"><?php echo $fonk->sayfalama($limit,$toplamyorum,$sayfano,'?sayfa=yorum&kategori='.$kategorino.'&s=[sn]'.$aranan); ?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
  </tr>
</table>
  <?php
	} else {
    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[YAZI_OKUMA_IZIN]));
		exit;
  }
//==========================================
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