<?php
/*======================================================================*\
|| #################################################################### ||
|| # PeHePe yelik Sistemi                                            # ||
|| # ---------------------------------------------------------------- # ||
|| #  zel Mesaj Gelen Kutusu ...                                      # ||
|| # Gelen zel Mesajlar n Gr ntlenmesi, D zenlenmesi, Silinmesi    # ||
|| # ---------------------------------------------------------------- # ||
|| #                      www.pehpe.org                               # ||
|| #################################################################### ||
\*======================================================================*/
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
{
  echo 'Tek Kullanilmaz';
  exit;
}
//==============================
try
{
//==============================
if (UYE_SEVIYE == 0)
{
  $_SESSION['sayfaadi'] = serialize($sayfa);
  header('Location: ?sayfa=giris&hata=10');
	exit;
}
if (empty($islem) || $islem == 1) 
{
/* ================================ *\
|| -- OZEL MESAJLAR   -- 		      ||
\* ================================ */
require_once(SAYFA_KLASOR.'/mesaj_menu.php');
@ $sirala = intval($_GET['sirala']);
switch ($sirala)
{
  case 1;
  //Uye Adi A-Z
  $siralama = 'u.uyeadi ASC, m.tarih DESC';
  break;
  case 2;
  //Uye Adi Z-A
  $siralama = 'u.uyeadi DESC, m.tarih DESC';
  break;
  case 3;
  //Tarih Azalan
  $siralama = 'm.tarih DESC';
	$sirala_adres = "sirala=3";
  break;
  case 4;
  //Tarih Artan
  $siralama = 'm.tarih ASC';
  break;
  case 5;
  //Okunmayanlar
  $siralama = 'okundu DESC,m.tarih DESC';
  break;
  case 6;
  //Okunanlar
  $siralama = 'okundu ASC,m.tarih DESC';
  break;
  default;
  $sirala = 3;
  $siralama = 'm.tarih DESC';
  break;
}
//SIRALAMALAR AYARLANIYOR
//Tarihe Gore Siralamasi
if ($sirala == 4)
{
  $tsirala = 3;
  $tresim  = 'yukari.gif';
  $tmesaj  = $dil['MesajTarihineGoreYeni'];
} else { 
  $tsirala = 4;
  $tresim  = 'asagi.gif';
  $tmesaj  = $dil['MesajTarihineGoreEski'];
}
//Uye Adina Gore Siralama
if ($sirala == 1)
{
  $ksirala = 2;
  $kresim  = 'yukari.gif';
  $kmesaj  = $dil['AliciZa'];
} else {
  $ksirala = 1;
  $kresim  = 'asagi.gif';
  $kmesaj  = $dil['AliciAz'];
}
//Okunmaya Gore Siralama
if ($sirala == 5)
{
  $dsirala = 6;
  $dresim = 'yukari.gif';
  $dmesaj = $dil['Okunanlar'];
} else {
  $dsirala = 5;
  $dresim  = 'asagi.gif';
  $dmesaj = $dil['Okunmayanlar'];
}

//TOPLAM MESAJ
$gmtm_vt = new Baglanti();
$gmtm_vt->query("SELECT (SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."ozelmesaj WHERE kimden=".UYE_NO." AND okundu='H') AS okunmayan,mesajno FROM ".TABLO_ONEKI."ozelmesaj WHERE kimden=".UYE_NO."");
@ $toplam_mesaj = intval($gmtm_vt->numRows());
@ $okunmayan = intval($gmtm_vt->fetchObject()->okunmayan);
$gmtm_vt->freeResult();
unset($gmtm_vt);

$limit = 15;
@$sayfano = abs(intval($_GET['s']));
if(empty($sayfano) || $sayfano>ceil($toplam_mesaj/$limit)) 
{                
  $sayfano = 1;                
  $baslangic = 0;        
} else {               
  $baslangic = ($sayfano - 1) * $limit;        
}

//MESAJ BILGILERI
$vt = new Baglanti();
$vt->query("SELECT u.uyeadi,m.mesajno,m.kime,m.baslik,m.tarih,m.okundu,m.cevaplandi FROM ".TABLO_ONEKI."ozelmesaj AS m,".TABLO_ONEKI."uyeler AS u WHERE m.kimden=".UYE_NO." AND m.kime=u.uyeno ORDER BY $siralama LIMIT $baslangic,$limit");

?>
<form name="giden_mesaj" id="giden_mesaj"  action="javascript:void(null);">
<table cellspacing="0" cellpadding="0" width="98%" align="center">
  <tr>
    <td width="100%" align="center">
      <table width="100%" align="center">
        <tr>
          <td width="100%" height="20" colspan="5" align="center"><h1><?php echo $dil['GIDEN_KUTUNUZ']; ?></h1><?php echo $dil['GonderdiginizMesajlarSaklanir']; ?></td>
        </tr>
        <tr>
          <td width="50%" colspan="2" align="left"><?php echo $dil['GonderilenToplamMesaj']; ?> : <b><?php echo $toplam_mesaj; ?></b><br /><?php echo $dil['Okunmamis']; ?> : <b><?php echo $okunmayan; ?></b></td>
        </tr>
      </table>
      <table width="99%" align="center" cellpadding="1" cellspacing="2" class="tablolar">
        <tr class="tablobaslik">
          <td width="5%" height="20" align="center"><b>SN</b></td>
          
					<td width="5%" height="20" align="center"><a href="?sayfa=omgiden&sirala=<?php echo $dsirala; ?>" title="<?php echo $dmesaj; ?>"><b><?php echo $dil['DURUMU']; ?></b><img src="resim/<?php echo $dresim; ?>" align="absmiddle" border="0" /></a></td>
          
					<td width="15%" height="20" align="center"><a href="?sayfa=omgiden&sirala=<?php echo $ksirala; ?>"  title="<?php echo $kmesaj; ?>"><b><?php echo $dil['KIME']; ?></b><img src="resim/<?php echo $kresim; ?>" align="absmiddle" alt="" border="0" /></a></td>
          <td width="30%" height="20" align="center"><b><?php echo $dil['BASLIK']; ?></b></td>
          
					<td width="10%" height="20" align="center"><a href="?sayfa=omgiden&sirala=<?php echo $tsirala; ?>" title="<?php echo $tmesaj; ?>"><b><?php echo $dil['TARIH']; ?></b><img src="resim/<?php echo $tresim; ?>"align="absmiddle" alt="" border="0" /></a></td>
        </tr>
        <?php
        $sira = 0;
        $sirano = 0;
        if ($toplam_mesaj == 0)
        {

          echo '<tr><td align="center" colspan="7">'.$fonk->hata_mesaj($dil["MesajBulunamadi"],false).'</td></tr>';

        } else {
          while ($mesajlar_veri = $vt->fetchObject())
          {
            $sira++;
            $sirano = $sira+$baslangic;
            $mesaj_no                = $mesajlar_veri->mesajno;
            $giden_kime              = $mesajlar_veri->uyeadi;
            $giden_baslik            = $fonk->yazdir_duzen($mesajlar_veri->baslik);
            $giden_tarih             = $mesajlar_veri->tarih;
            $giden_okundu            = $mesajlar_veri->okundu;
            $giden_cevaplandi        = $mesajlar_veri->cevaplandi;
			      $giden_baslik            = substr($giden_baslik,0,30).'...';
				    
            if ($giden_cevaplandi > 1 && $giden_okundu == 'H')
            {
              $okunma = 'okunmamisc.gif';
            } elseif ($giden_cevaplandi > 1 && $giden_okundu == 'E') {
              $okunma = 'okunmusc.gif';
            } elseif ($giden_cevaplandi < 2 && $giden_okundu == 'H') {
              $okunma = 'okunmamis.gif';
            } else {
              $okunma = 'okunmus.gif';
            }
						if (($sira % 2) == 0)
						{
						  $renk = ' class="renk1"';
						} else {
						  $renk = ' class="renk2"';
						}
            ?>
            <tr<?php echo $renk; ?>> 
              <td width="5%" align="center"><b><?php echo $sirano; ?></b></td>
              <td width="5%" align="center"><img src="resim/<?php echo $okunma; ?>" border="0" /></td>
              <td width="15%" align="center"><?php echo $giden_kime; ?></td>
              <td width="30%" align="left">&nbsp;&nbsp;<a href="?sayfa=omgiden&islem=2&mesajno=<?php echo $mesaj_no; ?>"><?php echo $giden_baslik; ?></a></td>
              <td width="10%" align="center"><?php echo $fonk->duzgun_tarih_saat($giden_tarih,true); ?></td>
            </tr>
					<?php
          }
					unset($sirano,$sira,$mesaj_no,$giden_kime,$giden_baslik,$giden_tarih,$giden_okundu,$giden_cevaplandi,$okunma);
        } 
        ?>
        <tr>
          <td colspan="5" width="100%" align="center"><?php echo $fonk->sayfalama($limit, $toplam_mesaj,$sayfano,'?sayfa=omgiden&s=[sn]&sirala='.$sirala); ?></td>
        </tr>
      </table>
      <table width="100%" align="center">
        <tr>
          <td align="center" colspan="5" width="100%" height="20"><?php echo mesaj_menu(2); ?></td>
        </tr>
        <tr>
          <td align="center" colspan="5" width="100%" height="20"><a href="index.php"><?php echo $dil['AnaSayfa']; ?></a></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?php
unset($sirala,$siralama,$toplam_sayfa,$limit,$baslangic,$s);
//===============================================================
} else { // 2. ADIM
//===============================================================
if (UYE_SEVIYE == 0)
{
  $_SESSION['sayfaadi'] = serialize($sayfa);
  header('Location: ?sayfa=giris&hata=15');
	exit;
}

/* ================================ *\
|| -- MESAJ OKU -- 		            ||
\* ================================ */
@ $mesajno = intval($_GET['mesajno']);
$vt->query("SELECT u.uyeadi,m.baslik,m.mesaj,m.tarih,m.okundu FROM ".TABLO_ONEKI."ozelmesaj AS m,".TABLO_ONEKI."uyeler AS u WHERE m.kimden=".UYE_NO." AND m.kime=u.uyeno AND m.mesajno=$mesajno");

$mesaj_ayrinti    = $vt->fetchObject();

$mesaj_baslik     = $fonk->yazdir_duzen($mesaj_ayrinti->baslik);
$mesaj_icerik     = $fonk->yazdir_duzen($mesaj_ayrinti->mesaj);
$mesaj_giden      = $mesaj_ayrinti->uyeadi;
$mesaj_tarih      = $mesaj_ayrinti->tarih;
$mesaj_okundu     = $mesaj_ayrinti->okundu;
$mesaj_baslik     = wordwrap($mesaj_baslik, 70, "\n",1);
$mesaj_icerik     = wordwrap($mesaj_icerik, 70, "\n",1);

if ($mesaj_okundu > 0)
{
  $okundu_mesaj = $dil['AliciOkumus'];
} else {
  $okundu_mesaj = $dil['AliciOkumamis'];
}
require_once(SAYFA_KLASOR.'/mesaj_menu.php');
?>
<form action="?sayfa=mesaj"  method="post" name="mesajbilgileri">
<table cellspacing="0" cellpadding="0" width="95%" align="center">
  <tr>
    <td width="100%" align="center">
      <table width="100%" align="center">
        <tr>
          <td width="100%" height="20" align="center"><h1><?php echo  $dil['GidenOzelMesajiniz']; ?></h1></td>
        </tr>
        <tr>
          <td width="100%" align="center"><font color="ff0000"><?php echo  $okundu_mesaj; ?></font></td>
        </tr>
        <tr>
          <td width="100%" align="left">&nbsp;&nbsp;<b><?php echo  $dil['Kime']; ?> : </b><?php echo  $mesaj_giden; ?></td>
        </tr>
        <tr>
          <td width="100%" align="left">&nbsp;&nbsp;<b><?php echo  $dil['MesajBasligi']; ?> : </b><?php echo  $mesaj_baslik; ?></td>
        </tr>
        <tr>
          <td width="100%" align="left">&nbsp;&nbsp;<b><?php echo  $dil['MesajTarihi']; ?> : </b><?php echo  $fonk->duzgun_tarih_saat($mesaj_tarih,true); ?></td>
        </tr>
        <tr>
          <td width="100%" align="left">&nbsp;&nbsp;<b><?php echo  $dil['Mesaj']; ?> : </b></td>
        </tr>
      </table>
      <table width="99%" align="center">
			 
        <tr>
          <td width="100%" align="left" valign="top" class="mesaj1" height="30"><?php echo  nl2br($mesaj_icerik); ?></td>
        </tr> 
				<tr>
          <td width="100%" align="center"><?php echo  mesaj_menu(0); ?></td>
        </tr>	  
      </table>
    </td>
  </tr>
</table>
</form>
<?php
unset($mesajno,$mesajlar_veri_sql,$mesaj_ayrinti,$mesaj_baslik,$mesaj_icerik,$mesaj_giden,$mesaj_tarih,$mesaj_okundu,$okundu_mesaj);
}
//=========================================
} // try Sonu
//=========================================
catch (Exception $e)
{
  $hatakod = $e->getCode();
  if ($hatakod == 1)
  {
    $adres = '<a href="index.php?sayfa=omgelen">'.$dil['Tamam'].'</a>';
    $hata  = false;
  } elseif ($hatakod == 2) {
    $adres = '<a href="index.php?sayfa=omgelen">'.$dil['Tamam'].'</a>';
    $hata = true;
  } else {
    $adres = '<a href="index.php">'.$dil['Tamam'].'</a>';
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
?>