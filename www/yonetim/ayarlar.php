<?php
ob_start();
session_start();
require_once ("../icerik/vt.inc.php");
require_once ("../icerik/ayar.inc.php");
require_once ("../icerik/dil.inc.php");
dil_belirle('','yonetimdil');
require_once ("../icerik/sev.inc.php");
require_once ("../icerik/fonk.inc.php");


//Yonetici Girisi Yapilmamissa Yasakla
if (UYE_SEVIYE < 6) 
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
<script language="JavaScript">
<!-- Begin
function ayar_kontrol() 
{
  hata_mesaj = "";
  if (document.ayarlar.siteadi.value == "") 
  {
    hata_mesaj += "\n     -  <?php echo $dil['SiteAdiniBosBiraktiniz']; ?>";
  }

  if (document.ayarlar.siteeposta.value == "") 
  {
    hata_mesaj += "\n     -  <?php echo $dil['SiteEpostaAdresiniBosBiraktiniz']; ?>";
  }

  if (document.ayarlar.siteadresi.value == "") 
  {
    hata_mesaj += "\n     -  <?php echo $dil['SiteAdresiniBosBiraktiniz']; ?>";
  }
    
  if (hata_mesaj != "") 
  {
    hata_mesaj ="<?php echo $dil['AsagidakiAlanlardaHatalarVar']; ?>:" +
    "\n____________________________________\n" +
    hata_mesaj + 
    "\n____________________________________" +
    "\n<?php echo $dil['LutfenTekrarDuzenleyiniz']; ?>";
    alert(hata_mesaj);
    return false;
  } else { 
	return true;
  }
}
function bolumler(bolumid)
{
  if (bolumid)
  {
    var divv = document.getElementById(bolumid);
    return (divv.style.display == 'block'?divv.style.display='none':divv.style.display='block');
  }
}
//-->
</script>
</head>
<body background="yonetimresim/bg.gif">

<?php
@ $sayfa = $_GET['sayfa'];

if (!$sayfa) 
{

?>
<div align="center">
<form name="ayarlar" action="ayarlar.php?sayfa=2" method="post" onsubmit="return ayar_kontrol()">
<table border="1" align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b><?php echo $dil['AYARLAR']; ?></b></font></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
		    <tr bgcolor="#b6c5f2">
		      <td  align="center" height="20" class="border_4" style="cursor:pointer" onclick="bolumler('a1');"><b><?php echo $dil['SiteAyarlari']; ?></b></td>
		    </tr>
        <tr>
          <td colspan="2">
			      <div id="a1" style="display:block">
		        <table width="100%">
		          <tr bgcolor="#ffffff">
		            <td width="35%" height="25%" align="right"><b><?php echo $dil['SiteAdi']; ?>  :</b>&nbsp;&nbsp;</td>
		            <td width="75%" height="25%" align="left"><input type="text" id="siteadi" name="siteadi" value="<?php echo SITE_ADI; ?>" class="input" size="60" maxlength="100" /></td>
		          </tr>
		          <tr bgcolor="#ffffff">
		            <td width="35%" height="25%" align="right"><b><?php echo $dil['SiteEpostaAdresi']; ?> :</b>&nbsp;&nbsp;</td>
		            <td width="75%" height="25%" align="left"><input type="text" id="siteeposta" name="siteeposta" value="<?php echo SITE_EPOSTA; ?>" class="input" size="40" maxlength="100" /></td>
		          </tr>
		          <tr bgcolor="#ffffff">
		            <td width="35%" height="25%" align="right"><b><?php echo $dil['SiteWebAdresi']; ?> :</b>&nbsp;&nbsp;</td>
		            <td width="75%" height="25%" align="left"><input type="text" id="siteadresi" name="siteadresi" value="<?php echo SITE_ADRESI; ?>" class="input" size="40" maxlength="100" /></td>
		          </tr>
							<tr bgcolor="#ffffff">
		            <td width="35%" height="25%" align="right"><b><?php echo $dil['SiteDil']; ?> :</b>&nbsp;&nbsp;</td>
		            <td width="75%" height="25%" align="left"><select name="sitedil" id="sitedil" class="input">
								<option value="0"><?php echo $dil['OtomatikBelirle']; ?></option>
								<?php
								foreach($dil_ayar as $dil_kisaad=>$dil_uzunad)
								{
								  echo '<option value="'.$dil_kisaad.'"'; if (SITE_DIL == $dil_kisaad) echo ' selected="selected".'; echo '>'.$dil_uzunad[1].'</option>';
								}
								?>
								</select>&nbsp;<?php echo $dil['VarsayilanSiteDil']; ?></td>
		          </tr>
							<tr bgcolor="#ffffff">
		            <td width="35%" height="25%" align="right"><b><?php echo $dil['SiteTema']; ?> :</b>&nbsp;&nbsp;</td>
		            <td width="75%" height="25%" align="left">
                <select name="sitetema" id="sitetema" class="input">
								<?php
								$tema = dir('../tema');
								while (false !== ($temaadi = $tema->read()))
								{
									if (is_dir('../'.TEMA_KLASOR.'/'.$temaadi) && $temaadi !== '.' && $temaadi != '..')
								  echo '<option value="'.$temaadi.'"'; if (SITE_TEMA == TEMA_KLASOR.'/'.$temaadi) echo ' selected="selected".'; echo '>'.$temaadi.'</option>';
								}
								?>
								</select>&nbsp;</td>
		          </tr>
            </table>
				    </div>
          </td>
		    </tr>
			</table>
		</td>
  </tr>
  <tr bgcolor="#b6c5f2">
    <td align="center" height="20" class="border_4" style="cursor:pointer" onclick="bolumler('a2');"><b><?php echo $dil['UyelikAyarlari']; ?></b></td>
  </tr>
  <tr>
    <td width="100%">
      <div id="a2" style="display: none">
      <table width="100%">
        <tr bgcolor="#ffffff">
          <td width="35%" height="25%" align="right"><b><?php echo $dil['UyelikOnaylama']; ?></b>&nbsp;&nbsp;</td>
          <td width="75%" height="25%" align="left">
			      <select id="uyelikonayi" name="uyelikonayi" class="input">
						<?php
						echo '
				    <option value="1"'; if (UYELIK_ONAYI == 1){ echo' selected="selected"'; } echo '>'.$dil['HemenUyelik'].'</option>
				    <option value="2"'; if (UYELIK_ONAYI == 2){ echo' selected="selected"'; } echo '>'.$dil['YoneticiOnayliUyelik'].'</option>
				    <option value="3"'; if (UYELIK_ONAYI == 3){ echo ' selected="selected"'; } echo '>'.$dil['EpostaOnayliUyelik'].'</option>
				    <option value="4"'; if (UYELIK_ONAYI == 4){ echo ' selected="selected"'; } echo '>'.$dil['EpostaYoneticiOnayliUyelik'].'</option>';
						?>
			      </select></td>
		      </tr>
		      <tr bgcolor="#ffffff">
		        <td width="35%" height="25%" align="right"><b><?php echo $dil['OnaylanmayanUyelerinSilinmesi']; ?> :</b>&nbsp;&nbsp;</td>
		        <td width="75%" height="25%" align="left">
            <select id="uyesilmezamani" name="uyesilmezamani" class="input">
						<?php
		        for($s=1; $s<=60; $s++) 
						{
			        echo '<option value="'.$s.'"';
			        if ($s == UYE_SILME_ZAMANI) { echo ' selected="selected"'; }
			        echo '>'.$s.' '.$dil['Saat'].'</option>';
		        }
						?>
		        </select> <?php echo $dil['KoduOnaylamamisUyeyiSil']; ?></td>
		      </tr>
					 <tr bgcolor="#ffffff">
		        <td width="35%" height="25%" align="right"><b><?php echo $dil['GuncellemeSonrasiOnayDurumu']; ?> :</b>&nbsp;&nbsp;</td>
		        <td width="75%" height="25%" align="left">
            <select id="epostadegisti" name="epostadegisti" class="input">
		        <option value="0"<?php if (EPOSTA_DEGISTI==0) echo ' selected="selected"'; ?>><?php echo $dil['EpostaDegisikligindeUyeOnayli']; ?></option>
						<option value="1"<?php if (EPOSTA_DEGISTI==1) echo ' selected="selected"'; ?>><?php echo $dil['EpostaDegisikligindeUyeAskida']; ?></option>
						<option value="2"<?php if (EPOSTA_DEGISTI==2) echo ' selected="selected"'; ?>><?php echo $dil['EpostaDegisikligindeUyeOnayKoduGonder']; ?></option>
		        </select>&nbsp;<?php echo $dil['UyeEpostaDegisikligindeIslem']; ?></td>
		      </tr>
					 <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['UyeBilgileriniGormeIzinleri']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="uyegormeizin" name="uyegormeizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == UYE_GORME_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['IleUstSeviyedekiUyelerGorebilir']; ?></td>
          </tr>
		      <tr bgcolor="#ffffff">
		        <td width="35%" height="25%" align="right"><b><?php echo $dil['UyeSeviyeIsimleri']; ?> :</b>&nbsp;&nbsp;</td>
		        <td width="75%" height="25%" align="left">
            <b>1- <input type="text" name="uye1" id="uye1" value="<?php echo $seviyeler[1]; ?>" class="input" /> : <?php echo $dil['NormalUye']; ?><br />
			      2- <input type="text" name="uye2" id="uye2" value="<?php echo $seviyeler[2]; ?>" class="input" /> : <?php echo $dil['2SeviyeUye']; ?><br />
			      3- <input type="text" name="uye3" id="uye3" value="<?php echo $seviyeler[3]; ?>" class="input" /> : <?php echo $dil['3SeviyeUye']; ?><br />
			      4- <input type="text" name="uye4" id="uye4" value="<?php echo $seviyeler[4]; ?>" class="input" /> : <?php echo $dil['4SeviyeUye']; ?><br />
			      5- <input type="text" name="uye5" id="uye5" value="<?php echo $seviyeler[5]; ?>" class="input" /> : <?php echo $dil['Yonetici']; ?><br />
						6- <input type="text" name="uye6" id="uye6" value="<?php echo $seviyeler[6]; ?>" class="input" /> : <?php echo $dil['SiteSahibi']; ?></b></td>
		      </tr>
        </table>
		  </div>
		 </td>
		</tr>
    <tr bgcolor="#b6c5f2">
      <td align="center" height="20" class="border_4" style="cursor:pointer" onclick="bolumler('a3');"><b><?php echo $dil['KayitVeGirisAyarlari']; ?></b></td>
  </tr>
  <tr>
    <td width="100%">
      <div id="a3" style="display:none">
      <table width="100%">
        <tr bgcolor="#ffffff">
          <td width="35%" height="25%" align="right"><b><?php echo $dil['GirisDenemeSayisi']; ?> :</b>&nbsp;&nbsp;</td>
          <td width="75%" height="25%" align="left">
          <select id="girisdenemesayisi" name="girisdenemesayisi" class="input">
          <?php
          for  ($g=1; $g<=60; $g++) 
          {
            echo '<option value="'.$g.'"'; 
						if ($g == GIRIS_DENEME_SAYISI) echo ' selected="selected"'; 
						echo '>'.$g.' '.$dil['Kez'].'</option>';
          }
          ?>
          </select> <?php echo $dil['GirisDenemesindenSonraBeklet']; ?>
          </td>
        </tr>
        <tr bgcolor="#ffffff">
          <td width="35%" height="25%" align="right"><b><?php echo $dil['GirisDenemeSuresi']; ?></b>&nbsp;&nbsp;</td>
          <td width="75%" height="25%" align="left">
          <select id="girisdenemesuresi" name="girisdenemesuresi" class="input">
          <?php
          for  ($s=1; $s<=60; $s++) 
          {
            echo '<option value="'.$s.'"';
            if ($s == GIRIS_DENEME_SURESI) echo ' selected="selected"';
            echo '>'.$s.' '.$dil['Dakika'].'</option>';
          }
          ?>
          </select>&nbsp;<?php echo $dil['GirisHakkiDoluncaBeklet']; ?></td>
        </tr>
        <tr bgcolor="#ffffff">
          <td width="35%" height="25%" align="right"><b><?php echo $dil['KayitArasiSure']; ?></b>&nbsp;&nbsp;</td>
          <td width="75%" height="25%" align="left">
          <select id="kayitarasisure" name="kayitarasisure" class="input">
          <?php
          for  ($d=1; $d<=60; $d++) 
          {
            echo '<option value="'.$d.'"';
            if ($d == UYE_KAYIT_ARASI_SURE) { echo ' selected="selected"'; }
            echo '>'.$d.' '.$dil['Dakika'].'</option>';
          }
          ?>
          </select>  <?php echo $dil['IcindeYeniKayitEklenmesin']; ?>
        </td>
      </tr>
      <tr bgcolor="#ffffff">
        <td width="35%" height="25%" align="right"><b><?php echo $dil['UyelikKaydiDurdurma']; ?></b>&nbsp;&nbsp;</td>
        <td width="75%" height="25%" align="left">
        <select id="uyekayitkapat" name="uyekayitkapat" class="select">
        <?php
        echo '<option value="E"'; if (UYE_KAYIT_KAPAT == 'E') { echo ' selected="selected"'; } echo '>'.$dil['EVET'].'</option><option value="H"'; if (UYE_KAYIT_KAPAT == 'H') { echo 'selected="selected"'; } echo '>'.$dil['HAYIR'].'</option>';
        ?>
        </select> <?php echo $dil['UyeKaydiKapat']; ?>
        </td>
      </tr>	    
    </table>
    </div>
    </td>
  </tr>
	
  <tr bgcolor="#b6c5f2">
		  <td align="center" height="20" class="border_4" style="cursor:pointer" onclick="bolumler('a4');"><b><?php echo $dil['YaziDuzenlemeAyarlari']; ?></b></a></td>
		</tr>
    <tr>
      <td width="100%">
			  <div id="a4" style="display:none">
        <table width="100%">
				  <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YaziDuzenlemeIzni']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="yaziduzenlemeizin" name="yaziduzenlemeizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == YAZI_DUZENLEME_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyeUyelerYazilariniDuzenleyebilir']; ?></td>
          </tr>
          <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YaziDuzenlemeSuresi']; ?> :</b>&nbsp;&nbsp;</td>
              <td width="75%" height="25%" align="left">
              <select id="yaziduzenlemesuresi" name="yaziduzenlemesuresi" class="select">
							<?php
              for ($i=1; $i<25; $i++)
              {
                echo '<option value="'.$i.'"';
                if (YAZI_DUZENLEME_SURESI == $i) { echo ' selected="selected"'; } echo '>'.$i.' '.$dil['Saat'].'</option>';
              }
							?>
              </select>&nbsp;<?php echo $dil['AltSeviyeUyelerIcinDuzenlemeSuresi']; ?></td>
            </tr>	
		        <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YaziEklemeArasiSure']; ?> :</b>&nbsp;&nbsp;</td>
              <td width="75%" height="25%" align="left">
              <select id="yazikayitarasisure" name="yazikayitarasisure" class="select">
							<?php
              for ($i=1; $i<25; $i++)
              {
                echo '<option value="'.$i.'"';
                if (YAZI_KAYIT_ARASI_SURE == $i) { echo ' selected="selected"'; } echo '>'.$i.' '.$dil['Dakika'].'</option>';
		  
              }
							?>
              </select> <?php echo $dil['AyniUyeYaziEklemeSuresi']; ?></td>
            </tr>	
          <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YaziEklemeIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="yazieklemeizin" name="yazieklemeizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == YAZI_EKLEME_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyedekiUyelerYaziEklesin']; ?></td>
          </tr>	
					
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YaziOnay']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="yazionay" name="yazionay" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == YAZI_ONAY) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyedekiUyelerinYazilariOnayliOlsun']; ?></td>
          </tr>
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YaziOzetiOkumaIzni']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="yaziokumaizin" name="yaziokumaizin" class="select">
						<option value="0"><?php echo $dil['Herkes']; ?></option>
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == YAZI_OKUMA_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyedekiUyelerYaziOzetiniOkuyabilsin']; ?></td>
          </tr>	
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YaziTamamiOkumaIzni']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="yaziayrintiokumaizin" name="yaziayrintiokumaizin" class="select">
						<option value="0"><?php echo $dil['Herkes']; ?></option>
            <?php
						foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == YAZI_AYRINTI_OKUMA_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyedekiUyelerYaziTamaminiOkuyabilsin']; ?></td>
          </tr>
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YaziKarakter']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left"><input type="index" name="yazikarakter" id="yazikarakter" value="<?php echo YAZI_KARAKTER; ?>" class="input" size="6" maxlength="10" /> <?php echo $dil['YaziKarakterSayi']; ?></td>
          </tr>	
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YaziOylama']; ?></b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <select id="yazioylama" name="yazioylama" class="select">
            <?php
            echo '<option value="E"'; if (YAZI_OYLAMA == 'E') { echo ' selected="selected"'; } echo '>'.$dil['EVET'].'</option><option value="H"'; if (YAZI_OYLAMA == 'H') { echo 'selected="selected"'; } echo '>'.$dil['HAYIR'].'</option>';
            ?>
            </select>
            </td>
          </tr>	    		
				</table>
				</div>
			</td>
		</tr>
    <tr bgcolor="#b6c5f2">
		  <td  align="center" height="20" class="border_4" style="cursor:pointer" onclick="bolumler('a5');"><b><?php echo $dil['YorumEklemeDuzenlemeAyarlari']; ?></b></td>
		</tr>
		<tr>
      <td width="100%">
			  <div id="a5" style="display:none">
        <table width="100%">
          <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YorumEklemeIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <select id="yorumeklemeizin" name="yorumeklemeizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye==YORUM_EKLEME_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
            </select>&nbsp;&nbsp;<?php echo $dil['SeviyedekiUyelerYorumEklesin']; ?></td>
          </tr>
          <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YorumOnayAyari']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <select id="yorumonay" name="yorumonay" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye==YORUM_ONAY) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
            </select>&nbsp;&nbsp;<?php echo $dil['SeviyedekiUyelerinYorumlariOnayliOlsun']; ?></td>
          </tr>
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['IkiYorumArasiSure']; ?></b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <select id="yorumarasisure" name="yorumarasisure" class="select">
						  <?php
              for ($i=1; $i<25; $i++)
              {
                echo '<option value="'.$i.'"';
                if (YORUM_ARASI_SURE == $i) { echo ' selected="selected"'; } echo '>'.$i.' '.$dil['Dakika'].'</option>';
		  
              }
							?>
              </select>&nbsp;&nbsp;<?php echo $dil['KacDakikaIcindeYeniYorumYazilsin']; ?></td>
          </tr>	
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['YorumKarakterSayi']; ?></b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <input type="text" name="yorumkarakter" id="yorumkarakter" class="input" value="<?php echo YORUM_KARAKTER; ?>" size="6" /></td>
          </tr>			
        </table>
				</div>
			</td>
		</tr>
		<tr bgcolor="#b6c5f2">
		  <td align="center" height="20" class="border_4" style="cursor:pointer" onclick="bolumler('a6');"><b><?php echo $dil['HizliMesajAyarlari']; ?></b></td>
		</tr>
		<tr>
		  <td width="100%">
			  <div id="a6" style="display:none">
			  <table width="100%">
				  <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['HizliMesajEklemeIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <select id="hizlimesajeklemeizin" name="hizlimesajeklemeizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == HIZLI_MESAJ_EKLEME_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
            </select>&nbsp;<?php echo $dil['SeviyedekiUyelerHizliMesajEklesin']; ?></td>
          </tr>	
          <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['MesajOnayAyari']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <select id="hizlimesajonay" name="hizlimesajonay" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye==HIZLI_MESAJ_ONAY) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
            </select>&nbsp;</td>
          </tr>	
          <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['HizliMesajAraligi']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <select id="hizlimesajsure" name="hizlimesajsure" class="select">
						<?php
            for ($i=1; $i<61; $i++)
            {
              echo '<option value="'.$i.'"';
              if (HIZLI_MESAJ_SURE == $i) { echo ' selected="selected"'; } echo '>'.$i.' '.$dil['Dakika'].'</option>';
            }
						?>
            </select> <?php echo $dil['AyniUyeHizliMesajEklemeSuresi']; ?></td>
          </tr>	
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['HizliMesajKarakterSayi']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
           <input type="text" name="hizlimesajkarakter" id="hizlimesajkarakter" value="<?php echo HIZLI_MESAJ_KARAKTER; ?>" size="6" class="input" /></td>
          </tr>	
        </table>
				</div>
			</td>
		</tr>
		<tr bgcolor="#b6c5f2">
		  <td align="center" height="20" class="border_4" style="cursor:pointer" onclick="bolumler('a7');"><b><?php echo $dil['OzelMesajAyarlari']; ?></b></td>
		</tr>
		<tr>
		  <td width="100%">
			  <div id="a7" style="display:none">
        <table width="100%">
				  <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['OzelMesajGondermeIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <select id="ozelmesajgondermeizin" name="ozelmesajgondermeizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == OZEL_MESAJ_GONDERME_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
            </select>&nbsp;<?php echo $dil['SeviyedekiUyelerOzelMesajGondersin']; ?></td>
          </tr>	
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['OzelMesajIsimGormeIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <select id="ozelmesajisimizin" name="ozelmesajisimizin" class="select">
						   <?php
               foreach($seviyeler AS $seviye => $seviyeadi)
						   {
						      echo '<option value="'.$seviye.'"'; if ($seviye == OZEL_MESAJ_ISIM_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						    }
						    ?>
                </select>&nbsp;<?php echo $dil['SeviyedekiUyelerDigerUyeIsimleriniGorsun']; ?></td>
          </tr>	
          <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['OzelMesajAraligi']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <select id="ozelmesajarasisure" name="ozelmesajarasisure" class="select">
            <?php
            for ($i=1; $i<61; $i++)
            {
              echo '<option value="'.$i.'"';
              if (OZEL_MESAJ_ARASI_SURE == $i) { echo ' selected="selected"'; } echo '>'.$i.' '.$dil['Dakika'].'</option>';
            }
            ?>
            </select>&nbsp;<?php echo $dil['AyniUyeOzelMesajGondermeSuresi']; ?></td>
          </tr>	
          <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['OzelMesajKarakterSayi']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
           <input type="text" name="ozelmesajkarakter" id="ozelmesajkarakter" value="<?php echo OZEL_MESAJ_KARAKTER; ?>" size="6" class="input" /></td>
          </tr>	
          <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['OzelMesajKlasorIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <input type="text" name="ozelmesajizin" id="ozelmesajizin" value="<?php echo OZEL_MESAJ_IZIN; ?>" size="6" class="input" />&nbsp;<?php echo $dil['GelenKlasordeTutulacakOzelMesaj']; ?></td>
          </tr>	
        </table>
        </div>
      </td>
    </tr>
    <tr bgcolor="#b6c5f2">
		  <td align="center" height="20" class="border_4" style="cursor:pointer" onclick="bolumler('a8');"><b><?php echo $dil['ResimGaleriAyarlari']; ?></b></a></td>
		</tr>
    <tr>
      <td width="100%">
			  <div id="a8" style="display:none">
        <table width="100%">
				  <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['ResimGormeIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="galeriresimgormeizin" name="galeriresimgormeizin" class="select">
						<option value="0"><?php echo $dil['Herkes']; ?></option>
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == GALERI_RESIM_GORME_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyeUyeResimGorsun']; ?></td>
          </tr>
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['ResimEklemeIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="galeriresimeklemeizin" name="galeriresimeklemeizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == GALERI_RESIM_EKLEME_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyeUyeResimEklesin']; ?></td>
          </tr>
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['ResimEklemeArasiSure']; ?> :</b>&nbsp;&nbsp;</td>
              <td width="75%" height="25%" align="left">
              <select id="galeriresimkayitsure" name="galeriresimkayitsure" class="select">
							<?php
              for ($i=1; $i<25; $i++)
              {
                echo '<option value="'.$i.'"';
                if (GALERI_RESIM_KAYIT_SURE == $i) { echo ' selected="selected"'; } echo '>'.$i.' '.$dil['Dakika'].'</option>';
		  
              }
							?>
              </select> <?php echo $dil['AyniUyeResimEklemeSuresi']; ?></td>
          </tr>	
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['ResimDuzenIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="galeriresimduzenizin" name="galeriresimduzenizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == GALERI_RESIM_DUZEN_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyeUyelerResimDuzenleyebilir']; ?></td>
          </tr>	
          <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['ResimDuzenSure']; ?> :</b>&nbsp;&nbsp;</td>
              <td width="75%" height="25%" align="left">
              <select id="galeriresimduzensure" name="galeriresimduzensure" class="select">
							<?php
              for ($i=1; $i<25; $i++)
              {
                echo '<option value="'.$i.'"';
                if (GALERI_RESIM_DUZEN_SURE == $i) { echo ' selected="selected"'; } echo '>'.$i.' '.$dil['Saat'].'</option>';
              }
							?>
              </select>&nbsp;<?php echo $dil['AltSeviyeUyelerIcinDuzenlemeSuresi']; ?></td>
          </tr>	

					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['ResimOnay']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="galeriresimonay" name="galeriresimonay" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == GALERI_RESIM_ONAY) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyedekiUyelerinResimleriOnayliOlsun']; ?></td>
          </tr>
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['ResimOylama']; ?></b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
            <select id="galeriresimoylama" name="galeriresimoylama" class="select">
            <?php
            echo '<option value="E"'; if (GALERI_RESIM_OYLAMA == 'E') { echo ' selected="selected"'; } echo '>'.$dil['EVET'].'</option><option value="H"'; if (GALERI_RESIM_OYLAMA == 'H') { echo 'selected="selected"'; } echo '>'.$dil['HAYIR'].'</option>';
            ?>
            </select>
            </td>
          </tr>	
					
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['AlbumEklemeIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="galerialbumeklemeizin" name="galerialbumeklemeizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == GALERI_ALBUM_EKLEME_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyeUyeAlbumEklesin']; ?></td>
          </tr>
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['AlbumEklemeArasiSure']; ?> :</b>&nbsp;&nbsp;</td>
              <td width="75%" height="25%" align="left">
              <select id="galeriresimkayitsure" name="galeriresimkayitsure" class="select">
							<?php
              for ($i=1; $i<25; $i++)
              {
                echo '<option value="'.$i.'"';
                if (GALERI_ALBUM_KAYIT_SURE == $i) { echo ' selected="selected"'; } echo '>'.$i.' '.$dil['Dakika'].'</option>';
		  
              }
							?>
              </select> <?php echo $dil['AyniUyeAlbumEklemeSuresi']; ?></td>
          </tr>	
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['AlbumDuzenIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="galerialbumduzenizin" name="galerialbumduzenizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == GALERI_ALBUM_DUZEN_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyeUyelerAlbumDuzenleyebilir']; ?></td>
          </tr>	
          <tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['AlbumDuzenSure']; ?> :</b>&nbsp;&nbsp;</td>
              <td width="75%" height="25%" align="left">
              <select id="galerialbumduzensure" name="galerialbumduzensure" class="select">
							<?php
              for ($i=1; $i<25; $i++)
              {
                echo '<option value="'.$i.'"';
                if (GALERI_ALBUM_DUZEN_SURE == $i) { echo ' selected="selected"'; } echo '>'.$i.' '.$dil['Saat'].'</option>';
              }
							?>
              </select>&nbsp;<?php echo $dil['AltSeviyeUyelerIcinDuzenlemeSuresi']; ?></td>
          </tr>	

					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['AlbumOnay']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="galerialbumonay" name="galerialbumonay" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == GALERI_ALBUM_ONAY) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyedekiUyelerinAlbumleriOnayliOlsun']; ?></td>
          </tr>   
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['AlbumSayi']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="galerialbumeklemesayi" name="galerialbumeklemesayi" class="select">
						<?php
            for ($i=1; $i<26; $i++)
						{
						  echo '<option value="'.$i.'"'; if ($i == GALERI_ALBUM_EKLEME_SAYI) echo ' selected="selected"'; echo '>'.$i.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['AlbumEklemeSayi']; ?></td>
          </tr>    		 		
				</table>
				</div>
			</td>
		</tr>	
		<tr bgcolor="#b6c5f2">
		  <td align="center" height="20" class="border_4" style="cursor:pointer" onclick="bolumler('a9');"><b><?php echo $dil['SozlukAyarlari']; ?></b></a></td>
		</tr>
		<tr>
      <td width="100%">
			  <div id="a9" style="display:none">
        <table width="100%">
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['SozcukEklemeIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="sozcukeklemeizin" name="sozcukeklemeizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == SOZCUK_EKLEME_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['VeUstSeviyedekiUyeler']; ?></td>
          </tr>
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['SozcukDuzenlemeIzin']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="sozcukduzenlemeizin" name="sozcukduzenlemeizin" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == SOZCUK_DUZENLEME_IZIN) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['VeUstSeviyedekiUyeler']; ?></td>
          </tr>
					<tr bgcolor="#ffffff">
            <td width="35%" height="25%" align="right"><b><?php echo $dil['SozcukOnay']; ?> :</b>&nbsp;&nbsp;</td>
            <td width="75%" height="25%" align="left">
						<select id="sozcukonay" name="sozcukonay" class="select">
						<?php
            foreach($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($seviye == SOZCUK_ONAY) echo ' selected="selected"'; echo '>'.$seviyeadi.'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['SeviyedekiUyelerinSozcukleriOnayliOlsun']; ?></td>
          </tr>
				</table>
			</div>
		</td>
		</tr>	
    <tr>
      <td align="center" valign="center" height="30"><input type="submit" value="..:: <?php echo $dil['AyarlariKaydet']; ?> ::.." class="input" /></td>
    </tr>
  </table>
</td>
</tr>
</table>
</form>
</div>
<?php
} else {
  foreach ($_POST as $anahtar=>$deger ) 
  {
    if ( gettype ($deger ) != "array" ) 
    {
      $vt->query2("UPDATE ".TABLO_ONEKI."yonetim SET $anahtar='".$fonk->post_duzen($deger)."' WHERE ayarno=1");
    }
  }
  echo "<SCRIPT>alert('".$dil['SiteAyarlariKaydedildi']."');location.href='ayarlar.php';</SCRIPT>";
}

?>
</body>
</html>
