<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;

///////////////////////////////////////
///HIZLI MESAJ KAYIT BASLANGICI  //////
///////////////////////////////////////
try 
{
  $vt = new Baglanti();
  @ $hm = $fonk->post_duzen($_POST['hmesaj']);
  if (UYE_SEVIYE == 0) 
  {
    $_SESSION['sayfaadi'] = serialize($sayfa);
    header('Location: ?sayfa=giris&hata=15'); 
    exit;
    
  }  elseif (UYE_SEVIYE < HIZLI_MESAJ_EKLEME_IZIN) {
    throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],$seviyeler[HIZLI_MESAJ_EKLEME_IZIN]));
    exit;
  }  elseif (empty($hm)) {
    throw new Exception($dil['BosAlanBirakmayiniz']);
    exit;
  } elseif (strlen($fonk->yazdir_duzen($hm)) > HIZLI_MESAJ_KARAKTER) {
    throw new Exception($fonk->yerine_koy($dil['HizliMesajKarakterIzin'],HIZLI_MESAJ_KARAKTER));
    exit;
  } elseif ($vt->kayitSay("SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."hizlimesaj WHERE tarih > DATE_SUB(NOW(), INTERVAL ".HIZLI_MESAJ_SURE." MINUTE) AND uyeno=".UYE_NO."") > 0 && UYE_SEVIYE < 6) {
    throw new Exception($fonk->yerine_koy($dil['IslemIcinBeklemenizGerekiyor'],HIZLI_MESAJ_SURE));
    exit;
  } else {
    if (UYE_SEVIYE >= HIZLI_MESAJ_ONAY) 
    {
      $onay = 'E'; 
      $onay_mesaj = '';
    } else {
      $onay = 'H';
      $onay_mesaj = $dil['YoneticiOnayiGerekiyor'];
    }

    $kayit = $vt->query2("INSERT INTO ".TABLO_ONEKI."hizlimesaj (mesaj,tarih,uyeno,onay) VALUES ('".$vt->escapeString($hm)."',NOW(),".UYE_NO.",'$onay')");
    if (!$kayit)
    {
      throw new Exception($dil['IslemBasarisiz']);
      exit;
    } else { 
      throw new Exception($dil['KayitIslemiTamamlandi'].' '.$onay_mesaj,2);
    }
  }
  unset($kayit,$onay,$mesajayar,$mesaj_onay,$hm_kayit,$hmkontrol,$mesaj_sure);
}
catch (Exception $e)
{
  $hatakod = $e->getCode();
  if ($hatakod == 1)
  {
    $hata = false;
    $adres = '';
  } elseif ($hatakod == 2) {
    $hata = true;
    $adres = '<a href="?">'.$dil['Tamam'].'</a>';
  } else {
    $hata = false;
    $adres = '<a href="?">'.$dil['Tamam'].'</a>';
  }
  echo $fonk->hata_mesaj($e->getMessage(),$hata,$adres);
}
unset($vt);
///////////////////////////////////////
///HIZLI MESAJ KAYIT SONU       ///////
///////////////////////////////////////
?>
