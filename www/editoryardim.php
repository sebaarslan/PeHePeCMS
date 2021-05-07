<?php 
require_once('icerik/vt.inc.php'); 
require_once('icerik/ayar.inc.php'); 
require_once('genel.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_ADI; ?></title>
<link href="stil.css" rel="stylesheet" />
</head>

<body style="margin:0">
<table width="100%" align="center" cellspacing="1" cellpadding="2">
  <tr class="tablobaslik">
    <td width="100%" colspan="2" align="center"><h1><?php echo $dil['Yardim']; ?></h1></td>
  </tr>
	<tr class="renk1">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[b] text metin [/b]</b></td>
    <td align="left" style="padding-left:5px"><b><?php echo $dil['Kalin']; ?></b></td>
	</tr>
	<tr class="renk2">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[i]</b> <i>text metin</i> <b>[/i]</b></td>
    <td align="left" style="padding-left:5px"><i><?php echo $dil['Egik']; ?></i></td>
	</tr>
	<tr class="renk1">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[u]</b> <u>text metin</u> <b>[/u]</b></td>
    <td align="left" style="padding-left:5px"><u><?php echo $dil['AltiCizgili']; ?></u></td>
	</tr>
	<tr class="renk2">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[s]</s> <u>text metin</s> <b>[/s]</b></td>
    <td align="left" style="padding-left:5px"><u><?php echo $dil['UstuCizgili']; ?></u></td>
	</tr>
	<tr class="renk1">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[left]</b> text metin <b>[/left]</b></td>
    <td align="left" style="padding-left:5px"><?php echo $dil['SolaYasla']; ?></td>
	</tr>
	<tr class="renk2">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[center]</b> text metin <b>[/center]</b></td>
    <td align="left" style="padding-left:5px"><?php echo $dil['Ortala']; ?></td>
	</tr>
	<tr class="renk1">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[right]</b> text metin <b>[/right]</b></td>
    <td align="left" style="padding-left:5px"><?php echo $dil['SagaYasla']; ?></td>
	</tr>
	<tr class="renk2">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[a href=</b>url<b>]</b> text metin <b>[/a]</b></td>
    <td align="left" style="padding-left:5px"><a href="editoryardim.php"><?php echo $dil['AdresEkle']; ?></a></td>
	</tr>
	<tr class="renk1">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[a href=</b>url <b>target=</b>_blank<b>]</b> <span style="text-align:left">text metin</span> <b>[/a]</b></td>
    <td align="left" style="padding-left:5px"><a href="http://projeler.arslandizayn.com/uyelik5" target="_blank"><?php echo $dil['AdresEkle']; ?></a></td>
	</tr>
  <tr class="renk2">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[sub]</b> text metin <b>[/sub]</b></td>
    <td align="left" style="padding-left:5px"><?php echo $dil['AltSimge']; ?>&nbsp;X<sub>2</sub></td>
	</tr>
	<tr class="renk1">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[sup]</b> text metin <b>[/sup]</b></td>
    <td align="left" style="padding-left:5px"><?php echo $dil['UstSimge']; ?>&nbsp;X<sup>2</sup></td>
	</tr>
	<tr class="renk2">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[table][tr][td]</b> text metin <b>[/td][/tr][/table]</b></td>
    <td align="left" style="padding-left:5px"><table width="100%" border="1"><tr><td><?php echo $dil['TabloEkle']; ?></td></tr></table></td>
	</tr>
	<tr class="renk1">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[ol][li]</b> text metin <b>[/li][li]</b> text metin <b>[/li][/ol]</b></td>
    <td align="left"><ol><li><?php echo $dil['SiraliListe']; ?></li><li><?php echo $dil['SiraliListe']; ?></li></ol></td>
	</tr>
	<tr class="renk2">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[ul][li]</b> text metin <b>[/li][li]</b> text metin <b>[/li][/ul]</b></td>
    <td align="left"><ul><li><?php echo $dil['SirasizListe']; ?></li><li><?php echo $dil['SirasizListe']; ?></li></ul></td>
	</tr>
	<tr class="renk1">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[renk=#html color]</b> text metin <b>[/renk]</b></td>
    <td align="left"><?php echo $dil['YaziRengi']; ?></td>
	</tr>
	<tr class="renk2">
    <td align="left" nowrap="nowrap" style="padding-left:5px"><b>[renk=#008000]</b> text metin <b>[/renk]</b></td>
    <td align="left"><font color="#008000"><?php echo $dil['YaziRengi']; ?></font></td>
	</tr>
</table>
</body>
</html>
