<?php
/*======================================================================*\
|| #################################################################### ||
|| # PeHePe Üyelik Sistemi                                            # ||
|| # ---------------------------------------------------------------- # ||
|| # Özel Mesaj Gelen Kutusu ...                                      # ||
|| # Gelen Özel Mesajlarin Goruntulenmesi, Düzenlenmesi, Silinmesi    # ||
|| # ---------------------------------------------------------------- # ||
|| #                      www.arslandizayn.com                        # ||
|| #################################################################### ||
\*======================================================================*/
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;

//===============================================
try { // try Başlangıcı
//===============================================

if (UYE_SEVIYE == 0)
{
  $_SESSION['sayfaadi'] = serialize($sayfa);
  header('Location: ?sayfa=giris&hata=10');
	exit;
}
//==============================================================
if (empty($islem))
{
//===============================================================
@$sirala   = intval($_REQUEST['sirala']);

/* ================================ *\
|| -- ÖZEL MESAJLAR   -- 		      ||
\* ================================ */
$aranan = '';
switch ($sirala)
{
  case 1;
  //Uye Adi A-Z
  $siralama = 'uyeadi ASC, m.tarih DESC';
  break;
  case 2;
  //Uye Adi Z-A
  $siralama = 'uyeadi DESC, m.tarih DESC';
  break;
  case 3;
  //Tarih Azalan
  $siralama = 'm.tarih DESC';
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
if ($sirala == 3)
{
  $tsirala = 4;
  $tresim  = 'asagi.gif';
  $tmesaj  = $dil['MesajTarihineGoreEski'];
} else {
  $tsirala = 3;
  $tresim  = 'yukari.gif';
  $tmesaj  = $dil['MesajTarihineGoreYeni'];
}
//Uye Adina Gore Siralama
if ($sirala == 1)
{
  $ksirala = 2;
  $kresim  = 'yukari.gif';
  $kmesaj  = $dil['GonderenZa'];
} else {
  $ksirala = 1;
  $kresim  = 'asagi.gif';
  $kmesaj  = $dil['GonderenAz'];
}
//Okunmaya Gore Siralama
if ($sirala == 5)
{
  $dsirala = 6;
  $dresim = 'yukari.gif';
  $dmesaj = $dil['Okunmayanlar'];
} else {
  $dsirala = 5;
  $dresim  = 'asagi.gif';
  $dmesaj = $dil['Okunanlar'];
}
//Sayfalama Bilgileri Aliniyor
$limit = 10;
@ $sayfano = abs(intval($_GET['s']));

//TOPLAM MESAJ
$gmtm_vt = new Baglanti();
$gmtm_vt->query("SELECT m.mesajno,IFNULL((SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."ozelmesaj WHERE kime=".UYE_NO." AND okundu='H'),0) AS okunmayan FROM ".TABLO_ONEKI."ozelmesaj AS m,".TABLO_ONEKI."uyeler AS u WHERE m.kime=".UYE_NO." AND u.uyeno=m.kime");
$toplam_mesaj    = $gmtm_vt->numRows();
@$okunmayan_veri = $gmtm_vt->fetchObject();
$okunmayan       = intval($okunmayan_veri->okunmayan);
$gmtm_vt->freeResult();

if(empty($sayfano) || $sayfano>ceil($toplam_mesaj/$limit)) 
{                
  $sayfano = 1;                
  $baslangic = 0;        
} else {               
  $baslangic = ($sayfano - 1) * $limit;        
}

$yonetimden = intval($gmtm_vt->kayitSay("SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."ozelmesaj WHERE kimden=0 AND kime=".UYE_NO.""));

unset($gmtm_vt);
	

//MESAJ SIRALAMA
$gm_vt = new Baglanti();
$gm_vt->query("SELECT IF (m.kimden=0,u.uyeadi,'".$dil['Yonetim']."')  AS uyeadi,m.mesajno,m.kimden,m.baslik,m.tarih,m.okundu,m.cevaplandi FROM ".TABLO_ONEKI."ozelmesaj AS m,".TABLO_ONEKI."uyeler AS u WHERE m.kime=".UYE_NO." AND m.kime=u.uyeno ORDER BY $siralama LIMIT $baslangic,".$limit."");

//MESAJ DOLULUK ORANI
if ($toplam_mesaj == 0 || OZEL_MESAJ_IZIN == 0 ) 
{
  $boyut = 0;
  $gelenyuzde = 0;
} else {
  $boyut = ROUND(($toplam_mesaj * 100) / (OZEL_MESAJ_IZIN+$yonetimden));
}	
//Mesaj Menu
require_once(SAYFA_KLASOR.'/mesaj_menu.php');
?>
	
<form name="gelen_mesaj" id="gelen_mesaj"  action="?sayfa=omgelen&islem=3" method="post" onsubmit="return confirm('<?php echo $dil['MesajiSilmekIstiyormusunuz']; ?>');false;">
<table cellspacing="0" cellpadding="0" width="98%" align="center">
  <tr>
    <td width="100%" align="center">
      <table width="100%" align="center">
        <tr>
          <td width="100%" height="20" colspan="8" align="center"><h1><?php echo $dil['GELEN_KUTUNUZ']; ?></h1></td>
        </tr>
        <tr>
          <td width="100%" colspan="7" align="center">
            <table width="100%" cellpadding="3">
              <tr>
                <td width="50%" align="left"><?php echo $dil['ToplamMesajSayiniz'].' : <b>'.$toplam_mesaj.'</b><br />';
								if ($yonetimden>0) echo $dil['Yonetim'].' : <b>'.$yonetimden.'</b><br />';
								echo $dil['Okunmamis'].' : <b>'.$okunmayan.'</b>'; ?></td>
								<?php
								if (UYE_SEVIYE<6)
								{
								?>
                <td width="50%" align="left"><?php echo $dil["GelenKutunuz"].' : % '.$boyut.' '.$dil['Dolu']; ?>
                  <table width="200" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#cccccc" height="10">
                      <td border="1"><img name="yuzde" src=resim/bar.gif width="<?php echo $boyut; ?>%" height="12" border="0" style="border-color: #4faafa"></td>
                    </tr>
                  </table>
                </td>
								<?php
								}
								?>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table width="99%" align="center" class="tablolar">
        <tr class="tablobaslik">
          <td width="5%" height="20" align="center"><input type="checkbox" onclick="this.value=sec(this.form.mesajlar,'gelen_mesaj')"></td>
          <td width="5%" height="20" align="center"><b>SN</b></td>
          <td width="5%" height="20" align="center" nowrap="nowrap"><a href="?sayfa=omgelen&s=<?php echo $sayfano; ?>&sirala=<?php echo $dsirala; ?>" title="<?php echo $dmesaj; ?>"><b><?php echo $dil['DURUMU']; ?></b><img src="resim/<?php echo $dresim; ?>" align="absmiddle" border="0" /></a></td>
          <td width="15%" height="20" align="center" nowrap="nowrap"><a href="?sayfa=omgelen&s=<?php echo $sayfano; ?>&sirala=<?php echo $ksirala; ?>" title="<?php echo $kmesaj; ?>"><b><?php echo $dil['KIMDEN']; ?></b><img src="resim/<?php echo $kresim; ?>" align="absmiddle" alt="" border="0" /></a></td>
          <td width="30%" height="20" align="center"><b><?php echo $dil['BASLIK']; ?></b></td>
          
					<td width="10%" height="20" align="center" nowrap="nowrap"><a href="?sayfa=omgelen&s=<?php echo $sayfano; ?>&sirala=<?php echo $tsirala; ?>" title="<?php echo $tmesaj; ?>"><b><?php echo $dil['TARIH']; ?></b><img src="resim/<?php echo $tresim; ?>"align="absmiddle" alt="" border="0" /></a></td>
          
					<td width="5%" height="20" align="center"><b><?php echo $dil['SIL']; ?></b></td>
        </tr>
        <?php				
        $sira = 0;
        $sirano = 0;
        if ($toplam_mesaj == 0)
        {
          echo '<tr><td align="center" colspan="7">'.$fonk->hata_mesaj($dil["MesajBulunamadi"],false).'</td></tr>';
        } else {
				  
          while ($mesajlar_veri = $gm_vt->fetchObject())
          {
            $sira++;
            $sirano = $sira+$baslangic;
            $mesaj_no                = $mesajlar_veri->mesajno;
            $gelen_kimden            = $mesajlar_veri->kimden;
						if ($gelen_kimden>0)
						$gelen_kimden            = $fonk->uye_adi($mesajlar_veri->kimden);
						else
						$gelen_kimden            = '<font color="#008000">'.$dil['Yonetim'].'</font>';
            $gelen_baslik            = $fonk->yazdir_duzen($mesajlar_veri->baslik);
            $gelen_tarih             = $mesajlar_veri->tarih;
            $gelen_cevaplandi        = $mesajlar_veri->cevaplandi;
            $gelen_okundu            = $mesajlar_veri->okundu;
			      $gelen_baslik            = substr($gelen_baslik,0,30).'...';	
						
						$gelen_baslik = $fonk->yazdir_duzen($gelen_baslik);
   
            if ($gelen_okundu == 'E' && $gelen_cevaplandi == 0)
            {
              $okunma = 'okunmus.gif';
					
             } elseif ($gelen_okundu == 'E' && $gelen_cevaplandi > 0) {
               $okunma = 'okunmusc.gif';
				    
            } elseif ($gelen_okundu == 'H') {
              $okunma = 'okunmamis.gif';
            } else {
              $okunma = 'okunmamisc.gif';
            }
						
						if (($sira % 2) == 0)
						{
						  $renk = ' class="renk1"';
						} else {
						  $renk = ' class="renk2"';
						}
				    ?>
            <tr<?php echo $renk; ?>">
              <td width="5%" align="center"><input type="checkbox" id="mesajlar" name="mesajlar[]" value="<?php echo $mesaj_no; ?>" class="onaykutusu"></td> 
              
							<td width="5%" align="center"><b><?php echo $sirano; ?></b></td>
              
							<td width="5%" align="center"><img src="resim/<?php echo $okunma; ?>"></td>
              
							<td width="15%" align="center"><?php echo $gelen_kimden; ?></td>
              
							<td width="30%" align="left">&nbsp;&nbsp;<a href="?sayfa=omgelen&islem=2&mesajno=<?php echo $mesaj_no; ?>"><?php echo $gelen_baslik; ?></a></td>
              <td width="10%" align="center"><?php echo $fonk->duzgun_tarih_saat($gelen_tarih,true); ?></td>
              
							<td width="5%" align="center"><a href="javascript:if(confirm('<?php echo $dil['MesajiSilmekIstiyormusunuz']; ?>'))location='?sayfa=omgelen&islem=3&mesajno=<?php echo $mesaj_no; ?>';"><img src="resim/sil.gif" border="0"></a></td>
            </tr>
					 <?php
           }
					 ?>
           <tr>
             <td width="5%" align="center"><img src="resim/cizgi.gif" align="middle"></td>
             <td colspan="5" width="95%" align="left"><font color="#FF0000"><?php echo $dil['Secilileri']; ?> : </font>
             <input type="submit" value="<?php echo $dil['SIL']; ?>" name="mesajSil" id="mesajSil" /></td>
          </tr>
					<?php
					unset($sira,$sirano,$mesaj_no,$gelen_kimden,$gelen_baslik,$gelen_tarih,$gelen_cevaplandi,$gelen_okundu,$okunma,$renk,$mesajlar_veri);
        } 
        ?>
        <tr>
          <td colspan="7" width="100%" align="center"><?php echo $fonk->sayfalama($limit, $toplam_mesaj, $sayfano, '?sayfa=omgelen&s=[sn]&sirala='.$sirala.$aranan); ?>
					</td>
        </tr>
      </table>
      <table width="100%" align="center">
        <tr>
          <td align="center" colspan="7" width="100%" height="20"><?php echo mesaj_menu(1); ?></td>
        </tr>
        <tr>
          <td align="center" colspan="7" width="100%" height="20"><a href="index.php"><?php echo $dil['AnaSayfa']; ?></a></td>
        </tr>    
      </table>
    </td>
  </tr>
</table>
</form>
<?php
$gm_vt->freeResult();
unset($gm_vt,$toplam_mesaj,$siralama_dizi,$okunmayan,$boyut,$sirala,$siralama,$toplam_sayfa,$limit,$baslangic);
//===============================================
} elseif ($islem == 2) { // 2. ADIM - MESAJ OKUMA
//===============================================
if (UYE_SEVIYE == 0)
{
  $_SESSION['sayfaadi'] = serialize($sayfa);
  header('Location: ?sayfa=giris&hata=10');
	exit;
}
/* ================================ *\
|| -- MESAJ OKU -- 		            ||
\* ================================ */
@ $mesajno = intval($_GET['mesajno']);
$mo_vt = new Baglanti();
$mo_vt->query("SELECT m.mesajno,m.kimden,m.baslik,m.mesaj,m.tarih,m.okundu,m.cevaplandi 
FROM ".TABLO_ONEKI."ozelmesaj AS m,".TABLO_ONEKI."uyeler AS u WHERE m.kime=".UYE_NO." AND m.mesajno=$mesajno");
$mesaj_ayrinti       = $mo_vt->fetchObject();
$mesaj_no            = $mesaj_ayrinti->mesajno;
$mesaj_baslik        = $fonk->yazdir_duzen($mesaj_ayrinti->baslik);
$mesaj_icerik        = $fonk->yazdir_duzen($mesaj_ayrinti->mesaj);
$mesaj_gonderen_no   = $mesaj_ayrinti->kimden;
if ($mesaj_gonderen_no>0)
$mesaj_gonderen      = $fonk->uye_adi($mesaj_gonderen_no);
else
$mesaj_gonderen      = $dil['Yonetim'];
$mesaj_tarih         = $mesaj_ayrinti->tarih;

$mesaj_baslik      = $mesaj_baslik;
$mesaj_icerik      = $mesaj_icerik;

$mesaj_baslik        = wordwrap($mesaj_baslik, 70, "\n",1);
$mesaj_icerik        = wordwrap($mesaj_icerik, 70, "\n",1);

$mo_vt->freeResult();
$mo_vt->query2("UPDATE ".TABLO_ONEKI."ozelmesaj SET okundu='E' WHERE kime=".UYE_NO." AND mesajno=$mesaj_no");

require_once(SAYFA_KLASOR.'/mesaj_menu.php');
?>
<form id="mesajOku" name="mesajOku">
<input type="hidden" name="mesajno" value="'.$mesaj_no.'">
<table cellspacing="0" cellpadding="0" width="95%" align="center">
  <tr>
     <td width="100%" align="center">
      <table width="100%" align="center">
        <tr>
          <td width="100%" height="20" align="center"><h1><?php echo $dil['GelenMesajlarim']; ?></h1></td>
        </tr>
        <tr>
          <td width="100%" align="center"></td>
        </tr>
        <tr>
          <td width="100%" align="center"><a href="javascript:if(confirm('<?php echo $dil['MesajiSilmekIstiyormusunuz']; ?>'))location='?sayfa=omgelen&islem=3&;"><b><?php echo $dil['MesajiSil']; ?></b></a></td>
        </tr>
        <tr>
					  <td align="left">
           <img src="<?php echo UYE_RESIM_DIZIN.'/'.$fonk->uye_resim($mesaj_gonderen_no); ?>" width="90" height="90" border="1" />
					</td>
				</tr>
						
        <tr>
          <td width="100%" align="left">&nbsp;&nbsp;<b><?php echo $dil['Kimden']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </b><?php echo $mesaj_gonderen; ?></td>
        </tr>
        <tr>
          <td width="100%" align="left">&nbsp;&nbsp;<b><?php echo $dil['MesajBasligi']; ?> : </b><?php echo $mesaj_baslik; ?></td>
        </tr>
        <tr>
          <td width="100%" align="left">&nbsp;&nbsp;<b><?php echo $dil['MesajTarihi']; ?>&nbsp;&nbsp;&nbsp;: </b><?php echo $fonk->duzgun_tarih_saat($mesaj_tarih,true); ?></td>
        </tr>
        <tr>
          <td width="100%" align="left">&nbsp;&nbsp;<b><?php echo $dil['Mesaj']; ?> : </b></td>
        </tr>
      </table>
      <table width="99%" align="center">
        <tr>
          <td width="100%" align="left" valign="top" class="mesaj1"><?php echo nl2br($mesaj_icerik); ?></td>
        </tr>
      </table>
      <table width="100%" align="center">
        <tr>
          <td align="center" width="100%" height="20"><?php echo mesaj_menu(0); ?></td>
        </tr>
        <tr>
          <td align="center" width="100%" height="20"><a href="?sayfa=omgonder&mesajno=<?php echo $mesaj_no; ?>"><?php echo $dil['CEVAPLA']; ?></a></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?php
$vt->freeResult();
unset($mesajno,$mesajlar_veri_sql,$mesaj_ayrinti,$mesaj_no,$mesaj_baslik,$mesaj_icerik,$mesaj_gonderen,$mesaj_tarih,$okundu);
//=====================================================
} elseif ($islem == 3) { //3. ADIM ÖZEL MESAJ SİLME
//=====================================================
if (UYE_SEVIYE == 0) 
{
  $_SESSION['sayfaadi'] = serialize($sayfa);
  header('Location: ?sayfa=giris&hata=15');
  exit;
}
@ $mesajno = intval($_GET['mesajno']);
$vt = new Baglanti();

if (empty($mesajno))
{
  if (empty($_POST['mesajlar']))
  {
    throw new Exception($dil['SecimYapmadiniz']); 
    exit;
  }
  //SILME ISLEMLERI
  $secim_sayi = count($_POST['mesajlar']);
  foreach($_POST['mesajlar'] as $anahtar => $deger)
  {
    $vt->query2("DELETE FROM ".TABLO_ONEKI."ozelmesaj WHERE kime=".UYE_NO." AND mesajno=$deger");
  }
	
} else {
  if ($vt->kayitSay("SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."ozelmesaj WHERE mesajno=$mesajno") == 0)
  {
    throw new Exception($dil['IslemBasarisiz']); 
    exit;
  }
  $vt->query2("DELETE FROM ".TABLO_ONEKI."ozelmesaj WHERE kime=".UYE_NO." AND mesajno=$mesajno");
}
throw new Exception($dil["OzelMesajSilindi"],2);
unset($vt);
//=====================================================
} // 3. ADIM SONU
//=====================================================

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