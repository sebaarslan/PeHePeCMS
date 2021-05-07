<?php
if (!defined("UYE_SEVIYE"))
exit;
?>
<div align="center" style="text-align:center"><a href="http://www.pehepe.org" target="_blank"><img src="resim/php-mysql.png" alt="PHP & MySQL" border="0" /></a><br />
Vakit Darlığı Nedeniyle <a href="http://www.pehepe.org" target="_blank">www.pehepe.org</a> Adresimi Bir Arkadaşıma Devrettim. PHP Gönüllülerine En Az Benim Kadar Yardımcı Olacağını Düşünüyorum. Yeni Eklemeler ve Düzenlemelerle PeHePe.org Daha da Güzelleşti</div>
<br />
<select name="tema" id="tema" class="input" onchange="location.href='?tema='+this.value">
<?php
$tema_klasor = dir(TEMA_KLASOR);
while (false !== ($tema_adi = $tema_klasor->read()))
{
  if (is_dir(TEMA_KLASOR.'/'.$tema_adi) && $tema_adi !== '.' && $tema_adi != '..')
  echo '<option value="'.$tema_adi.'"'; if (SITE_TEMA == TEMA_KLASOR.'/'.$tema_adi) echo ' selected="selected".'; echo '>'.$tema_adi.'</option>';
}
?>
</select>
<hr />
<br />
<table width="98%" cellspacing="0" cellpadding="0">
<?php
$yazi_vt = new Baglanti();
$yazi_vt->query("SELECT IFNULL((SELECT COUNT(yorumno) FROM ".TABLO_ONEKI."yorumlar WHERE yazino=y.yazino),0) AS yorumsayi,u.uyeno,u.uyeadi,u.resim AS uyeresim,y.yazino,y.kategorino,y.resim,y.baslik,y.yazi,y.eklemetarihi,y.okunma,y.puan FROM ".TABLO_ONEKI."yazilar AS y, ".TABLO_ONEKI."uyeler AS u WHERE y.onay='E' AND y.uyeno=u.uyeno ORDER BY eklemetarihi DESC LIMIT 0,5");

$yazi_sayi   = $yazi_vt->numRows();
if ($yazi_sayi > 0)
{
  while ($yazi_veri       = $yazi_vt->fetchObject())
	{
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
	$yazi_yorum      = $yazi_veri->yorumsayi;
	
	$yazi_icerik = preg_replace('(\[/?[^\]]+\])is',"",$yazi_icerik);
  $yazi_icerik      = substr($yazi_icerik,0,200).'...';
  $yazi_icerik      = wordwrap($yazi_icerik, 100, "\n",1);
				
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
	
  <tr>
    <td  align="left" width="100%" height="100%" style="padding-left:5px">
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
		//Baslik
    echo '<span style="font-size:13px"><a href="?sayfa=yazi&yazino='.$yazi_no.'&islem=2"><b>'.$yazi_baslik.'</b></a></span>';
		echo '<br />'.$fonk->duzgun_tarih_saat($yazi_tarih,1);
    //Duzenleme Linki
    if (UYE_SEVIYE > 0)
    {
      $sure_asimi = $vt->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE eklemetarihi > DATE_SUB(NOW(), INTERVAL ".YAZI_DUZENLEME_SURESI." HOUR) AND uyeno=".UYE_NO." AND yazino=$yazi_no AND onay='E'");
      if ($sure_asimi > 0 || UYE_SEVIYE > 5)
      {
        echo '&nbsp;&nbsp;&nbsp;{ <a href="?sayfa=yaziekle&yazino='.$yazi_no.'"><b>'.$dil['Duzenle'].'</b></a> }';
      }
    }
		
    ?>
		<br /><br />
    <?php echo nl2br($fonk->bb_html($yazi_icerik)); ?>
    </td>
  </tr>
  <tr>
    <td align="center" height="25" colspan="2">&raquo;&raquo;&nbsp;<a href="?sayfa=yazi&yazino=<?php echo $yazi_no; ?>&islem=2"><?php echo $dil['TamaminiOku']; ?></a>&nbsp;&laquo;&laquo;</td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <table>
        <tr>
						<td align="center"><img src="resim.php?resim=<?php echo $uyeresim; ?>&en=<?php echo UYE_RESIM_EN; ?>&boy=<?php echo UYE_RESIM_BOY; ?>" hspace="10"></td>
						<td align="left" valign="center" nowrap="nowrap">
						<?php 
						if (KATEGORI_SAYI > 0)
						{
							if (!$kategoriadi = $fonk->kategoriAdi($yazi_kategorino))
							$kategoriadi = $dil['ButunKonular'];
							echo '<b>'.$dil['Kategori'].' : </b><a href="?sayfa=yazi&kategori='.$yazi_kategorino.'">'.$kategoriadi.'</a>&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;';
						}
						echo '<b>'.$dil['Yazar'].' :</b>&nbsp;'.$yazi_yazan.'&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<b>'.$dil['Okunma'].' :</b>&nbsp;'.$yazi_okunma.'&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<b>'.$dil['Yorum'].' :</b>&nbsp;'.$yazi_yorum;
            echo '&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;<a href="xml.php?yazino='.$yazi_no.'"><img src="resim/rss.jpg" width="20" height="20" align="absmiddle" border="0" title="'.$dil['BuYaziIcinRssCiktisi'].'" /></a>';
            unset($yazi_veri,$yazi_baslik,$yazi_yazar,$yazi_icerik,$yazi_tarih,$yazi_okunma,$yazi_resim);
            echo '
            </font>';
						?>
						</td>
					</tr>
				</table>
				<hr />
				<br /><br />
			</td>
		</tr>
		
<?php
}
} else {
  echo '<tr><td align="center">'.$fonk->hata_mesaj($dil['KayitBulunamadi']).'</td></tr>';
}
?>
</table>
<table width="100%" align="center">
  <tr>
    <td align="center"><a href="?sayfa=yazi"><?php echo $dil['SonYazilar']; ?></a></td>
  </tr>
</table>