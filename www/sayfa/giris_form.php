<?php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
{
  echo 'Tek Kullanilmaz';
  exit;
}

if (UYE_SEVIYE > 0)
{ 
//Uye Menu
function uyeMenu($uyeadi)
{
  global $dil;
	global $seviyeler;
	$msj_vt = new Baglanti();
	$ur_vt  = new Baglanti();
	$ur_vt->query("SELECT resim FROM ".TABLO_ONEKI."uyeler WHERE uyeno=".UYE_NO."");
	$u_resim   = $ur_vt->fetchObject()->resim;
	$uye_resim = UYE_RESIM_DIZIN.'/'.$u_resim;
  if (!file_exists($uye_resim) || empty($u_resim))
  {
    $uye_resim = UYE_RESIM_DIZIN.'/bos.gif';
  }
  //Giris Yapildiysa Bu Bolum Cagriliyor
	$menuler = '<img src="'.$uye_resim.'" name="uyeresim" id="uyeresim"  border="0" align="center" width="50" height="50" /><br />'.$dil['HosGeldiniz'].' <b>'.$uyeadi.'</b><br />
	['.$seviyeler[UYE_SEVIYE].']<br />

  <div class="menu">
  <a href="?sayfa=profil"><div>'.$dil["UyelikBilgilerim"].'</div></a>
	<a href="?sayfa=omgelen"><div id="gelenMesaj">'.$dil["GelenMesajlarim"].' '.GELEN_MESAJ.'/'.OKUNMAYAN_MESAJ.'</div></a>
	<a href="?sayfa=omgiden"><div id="gidenMesaj">'.$dil["GidenMesajlarim"].'&nbsp;'.$msj_vt->kayitSay("SELECT COUNT(mesajno) FROM ".TABLO_ONEKI."ozelmesaj WHERE kimden=".UYE_NO."").'</div></a>
	<a href="?sayfa=omgonder"><div>'.$dil["OzelMesajGonder"].'</div></a>
	<a href="cikis.php"><div>'.$dil["Cikis"].'</div></a>
	</div>';
	unset($msj_vt);
  return $menuler;
} // uyeMenu Fonksiyon Sonu
} else {
function girisForm()
{
  global $dil,$func;

  @ $cerez_kuladi = $_COOKIE['pehepe_kullanici']['adi'];
  $menuler = '
  <form name="girisForm" id="girisForm"  action="giris.php" method="post" autocomplete="off">
    <table cellspacing="0" cellpadding="0" border="0">
		<tr>
		  <td>&nbsp;&nbsp;'.$dil['KullaniciAdiniz'].'</td>
    </tr>
		<tr>
		  <td><input type="text" size="20" name="kuladi" id="kuladi" value="'.$cerez_kuladi.'" /></td>
		</tr>
		<tr>
		  <td>&nbsp;&nbsp;'.$dil['Sifreniz'].'</td>
		</tr>
		<tr>
		  <td><input type="password" size="20" name="sifre" id="sifre" /></td>
		</tr>
    <tr>
      <td valign="middle" align="left">&nbsp;';
			$menuler .= '<input type="checkbox" value="1" name="benitani" id="benitani"'; if ($cerez_kuladi)      $menuler .= ' checked="checked"';
      $menuler .= ' />&nbsp;<label for="benitani">'.$dil['BeniTani'].'</label>';
			$menuler .= '</td>
    </tr>
    <tr>
      <td align="center"><input type="submit" id="girisButon" name="girisButon" value="'.$dil['GIRIS'].'" /><br />&nbsp;</td>
    </tr>
    <tr>
      <td align="center">
      <div class="menu">
      <a href="?sayfa=sifre">'.$dil['SifremiUnuttum'].'</a> 
      <a href="?sayfa=kayit">'.$dil['KayitOl'].'</a>
      </div></td>
    </tr>
  </table>
  </form>';
  unset($cerez_kuladi);
  return $menuler;

//================================
} //girisForm Fonksiyon Sonu
//================================

}
?>