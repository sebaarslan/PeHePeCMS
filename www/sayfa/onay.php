<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
{
  echo 'Anasayfadan Giris Yapiniz';
  exit;
}
////////////////////////////////
///UYELIK ONAY BASLANGICI ////// 
////////////////////////////////
function uyeOnay($kullaniciadi='',$onay_kodu='')
{
  global $dil;
  global $fonk;
	global $vt;
  global $_SESSION;
	global $_COOKIE;

  try
  {
		if (empty($kullaniciadi) || empty($onay_kodu))
    {
      //Kullanici Adi ve/veya Onay Kodu Boş Bırakıldı
      throw new Exception($dil["BosAlanBirakmayiniz"]);
    } elseif (!$fonk->kuladi_kontrol($kullaniciadi)) {
      //Kullanici Adinda A-Z Harf, 0-9 Rakam ve Alt Cizgi Kullanilabilir
      throw new Exception($dil["KullaniciAdiGecersiz"]);
      exit;
    } elseif (!$fonk->parola_kontrol($onay_kodu)) {
      //Onaykodunda A-Z Harf, 0-9 Rakam ve Alt Cizgi Kullanilabilir
      throw new Exception($dil["OnayKoduGecersiz"]);
      exit;
    } else {
		  //Giris Deneme Suresi Asildiysa Sifirlaniyor
      $vt->query("UPDATE ".TABLO_ONEKI."ipkontrol SET denemesayi=0 WHERE tarih<DATE_SUB(NOW(), INTERVAL ".GIRIS_DENEME_SURESI." MINUTE) AND ip='".UYE_IP."'");
      @ $giris_deneme_osure = $_SESSION['giris_deneme']['sure'];
		  @ $giris_deneme_csure = $_COOKIE['giris_deneme_suresi'];
      
			if ($giris_deneme_osure < (time()-(GIRIS_DENEME_SURESI*60)))
      {
        unset($_SESSION['giris_deneme']);
      }
		
	    //Giris Deneme IP
      $giris_deneme_isayi   = $vt->kayitSay("SELECT COUNT(*) FROM ".TABLO_ONEKI."ipkontrol WHERE ip='".UYE_IP."' AND denemesayi >= ".GIRIS_DENEME_SAYISI."");
      //Giris  Deneme Cerez Sayisi 
      @ $giris_deneme_csayi = $_COOKIE['girisdenemesayisi'];

      //Giris Deneme Oturum Sayisi 
      @ $giris_deneme_osayi = $_SESSION['giris_deneme']['sayi'];

      //GIRIS DENEME KONTROL
      if ($giris_deneme_isayi > 0 || $giris_deneme_osayi >= GIRIS_DENEME_SAYISI || $giris_deneme_csayi >= GIRIS_DENEME_SAYISI)
      {
        //Giris Deneme Suresi Kadar Bekleme Gerekiyor
				
        throw new Exception($fonk->yerine_koy($dil['GirisDenemeSuresiKadarBekleyiniz'],array(GIRIS_DENEME_SAYISI,GIRIS_DENEME_SURESI)));
        exit;
      } else {
        $vt->query("SELECT `uyeno`,`uyeadi`,`onay`,`yonay`,`onaykodu`,`kayittarihi` FROM `".TABLO_ONEKI."uyeler` WHERE uyeadi=TRIM('$kullaniciadi')");
        
        if ($vt->numRows() > 0)
        {
          $uye_veri          = $vt->fetchObject();
					$uye_no            = $uye_veri->uyeno;
          $uye_adi           = $uye_veri->uyeadi;
          $uye_onaykodu      = $uye_veri->onaykodu;
          $uye_onay          = $uye_veri->onay;
          $uye_yoneticionayi = $uye_veri->yonay;
					$uye_kayittarihi   = $uye_veri->kayittarihi;

          if ($kullaniciadi == $uye_adi && $onay_kodu == $uye_onaykodu)
          {
            if ($uye_onay == 'E')
            {
              if ($uye_yoneticionayi == 0)
              {
                throw new Exception($dil['UyeliginizOnayli'].' '.$dil['UyeliginizYoneticiOnayiBekliyor']);
                exit;
              } elseif ($uye_yoneticionayi == 1) {
							  throw new Exception($dil['UyeliginizOnayli'].' '.$dil['UyeliginizGeciciSureAskiyaAlinmistir']);
								exit;
							} else {
							  throw new Exception($dil['UyeliginizOnayli']);
							  exit;
							}
            } else {
						  if ($uye_kayittarihi < date('Y-m-d H:i:s',time()-UYE_SILME_ZAMANI*60*60))
							{
							  throw new Exception($dil['UyeSilmeZamaniGecti']);
								exit;
							}
						  $onaymesaj = $dil['OnaylamaIslemiTamamlandi'];
						  
							if (UYELIK_ONAYI == 4)
							{
							  $onaymesaj .= '<br />'.$dil['YoneticiOnayiGerekiyor'];
							}
							  
            
						  //Bilgiler Dogruysa

              //Oturumdaki Deneme Sayisi Sifirlaniyor   
              unset($_SESSION['giris_deneme'],$_SESSION['giris_deneme']['sure'],$_SESSION['giris_deneme']['sayi']);
				
              //Cerezdeki Deneme Sayisi Sifirlaniyor
              setcookie("girisdenemesayisi", "", time() -(GIRIS_DENEME_SURESI*60));
				
              //IP Deneme Saysi Sifirlaniyor
              $vt->query("UPDATE ".TABLO_ONEKI."ipkontrol SET denemesayi=0 WHERE ip='".UYE_IP."'"); 
						
              //IP Adresine Ait Tarih Sifirlaniyor
              $vt->query2("UPDATE ".TABLO_ONEKI."ipkontrol SET tarih='0000-00-00 00:00:00' WHERE ip='".UYE_IP."'");
						
						  //Onaylama Islemi Yapiliyor
						  $vt->query2("UPDATE ".TABLO_ONEKI."uyeler SET onay='E' WHERE uyeadi=TRIM('$kullaniciadi') AND onaykodu=TRIM('$onay_kodu')");
 
              throw new Exception($onaymesaj,2);
						}
					} else {
					  //Giris Basarili Degilse
					  $fonk->girisDeneme();
					  throw new Exception($dil['KuladiVeyaKodYanlis']);
						exit;
					}
				} else {
				  //Giris Basarili Degilse
				  $fonk->girisDeneme();
          throw new Exception($dil['KuladiVeyaKodYanlis']);
				}  
			}
    }
  }
  catch (Exception $e)
  {
    $hatakod = $e->getCode();
    if ($hatakod == 2)
    $hata = true;
    else
    $hata = false;
	  unset($uye_no,$uye_veri,$uye_adi,$uye_sifre,$uye_onay,$uye_yoneticionayi,$onay_kodu,$kullaniciadi);
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
  unset($kullaniciadi,$parola,$giris_deneme_osayi,$giris_deneme_csayi,$giris_deneme_isayi,$giris_deneme_osure,$giris_deneme_csure);
}
	
@ $kullaniciadi = trim(htmlspecialchars(strip_tags($_GET['kadi'])));
@ $onaykodu     = trim(htmlspecialchars(strip_tags($_GET['kod'])));

uyeOnay($kullaniciadi,$onaykodu);
?>