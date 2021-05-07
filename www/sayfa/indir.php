<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;
try {
@ $indir = intval($_GET['indir']);
$indirdb = new Baglanti();
if (empty($indir))
{
?>
<script type="text/javascript"><!--
google_ad_client = "pub-3219641692306880";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text_image";
google_ad_channel ="";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "3366FF";
google_color_url = "FF6600";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="100%" align="center">
  <tr>
    <td>
      <table align="center" width="100%">
        <tr>
          <td align="center" colspan="5"><h4><?php echo $dil['Indir']; ?></h4></td>
        </tr>
        <tr class="tablobaslik">
          <td height="20" align="center" nowrap="nowrap"><b><?php echo $dil['DosyaAdi']; ?></b></td>
          <td height="20" align="center" nowrap="nowrap"><b><?php echo $dil['Tarih']; ?></b></td>
          <td height="20" align="center" nowrap="nowrap"><b><?php echo $dil['Guncelleme']; ?></b></td>
          <td height="20" align="center" nowrap="nowrap"><b><?php echo $dil['Indirme']; ?></b></td>
          <td height="20" align="center" nowrap="nowrap"><b><?php echo $dil['Indir']; ?></b></td>
        </tr>

        <?php
  
        $indirdb->query("SELECT dosyano,dosyaadi,dosyayolu,dosyadeneme,dosyaaciklama,dosyakayittarih,dosyaduzentarih,dosyaindirsayi FROM ".TABLO_ONEKI."dosyalar WHERE dosyaonay='E' ORDER BY dosyakayittarih DESC");

        while($indirveri = $indirdb->fetchObject())
        {
          echo '<tr class="renk2">
          <td align="left" height="25" nowrap="nowrap">&nbsp;<a href="'.$indirveri->dosyadeneme.'" title="Deneyiniz"><b>'.$indirveri->dosyaadi.'</b></a></td>
	      <td align="center" nowrap="nowrap">'.str_replace(' ','<br />',$fonk->duzgun_tarih_saat($indirveri->dosyakayittarih,true)).'</td>
	      <td align="center" nowrap="nowrap">'.str_replace(' ','<br />',$fonk->duzgun_tarih_saat($indirveri->dosyaduzentarih,true)).'</td>
	      <td align="center" nowrap="nowrap">'.$indirveri->dosyaindirsayi.'</td>
		  <td align="center" nowrap="nowrap"><a href="?sayfa=indir&indir=2&no='.$indirveri->dosyano.'" title="'.$dil['Indir'].'"><b>'.$dil['Indir'].'</b></a></td>
          </tr>
					<tr><td align="left" colspan="5" style="padding-left:20px">'.nl2br($indirveri->dosyaaciklama).'</td></tr>
					<tr><td align="center" colspan="5"><hr /></td></tr>';
        }
        ?>

        <tr>
          <td colsap="5" height="50">&nbsp;</td>
        </tr>
        <tr>
          <td align="center" colspan="5">
          <br />
          <br />
          <script type="text/javascript">
          <!--
          google_ad_client = "pub-3219641692306880";
          google_ad_width = 468;
          google_ad_height = 60;
          google_ad_format = "468x60_as_rimg";
          google_cpa_choice = "CAAQn8D8zwEaCA5ooChA8qiWKJXll3Q";
          //--></script>
          <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<?php
} elseif ($indir == 2) {
  $no = intval($_GET['no']);

  $indirdb->query("SELECT dosyano,dosyaadi,dosyayolu,dosyaaciklama FROM ".TABLO_ONEKI."dosyalar WHERE dosyano=$no AND dosyaonay='E'");
  if ($indirdb->numRows()>0)
  {
	  $indir   = $indirdb->fetchObject();
	  $dosyano = $indir->dosyano;
    $adi     = $indir->dosyaadi;
    $dosya   = $indir->dosyayolu;
		

    @ $indirildi = $_SESSION['indir'][$dosyano];
		if (!is_file($dosya))
		{
		  throw new Exception($dil['DosyaBulunamadi']);
			exit;
		}
    if (!$indirildi)
    {
      $indirdb->query("UPDATE ".TABLO_ONEKI."dosyalar SET dosyaindirsayi=dosyaindirsayi+1 WHERE dosyano=$no");
    }
  
    echo '
    <p>&nbsp;</p>
    <table align="center" border="0" cellpadding="2" cellspacing="0" width="100%">
	   <tr>
        <td width="100%" align="left"><h5>'.$adi.'</h5><br>
	    '.nl2br($indir->dosyaaciklama).'<br />&nbsp;</td>
	  </tr>
	  </tr>
      <tr>
        <td width="100%" align="center">'.$dil['DosyaIndiriliyor'].'<br>
	   <a href="'.$dosya.'" class="link2">'.$dil['IndirilmeBaslamazsa'].'</a></a>
	  </tr>
    </table>
    <meta http-equiv="refresh" content="1; url='.$dosya.'">';
    $_SESSION['indir'][$dosyano] = true;
  } else {
    throw new Exception($dil['IslemBasarisiz']);
  }
}
unset($indirdb);
//=========================================
} // try Sonu
//=========================================
catch (Exception $e)
{
?>
  <table align="center" cellpadding="0" cellspacing="0" width="85%">
    <tr>
      <td align="center">
        <?php echo $fonk->hata_mesaj($e->getMessage(),$hata,'<a href="index.php">'.$dil['Tamam'].'</a>'); ?>
      </td>
    </tr>
  </table>
<?php
}
?>
