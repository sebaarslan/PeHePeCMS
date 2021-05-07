<?php
ob_start();
session_start();
require_once ("../icerik/vt.inc.php");
require_once ("../icerik/ayar.inc.php");
require_once ("../icerik/dil.inc.php");
@ dil_belirle('','yonetimdil');
require_once ("../icerik/sev.inc.php");
require_once ("../icerik/fonk.inc.php");

function dill()
{
  $secilen_dil = $_SESSION['oturum_dil'];
	if (empty($secilen_dil))
	$secilen_dil = 'tr';
	$dil_dosyasi = '../dil/'.$secilen_dil.'.php';

	if (!file_exists($dil_dosyasi))
	{
	  header('Location: ../index.php');
		exit;
	}
  require_once($dil_dosyasi);
	return $dil;
}
//Yönetici Girişi Yapılmamışsa Yasakla
if (UYE_SEVIYE < 5) 
{
  header('Location: ../index.php');
  exit;
}
$fonk         = new Fonksiyon();
@ $islem      = intval($_GET['islem']);
@ $sayfa      = intval($_GET['sayfa']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo SITE_ADI; ?> : <?php echo $dil['YonetimPaneli']; ?></title>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="ystil.css" />
</head>
<?php
function menu_ust_sira($menugrup=1,$menuno=0)
{
  if ($menugrup==1)
	$sira_alan = 'menu1sira';
	elseif($menugrup==2)
	$sira_alan = 'menu2sira';
	else
	$sira_alan = 'menu1sira';
	
  $vt = new Baglanti();
  $menuustsira = $vt->kayitSay("SELECT COUNT($sira_alan) AS ustsira FROM ".TABLO_ONEKI."menuler WHERE menuno<>".$menuno." AND (menugrup=$menugrup OR menugrup=0)");
	unset($vt);
  return intval($menuustsira);
}

$hata_kod = false;
$kayit_duzen_mesaj = '';
$menuno        = 0;
$menuanahtar   = '';
$menugrup      = 1;
$menuresim     = '';
$menuadi       = '';
$menudil       = 'E';
$menusayfaadi  = '';
$menusayfadil  = 'E';
$menudescription = 'PeHePe Üyelik Sistemi';
$menukeywords    = 'Üyelik Sistemi, Uyelik Sistemi';
$menuadres     = '';
$menuait       = 1;
$menuhedef     = '_self';
$menuizin      = 'E';
$menuduzen     = 'E';
$menudurum     = 'A';
$menuduzenleme = false;
$dosyaduzenle  = 0;
$dosyaicerik   = '&lt;php
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;
?&gt;';

if ($islem == 1)
{
  try
	{
    // MENU EKLEME / DUZENLEME
    $menuno        = intval($_POST['menuno']);
		$menuanahtar   = $fonk->post_duzen(strtolower($_POST['menuanahtar']));
		$menugrup      = intval($_POST['menugrup']);
		$menuresim     = $fonk->post_duzen($_POST['menuresim']);
		$menuadi       = $fonk->post_duzen($_POST['menuadi']);
		$menudil       = $fonk->post_duzen($_POST['menudil']);
		if ($menudil != 'E')
		$menudil       = 'H';
		$menusayfaadi  = $fonk->post_duzen($_POST['menusayfaadi']);
		$menusayfadil  = $fonk->post_duzen($_POST['menusayfadil']);
		if ($menusayfadil != 'E')
		$menusayfadil  = 'H';
		$menudescription = $fonk->post_duzen($_POST['menudescription']);
		$menukeywods     = $fonk->post_duzen($_POST['menukeywords']);
		$menuadres     = $fonk->post_duzen($_POST['menuadres']);
		$menuait       = intval($_POST['menuait']);

		$menuhedef     = $fonk->post_duzen($_POST['menuhedef']);
		if (!$menuhedef)
		$menuhedef     = '_self';
		$menuizin      = intval($_POST['menuizin']);
		$menudurum     = $fonk->post_duzen($_POST['menudurum']);
		if ($menudurum != 'A')
		$menudurum     = 'P';

		
		$dosyaduzenle  = intval($_POST['dosyaduzenle']);
		$dosyaicerik   = $_POST['dosyaicerik'];
    if (get_magic_quotes_gpc())
    $dosyaicerik = stripslashes($dosyaicerik);
    
		if (!empty($menuno))
		$menuduzenleme = true;
		
		if (!$menuadi)
		{
		  throw new Exception($dil['IsaretliAlanlariBosBirakmayiniz']);
			exit;
		} elseif ($vt->kayitSay("SELECT COUNT(menuno) FROM ".TABLO_ONEKI."menuler WHERE menuno<>$menuno AND menuanahtar='$menuanahtar'")>0) {
		    throw new Exception($dil['MenuAnahtarKullanimda']);
			  exit;
		 }
		 
	  
		if (empty($menuno))
		{
		  //Kayit Veritabani Sinifi
		   $mk_vt = new Baglanti();
			 //Kayit Veritabani Sinifi
		  if (!$menuadres || !$menuanahtar)
		  {
		    throw new Exception($dil['IsaretliAlanlariBosBirakmayiniz']);
			  exit;
		  }
			$dizin = pathinfo('../'.$menuadres);
			
			if ($menuait==1 || is_dir($dizin['dirname']))
			{  
        //Dosya Olusturuluyor
				if ($mk_vt->kayitSay("SELECT COUNT(menuno) FROM ".TABLO_ONEKI."menuler WHERE menuadres='$menuadres' AND menuait=1")==0)
        {
          if (!is_file('../'.$menuadres))
          {
            
            @$fp = fopen('../'.$menuadres,'w+');
            if (!$fp)
            {
              fwrite($fp,$dosyaicerik);
				    } else {
              throw new Exception($dil['DosyaOlusturulamadi']);
              exit;
            }
            fclose($fp);
					}
				}
			}
			
			
		  //KAYIT BOLUMU
		 
			//Menu Kayit Icin Sira Belirleniyor
		  $menusira = menu_ust_sira($menugrup)+1;
		  if ($menugrup==1)
		  {
		    $menu1sira = $menusira;
			  $menu2sira = 0;
		  } elseif ($menugrup == 2) {
		    $menu1sira = 0;
			  $menu2sira = $menusira;
		  } else {
		    $menu1sira = $menusira;
			  $menu2sira = $menusira;
		  }
		  //KAYIT ISLEMI YAPILIYOR
		  $mk_vt->query("INSERT INTO ".TABLO_ONEKI."menuler (`menugrup`,`menuanahtar`,`menuresim`,`menuadi`,`menudil`,`menusayfaadi`,`menusayfadil`,`menudescription`,`menukeywords`,`menuadres`,`menuait`,`menuhedef`,`menu1sira`,`menu2sira`,`menuizin`,`menuduzen`,`menudurum`) 
			VALUES ($menugrup,'$menuanahtar','$menuresim','$menuadi','$menudil','$menusayfaadi','$menusayfadil','$menudescription','$menukeywords','$menuadres',$menuait,'$menuhedef',$menu1sira,$menu2sira,'$menuizin','E','$menudurum')");
			$menuno = $mk_vt->insertID();
			

			unset($mk_vt,$dosyaicerik);
			$hata_kod = true;
		  
      throw new Exception($dil['KayitIslemiTamamlandi']);
    } else {
		  //DUZENLEME BOLUMU
      $md_vt = new Baglanti();
			//Menu Duzen Icin Sira Belirleniyor
			$md_vt->query("SELECT menugrup,menu1sira,menu2sira,menuduzen,menuadres,menuait FROM ".TABLO_ONEKI."menuler WHERE menuno=$menuno");

			$menu_veri = $md_vt->fetchObject();
			$menu_grup = $menu_veri->menugrup;
			$menu1_sira = $menu_veri->menu1sira;
			$menu2_sira = $menu_veri->menu2sira;
			$menuduzen = $menu_veri->menuduzen;
			$menu_adres = $menu_veri->menuadres;
			if ($menuduzen=='H')
			{
			  $menuait = $menu_veri->menuait;
			}
			
			//Duzenleme Aciksa Adres Kontrol Ediliyor
			if ((!$menuadres || !$menuanahtar) && $menuduzen=='E')
		  {
		    throw new Exception($dil['IsaretliAlanlariBosBirakmayiniz']);
			  exit;
		  }
			
      if ($dosyaduzenle==1 && $menuduzen=='E')
      {
			  $dizin = pathinfo('../'.$menuadres);
        if ($menuait==1 || is_dir($dizin['dirname']))
        {
				  //Dosya Duzenleniyor
          if ($md_vt->kayitSay("SELECT COUNT(menuno) FROM ".TABLO_ONEKI."menuler WHERE menuadres='$menuadres' AND menuno<>$menuno")>0)
          {
					  throw new Exception($dil['BuSayfaKayitli']);
						exit;
					} else {
					  if ($menuduzen=='E')
						{
              if (@file_put_contents('../'.$menuadres,$dosyaicerik))
              {
                //Eger Duzenlemede Yeni Adres Girilmisse ve Eski Kayittaki Adrese Ait Dosya Duruyorsa, O dosya Kaldiriliyor
                if ($menu_adres != $menuadres)
                {
                  if (is_file('../'.$menu_adres))
                  unlink('../'.$menu_adres);
                }
				      }   else {
                throw new Exception($dil['DosyaOlusturulamadi']);
                exit;
						  }
						}
          }
				}
			}
			//Duzenlemede Menu Siralari Ayarlaniyor
			$menu1sira  = menu_ust_sira(1,$menuno)+1;
			$menu2sira  = menu_ust_sira(2,$menuno)+1;
			
			if (($menu_grup==0 || $menu_grup==1) && $menugrup==2)
			{
			  //Menu 1 Siralari 1 Indiriliyor
        $md_vt->query("UPDATE ".TABLO_ONEKI."menuler SET menu1sira=menu1sira-1 WHERE menu1sira>$menu1_sira AND menu1sira<>0");
				//Menuye Ait menu1sira Sifirlaniyor
				$md_vt->query("UPDATE ".TABLO_ONEKI."menuler SET menu1sira=0,menu2sira=$menu2sira WHERE menuno=$menuno");
			} elseif (($menu_grup==0 || $menu_grup==2) && $menugrup==1) {
				//Menu 2 Siralari 1 Indiriliyor
        $md_vt->query("UPDATE ".TABLO_ONEKI."menuler SET menu2sira=menu2sira-1 WHERE menu2sira>$menu2_sira AND menu2sira<>0");
				//Menuye Ait menu2sira Sifirlaniyor
				$md_vt->query("UPDATE ".TABLO_ONEKI."menuler SET menu2sira=0,menu1sira=$menu1sira WHERE menuno=$menuno");
			} elseif ($menu_grup==1 && $menugrup==0) {
			  $md_vt->query("UPDATE ".TABLO_ONEKI."menuler SET menu2sira=$menu2sira WHERE menuno=$menuno");
			} elseif ($menu_grup==2 && $menugrup==0) {
			  $md_vt->query("UPDATE ".TABLO_ONEKI."menuler SET menu1sira=$menu1sira WHERE menuno=$menuno");
			}
			
		  //DUZENLEME ISLEMI YAPILIYOR

			$md_vt->query("UPDATE ".TABLO_ONEKI."menuler SET menugrup=$menugrup,menuresim='$menuresim',menuadi='$menuadi',menudil='$menudil',menusayfaadi='$menusayfaadi',menusayfadil='$menusayfadil',menudescription='$menudescription',menukeywords='$menukeywords',menuait=$menuait,menuhedef='$menuhedef',menuizin=$menuizin,menudurum='$menudurum' WHERE menuno=$menuno");
			if ($menuduzen=='E')
			$md_vt->query("UPDATE ".TABLO_ONEKI."menuler SET menuanahtar='$menuanahtar',menuadres='$menuadres' WHERE menuno=$menuno AND menuduzen='E'");
			unset($md_vt,$menu_veri,$menu_grup,$menu1_sira,$menu2_sira);
      if ($md_vt->hata)
			{
			  throw new Exception($dil['IslemBasarisiz']);
			} else {
			  $hata_kod=true;
				unset($dosyaicerik);
				throw new Exception($dil['IslemTamamlandi']);
			}
    }
  }
  catch (Exception $e)
  {
    $kayit_duzen_mesaj = $e->getMessage();
  }
//=================================================================================
} elseif ($islem == 2) { //MENU SIRA DEGISTIRME
//=================================================================================
  
	@ $yon    = intval($_GET['yon']);
  @ $menuno = intval($_GET['menuno']);
	@ $grup   = intval($_GET['grup']);

  try 
	{
	  $svt = new Baglanti();
		//Menu Sirasi Aliniyor
		$svt->query("SELECT menu1sira,menu2sira,menugrup FROM ".TABLO_ONEKI."menuler WHERE menuno=$menuno");
		$menu_sira_veri = $svt->fetchObject();
		$menu1_sira     = $menu_sira_veri->menu1sira;
		$menu2_sira     = $menu_sira_veri->menu2sira;
		
		$menu_grup      = $menu_sira_veri->menugrup;
		
		$menu_ustsayi   = menu_ust_sira($grup);
		
		if ($grup == 1)
		{
		  $menu_sira = $menu1_sira;
			$menu_sira_alan = 'menu1sira';
		} else {
		  $menu_sira = $menu2_sira;
			$menu_sira_alan = 'menu2sira';
		}

		$islem_tamam = 0;
		//yon 1 - Yukari
		//yon 2 - Asagi
		if ($yon==1 && $menu_sira>1)
		{
		  $islem_tamam++;
			$yeni_menu_sira = $menu_sira-1;
		} elseif ($yon==2 && $menu_sira<$menu_ustsayi) {
		  $islem_tamam++;
		  $yeni_menu_sira = $menu_sira+1;
    } else { 
		  $islem_tamam = 0;
		}
		
		if ($islem_tamam>0)
		{
		  //Yeni Menu Sirasi Eski Menuye Ekleniyor
		  $svt->query2("UPDATE ".TABLO_ONEKI."menuler SET $menu_sira_alan=$menu_sira WHERE $menu_sira_alan=$yeni_menu_sira");
		  //Menu Sirasi Degistiriliyor
		  $svt->query2("UPDATE ".TABLO_ONEKI."menuler SET $menu_sira_alan=$yeni_menu_sira WHERE menuno=$menuno");
		  $islem_tamam++;
    }
		if ($islem_tamam>1)
		{
		  $hata_kod = true;
		  throw new Exception($dil['IslemTamamlandi']);
		} else {
		  $hata_kod = false;
			throw new Exception($dil['IslemBasarisiz']);
		}

  }
	catch (Exception $e)
  {
    $menu_mesaj = $e->getMessage();
  }
//=================================================================================
} elseif ($islem == 3) { // MENU SILME
//=================================================================================
  @ $menuno = intval($_GET['menuno']);
  try 
	{
	  $sil_vt  = new Baglanti();
		$sil_vt->query("SELECT menuadres FROM ".TABLO_ONEKI."menuler WHERE menuno=$menuno");
		$menu_dosya_adres = $sil_vt->fetchObject()->menuadres;
		if (is_file('../'.$menu_dosya_adres))
		@ unlink($menu_dosya_adres);
		
    $sil_vt->query("DELETE FROM ".TABLO_ONEKI."menuler WHERE menuno=$menuno AND menuduzen='E'");
    $hata_kod = true;
    if ($sil_vt->affectedRows())
    {
      throw new Exception($dil['SilmeIslemiTamamlandi']);
    } else {
      throw new Exception($dil['IslemBasarisiz']);
    }	
	}
	catch (Exception $e)
  {
    $menu_mesaj = $e->getMessage();
  }
//=================================================================================
} //ISLEMLER SONU
//=================================================================================
?>


<body background="yonetimresim/bg.gif">
<div align="center">
<script language="javascript">
function atla(targ,selObj,restore)
{ 
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script>
<table border="1" align="center" cellpadding="0" cellspacing="0" width="100%" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><a href="menu_sayfa_yonet.php"><font color="#ffffff"><b><?php echo $dil['MENU_SAYFA_YONETIMI']; ?></b></font></a></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
      <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
		    
				<?php
				if ($sayfa==1)
				{
				  if ($kayit_duzen_mesaj)
				  {
				    echo '<tr>
              <td colspan="2" align="center" valign="center" height="30">';
						  if ($hata_kod)
						  echo '<font color="#008000">'.$kayit_duzen_mesaj.'</font>';
						  else
						  echo '<font color="#ff0000">'.$kayit_duzen_mesaj.'</font>';
						  echo '</td>
            </tr>';
				  }
				  $menu_no = intval($_GET['menuno']);
					if (empty($menu_no))
					$menu_no = $menuno;
				  if ($menu_no)
					{
					  $menu_vt = new Baglanti();
				    $menu_vt->query("SELECT menuno, menuanahtar, menuresim,menuadi, menudil, menusayfaadi, menusayfadil, menudescription,menukeywords,menuadres, menuait, menuizin, menugrup, menuduzen, menudurum 
					FROM ".TABLO_ONEKI."menuler WHERE menuno=$menu_no");
					
            if ($menu_vt->numRows() > 0)
            {

              if ($menuduzunleme==false)
							{
							$menu_kayit_veri = $menu_vt->fetchObject();
              $menuno          = $menu_kayit_veri->menuno;
              $menuanahtar     = $menu_kayit_veri->menuanahtar;
              $menuresim       = $menu_kayit_veri->menuresim;
              $menuadi         = $menu_kayit_veri->menuadi;
              $menudil         = $menu_kayit_veri->menudil;

              $menusayfaadi    = $menu_kayit_veri->menusayfaadi;
              $menusayfadil    = $menu_kayit_veri->menusayfadil;
							
              $menuadres       = $menu_kayit_veri->menuadres;
						  $menuait         = $menu_kayit_veri->menuait;
              $menuizin        = $menu_kayit_veri->menuizin;
              $menugrup        = $menu_kayit_veri->menugrup;
              $menudurum       = $menu_kayit_veri->menudurum;
							}
							$menudescription = $menu_kayit_veri->menudescription;
							$menukeywords    = $menu_kayit_veri->menukeywords;
						  $menu_duzen      = $menu_kayit_veri->menuduzen;
						  $menu_buton      = $dil['MENU_DUZENLE'];
						  $menu_baslik     = $dil['MENU_DUZENLEME_BOLUMU'];
					  }
					} else {
					  $menu_duzen       = 'E';
					  $menu_buton       = $dil['MENU_EKLE'];
					  $menu_baslik      = $dil['MENU_EKLEME_BOLUMU'];
				  }
					unset($menu_vt);
				?> 
				<form name="menu" action="menu_sayfa_yonet.php?islem=1&sayfa=1" method="post">
				<input type="hidden" name="menuno" value="<?php echo $menuno; ?>" />
				<tr bgcolor="#b6c5f2">
		      <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $menu_baslik; ?></b></td>
		    </tr>
				<tr bgcolor="#f7f7fd">
		      <td width="100%" height="25" align="center" colspan="2"></td>
				</tr>
				<tr bgcolor="#f7f7fd">
		      <td width="30%" height="25" align="right"><b><?php echo $dil['MenuGrup']; ?>&nbsp;:</b>&nbsp;&nbsp;</td>
				  <td width="70%" height="25" align="left">
          <select name="menugrup" id="menugrup" class="input">
					<option value="1"<?php if ($menugrup==1) echo ' selected="selected"'; ?>><?php echo $dil['MenuGrup'].' : 1'; ?></option>
					<option value="2"<?php if ($menugrup==2) echo ' selected="selected"'; ?>><?php echo $dil['MenuGrup'].' : 2'; ?></option>
					<option value="0"<?php if ($menugrup==0) echo ' selected="selected"'; ?>><?php echo $dil['MenuGrup'].' : 1-2'; ?></option>
					</select>&nbsp;&nbsp;<?php echo $dil['MenuGrupAciklama']; ?></td>
				</tr>
				<?php
				if ($menu_duzen=='E')
				{
				?>
        <tr bgcolor="#f7f7fd">
		      <td width="30%" height="25" align="right"><b><?php echo $dil['MenuAnahtar']; ?> :</b>&nbsp;&nbsp;<b>*</b></td>
		      <td width="70%" height="25" align="left"><input type="text" id="menuanahtar" name="menuanahtar" class="input" size="20" maxlength="25" value="<?php echo $menuanahtar; ?>" />&nbsp;&nbsp;<?php echo $dil['MenuAnahtarAciklama']; ?></td>
		    </tr>
				<?php
				}
				?>
				<tr bgcolor="#f7f7fd">
		      <td width="30%" height="25" align="right" valign="top"><b><?php echo $dil['MenuResim']; ?> :</b>&nbsp;&nbsp;</td>
		      <td width="70%" height="25" align="left"><input type="text" id="menuresim" name="menuresim" class="input" size="20" maxlength="50" value="<?php echo $menuresim; ?>" />&nbsp;<i><?php echo $dil['MenuResimAciklama']; ?></i></td>
		    </tr>
				<tr bgcolor="#f7f7fd">
		      <td width="30%" height="25" align="right" valign="top"><b><?php echo $dil['MenuAdi']; ?> :</b>&nbsp;&nbsp;<b>*</b></td>
		      <td width="70%" height="25" align="left"><input type="text" id="menuadi" name="menuadi" class="input" size="35" maxlength="100" value="<?php echo $menuadi; ?>" />&nbsp;&nbsp;<b><?php echo $dil['DilKullan']; ?></b>&nbsp;:&nbsp;<input type="checkbox" name="menudil" value="E"<?php if ($menudil=='E') echo ' checked="checked"'; ?> /><br /><?php echo $dil['MenuAdiAciklama']; ?></td>
		    </tr>
				<tr bgcolor="#f7f7fd">
		      <td width="30%" height="25" align="right" valign="top"><b><?php echo $dil['MenuSayfaAdi']; ?> :</b>&nbsp;&nbsp;</td>
		      <td width="70%" height="25" align="left"><input type="text" id="menusayfaadi" name="menusayfaadi" class="input" size="35" maxlength="100" value="<?php echo $menusayfaadi; ?>" />&nbsp;&nbsp;<b><?php echo $dil['DilKullan']; ?></b>&nbsp;:&nbsp;<input type="checkbox" name="menusayfadil" value="E"<?php if ($menusayfadil=='E') echo ' checked="checked"'; ?> /><br /><?php echo $dil['MenuSayfaAciklama']; ?></td>
		    </tr>
				<tr bgcolor="#f7f7fd">
		      <td width="30%" height="25" align="right" valign="top"><b><?php echo $dil['MenuKeywords']; ?> :</b>&nbsp;&nbsp;</td>
		      <td width="70%" height="25" align="left"><input type="text" id="menukeywords" name="menukeywords" class="input" size="35" maxlength="100" value="<?php echo $menukeywords; ?>" /><br /><?php echo $dil['MenuKeywordsAciklama']; ?></td>
		    </tr>
				<tr bgcolor="#f7f7fd">
		      <td width="30%" height="25" align="right" valign="top"><b><?php echo $dil['MenuDescription']; ?> :</b>&nbsp;&nbsp;</td>
		      <td width="70%" height="25" align="left"><input type="text" id="menudescription" name="menudescription" class="input" size="35" maxlength="200" value="<?php echo $menudescription; ?>" /><br /><?php echo $dil['MenuDescriptionAciklama']; ?></td>
		    </tr>
				<?php
				//Duzenleme Izni Olmayan Sayfalar Icin Adres Degisikligi Yapilamaz
				if ($menu_duzen=='E')
				{
				?>
				<tr bgcolor="#f7f7fd">
		      <td width="30%" height="25" align="right" valign="top"><b><?php echo $dil['MenuAdres']; ?> :</b>&nbsp;&nbsp;<b>*</b></td>
		      <td width="70%" height="25" align="left" valign="top">
					<input type="text" id="menuadres" name="menuadres" class="input" size="50" value="<?php echo $menuadres; ?>" />&nbsp;&nbsp;
					<input type="checkbox" name="menuait" value="1"<?php if ($menuait==1) echo ' checked="checked"'; ?> />&nbsp;<b><?php echo $dil['IndexPhpAltinda']; ?></b>&nbsp;&nbsp;&nbsp;
					<select name="menuhedef" class="select">
					<option value="_self">_self</option>
					<option value="_blank">_blank</option>
					</select>
					<br /><?php echo $dil['MenuAdresAciklama']; ?></td>
		    </tr>
				<?php
				}
				?>
				<tr bgcolor="#f7f7fd">
		      <td width="30%" height="25" align="right"><b><?php echo $dil['MenuIzin']; ?> :</b>&nbsp;&nbsp;</td>
		      <td width="70%" height="25" align="left">
				  <select id="menuizin" name="menuizin" class="select">
					<option value="-1"<?php if ($menuizin==-1) echo ' selected="selected"'; ?>><?php echo $dil['GirisYapmamisKisiler']; ?></option>
						<option value="0"<?php if ($menuizin==0) echo ' selected="selected"'; ?>><?php echo $dil['Herkes']; ?></option>
						<?php
            foreach ($seviyeler AS $seviye => $seviyeadi)
						{
						  echo '<option value="'.$seviye.'"'; if ($menuizin==$seviye) echo ' selected="selected"'; echo '>'.$seviyeadi.'&nbsp;-&nbsp;'.$dil['VeUstSeviyedekiUyeler'].'</option>';
						}
						?>
						</select>&nbsp;&nbsp;<?php echo $dil['MenuIzinAciklama']; ?>
           </td>
        </tr>
				<tr bgcolor="#f7f7fd">
		      <td width="30%" height="25" align="right"><b><?php echo $dil['MenuDurum']; ?> :</b>&nbsp;&nbsp;</td>
		      <td width="70%" height="25" align="left">
				  <select name="menudurum" class="select">
          <option value="A"<?php if ($menudurum=='A') echo ' selected="selected"'; ?>><?php echo $dil['Aktif']; ?></option>
          <option value="P"<?php if ($menudurum=='P') echo ' selected="selected"'; ?>><?php echo $dil['Pasif']; ?></option>
          </select>
           </td>
        </tr>
				<tr>
		      <td colspan="2" align="center" valign="center" height="30"><input type="submit" value="<?php echo $menu_buton; ?>" class="input"/></td>
		    </tr>
				<?php
				//Duzenleme Izni Olmayan Sayfalar Icin Sayfa Icerigi Degistirilemez
				if ($menu_duzen=='E')
				{
				if ($menu_duzenle==false)
				{
				  $menu_dosya   = '../'.$menuadres;
          if (file_exists($menu_dosya) && is_file($menu_dosya))
				  {
				    $fp = fopen($menu_dosya,'rb');
				    @$dosyaicerik = fread($fp,filesize($menu_dosya));
				    fclose($fp);
				  }
				}
				if ($menuno)
				{
				?>
				<tr>
		      <td colspan="2" align="center" valign="top"><input type="checkbox" name="dosyaduzenle" value="1"<?php if ($dosyaduzenle==1) echo ' checked="checked"'; ?> />&nbsp;&nbsp;<?php echo $dil['DosyaIceriginiTekrarKaydet']; ?></td>
		    </tr>
				<?php
				} else {
				?>
				<tr>
		      <td colspan="2" align="center" valign="top"><font color="#ff0000"><?php echo $dil['SistemeAitDosyalariDegistiremezsiniz']; ?></font></td>
		    </tr>
				<?php
				}
				?>
				<tr>
		      <td colspan="2" align="center" valign="top"><textarea name="dosyaicerik" style="width:700px;height:400px"><?php echo $dosyaicerik; ?></textarea></td>
		    </tr>
				</form>
				<?php
				} //Duzenleme Izni Olmayan Sayfalar Icin Sayfa Icerigi Degistirilemez -Kontrol Sonu-
				} elseif (empty($sayfa)) {
				?>

		    <tr bgcolor="#b6c5f2">
		      <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['MENULER']; ?></b>&nbsp;&nbsp;<a href="menu_sayfa_yonet.php?sayfa=1"><?php echo $dil['MENU_EKLE']; ?></a></td>
		    </tr>
				<?php
				if ($menu_mesaj)
				{
				  echo '<tr>
            <td colspan="2" align="center" valign="center" height="30">';
						if ($hata_kod)
						echo '<font color="#008000">'.$menu_mesaj.'</font>';
						else
						echo '<font color="#ff0000">'.$menu_mesaj.'</font>';
						echo '</td>
          </tr>';
				}
				?>
				<tr bgcolor="#b6c5f2">
		      <td colspan="2" align="center" height="20" class="border_4"><b><?php echo $dil['MenuGrup']; ?> : 1</b></td>
		    </tr>
				<tr bgcolor="#ffffff">
		      <td width="100%" height="25" align="center" colspan="2">
					  <table width="100%" bgcolor="#ffffff">
						  
							<?php
							$dill = dill();
              //Kategori Siralari
							$vt->query("SELECT menuno,menuanahtar,menuresim,menuadi,menudil,menusayfaadi,menusayfadil,menuadres,menu1sira,menu2sira,menuizin,menugrup,menuduzen,menudurum FROM ".TABLO_ONEKI."menuler WHERE menugrup=1 OR menugrup=0 ORDER BY menu1sira ASC");
							$menu1_sayi = $vt->numRows();
							?>
							<tr>
							  <td colspan="4" align="left">
								<?php
								echo $dil['MENULER'].' : <b>'.$menu1_sayi.'</b>'; ?>
								</td>
							</tr>
						  <tr bgcolor="#b6c5f2">
								<td align="center"><b><?php echo $dil['MenuAnahtar']; ?></b></td>
								<td align="center" colspan="3"><b><?php echo $dil['MenuSira']; ?></b></td>
								<td align="center"><b><?php echo $dil['MenuResim']; ?></b></td>
								<td align="center"><b><?php echo $dil['MenuAdi']; ?></b></td>
								<td align="center"><b><?php echo $dil['MenuSayfaAdi']; ?></b></td>
								<td align="center"><b><?php echo $dil['MenuAdres']; ?></b></td>
								<td align="center"><b><?php echo $dil['MenuIzin']; ?></b></td>
								<td align="center"><b><?php echo $dil['Durum']; ?></b></td>
							</tr>
				      <?php
							

							if ($menu1_sayi > 0)
							{
                while ($menu_veri = $vt->fetchObject())
							  {
								  $menuno       = $menu_veri->menuno;
									$menuanahtar       = $menu_veri->menuanahtar;
									$menuresim    = $menu_veri->menuresim;
									$menuadi      = $menu_veri->menuadi;
									$menudil      = $menu_veri->menudil;
									if ($menudil=='E')
									$menuadi      = $dill[$menuadi];
									$menusayfaadi = $menu_veri->menusayfaadi;
									$menusayfadil = $menu_veri->menusayfadil;
									if ($menusayfadil=='E')
									$menusayfaadi = $dill[$menusayfaadi];
									$menuadres    = $menu_veri->menuadres;
									$menusira     = $menu_veri->menu1sira;
									$menugrup     = $menu_veri->menugrup;
									
									$menuizin     = $menu_veri->menuizin;
									if ($menuizin==-1)
									$menuizin     = $dil['GirisYapmamisKisiler'];
									else if ($menuizin==0) 
									$menuizin     = $dil['Herkes'];
									else
									$menuizin     = $seviyeler[$menuizin];
									
									$menuduzen    = $menu_veri->menuduzen;
									$menudurum    = $menu_veri->menudurum;
									
									$menu1_ustsira  = menu_ust_sira(1);
				          echo '
		              <tr>
										<td align="center">'.$menuanahtar.'</td>';
										//Menu Sira Baslangici
										echo '<td align="center">';
                    if ($menusira>0)
                    {
                      if ($menusira>1) 
                      echo '<a href="menu_sayfa_yonet.php?islem=2&menuno='.$menuno.'&yon=1&grup=1"><img src="yonetimresim/yukari.gif" alt="'.$dil['Yukari'].'" align="absmiddle" border="0" /></a>';
										} else {
                      echo '---';
                    }
										echo '</td>';
										
										echo '<td align="center"><b>'.$menusira.'</b></td>';
                    echo '<td align="center">';
										if ($menusira>0)
										{ 
											if ($menusira<$menu1_ustsira)
                      echo '&nbsp;&nbsp;<a href="menu_sayfa_yonet.php?islem=2&menuno='.$menuno.'&yon=2&grup=1"><img src="yonetimresim/asagi.gif" alt="'.$dil['Asagi'].'" align="absmiddle" border="0" /></a>';
                    } else {
                      echo '---';
                    }
                    echo '</td>';
										//Menu Sira Bitis
										echo '
										<td align="center">'.$menuresim.'</td>
		                <td align="left"><a href="menu_sayfa_yonet.php?menuno='.$menuno.'&sayfa=1" title="'.$dil['MENU_DUZENLE'].'">'.$menuadi.'</a></td>
										<td align="left">'.$menusayfaadi.'</td>
								    <td align="center"><div title="'.$menuadres.'">'.substr($menuadres,0,25).'</div></td>
	                  <td align="center"><div title="'.$menuizin.' '.$dil['VeUstSeviyedekiUyeler'].'">'.$menuizin.'</div></td>
										<td align="center">';
										if ($menudurum=='A') 
										echo '<font color="#008000">'.$dil['Aktif'].'</font>';
										else
										echo '<font color="#ff0000">'.$dil['Pasif'].'</font>';
										
										if ($menuduzen=='E') echo '&nbsp;<a href="?islem=3&menuno='.$menuno.'">'.$dil['Sil'].'</a>';
										echo '</td>
							    </tr>';
							  }
							} else {
							  echo '<tr><td width="100%" colspan="5" align="center"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td></tr>';
							}
							unset($menu_veri,$menuno,$menuanahtar,$menuresim,$menuadi,$menudil,$menuadi,$menusayfaadi,$menusayfadil,$menuadres,$menusira,$menugrup,$menuizin,$menuduzen,$menudurum,$menu1_ustsira);
							?>
             <tr bgcolor="#b6c5f2">
               <td colspan="10" align="center" height="20" class="border_4"><b><?php echo $dil['MenuGrup']; ?> : 2</b></td>
              </tr>
							<?php
              //Menu 2 
              $m2_vt = new Baglanti();
							$m2_vt->query("SELECT menuno,menuanahtar,menuresim,menuadi,menudil,menusayfaadi,menusayfadil,menuadres,menu1sira,menu2sira,menuizin,menugrup,menuduzen,menudurum FROM ".TABLO_ONEKI."menuler WHERE menugrup=2 OR menugrup=0 ORDER BY menu2sira ASC");
							$menu2_sayi = $m2_vt->numRows();
              echo '
							<tr>
							  <td colspan="10" align="left">'.$dil['MENULER'].' : <b>'.$menu2_sayi.'</b></td>
							</tr>';
							?>
              <tr bgcolor="#b6c5f2">
								<td align="center"><b><?php echo $dil['MenuAnahtar']; ?></b></td>
								<td align="center" colspan="3"><b><?php echo $dil['MenuSira']; ?></b></td>
								<td align="center"><b><?php echo $dil['MenuResim']; ?></b></td>
								<td align="center"><b><?php echo $dil['MenuAdi']; ?></b></td>
								<td align="center"><b><?php echo $dil['MenuSayfaAdi']; ?></b></td>
								<td align="center"><b><?php echo $dil['MenuAdres']; ?></b></td>
								<td align="center"><b><?php echo $dil['MenuIzin']; ?></b></td>
								<td align="center"><b><?php echo $dil['Durum']; ?></b></td>
							</tr>
              <?php
							if ($menu2_sayi > 0)
							{
							  
                while ($menu_veri = $m2_vt->fetchObject())
							  {
								  $menuno       = $menu_veri->menuno;
									$menuanahtar       = $menu_veri->menuanahtar;
									$menuresim    = $menu_veri->menuresim;
									$menuadi      = $menu_veri->menuadi;
									$menudil      = $menu_veri->menudil;
									if ($menudil=='E')
									$menuadi      = $dill[$menuadi];
									$menusayfaadi = $menu_veri->menusayfaadi;
									$menusayfadil = $menu_veri->menusayfadil;
									if ($menusayfadil=='E')
									$menusayfaadi = $dill[$menusayfaadi];
									$menuadres    = $menu_veri->menuadres;
									$menusira     = $menu_veri->menu2sira;
									$menugrup     = $menu_veri->menugrup;
									
									$menuizin     = $menu_veri->menuizin;
									if ($menuizin==-1)
									$menuizin     = $dil['GirisYapmamisKisiler'];
									else if ($menuizin==0) 
									$menuizin     = $dil['Herkes'];
									else
									$menuizin     = $seviyeler[$menuizin];
									
									$menuduzen    = $menu_veri->menuduzen;
									$menudurum    = $menu_veri->menudurum;
									
									$menu2_ustsira  = menu_ust_sira(2);
				          echo '
		              <tr>
										<td align="center">'.$menuanahtar.'</td>';
										//Menu Sira Baslangici
										echo '<td align="center">';
                    if ($menusira>0)
                    {
                      if ($menusira>1) 
                      echo '<a href="menu_sayfa_yonet.php?islem=2&menuno='.$menuno.'&yon=1&grup=2"><img src="yonetimresim/yukari.gif" alt="'.$dil['Yukari'].'" align="absmiddle" border="0" /></a>';
										} else {
                      echo '---';
                    }
										echo '</td>';
										
										echo '<td align="center"><b>'.$menusira.'</b></td>';
                    echo '<td align="center">';
										if ($menusira>0)
										{ 
											if ($menusira<$menu2_ustsira)
                      echo '&nbsp;&nbsp;<a href="menu_sayfa_yonet.php?islem=2&menuno='.$menuno.'&yon=2&grup=2"><img src="yonetimresim/asagi.gif" alt="'.$dil['Asagi'].'" align="absmiddle" border="0" /></a>';
                    } else {
                      echo '---';
                    }
                    echo '</td>';
										//Menu Sira Bitis
										echo '
										<td align="center">'.$menuresim.'</td>
		                <td align="left"><a href="menu_sayfa_yonet.php?menuno='.$menuno.'&sayfa=1" title="'.$dil['MENU_DUZENLE'].'">'.$menuadi.'</a></td>
										<td align="left">'.$menusayfaadi.'</td>
								    <td align="center"><div title="'.$menuadres.'">'.substr($menuadres,0,25).'</div></td>
	                  <td align="center"><div title="'.$menuizin.' '.$dil['VeUstSeviyedekiUyeler'].'">'.$menuizin.'</div></td>
										<td align="center">';
										if ($menudurum=='A') 
										echo '<font color="#008000">'.$dil['Aktif'].'</font>';
										else
										echo '<font color="#ff0000">'.$dil['Pasif'].'</font>';
										if ($menuduzen=='E') echo '&nbsp;<a href="?islem=3&menuno='.$menuno.'">'.$dil['Sil'].'</a>';
										echo '</td>
							    </tr>';
							  }
							} else {
							  echo '<tr><td width="100%" colspan="5" align="center"><font color="#ff0000">'.$dil['KayitBulunamadi'].'</font></td></tr>';
							}
							?>
						</table>
					</td>
		    </tr>
				<?php
				}
				?>
				
	    </table>
	  </td>
  </tr>
	<tr>
    <td align="center"><a href="menu_sayfa_yonet.php?sayfa=1"><?php echo $dil['MENU_EKLE']; ?></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="menu_sayfa_yonet.php"><?php echo $dil['MENULER']; ?></a></td>
  </tr>
</table>
</div>