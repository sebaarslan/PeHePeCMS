<?php
/*======================================================================*\
|| #################################################################### ||
|| # PeHePe Uyelik Sistemi                                            # ||
|| # ---------------------------------------------------------------- # ||
|| # Ozel Mesaj Menü                                                  # ||
|| # Ozel Mesaj Icin Her Sayfaya Eklenen Mesaj Menuleri               # ||
|| # ---------------------------------------------------------------- # ||
|| #                      www.pehepe.org                              # ||
|| #################################################################### ||
\*======================================================================*/
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
{
  echo 'Tek Kullanilmaz';
  exit;
}

function mesaj_menu($klasor,$goster=1)
{
  global $dil;
  $menu = '
  <table style="border-collapse: collapse" bordercolor="#111111" cellspacing="0" cellpadding="0" border="0" align="center">
    <tr>';
    if ($klasor != 1)
	  {
        $menu .= '<td align="center">&nbsp;&nbsp;<a href="?sayfa=omgelen"><b>:: '.$dil['GelenKutunuz'].' ::</b></a>&nbsp;&nbsp;</td>';
	  }
	  if ($klasor != 2)
	  {
		  $menu .= '<td align="center">&nbsp;&nbsp;<a href="?sayfa=omgiden"><b>:: '.$dil['GidenKutunuz'].' ::</b></a>&nbsp;&nbsp;</td>';
	  }
	  if ($klasor != 3)
	  {
	    $menu .= '<td align="center">&nbsp;&nbsp;<b><a href="?sayfa=omgonder">:: '.$dil['OzelMesajGonder'].' ::</a></b></td>';
	  }
	  $menu .= '
	</tr>
  </table>
	<br />';
  if ($goster == 1)
  {
    $menu .= '
    <table width="100%" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0" cellpadding="0" border="0" align="center">
      <tr>
        <td width="100%" align="center" valign="center">
        <img src="resim/okunmus.gif" align="bottom" border="0" />&nbsp;'.$dil["Okunmus"].'&nbsp;&nbsp;
        <img src="resim/okunmamis.gif" align="absmiddle" border="0" />&nbsp;'.$dil["Okunmamis"].'&nbsp;&nbsp;
        <img src="resim/okunmusc.gif" align="bottom" border="0" />&nbsp;'.$dil["CevaplananOkunmus"].'&nbsp;&nbsp;
        <img src="resim/okunmamisc.gif" align="absmiddle" border="0" />&nbsp;'.$dil["CevaplananOkunmamis"].'
      </tr>
    </table>';
  }
	unset($klasor,$goster);
	return $menu;
}
?>