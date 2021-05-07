<?php 
require_once('genel.php');

 
header("Content-Type: text/html; charset=UTF-8");
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>"; 
?>
<rss version="2.0">
<channel>
<title><?php echo SITE_ADI; ?> RSS</title>
<link><?php echo SITE_ADRESI; ?></link>
<description><?php echo $dil['description'];?></description>
<docs><?php echo SITE_ADRESI; ?>/xml.php</docs>
<language><?php echo SITE_DIL; ?></language>
<genarator><?php echo SITE_ADI; ?></genarator>
<?php
@$yazi_no = intval($_GET['yazino']);
@$yazino  = $_POST['yazino'];
if (@is_array($yazino))
$yazi_kosul = " AND y.yazino IN (".implode(',',$yazino).")";
else
$yazi_kosul = " AND y.yazino=$yazi_no"; 

$yvt = new Baglanti();
$yvt->query("SELECT u.uyeadi,y.yazino,y.resim,y.kategorino,y.uyeno,y.baslik,y.yazi,y.eklemetarihi,y.okunma,y.puan FROM ".TABLO_ONEKI."yazilar AS y, ".TABLO_ONEKI."uyeler AS u WHERE y.uyeno=u.uyeno ".$yazi_kosul." AND y.onay='E' ORDER BY y.eklemetarihi DESC LIMIT 10");
$toplam_yazi = $yvt->numRows(); 
if ($toplam_yazi>0)
{ 
while ($yazi_bilgi  = $yvt->fetchArray())
{
  $yazi_no          = $yazi_bilgi["yazino"];
  $yazi_baslik      = $fonk->yazdir_duzen($yazi_bilgi["baslik"]);
  $yazi_okunma      = $yazi_bilgi["okunma"];
  $yazi_tarih       = $yazi_bilgi["eklemetarihi"];
  $yazi_yazar       = $yazi_bilgi["uyeadi"];
  $yazi_kategori    = $yazi_bilgi['kategorino'];
  $yazi_icerik      = $yazi_bilgi['yazi'];
  $yazi_puan        = $yazi_bilgi['puan'];
  //Sadece Yazılari Aliniyor... BB Kodlari Atiliyor
  $yazi_icerik = $fonk->yazdir_duzen(preg_replace('(\[/?[^\]]+\])is',"",$yazi_icerik));
  
	if (KATEGORI_SAYI>0)
	{
	  $yazi_kategoriadi = '';	
    if ($yazi_kategori>0)
    {
      $yazi_kategoriadi .= $fonk->kategoriAdi($yazi_kategori);
      $altkategori_dizi = $fonk->kategoriSecListe($yazi_kategori);
      foreach($altkategori_dizi as $altkategorino=>$altkategoriadi)
      {
        $yazi_kategoriadi .= '&amp;nbsp;&amp;nbsp;'.$altkategoriadi;
      }
    } else {
      $yazi_kategoriadi .= $dil['ButunKonular'];
    }
	}
  
  echo "<item>";
  echo "<title>".$yazi_baslik."</title>";
	echo '<link>'.SITE_ADRESI.'/?sayfa=yazi&amp;yazino='.$yazi_no.'&amp;islem=2</link>';
	echo "<pubDate>".date('r')."</pubDate>";
	if (KATEGORI_SAYI>0)
	echo "<category>".$yazi_kategoriadi."</category>";
	
	echo '<description><![CDATA['.$yazi_icerik.']]></description>';
	echo "<author>".$yazi_yazar."</author>";
	echo "</item>";
}
} else {
  echo "<item>";
	echo "<title>...</title>";
	echo "<link>".SITE_ADRESI."</link>";
	echo "<description>".$dil['KayitBulunamadi']."</description>";
	echo "</item>";
}
?>
</channel>
</rss>