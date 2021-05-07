<?php
///////////////////////////////////
///GUNCEL VERİLER BASLANGICI //////
///////////////////////////////////
function guncelVeri()
{
  global $dil;
  global $fonk;

  //SAYAC VERILERI YUKLENIYOR
  require_once('icerik/say.inc.php');
  //HIZLI MESAJ YUKLENIYOR
  require_once(SAYFA_KLASOR.'/hizli_mesaj.php');

	return array('bugunTekil'=>BUGUN_TEKIL,
	'toplamTekil'=>TOPLAM_TEKIL,
	'bugunCogul'=>BUGUN_COGUL,
	'toplamCogul'=>TOPLAM_COGUL,
	'toplamUye'=>TOPLAM_UYE,
	'onlineMisafir'=>ONLINE_MISAFIR,
	'onlineUye'=>ONLINE_UYE,
	'onlineUyeler'=>ONLINE_UYELER,
	'gelenMesaj'=>$dil['GelenMesajlarim'].'&nbsp;'.GELEN_MESAJ.'/<font color="#ff0000">'.OKUNMAYAN_MESAJ.'</font>',
	'hizliMesaj'=>hizliMesaj(),
	'bugunKayit'=>BUGUN_KAYIT_UYE,
	'bugunKayitOlanUyeler'=>BUGUN_KAYIT_UYELER
	);
	
}
///////////////////////////////////
///GUNCEL VERILER SONU/////////////
///////////////////////////////////
?>