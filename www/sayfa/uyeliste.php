<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;
//==============================================
//Ozel Mesajlarda Uye Aramayi Saglayan Fonksiyon
//==============================================
///////////////////////////////////////////
///UYE LİSTELE BASLANGICI /////////////////
///////////////////////////////////////////
function uyeListe($alanlar)
{
  global $dil;
  $vt = new Baglanti();
	global $fonk;

	@ list($uyeadi,$uyelik,$adi,$soyadi,$sayfano) = explode(':',$alanlar);
  try 
  {
    if (UYE_SEVIYE == 0) 
    {
      exit;
    }
		
    @ $uyeadi  = trim(strip_tags(htmlspecialchars($uyeadi)));
		/*
		@ $uyelik  = intval($uyelik);
		@ $adi     = intval($adi);
		@ $soyadi  = intval($soyadi);
		*/

    if (!$fonk->kuladi_kontrol($uyeadi)) 
    {
      //Kullanici Adinda A-Z Harf, 0-9 Rakam ve Alt Cizgi Kullanilabilir
      throw new Exception($dil["KullaniciAdiGecersiz"]);
      exit;
		} else {
      if (UYE_SEVIYE >= OZEL_MESAJ_ISIM_IZIN)
			{
		    if ($uyelik && $adi && $soyadi)
			  {
			    $aramakosul = "AND (uyeadi LIKE '$uyeadi%' OR adi LIKE '$uyeadi%' OR soyadi LIKE '$uyeadi%')";
			  } elseif ($uyelik && $adi) {
			    $aramakosul = "AND (uyeadi LIKE '$uyeadi%' OR adi LIKE '$uyeadi%')";
			  } elseif ($adi && $soyadi) {
          $aramakosul = "AND (adi LIKE '$uyeadi%' OR soyadi LIKE '$uyeadi%')";
			  } elseif ($uyelik && $soyadi) {
			    $aramakosul = "AND (uyeadi LIKE '$uyeadi%' OR soyadi LIKE '$uyeadi%')";
			  } elseif ($uyelik) {
			    $aramakosul = "AND (uyeadi LIKE '$uyeadi%')";
			  } elseif ($adi) { 
			    $aramakosul = "AND (adi LIKE '$uyeadi%')";
			  } elseif ($soyadi) {
			    $aramakosul = "AND (soyadi LIKE '$uyeadi%')";
			  } else {
			    $aramakosul = "AND (uyeadi LIKE '$uyeadi%)";
			  }
			} else {
			  $aramakosul = "AND (uyeadi LIKE '$uyeadi%')";
			}
			
			
		  if (empty($uyeadi))
			{
			  $uye_var = 0;
			} else {
			  $limit = 25;
        @ $s = abs(intval($sayfano));
        if(empty($s)) 
        {                
          $s = 1;                
          $baslangic = 0;        
        } else {               
          $baslangic = ($s - 1) * $limit;        
        }
				$toplam_uye = $vt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler WHERE onay='E' AND yonay=5 AND uyeno<>".UYE_NO." $aramakosul");
        $vt->query("SELECT uyeadi,adi,soyadi FROM ".TABLO_ONEKI."uyeler WHERE onay='E' AND yonay=5 AND uyeno<>".UYE_NO." $aramakosul LIMIT $baslangic,$limit");
			  $uye_var = $vt->numRows();
			}
			$uye_adi = '';
      if ($uye_var > 0)
			{
			  $sayi = 0;
			  while ($uyeler = $vt->fetchObject())
				{
				  if (($sayi % 2) == 0)
					{
					  $renk = '#DFDFDF';
					} else {
					  $renk = '';
					}
				  $uye = $uyeler->uyeadi;
					$adi = $uyeler->adi;
					$soyadi = $uyeler->soyadi;
				  if (UYE_SEVIYE >= OZEL_MESAJ_ISIM_IZIN)
					{
					  $uye_adi .= '<div style="background-color:'.$renk.';text-align:left" id="menu"><label onclick="this.form.uyeadi.value=\''.$uye.'\';document.getElementById(\'ana_div\').style.display=\'none\'">&nbsp;<b>'.$uye.' -</b> '.$adi.' '.$soyadi.'</label></div>';
					} else {
					  $uye_adi .= '<div style="background-color:'.$renk.';text-align:left" id="menu"><label onclick="this.form.uyeadi.value=\''.$uye.'\'">&nbsp;<b>'.$uye.'</b></label></div>';
					}
					
					$sayi++;
				}
				$uye_adi .= '<div align="center">';
        if ($s > 1)
        {
          $onceki = $s-1;
          $uye_adi .= '<a href="javascript:void(null)" onclick="ajaxSorgu(\''.$uyeadi.':'.$uyelik.':'.$adi.':'.$soyadi.':'.$onceki.'\',\'uyeliste\',\'uyeListe\',\'ajaxSonuc\');">«&nbsp;'.$dil['Onceki'].'&nbsp;&nbsp;&nbsp;&nbsp;</a>';
        }
        if ($toplam_uye > ($s*$limit))
        {
          $sonraki = $s+1;
          $uye_adi .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(null)" onclick="ajaxSorgu(\''.$uyeadi.':'.$uyelik.':'.$adi.':'.$soyadi.':'.$sonraki.'\',\'uyeliste\',\'uyeListe\',\'ajaxSonuc\');">'.$dil['Sonraki'].'&nbsp;»</a>';
        }
			  $uye_adi .= "</div>";
				unset($uye,$adi,$soyadi);
				return array('ana_div'=>'ana_div','icerik_div'=>'icerik_div','icerik'=>$uye_adi);
			  unset($uye_adi);
			} else {
		    throw new Exception($dil['KayitBulunamadi']);
		  }	
		}
  }
  catch (Exception $e)
  {
    return array('ana_div'=>'ana_div','icerik_div'=>'icerik_div','icerik'=>$e->getMessage());
  }
	unset($vt);
}
///////////////////////////////////////////
///UYE LİSTELE SONU ///////////////////////
///////////////////////////////////////////
?>
