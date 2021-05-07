<?php 
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;
//=======================================================
try
{ // try Başlangıcı
//=======================================================
@ $sayfano   = intval($_REQUEST['s']);
@ $albumno   = intval($_REQUEST['album']);
@ $resimno   = intval($_GET['resim']);
@ $asno      = intval($_GET['asno']);
@ $rsno      = intval($_GET['rsno']);
@ $sk        = intval($_GET['sk']);
//=====================================================
if (empty($islem) || $islem == 1) { //Yazi Listesi
//=====================================================
?>
<table cellspacing="0" cellpadding="0" width="98%" align="center">
  <tr>
    <td width="100%" align="center">
      <table width="100%" align="center">
        <tr>
          <td width="100%" colspan="8" align="center"><h1><?php echo $dil['RESIM_GALERISI']; ?></h1></td>
        </tr>
      </table>
      <form name="yazi_yorum" id="yazi_yorum">
      <table width="99%" align="center" cellpadding="0">
			  <?php
				$limit = 12;
        @ $sayfano = abs(intval($sayfano));
				
        $toplamalbum = $vt->kayitSay("SELECT COUNT(a.albumno) FROM ".TABLO_ONEKI."album AS a, ".TABLO_ONEKI."uyeler AS u WHERE a.uyeno=u.uyeno AND (a.onay='E' OR a.uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5)");

        if(empty($sayfano) || $sayfano>ceil($toplamalbum/$limit)) 
        {                
          $sayfano = 1;                
          $baslangic = 0;        
        } else {               
          $baslangic = ($sayfano - 1) * $limit;        
        }
				?>
        <tr>
          <td align="center" colspan="3"><b><?php echo $dil['TumAlbumlerGosteriliyor']; ?></b><br /><?php echo $dil['ToplamAlbum'].' : '.$toplamalbum; ?></td>
        </tr>
        <?php
        $vt->query("SELECT u.uyeadi,a.albumno,a.resim,a.albumadi,a.tarih,a.aciklama,a.onay FROM ".TABLO_ONEKI."album AS a, ".TABLO_ONEKI."uyeler AS u WHERE u.uyeno=a.uyeno AND (a.uyeno=".UYE_NO." OR a.onay='E' OR ".UYE_SEVIYE.">5) ORDER BY a.tarih DESC LIMIT $baslangic,$limit");
        $toplam_album = $vt->numRows();  
        if ($toplam_album == 0)
        {
          echo '
          <tr>
            <td colspan="4" width="100%" align="center" height="30"><font color="#ff0000">'.$dil["KayitBulunamadi"].'</font></td>
          </tr>';
        } else {
          $ysayi = 0;
					$y_sira = 0;
					$vt3 = new Baglanti();
          while ($album_bilgi  = $vt->fetchArray())
          {
            $album_no          = $album_bilgi["albumno"];
            $album_adi         = $fonk->yazdir_duzen($album_bilgi["albumadi"]);
            $album_tarih       = $album_bilgi["tarih"];
            $album_ekleyen     = $album_bilgi["uyeadi"];
            $album_aciklama    = $fonk->yazdir_duzen($album_bilgi['aciklama']);
						$album_resim       = $album_bilgi['resim'];
						$album_onay        = $album_bilgi['onay'];


            $album_adi        = wordwrap($album_adi, 45, "\n",1);
            $album_aciklama   = wordwrap($album_aciklama, 60, "\n",1);

            if (($ysayi % 2) == 0)
            {
              $renk = ' class="renk1"';
            } else {
              $renk = ' class="renk2"';
            }
            $resim = GALERI_ALBUM_DIZIN.'/'.$album_resim;
            if (!file_exists($resim) || !$album_resim)
            $resim = GALERI_ALBUM_DIZIN.'/klasor.gif';
						
						$resim_sayi = $vt3->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE albumno=$album_no AND (onay='E' OR uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5)");
						if (($ysayi % 3) == 0)
						echo '<tr>';
            ?>
              <td width="200" height="250" align="center" class="resim_kenarlik2"><a href="?sayfa=galeri&album=<?php echo $album_no; ?>&islem=2&asno=<?php echo $sayfano; ?>"><img src="resim.php?resim=<?php echo $resim; ?>&en=<?php echo GALERI_ALBUM_EN; ?>&boy=<?php echo GALERI_ALBUM_BOY; ?>" border="0" alt="<?php echo $album_adi; ?>" title="<?php echo $album_adi; ?>" align="absmiddle" /></a><br />
							<a href="?sayfa=galeri&album=<?php echo $album_no; ?>&islem=2&asno=<?php echo $sayfano; ?>"><font color="#3366ff"><strong><?php echo $album_adi; ?></strong></font></a><br /><?php echo $album_aciklama; 
							if ($album_onay == 'H')
							echo '<br /><br /><font color="#ff0000">'.$dil['YoneticiOnayiGerekiyor'].'</font>';
              ?>
              <hr />
							<b><?php echo $album_ekleyen; ?></b><br />
							<?php echo $fonk->duzgun_tarih_saat($album_tarih,true); ?><br />
              <b><?php echo $dil['Resim']; ?> : </b><?php echo $resim_sayi; ?>
              <?php
              if (UYE_SEVIYE > 0)
              {
                $album_sure_asimi = $vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE tarih > DATE_SUB(NOW(), INTERVAL ".GALERI_ALBUM_DUZEN_SURE." HOUR) AND uyeno=".UYE_NO." AND albumno=$album_no AND onay='E'");
                if ($album_sure_asimi > 0 || UYE_SEVIYE > 5)
                {
                  echo '<br /><a href="?sayfa=albumekle&albumno='.$album_no.'"><b>'.$dil['Duzenle'].'</b></a>';
                }
              }
							?>
							</td>
							<?php
							$ysayi++;
            if (($ysayi % 3) == 0)
						echo '</tr>';
          }
					unset($vt3);
          ?>
        <tr>
          <td align="center" width="100%" colspan="3">
            <table width="100%" align="center">
              <tr>
                <td colspan="4" width="100%" align="center"><?php echo $fonk->sayfalama($limit,$toplamalbum,$sayfano,'?sayfa=galeri&s=[sn]'); ?></td>
              </tr>
            </table>
          </td>
        </tr>
        <?php
        }
        $vt->freeResult();
        ?>
      </table>
      </form>
      <table align="center" width="95%">
        <tr>
          <td align="right"><br /><a href="index.php"><b><?php echo $dil['AnaSayfa']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=albumekle"><b><?php echo $dil['AlbumEkle']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=resimekle"><b><?php echo $dil['ResimEkle']; ?></b></a></td>
        </tr>
      </table>
			<?php
				$yvt = new Baglanti();
				$yvt->query("SELECT y.uyeno,y.resimno,SUBSTRING(y.yorum,1,50) AS yorum,y.baslik,y.tarih FROM ".TABLO_ONEKI."yorumlar AS y, ".TABLO_ONEKI."resim AS r WHERE r.resimno=y.resimno AND y.onay='E' AND y.resimno>0 AND r.onay='E' ORDER BY y.tarih DESC LIMIT 0,10");
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
          <td align="center" width="15%" height="25"><b><?php echo $dil['RESIM_NO']; ?></b></td>
          <td align="center" width="50%" height="25"><b><?php echo $dil['YORUM']; ?></b></td>
          <td align="center" width="30%" height="25"><b><?php echo $dil['YAZAR'].' - '.$dil['TARIH']; ?></b></td>
        </tr>
				<?php
				$ysira = 0;
				while ($yorum_veri = $yvt->fetchObject())
				{
				  $ysira++;
				  $yorum_icerik  = $fonk->yazdir_duzen($yorum_veri->yorum);
					$yorum_tarih   = $yorum_veri->tarih;
					$yorum_yazan   = $fonk->uye_adi($yorum_veri->uyeno);
					$yorum_resimno = $yorum_veri->resimno;
					if (($ysayi % 2) == 0)
          {
            $renk = ' class="renk1"';
          } else {
            $renk = ' class="renk2"';
          }
					echo '
					<tr class="'.$renk.'">
          <td align="center" width="5%" height="25">'.$ysira.'</td>
					<td align="center" width="15%" height="25"><a href="?sayfa=galeri&resim='.$yorum_resimno.'&islem=3">'.$yorum_resimno.'</a></td>
          <td align="left" width="50%" height="25"><a href="?sayfa=galeri&resim='.$yorum_resimno.'&islem=3">'.$yorum_icerik.'</a></td>
          <td align="center" width="30%" height="25" nowrap="nowrap">'.$yorum_yazan.'<br />'.$fonk->duzgun_tarih_saat($yorum_tarih,true).'</td>
        </tr> ';
				}
				?>
			  </table>
				<?php
				} //Yorum if Kontrol Sonu
				?>
    </td>
  </tr>
</table>
<?php
//==========================================
} elseif ($islem == 2) { // Albumdeki Resimler Siralaniyor
//==========================================
  $vt = new Baglanti();
  if ($vt->kayitSay("SELECT COUNT(albumno) FROM ".TABLO_ONEKI."album WHERE albumno=$albumno AND (onay='E' OR uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5)") == 0)
  {
    throw new Exception($dil['IslemGecersiz']);
    exit;
  }
  if (UYE_SEVIYE >= GALERI_RESIM_GORME_IZIN)
  {
    $limit = 12;
    @ $sayfano = abs(intval($sayfano));
				
    $toplamresim = $vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE albumno=$albumno AND (onay='E' OR uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5)");

    if(empty($sayfano) || $sayfano>ceil($toplamresim/$limit)) 
    {                
      $sayfano = 1;                
      $baslangic = 0;        
    } else {               
      $baslangic = ($sayfano - 1) * $limit;        
    }

		$vt->query("SELECT u.uyeadi,a.albumno,a.albumadi,a.tarih,a.aciklama FROM ".TABLO_ONEKI."album AS a, ".TABLO_ONEKI."uyeler AS u WHERE a.albumno=$albumno AND u.uyeno=a.uyeno");
    if ($vt->numRows()>0)
		{ 
		  $album_adi = $vt->fetchObject()->albumadi;
		}
		$vt->freeResult();
		$vt2 = new Baglanti();
		if (!$vt2->query("SELECT IFNULL((SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE resimno=r.resimno),0) AS yorumsayi,u.uyeadi,r.resimno,r.albumno,r.resim,r.resimadi,r.aciklama,r.goruntuleme,r.onay FROM ".TABLO_ONEKI."resim AS r, ".TABLO_ONEKI."uyeler AS u WHERE r.albumno=$albumno AND u.uyeno=r.uyeno AND (r.onay='E' OR r.uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5) LIMIT $baslangic,$limit"))
		{
		  echo $vt2->hataGoster();
		}
    $resim_sayisi = $vt2->numRows();
	?>
	<table width="98%" cellspacing="0" cellpadding="0">
    <tr>
      <td  align="center" valign="center"><h1><?php echo $dil['RESIM_GALERISI']; ?></h1></td>
    </tr>
    <tr>
      <td align="left" style="padding-right:25px"><b><?php echo $dil['Album'].' :</b> <a href="?sayfa=galeri&album='.$albumno.'&islem=2">'.$album_adi.'</a>'; ?><br /><?php echo $fonk->yerine_koy($dil['AlbumdeResimVar'],$toplamresim); ?></td>
    </tr>
  </table>
	<p>&nbsp;</p>
  <table width="98%" cellspacing="0" cellpadding="0">
	  <tr>
      <td align="right" style="padding-right:25px"><a href="?sayfa=galeri&s=<?php echo $asno; ?>"><b><?php echo $dil['GeriDon']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=albumekle"><b><?php echo $dil['AlbumEkle']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=resimekle&albumno=<?php echo $albumno; ?>"><b><?php echo $dil['ResimEkle']; ?></b></a><br />&nbsp;</td>
    </tr>
    <tr>
      <td  align="center">
			  <?php
				if ($resim_sayisi>0)
				{
				?>
			  <table align="left">
				  <?php
					$sirano = 0;
					while ($resim_veri = $vt2->fetchObject())
					{
					$resim_no          = $resim_veri->resimno;
					$resim             = $resim_veri->resim;
					$resim_adi         = $fonk->yazdir_duzen($resim_veri->resimadi);
					$resim_aciklama    = $fonk->yazdir_duzen($resim_veri->aciklama);
					$resim_uyeadi      = $resim_veri->uyeadi;
					$resim_goruntuleme = $resim_veri->goruntuleme;
					$resim_yorum       = $resim_veri->yorumsayi;
					$resim_albumno     = $resim_veri->albumno;
					$resim_onay        = $resim_veri->onay;
					
					$resim = GALERI_ALBUM_DIZIN.'/album_'.$albumno.'/'.$resim;
          if (!file_exists($resim) || !$resim)
          $resim = GALERI_ALBUM_DIZIN.'/bos.gif';
					
					if (($sirano%3)==0) echo '<tr>';
					?>
          <script language="javascript">
          function resimAc(resimno,en,boy)
          {
            window.open("resimac.php?resimno="+resimno+"r=2","","width="+en+",height="+boy);
          }
          </script>
					<td align="center" width="180" height="160" class="resim_kenarlik1" onmouseover="this.className='resim_kenarlik2'" onmouseout="this.className='resim_kenarlik1'"><a href="?sayfa=galeri&resim=<?php echo $resim_no; ?>&islem=3&rsno=<?php echo $sayfano; ?>"><img src="resim.php?resim=<?php echo $resim; ?>&en=175&boy=115" border="0" alt="<?php echo $resim_adi; ?>" title="<?php echo $resim_adi; ?>" align="absmiddle" /></a><br />
					<?php echo $dil['ResimNo'].' : '.$resim_no; ?><br />
					<?php echo $dil['Ekleyen'].' : '.$resim_uyeadi; ?><br />
					<?php echo $dil['Goruntuleme'].' : '.$resim_goruntuleme; ?><br />
					<?php echo $dil['Yorum']. ' : '.$resim_yorum;
          if (!empty($resim))
          {
            $resim_boyut = getimagesize($resim);
            $resim_en    = $resim_boyut[0];
            $resim_boy   = $resim_boyut[1];
          ?>
          <div style="float:right"><a href="javascript:resimAc('<?php echo $resim_no; ?>','<?php echo $resim_en; ?>','<?php echo $resim_boy; ?>')"><img alt="" title="<?php echo $resim_adi; ?>" src="resim/goster.png" align="absmiddle" border="0" width="25" height="25" /></a></div>
          <?php
          }
          ?><br />
					<?php if ($resim_onay == 'H') echo '<font color="#ff0000">'.$dil['OnayBekliyor'].'</font>'; ?></td>
					<?php
					$sirano++;
					if (($sirano%3)==0) echo '</tr>';
					}
					?>
				</table>
				<?php
				} else {
				  echo '<font color="#ff0000">'.$dil['KayitBulunamadi'].'</font>';
				}
				?>
			</td>
		</tr>
		<tr>
      <td align="center" style="padding-right:25px"><?php echo $fonk->sayfalama($limit,$toplamresim,$sayfano,'?sayfa=galeri&album='.$albumno.'&islem=2&s=[sn]'); ?></td>
    </tr>
		
		<tr>
      <td align="right" style="padding-right:25px"><br /><a href="?sayfa=galeri&s=<?php echo $asno; ?>"><b><?php echo $dil['GeriDon']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=albumekle"><b><?php echo $dil['AlbumEkle']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=resimekle&albumno=<?php echo $albumno; ?>"><b><?php echo $dil['ResimEkle']; ?></b></a></td>
    </tr>
	</table>
	<?php
	} else {
    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[GALERI_RESIM_GORME_IZIN]));
		exit;
	}
//==========================================
} elseif ($islem == 3) { // Resim Ayrinti
//==========================================
  $vt = new Baglanti();
	@$asno    = intval($_GET['asno']);

  if ($vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE resimno=$resimno AND (onay='E' OR uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5)") == 0)
  {
    throw new Exception($dil['IslemGecersiz']);
    exit;
  }

  if (UYE_SEVIYE >= GALERI_RESIM_GORME_IZIN)
  {
    @ $goruldu = $_SESSION['goruldu'];
    if ($goruldu != $resimno)
    {
      //Okunma Sayisi Artiriliyor
      if (!$vt->query2("UPDATE ".TABLO_ONEKI."resim SET goruntuleme=goruntuleme+1 WHERE resimno=$resimno"))
      {
        throw new Exception($dil['IslemGecersiz']);
        exit;
      }
      $_SESSION['goruldu'] = $resimno;
    }
    $vt->query("SELECT u.uyeno,u.uyeadi,u.resim AS uyeresim,a.albumadi,r.resimno,r.albumno,r.resim,r.resimadi,r.aciklama,r.tarih,r.puan,r.goruntuleme,r.onay FROM ".TABLO_ONEKI."resim AS r, ".TABLO_ONEKI."uyeler AS u,".TABLO_ONEKI."album AS a WHERE r.resimno=$resimno AND r.uyeno=u.uyeno AND r.albumno=a.albumno AND (r.onay='E' OR r.uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5)");

    $resim_sayi   = $vt->numRows();
  
    if ($resim_sayi > 0)
    {
      $resim_veri        = $vt->fetchObject();
      $resim_uyeno       = $resim_veri->uyeno;
      $resim_no          = $resim_veri->resimno;
			$resim_albumno     = $resim_veri->albumno;
      $resim_adi         = $fonk->yazdir_duzen($resim_veri->resimadi);
      $resim_ekleyen     = $resim_veri->uyeadi;
      $resim_aciklama    = $fonk->yazdir_duzen($resim_veri->aciklama);
      $resim_tarih       = $resim_veri->tarih;
      $resim_resim       = $resim_veri->resim;
			$resim_puan        = $resim_veri->puan;
			$resim_goruntuleme = $resim_veri->goruntuleme;
			$album_adi         = $resim_veri->albumadi;
			$resim_onay        = $resim_veri->onay;

      $resim = GALERI_ALBUM_DIZIN.'/album_'.$resim_albumno.'/'.$resim_resim;
      if (!file_exists($resim) || empty($resim_resim))
      {
        $resim = 0;
      }
				
      $resim_adi = wordwrap($resim_adi, 100, "\n",1);
      $resim_aciklama = wordwrap($resim_aciklama, 100, "\n",1);

      ?>
      <table width="98%" cellspacing="0" cellpadding="0">
      <tr>
      <td  align="center" valign="center"><h1><?php echo $dil['RESIM_GALERISI']; ?></h1></td>
      </tr>
      <tr>
      <td align="right" style="padding-right:25px"><br />
			<?php
			if ($rsno>0)
			{
			?>
			<a href="?sayfa=galeri&album=<?php echo $resim_albumno; ?>&islem=2&s=<?php echo $rsno; ?>"><b><?php echo $dil['GeriDon']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;
			<?php
			}
			?>
			<a href="?sayfa=galeri"><b><?php echo $dil['TumAlbumler']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=resimekle&albumno=<?php echo $resim_albumno; ?>"><b><?php echo $dil['ResimEkle']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=albumekle"><b><?php echo $dil['AlbumEkle']; ?><br />&nbsp;</td>
      </tr>
      </table>
      <table align="center" width="98%" cellspacing="0" cellpadding="0" class="mesaj1">
      <tr>
      <td align="left" width="100%"><b><?php echo $dil['Album']; ?> :</b> <?php echo '<a href="?sayfa=galeri&album='.$resim_albumno.'&islem=2">'.$album_adi.'</a>'; ?><br /><?php echo $fonk->yerine_koy($dil['AlbumdeResimVar'],$vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE albumno=$resim_albumno")); ?></td>
      </tr>
      <tr>
      <td align="center"><span style="font-size:12px"><b>» <?php echo $resim_adi; ?> «</b></span><br />
      <script language="javascript">
      function resimAc(resimno,en,boy)
      {
        window.open("resimac.php?resimno="+resimno+"r=2","","width="+en+",height="+boy);
      }
      </script>
      <?php
      if (!empty($resim))
      {
        $resim_boyut = getimagesize($resim);
        $resim_en    = $resim_boyut[0];
        $resim_boy   = $resim_boyut[1];
        ?>
        <a href="javascript:resimAc('<?php echo $resim_no; ?>','<?php echo $resim_en; ?>','<?php echo $resim_boy; ?>')"><img alt="<?php echo $resim_adi; ?>" src="resim.php?resim=<?php echo $resim; ?>&en=500&boy=400" align="middle" class="albumresim" border="0" /></a>
        <?php
      }
      ?>
      </td>
      </tr>
			<?php
			$rvt = new Baglanti();
			if ($sk==1)
			{
			  //Resim Ekleme Tarihine Göre Sıralama
			  $rvt->query("SELECT resimno AS resimno FROM ".TABLO_ONEKI."resim WHERE resimno>$resim_no AND (onay='E' OR uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5) ORDER BY tarih ASC LIMIT 1");
			  @$sonraki_resim_veri = $rvt->fetchObject();
				@$sonraki_resim      = $sonraki_resim_veri->resimno;

				
				$rvt->query("SELECT resimno  FROM ".TABLO_ONEKI."resim WHERE resimno<$resim_no AND (onay='E' OR uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5) ORDER BY tarih DESC LIMIT 1");
			  @$onceki_resim_veri = $rvt->fetchObject();
				@$onceki_resim      = $onceki_resim_veri->resimno;
				
				$ir_vt = new Baglanti();
				$ir_vt->query("SELECT resimno  FROM ".TABLO_ONEKI."resim WHERE (onay='E' OR uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5) ORDER BY tarih ASC LIMIT 1");
			  $ilk_resim = $ir_vt->fetchObject()->resimno;
				$ir_vt->freeResult();
				unset($ir_vt);
			} else {
			  //Albüm Ekleme Tarihine Göre Sıralama
			  $rvt->query("SELECT resimno  FROM ".TABLO_ONEKI."resim WHERE onay='E' OR uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5 ORDER BY albumno ASC,tarih ASC");
				$resim_nolari = array();
				while ($sonraki_veri = $rvt->fetchObject())
				{
				  $resim_nolari[] = $sonraki_veri->resimno;
				}

        while (list($key,$val) = each($resim_nolari))
				{
				  if ($val == $resim_no)
					$gecerli_key = $key;
				}
        @$sonraki_resim = $resim_nolari[$gecerli_key+1];
        @$onceki_resim  = $resim_nolari[$gecerli_key-1];
				
				$ir_vt = new Baglanti();
				$ir_vt->query("SELECT resimno  FROM ".TABLO_ONEKI."resim WHERE (onay='E' OR uyeno=".UYE_NO." OR ".UYE_SEVIYE.">5) ORDER BY albumno ASC,tarih ASC LIMIT 1");
				$ilk_resim = $ir_vt->fetchObject()->resimno;
				$ir_vt->freeResult();
				unset($ir_vt);
			}
			?>
			<tr>
        <td align="center">««&nbsp;
				<?php
				//Onceki 
				if ($onceki_resim>0)
			  echo '<a href="?sayfa=galeri&resim='.$onceki_resim.'&islem=3&sk='.$sk.'">'.$dil['Onceki'].'</a>'; 
			  else
			  echo $dil['Onceki']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php 
				//Sonraki      
				if ($sonraki_resim>0)
			  echo '<a href="?sayfa=galeri&resim='.$sonraki_resim.'&islem=3&sk='.$sk.'">'.$dil['Sonraki'].'</a>'; 
			  else
			  echo '<a href="?sayfa=galeri&resim='.$ilk_resim.'&islem=3&sk='.$sk.'">'.$dil['Sonraki'].'</a>'; ?>&nbsp;»»<br />
				<input type="radio" name="goster" value="0" onclick="location.href='?sayfa=galeri&resim=<?php echo $resimno; ?>&islem=3&sk=0'"<?php if (empty($sk)) echo ' checked="checked"'; ?>/>&nbsp;<?php echo $dil['AlbumSirasinaGoreGoster']; ?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="goster" value="1" onclick="location.href='?sayfa=galeri&resim=<?php echo $resimno; ?>&islem=3&sk=1'"<?php if ($sk==1) echo ' checked="checked"'; ?> />&nbsp;<?php echo $dil['ResimSirasinaGoreGoster']; ?>
				</td>
      </tr>
			<?php
			unset($rvt);
			?>
      <tr>
      <td align="left" valign="top"><?php echo nl2br($resim_aciklama); ?></td>
      </tr>
      <tr>
      <td align="center"><hr />
      <table>
      <tr>
      <td align="left" valign="center" nowrap="nowrap">
      <?php echo '<b>'.$dil['Ekleyen'].' : </b>'.$resim_ekleyen.'<br />
      <b>'.$dil['Goruntuleme'].' :</b>&nbsp;'.$resim_goruntuleme.'<br />
      <b>'.$dil['Tarih'].' :</b>&nbsp;'.$fonk->duzgun_tarih_saat($resim_tarih,1).'<br />';
      if ($resim_onay == 'H') 
       echo '<font color="#ff0000">'.$dil['OnayBekliyor'].'</font>';
      if (UYE_SEVIYE > 0)
      {
        $sure_asimi = $vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE tarih > DATE_SUB(NOW(), INTERVAL ".GALERI_RESIM_DUZEN_SURE." HOUR) AND uyeno=".UYE_NO." AND resimno=$resimno");
        if ($sure_asimi > 0 || UYE_SEVIYE > 5)
        {
          echo '<br /><a href="?sayfa=resimekle&resimno='.$resimno.'&albumno='.$resim_albumno.'"><b>'.$dil['Duzenle'].'</b></a>';
        }
      }

      echo '
      </font>';
      ?>
      </td>
      </tr>
			<?php
      if (GALERI_RESIM_OYLAMA=='E')
      {
      ?>
        <tr>
        <td align="center">
        <table width="100%" align="center">
        <tr>
        <?php
        $oys_vt = new Baglanti();
        $oys_vt->query("SELECT uyeno FROM ".TABLO_ONEKI."resimpuan WHERE resimno=$resimno");
        $oy_veri = $oys_vt->fetchObject();
        @$oy_sayi_dizi = explode(',',$oy_veri->uyeno);
        $oy_sayisi    = count($oy_sayi_dizi);
        if ($oy_sayisi==1 && empty($oy_sayi_dizi[0]))
        $oy_sayisi = 0;
        @ $ortalama     = number_format(($resim_puan/$oy_sayisi),2,',','');
        echo '<td colspan="2" align="center"><b>'.$dil['Puan'].' :</b> '.$resim_puan.' &nbsp;&nbsp;<b>'.$dil['OylayanKisi'].' :</b> '.$oy_sayisi.'&nbsp;&nbsp;<b>'.$dil['Ortalama'].' :</b> '.$ortalama.'</td>';
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
              echo '<td align="center"><input type="radio" name="oy" value="'.$i.'" onclick="location.href=\'?sayfa=galeri&resimno='.$resimno.'&islem=5&oy='.$i.'&sk='.$sk.'\';" /></td>';
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
      } //Galeri Resim Oylama if Sonu
      ?>
      </table>
      </td>
      </tr>
      </table>
      <table width="98%" cellspacing="0" cellpadding="0">
      <tr>
      <td colspan="2" align="right" style="padding-right:25px"><br />
			<?php 
			if ($rsno>0) 
			{
			?>
		  <a href="?sayfa=galeri&album=<?php echo $resim_albumno; ?>&islem=2&s=<?php echo $rsno; ?>"><b><?php echo $dil['GeriDon']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;
			<?php
			}
			?>
			<a href="?sayfa=galeri"><b><?php echo $dil['TumAlbumler']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=resimekle&albumno=<?php echo $resim_albumno; ?>"><b><?php echo $dil['ResimEkle']; ?></b></a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="?sayfa=albumekle"><b><?php echo $dil['AlbumEkle']; ?></b></a></td>
      </tr>
      </table>
      <br />
      <?php
      $vt->query("SELECT u.uyeadi,k.baslik,k.yorum,k.uyeno,k.tarih FROM ".TABLO_ONEKI."resim AS r, ".TABLO_ONEKI."yorumlar AS k, ".TABLO_ONEKI."uyeler AS u WHERE k.resimno=$resim_no AND k.onay='E' AND k.resimno=r.resimno AND k.uyeno=u.uyeno ORDER BY tarih DESC");
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
          @ $yorumbaslik = unserialize($_SESSION['resim_yorum']['baslik']);
          @ $yorummesaj  = unserialize($_SESSION['resim_yorum']['mesaj']);
          ?>
          <br />
          <form name="yorum_yaz" id="yorum_yaz"  action="?sayfa=galeri&islem=4&resim=<?php echo $resimno; ?>" method="post">
          <input type="hidden" name="yorumresimno" id="yorumresimno" value="<?php echo $resim_no; ?>" />
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
          unset($_SESSION['resim_yorum']);
        } else {
          throw new Exception($dil['YorumEklemeIzninizYok']);
        }
      } else {
			  $_SESSION['sayfaadi'] = serialize($sayfa.'&resim='.$resim_no.'&islem=3');
        throw new Exception($dil['YorumIcinGirisGerekli'],2);
      }
      echo '<br />';
    } else {
      throw new Exception($dil['IslemBasarisiz']);
      exit;
    }
    $vt->freeResult();
    unset($yazi_sayi,$yno,$yorum_mesaj,$yorum_baslik,$yorum_veri,$yorum_yazan,$yorum_tarih);
  } else {
    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[GALERI_RESIM_GORME_IZIN]));
    exit;
	}
	unset($vt);
//==============================================
// 3. ADIM SONU
//==============================================
} elseif ($islem == 4) { // 4. ADIM BASLANGICI - YORUM KAYIT
//==============================================
@ $yorumresimno = intval($_POST['yorumresimno']);
@ $yorumbaslik = $fonk->post_duzen($_POST['yorumbaslik']);
@ $yorummesaj  = $fonk->post_duzen($_POST['yorummesaj']);
$_SESSION['resim_yorum']['baslik'] = serialize($yorumbaslik);
$_SESSION['resim_yorum']['mesaj']  = serialize($yorummesaj);

if ($vt->kayitSay("SELECT COUNT(r.resimno) FROM ".TABLO_ONEKI."resim AS r,".TABLO_ONEKI."album AS a WHERE r.resimno=$yorumresimno AND r.albumno=a.albumno AND r.onay='E' AND a.onay='E'") == 0 &&  UYE_SEVIYE<6)
  {
    throw new Exception($dil['IslemGecersiz']);
    exit;
  }

if (UYE_SEVIYE >= YORUM_EKLEME_IZIN)
{
  if (empty($yorummesaj))
  {
    throw new Exception($dil['IsaretliAlanlariBosBirakmayiniz'],4);
    exit;
  }
  if (strlen($fonk->yazdir_duzen($_POST['yorumbaslik'])) > 100)
  {
    throw new Exception($fonk->yerine_koy($dil['BaslikKarakterIzin'],100),4);
    exit;
  }
  if (strlen($fonk->yazdir_duzen($_POST['yorummesaj'])) > YORUM_KARAKTER)
  {
    throw new Exception($fonk->yerine_koy($dil['YaziKarakterIzin'],YORUM_KARAKTER),4);
    exit;
  }
  if ($vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE resimno=$yorumresimno") == 0)
  {
    throw new Exception($dil['IslemGecersiz']);
    exit;
  }
  if ($vt->kayitSay("SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE tarih>DATE_SUB(NOW(),INTERVAL ".YORUM_ARASI_SURE." MINUTE) AND resimno=$yorumresimno AND uyeno=".UYE_NO."") > 0)
  {
    throw new Exception($fonk->yerine_koy($dil['IslemIcinBeklemenizGerekiyor'],YORUM_ARASI_SURE));
    exit;
  } else {
	  unset($_SESSION['resim_yorum']);
    if (UYE_SEVIYE >= YORUM_ONAY)
    {
      $yorumonay = 'E';
      $kayit_mesaj = $dil['KayitIslemiTamamlandi'];
    } else {
      $yorumonay = 'H';
      $kayit_mesaj = $dil['KayitIslemiTamamlandi'].'\n'.$dil['YoneticiOnayiGerekiyor'];
    }
    
    if ($vt->query2("INSERT INTO ".TABLO_ONEKI."yorumlar (`resimno`,`uyeno`,`baslik`,`yorum`,`tarih`,`onay`) VALUES ($yorumresimno,".UYE_NO.",'".$vt->escapeString($yorumbaslik)."','".$vt->escapeString($yorummesaj)."',NOW(),'$yorumonay')"))
    {
		  /*
      if ($yorumonay == 'E')
      {
        $fonk->yorum_eposta_bilgi($yorumresimno,UYE_NO);
      }
			*/
      throw new Exception($kayit_mesaj,5);
    } else {
      throw new Exception($dil['IslemBasarisiz']);
      exit;
    }
  }
} else {
  throw new Exception ($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[YORUM_EKLEME_IZIN]));
}
unset($vt,$yorumyazino,$yorummesaj,$yorumbaslik,$yorumonay);
//=============================================================
} elseif ($islem == 5) {// 4. ADIM SONU 5. ADIM BASLANGICI
//=============================================================
@ $resimno = intval($_GET['resimno']);
@ $oy      = intval($_GET['oy']);
if (UYE_SEVIYE<1)
{
  throw new Exception($dil['OylamaIcinGirisGerekli']);
	exit;
}
if (UYE_SEVIYE >= GALERI_RESIM_GORME_IZIN)
{
$resim_vt = new Baglanti();
$oy_vt   = new Baglanti();
$oy_vt2  = new baglanti();
if ($resim_vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resim WHERE resimno=$resimno AND onay='E'") == 0)
{
  throw new Exception($dil['IslemGecersiz']);
  exit;
}
if ($oy>10 || $oy<1)
{
  throw new Exception($dil['IslemGecersiz'],4);
	exit;
}
if (GALERI_RESIM_OYLAMA!='E')
{
  throw new Exception($dil['IslemGecersiz'],4);
	exit;
}
$resim_vt->query("SELECT puan FROM ".TABLO_ONEKI."resim WHERE resimno=$resimno");
$resim_veri = $resim_vt->fetchObject();
$resim_puan = $resim_veri->puan;
unset($resim_veri);
$resim_vt->freeResult();
if ($resim_vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resimpuan WHERE resimno=$resimno") == 0)
{
  $oy_vt->query2("INSERT INTO ".TABLO_ONEKI."resimpuan (resimno,uyeno) VALUES($resimno,".UYE_NO.")");
	
  $oy_vt2->query2("UPDATE ".TABLO_ONEKI."resim SET puan=($resim_puan+$oy) WHERE resimno=$resimno");
  throw new Exception($dil['IslemTamamlandi'],5);
  exit;
} else {
  if ($resim_vt->kayitSay("SELECT COUNT(resimno) FROM ".TABLO_ONEKI."resimpuan WHERE ".UYE_NO." IN (uyeno)")>0)
  {
    throw new Exception($dil['DahaOnceOyKullandiniz']);
    exit;
  } else {
    $oy_vt->query2("UPDATE ".TABLO_ONEKI."resimpuan AS yp SET yp.uyeno=CONCAT(yp.uyeno,',".UYE_NO."') WHERE yp.resimno=$resimno");
    $oy_vt2->query2("UPDATE ".TABLO_ONEKI."resim SET puan=($resim_puan+$oy) WHERE resimno=$resimno");
    throw new Exception($dil['IslemTamamlandi'],5);
    exit;
  }
}
} else {
  throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[GALERI_RESIM_GORME_IZIN]));
  exit;
}
//=============================================================
} // 5. ADIM SONU
//=============================================================

} // try Sonu
//=========================================
catch (Exception $e)
{
  $hatakod = $e->getCode();
  if ($hatakod == 1)
  {
    $adres = '?sayfa=galeri&islem=3&album='.$albumno.'&resim='.$resimno.'&sk='.$sk;
    $hata  = false;
	} elseif ($hatakod == 2) {
	  $adres = 'index.php?sayfa=giris';
		$hata = false;
	} elseif ($hatakod == 4) {
    $adres = '?sayfa=galeri&islem=3&resim='.$resimno.'&sk='.$sk;
    $hata = false;
	} elseif ($hatakod == 5) {
    $adres = '?sayfa=galeri&islem=3&resim='.$resimno.'&sk='.$sk;
    $hata = true;
  } else {
	  unset($_SESSION['resim_yorum']);
    $adres = 'index.php?sayfa=galeri';
    $hata = false;
  }
	unset($oy_vt,$yazi_vt,$oy_vt2);
  ?>
  <table align="center" cellpadding="0" cellspacing="0" width="85%">
    <tr>
      <td align="center">
        <?php echo $fonk->hata_mesaj($e->getMessage(),$hata,'<a href="'.$adres.'">'.$dil['Tamam'].'</a>'); ?>
      </td>
    </tr>
  </table>
<?php
}
?>