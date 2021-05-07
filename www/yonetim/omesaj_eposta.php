<?php
ob_start();
session_start();
require_once ("../icerik/vt.inc.php");
require_once ("../icerik/ayar.inc.php");
require_once ("../icerik/dil.inc.php");
dil_belirle('','yonetimdil');
require_once ("../icerik/sev.inc.php");
require_once ("../icerik/fonk.inc.php");


//Yönetici Girişi Yapılmamissa Yasakla
if (UYE_SEVIYE < 5) 
{
  header('Location: ../index.php');
  exit;
}
$fonk = new Fonksiyon();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo SITE_ADI; ?> : <?php echo $dil['YonetimPaneli']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="ystil.css" />

<script language="javascript">
function yukleniyor_kapat()
{
document.getElementById('mesaj_gonderiliyor').style.display='none';
}
function yukleniyor_ac()
{
document.getElementById('mesaj_gonderiliyor').style.display='block';
document.getElementById('form_alan').style.display='none';
}
</script>
</head>
<body background="yonetimresim/bg.gif">
<div id="mesaj_gonderiliyor" style="display:none"><table border="1" align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="#efeffa" bordercolor="#b6c5f2"><tr><td align="center"><br /><img src="../resim/gonderiliyor.gif" /><br /><font color="#008000"><?php echo $dil['GondermeIsleminiBekleyiniz']; ?></font><br />&nbsp;</td></tr></table></div>
<?php
@ $islem     = strip_tags(trim($_GET['islem']));
if (empty($islem) || $islem==1)
{

$yvt = new Baglanti();
$mesajtur = 1;
$kime     = 1;
$konu     = '';
$mesaj    = '';
$uyeler   = array();
if (@is_array($_SESSION['ome_gonder']))
{
  foreach($_SESSION['ome_gonder'] as $anahtar=>$deger)
  {
    ${$anahtar} = unserialize($deger);
    if (empty(${$anahtar}))
    ${$anahtar} = '';
  }
}
?>
<div id="form_alan" style="display:block">
<form name="omesajeposta" id="omesajeposta" action="omesaj_eposta.php?islem=2" method="post">
<table border="1" align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['OzelMesajEpostaGonder']; ?></b></font></td>
  </tr>
  <tr>
    <td colspan="2" align="center" style="padding-right:25px"><input type="radio" name="mesajtur" id="tomesaj" value="1"<?php if ($mesajtur==1) echo ' checked="checked"'; ?> />&nbsp;<?php echo $dil['OzelMesajGonder']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="mesajtur" id="teposta" value="2"<?php if ($mesajtur==2) echo ' checked="checked"'; ?> />&nbsp;<?php echo $dil['EpostaGonder']; ?></td>
  </tr>
	<tr>
    <td colspan="2" align="center" valign="top">
		<input type="radio" name="kime" id="tuye" value="1" onclick="this.form.uyeler.disabled=true"<?php if ($kime==1) echo ' checked="checked"'; ?> />&nbsp;<?php echo $dil['TumUyeler']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="kime" id="suye" value="2" onclick="this.form.uyeler.disabled=false"<?php if ($kime==2) echo ' checked="checked"'; ?> />&nbsp;<?php echo $dil['SecilenUyeler']; ?><br />
		
		
		<?php
		if (count($uyeler)>0 && is_array($uyeler))
		$where = " AND uyeno IN (".implode(',',$uyeler).")";
    else
		$where = "";
		
		if ($yvt->query("SELECT uyeno,uyeadi FROM ".TABLO_ONEKI."uyeler WHERE onay='E' AND yonay=5".$where.""))
		{
		?>
		<select name="uyeler[]" id="uyeler" size="5" multiple="multiple" style="font-size:10px;width:250px"<?php if ($kime==1) echo ' disabled="disabled"'; ?>>
		<?php
		while($uye_bilgi = $yvt->fetchObject())
		{
		  $uyeno = $uye_bilgi->uyeno;
			$uyeadi = $uye_bilgi->uyeadi;
		  echo '<option value="'.$uyeno.'" selected="selected">'.$uyeno.' '.$uyeadi.'</option>';
		}
		} else {
		  echo $yvt->hataGoster();
		}
		?>
		</select>
		<?php
		$yvt->freeResult();
		unset($yvt);
		?>
		</td>
  </tr>
  <tr>
    <td  align="left" width="100%" class="main4">
      <table width="90%" align="center">
        <tr>
          <td colspan="2" align="center"><span style="color:#ff0000"><b>*</b></span>&nbsp;<?php echo $dil['Konu']; ?> : <input type="text" name="konu" value="<?php echo $konu; ?>" class="input" size="60" /></td>
				</tr>
				<tr>
          <td colspan="2" align="center"><span style="color:#ff0000"><b>*</b></span>&nbsp;<?php echo $dil['Mesaj']; ?><br />
          <textarea name="mesaj" id="mesaj"  style="background: #ffffff; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; height: 150px; width: 500px;"><?php echo $mesaj; ?></textarea></td>
        </tr>
        <tr>
          <td colspan="2" align="center"><input type="submit" value="<?php echo $dil['GONDER']; ?>" class="input" onclick="yukleniyor_ac();" /></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
</div>
<?php
//Hafiza Bosaltiliyor
if (@is_array($_SESSION['ome_gonder']))
{
  foreach($_SESSION['ome_gonder'] as $anahtar=>$deger)
  {
    unset(${$anahtar});
  }
	unset($_SESSION['ome_gonder']);
} 
//=================================
} else { // 2. ADIM BASLANGICI
//=================================
try {
  @ $mesajtur = intval($_POST['mesajtur']); //Mesaj Türü - Eposta veya Ozel Mesaj
	if (empty($mesajtur)) $mesajtur = 1;
	@ $kime     = intval($_POST['kime']);
	if (empty($kime)) $kime = 1;
	
	@ $uyeler   = $_POST['uyeler'];
	@ $konu     = $fonk->post_duzen($_POST['konu']);
	@ $mesaj    = $_POST['mesaj'];
  
	if ($mesajtur == 1)
  @ $mesaj    = $fonk->post_duzen($mesaj);
	
  $_SESSION['ome_gonder']['konu']     = serialize($konu);
	$_SESSION['ome_gonder']['mesaj']    = serialize($mesaj);
	$_SESSION['ome_gonder']['mesajtur'] = serialize($mesajtur);
	$_SESSION['ome_gonder']['kime']     = serialize($kime);
  $_SESSION['ome_gonder']['uyeler']   = serialize($uyeler);
	
	if (empty($konu) || empty($mesaj))
  {
    //Bos Alan Birakildi
    throw new Exception($dil["IsaretliAlanlariBosBirakmayiniz"]);
    exit;
  }
	$mk_vt = new Baglanti();
	if ($kime == 2)
	{
	  if (count($uyeler)==0)
		{
		  //Uye Secilmedi
			throw new Exception($dil['UyeSecmediniz']);
			exit;
		}
	}
	?>
	<div id="mesaj_gonderiliyor2" style="display:none"><table border="1" align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="#efeffa" bordercolor="#b6c5f2"><tr><td align="center"><br /><img src="../resim/gonderiliyor.gif" /><br /><font color="#008000"><?php echo $dil['GondermeIsleminiBekleyiniz']; ?></font><br />&nbsp;</td></tr></table></div>
	<?php
	if ($kime == 1)
  $kime_kosul = "";
  else
  $kime_kosul = " AND uyeno IN (".implode(',',$uyeler).")";
	$mk_vt->query("SELECT uyeno,uyeadi,eposta FROM ".TABLO_ONEKI."uyeler WHERE onay='E' AND yonay=5 ".$kime_kosul."");
	$gonderildi = 0;

	if ($mesajtur == 1)
	{
	  //OZEL MESAJ GONDERILIYOR
    $mkk_vt = new Baglanti();
		while ($kime_veri = $mk_vt->fetchObject())
		{
		  $uyeno = $kime_veri->uyeno;
			$mkk_vt->query2("INSERT INTO ".TABLO_ONEKI."ozelmesaj (kimden,kime,baslik,mesaj,tarih,okundu,cevaplandi) VALUES (0,".$uyeno.",'$konu','$mesaj',NOW(),'H',0)");
			$gonderildi++;
		}
	//==============================
	} else { // EPOSTA GONDERILIYOR
  //==============================
		$eposta_dizi = array();
		$gonderildi = 0;
    while ($kime_veri = $mk_vt->fetchObject())
		{
		  $uyeadi = $kime_veri->uyeadi;
			$eposta = $kime_veri->eposta;
			if ($eposta)
			{
				$eposta_dizi[$eposta] = $uyeadi;
				$gonderildi++;
			}
		}
		if (!$fonk->eposta_gonder($eposta_dizi,$konu,$mesaj,true,'html','../'))
		{
		  $gonderildi = 0;
		}
	}
	unset($_SESSION['ome_gonder']);
	$mk_vt->freeResult();
	echo "<script language=\"javascript\">yukleniyor_kapat();document.getElementById('mesaj_gonderiliyor2').style.display='none';</script>";
	echo '<table border="1" align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="#efeffa" bordercolor="#b6c5f2"><tr><td align="center"><br /><font color="#008000">'.$dil['GondermeIslemiTamamlandi'].'</font><br />'.$dil['ToplamGonderilenKisi'].' : '.$gonderildi.'';
	echo '<br />&nbsp;</td></tr></table>';
	
} //try Sonu  
catch (Exception $e)
{
echo "<script>alert('".$e->getMessage()."');location.href='omesaj_eposta.php';</script>";
} //catch Sonu
//============================
} // 2. ADIM SONU
//============================
?>
</body>
</html>