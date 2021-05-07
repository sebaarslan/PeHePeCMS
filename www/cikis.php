<?php
ob_start();
session_start();
header("Cache-Control: no-cache");

require_once('genel.php');
require_once("icerik/fonk.inc.php");

////////////////////////////////
///UYE CIKIS BOLUMU BASLANGICI//
////////////////////////////////

//Oturumlar Kapatliyor
function uyeCikis()
{
  $fonk = new Fonksiyon();
  global $vt;
  global $_SESSION;
  global $_COOKIE;

  @ $kuladi = trim(htmlspecialchars(strip_tags($_SESSION['pehepe_kullanici_adi'])));
  @ $parola = trim(htmlspecialchars(strip_tags($_SESSION['pehepe_kullanici_sifre'])));

  if ($fonk->kuladi_kontrol($kuladi) && $fonk->parola_kontrol($parola))
	{
    $vt->query("UPDATE ".TABLO_ONEKI."uyeler SET onlinetarih='0000-00-00 00:00:00' WHERE uyeadi='$kuladi' AND sifre='$parola'");

    $UYESEVIYEALMA["$kuladi"] = "$parola";
    foreach ($UYESEVIYEALMA as $kullanici=> $sifre) 
    {
      unset($_SESSION["$kullanici"]);
      unset($_SESSION["$sifre"]);
    }
    unset($kuladi,$parola);
    session_destroy ();
		session_start();
    $_SESSION['sayac_artirildi'] = date('Y-m-d');
  }
}
///////////////////////////////
///UYE CIKIS BOLUMU SONU 
//////////////////////////////
if (UYE_SEVIYE > 0)
{
  uyeCikis();
}
header('Location: index.php');
?>