<?php
function hizliMesaj()
{
  global $vt;
  global $fonk,$dil;
  $vt->query("SELECT m.mesajno,m.mesaj, m.tarih, u.uyeadi FROM ".TABLO_ONEKI."hizlimesaj AS m, ".TABLO_ONEKI."uyeler AS u WHERE m.onay='E' AND m.uyeno=u.uyeno ORDER BY m.tarih DESC LIMIT 20");
  $hmesaj_sayi = $vt->numRows();
  $hizli_mesaj = '';
  if ($hmesaj_sayi > 0)
  {
    for ($m=0; $m<$hmesaj_sayi; $m++)
	  {
		  
	    $hmesajveri = $vt->fetchObject();
		  $hmesaj     = $fonk->yazdir_duzen($hmesajveri->mesaj);
		  $hmtarih    = $hmesajveri->tarih;
		  $hmuyeadi   = $hmesajveri->uyeadi;

		  $hmesaj      = wordwrap($hmesaj, 20, "<br />",1);
		  $hizli_mesaj .= '<b>'.$hmuyeadi.'<br />'.$fonk->duzgun_tarih_saat($hmtarih,true).'</b><br />'.$hmesaj.'<hr style="border-style:dotted" />';
	  }
	  unset($hmesajveri,$hmesaj,$hmtarih,$hmuyeadi,$hmesaj_sayi);
  } else {
    $hizli_mesaj = '<div align="center" style="text-align:center; color:#ff0000">'.$dil['KayitBulunamadi'].'</div>';
  }
  return $hizli_mesaj;
}
?>