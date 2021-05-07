<?php
error_reporting(E_ALL | E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_CORE_ERROR | E_COMPILE_ERROR);
ini_set('display_errors', true);
ini_set('html_errors', true);

header("Content-Type: text/html; charset=UTF-8");

@ $adim     = strip_tags(trim($_GET['adim']));
if ($adim == 2 || $adim == 3)
{
if ($adim==2)
{
@$vtsunucu    = trim(htmlspecialchars(strip_tags($_POST['vtsunucu'])));
@$vtkullanici = trim(htmlspecialchars(strip_tags($_POST['vtkullanici'])));
@$vtsifre     = trim(htmlspecialchars(strip_tags($_POST['vtsifre'])));
@$vtisim      = trim(htmlspecialchars(strip_tags($_POST['vtisim'])));
@$vtonek      = trim(htmlspecialchars(strip_tags($_POST['vtonek'])));
@$vt_bag      = mysql_connect($vtsunucu,$vtkullanici,$vtsifre);
@$vt_db       = mysql_select_db($vtisim,$vt_bag);

if (!empty($vtsunucu) && !empty($vtkullanici) && !empty($vtisim) && !empty($vtonek) && $vt_bag && $vt_db)
{
if (@function_exists('mysqli_connect'))
{
//MySQLi Kutuphanesi Kurulu Ise mysqli Sinifi Olusturuluyor
$vt_kutuphane = '<?php
//VERITABANI BAGLANTI SINIFI
header("Content-Type: text/html; charset=UTF-8");
class Baglanti
{
	//=============================================================================
  //DUZENLENECEK BOLUM
	//Alttaki 4 Satiri Veritabani Bilgilerinize Gore Duzenleyiniz...
	//Sabit Degiskenler
  private $sunucu   = \''.$vtsunucu.'\'; // Sunucu - Server
  private $kuladi   = \''.$vtkullanici.'\'; // Veritabani Kullanici Adi - MySQL User
  private $sifre    = \''.$vtsifre.'\'; // Veritabani Sifresi - MysQL Password
  private $vtadi    = \''.$vtisim.'\'; // Veritabani Adi - MySQL Name
	// Sadece Yukaridaki Dort Satiri Degistiriniz
  //==============================================================================
	
	private $vt;
  public $db;
	private $result;
	private $result2;
  
  function __construct()
  {
    try
    {
		  //MySQL Baglantisi";
		  $this->vt = @ mysqli_connect($this->sunucu,$this->kuladi,$this->sifre,$this->vtadi);
      if (mysqli_connect_errno())
      {
        throw new Exception (\'Hata: Veritabani Baglantisi Kurulamadi\');
      }
			//Veritabani Secimi
			$this->db = @ mysqli_select_db($this->vt,$this->vtadi);
		  if (!$this->db )
			{
			  throw new Exception (\'Hata: Veritabanı Secilemedi\');
			}
			@ mysqli_query($this->vt,"SET NAMES \'utf8\'");
			@ mysqli_query($this->vt,"SET CHARACTER SET \'utf8\'");
    }
    catch (Exception $e)
    {
      die($e->getMessage());
			exit;
    }
  }

  //Veritabanında Tablo Olup Olmadıgini Kontrol Eder
  public function tablo_kontrol($tabloadi)
  {
    $sonuc = false;
    $this->query("SHOW TABLE STATUS");
    while($tablo = $this->fetchArray())
    {
      $tablo_adi = $tablo[\'Name\'];
      if ($tabloadi == $tablo_adi)
      {
        $sonuc = true;
      }
    }
    unset($tablo_adi,$tablo,$tabloadi);
    $this->freeResult();
    return $sonuc;
  }
	
  public function query($query,$goster=1)
  {
    try 
    {
      $this->result = @ mysqli_query($this->vt,$query);
      if ( !$this->result )
      {
        throw new Exception (\'Sorgu Hatasi : (\'.mysqli_error($this->vt).\')\');
        exit;
      } else {
        return true;
      }
    }
    catch (Exception $e)
    {
      die($e->getMessage());
      exit;
    }
  }

  //Kayit Ekleme veya Update Icin Kullanilabilir
  public function query2($sql2)
  {
  try
  {
    $this->result2 = @mysqli_query($this->vt,$sql2);
    if ( !$this->result2 )
    {
      throw new Exception (\'Sorgu Hatasi : (\'.mysqli_error($this->vt).\')\');
      exit;
    } else {
      return true;
    }
  }
  catch (Exception $e)
  {
      die($e->getMessage());
      exit;
    }
  }
	
	//Verileri Dizi Değişkeni Olarak Listele \'Kolon İsimli Dizi\'
	public function fetchAssoc()
	{
	  return ( @mysqli_fetch_assoc($this->result) );
	}
	
	//Verileri Dizi Degiskeni Olarak Listele \'Sayili Dizi\'
	public function fetchArray()
	{
	  return ( @mysqli_fetch_array($this->result) );
	}
	
	//Verileri Dizi Degiskeni Olarak Listele \'Obje Olarak\'
	public function fetchObject()
	{
	  return ( @mysqli_fetch_object($this->result) );
	}
	
	//Satır Sayisi
	public function numRows()
  {
    return ( @mysqli_num_rows($this->result) );
  }
	
	public function affectedRows()
  {
    return ( @mysqli_affected_rows($this->vt) );
  }

	public function fetchRow()
  {
    return ( @ mysqli_fetch_row($this->result) );
  }

	private $kayit_sayi = 0;
	public function kayitSay($sql)
	{
	  $this->query2($sql);
		list($this->kayit_sayi) = @ mysqli_fetch_row($this->result2);
		@ mysqli_free_result($this->result2);
		return $this->kayit_sayi;
	}
	
	//Hafiza Bosaltimi
	public function freeResult()
	{
	  return (@mysqli_free_result($this->result));
	}
	
	public function insertId()
	{
	  return (@mysqli_insert_id($this->vt));
	}

	function escapeString($metin)
	{
		$metin = @ mysqli_real_escape_string($this->vt, $metin);
		return $metin;
	}
  //MySQL Baglantisini Kapatma
	function __destruct()
	{
		return @ mysqli_close($this->vt);
	}
}

//VERITABANI SINIFI
//Bazi Yerlerde Bu Sinif Kullanildi
//Bazi Yerlerde Yeni Sinif Olusturuldu
$vt = new Baglanti();

//========================================================================================================
//TABLO ONEKI
define("TABLO_ONEKI", \''.$vtonek.'\'); //Kurulum Yapmadan Once Tablo Onekini Buradan Degistirebilirsiniz...
//========================================================================================================
?>';
} else {
//MySQLi Kutuphanesi Kurulu Degilse mysql Sinifi Olusturuluyor
$vt_kutuphane = '<?php
//VERITABANI BAGLANTI SINIFI
header("Content-Type: text/html; charset=UTF-8");
class Baglanti
{
	//=============================================================================
  //DUZENLENECEK BOLUM
	//Alttaki 4 Satiri Veritabani Bilgilerinize Gore Duzenleyiniz...
	//Sabit Degiskenler
  private $sunucu   = \''.$vtsunucu.'\'; // Sunucu - Server
  private $kuladi   = \''.$vtkullanici.'\'; // Veritabani Kullanici Adi - MySQL User
  private $sifre    = \''.$vtsifre.'\'; // Veritabani Sifresi - MysQL Password
  private $vtadi    = \''.$vtisim.'\'; // Veritabani Adi - MySQL Name
	// Sadece Yukaridaki Dort Satiri Degistiriniz
  //==============================================================================
	
	private $vt;
  public $db;
	private $result;
	private $result2;
  
  function __construct()
  {
    try
    {
		  //MySQL Baglantisi";
		  $this->vt = @ mysql_connect($this->sunucu,$this->kuladi,$this->sifre);
      if (mysql_errno())
      {
        throw new Exception (\'Hata: Veritabani Baglantisi Kurulamadi<br /><a href="kurulum_install.php">Kurulum - Install</a>\');
      }
			//Veritabani Secimi
			$this->db = @ mysql_select_db($this->vtadi,$this->vt);
		  if (!$this->db )
			{
			  throw new Exception (\'Hata: Veritabanı Secilemedi<br /><a href="kurulum_install.php">Kurulum - Install</a>\');
			}
			@ mysql_query($this->vt,"SET NAMES \'utf8\'");
			@ mysql_query($this->vt,"SET CHARACTER SET \'utf8\'");
    }
    catch (Exception $e)
    {
      die($e->getMessage());
			exit;
    }
  }

  //Veritabanında Tablo Olup Olmadıgini Kontrol Eder
  public function tablo_kontrol($tabloadi)
  {
    $sonuc = false;
    $this->query("SHOW TABLE STATUS");
    while($tablo = $this->fetchArray())
    {
      $tablo_adi = $tablo[\'Name\'];
      if ($tabloadi == $tablo_adi)
      {
        $sonuc = true;
      }
    }
    unset($tablo_adi,$tablo,$tabloadi);
    $this->freeResult();
    return $sonuc;
  }
  
	public function query($query,$goster=1)
  {
    try 
		{
		  $this->result = @ mysql_query($query);
      if ( !$this->result )
      {
        throw new Exception (\'Sorgu Hatasi : (\'.mysql_error($this->vt).\')\');
				exit;
      } else {
			  return true;
			}
		}
		catch (Exception $e)
		{
			 die($e->getMessage());
       exit;
		}
  }

  //Kayit Ekleme veya Update Icin Kullanilabilir
	public function query2($sql2)
  {
	  try
		{
	    $this->result2 = @mysql_query($sql2);
	    if ( !$this->result2 )
			{
			  throw new Exception (\'Sorgu Hatasi : (\'.mysql_error($this->vt).\')\');
				exit;
			} else {
			  return true;
			}
		}
		catch (Exception $e)
		{
			 die($e->getMessage());
       exit;
		}
  }
	
	//Verileri Dizi Değişkeni Olarak Listele \'Kolon İsimli Dizi\'
	public function fetchAssoc()
	{
	  return ( @mysql_fetch_assoc($this->result) );
	}
	
	//Verileri Dizi Degiskeni Olarak Listele \'Sayili Dizi\'
	public function fetchArray()
	{
	  return ( @mysql_fetch_array($this->result) );
	}
	
	//Verileri Dizi Degiskeni Olarak Listele \'Obje Olarak\'
	public function fetchObject()
	{
	  return ( @mysql_fetch_object($this->result) );
	}
	
	//Satır Sayisi
	public function numRows()
  {
    return ( @mysql_num_rows($this->result) );
  }
	
	public function affectedRows()
  {
    return ( @mysql_affected_rows($this->vt) );
  }

	public function fetchRow()
  {
    return ( @ mysql_fetch_row($this->result) );
  }

	private $kayit_sayi = 0;
	public function kayitSay($sql)
	{
	  $this->query2($sql);
		list($this->kayit_sayi) = @ mysql_fetch_row($this->result2);
		@ mysql_free_result($this->result2);
		return $this->kayit_sayi;
	}
	
	//Hafiza Bosaltimi
	public function freeResult()
	{
	  return (@mysql_free_result($this->result));
	}
	
	public function insertId()
	{
	  return (@mysql_insert_id($this->vt));
	}

	function escapeString($metin)
	{
		$metin =  mysql_real_escape_string($metin, $this->vt);
		return $metin;
	}
}

//VERITABANI SINIFI
//Bazi Yerlerde Bu Sinif Kullanildi
//Bazi Yerlerde Yeni Sinif Olusturuldu
$vt = new Baglanti();

//========================================================================================================
//TABLO ONEKI
define("TABLO_ONEKI", \''.$vtonek.'\'); //Kurulum Yapmadan Once Tablo Onekini Buradan Degistirebilirsiniz...
//========================================================================================================
?>';
}
$fp = fopen("icerik/vt.inc.php",'w+');
fwrite($fp,$vt_kutuphane);
fclose($fp);
}
}

include('icerik/vt.inc.php');
}

include("icerik/sajax.inc.php");
function kontrol()
{
	return TABLO_ONEKI;
}

$sajax_request_type = "GET";
sajax_init();
sajax_export("kontrol");
sajax_handle_client_request();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>KURULUM</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="stil.css" />
<?php
if ($adim == 2 || $adim == 3)
{
?>
<script language="javascript">
<?php sajax_show_javascript(); ?>
function yazdir(deger)
{
	document.getElementById('onek').innerHTML   = deger;
	document.getElementById('tonek').innerHTML  = deger;
	document.getElementById('onekk').innerHTML  = deger;
	document.getElementById('onekkk').innerHTML = deger;
}
function kontrol() 
{
  x_kontrol(yazdir);
  setTimeout("kontrol()", 1000);
}
</script>

</script>
</head>
<body onload="kontrol()">
<?php
} else {
?>
</head>
<body>
<?php
}

?>
<p>&nbsp;</p>
<table border="1" align="center" cellpadding="0" cellspacing="0" width="600" bgcolor="#efeffa" bordercolor="#b6c5f2">
  <tr>
    <td width="100%" align="center" bgcolor="#2d4488" height="25"><font color="#ffffff"><b>KURULUMA HOŞGELDİNİZ<br />(SETUP)</b></font></td>
  </tr>
  <tr bgcolor="#efeffa">
    <td align="center" width="100%" bgcolor="#efeffa">
		  <?php
			if (empty($adim))
			{
			?>
      <table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#efeffa">
			  <tr bgcolor="#b6c5f2">
		      <td align="center" height="20" class="border_4" colspan="2"><b>ADIM 1</b></td>
		    </tr>
        <tr>
          <td width="100%" align="center" height="25" colspan="2"><b><?php 
					$php_kontrol = false;
					$mysqli_kontrol   = false;
					echo '<hr />';
					echo 'Geçerli PHP Versiyonu : '.phpversion().'&nbsp;&nbsp;&nbsp;&nbsp;';
					if (floatval(phpversion())>=5) 
					{
					  $php_kontrol = true;
						
					  echo '<font color="#008000">UYGUN</font>';
					} else {
					  $php_kontrol = false;
					  echo '<font color="#ff0000">UYGUN DEĞİL</font>';
					}

					echo '<hr />MySQLi Kütüphanesi : ';
					if (function_exists('mysqli_connect'))
					{
					  $mysqli_kontrol = true;
					  echo '<font color="#008000">KURULU</font><br />Veritabanı İşlemleri İçin <a href="http://www.php.net/manual/tr/ref.mysqli.php" target="_blank"><font color="#008000">mysqli</font></a> Kütüphanesi Kullanılacaktır';

					} else {
					  $mysqli_kontrol = false;
					  echo '<font color="#ff0000">KURULU DEĞİL</font><br />Veritabanı İşlemleri İçin <a href="http://www.php.net/tr/ref.mysql.php" target="_blank"><font color="#008000">mysql</font></a> Kütüphanesi Kullanılacaktır';
					}
					echo '<hr />vt.inc.php : ';
					if (is_writable('icerik/vt.inc.php'))
					echo '<font color="#008000">Yazılabilir</font><br />Daha Sonra chmod Ayarlarını Yazılamaz Yapmayı Unutmayınız';
					else
					echo '<font color="#ff0000">Yazılamaz</font><br />icerik/vt.inc.php nin chmod İznini 777 Yapınız<br />Kurulum Tamamlandıktan Sonra Eski Hâline Getirmeyi Unutmayınız';
					echo '<hr />';
					?></b></td>
        </tr>
				<tr bgcolor="#D7D7FF">
          <td width="100%" align="center" height="25" colspan="2"><b>MySQL Bilgileri</b></td>
        </tr>
				<form name="adim1" action="kurulum_install.php?adim=2" method="post">
				<tr bgcolor="#efeffa">
          <td width="40%" align="right" height="25">* MySQL Sunucu Adı (Server) :</td>
          <td width="60%" align="left" height="25"><input type="text" name="vtsunucu" id="vtsunucu" class="input" size="40" value="localhost" /></td>
        </tr>
				<tr>
          <td width="40%" align="right" height="25">* MySQL Kullanıcı Adı (MySQL User) :</td>
          <td width="60%" align="left" height="25"><input type="text" name="vtkullanici" id="vtkullanici" class="input" size="40" /></td>
        </tr>
				<tr bgcolor="#efeffa">
          <td width="40%" align="right" height="25">&nbsp;MySQL Şifre (MySQL Password) :</td>
          <td width="60%" align="left" height="25"><input type="password" name="vtsifre" id="vtsifre" class="input" size="40" /></td>
        </tr>
				<tr>
          <td width="40%" align="right" height="25">* Veritabanı Adı (Database Name) :</td>
          <td width="60%" align="left" height="25"><input type="text" name="vtisim" id="vtisim" class="input" size="40" /></td>
        </tr>
				<tr bgcolor="#efeffa">
          <td width="40%" align="right" height="25">* MySQL Tablo Öneki (Prefix) :</td>
          <td width="60%" align="left" height="25"><input type="text" name="vtonek" id="vtonek" class="input" size="15" value="u_" /></td>
        </tr>
        <tr>
          <td width="100%" align="center" height="25" colspan="2">&nbsp;</td>
        </tr>
				<tr>
          <td width="100%" align="center" colspan="2">
					<?php
					if (!$php_kontrol)
					{
						echo '<font color="#ff0000"><b>İşleme Devam Edilemiyor</b><br />';
						if (!$php_kontrol)
						{
							echo 'PHP Versiyonu Uygun Değil<br />';
						}
						echo '</font>';
					} else {
						echo '<input type="submit" value="ADIM 2 >>>">';
					}
					?></td>
        </tr>
				</form>
				<tr>
          <td width="100%" align="center" height="25" colspan="2">&nbsp;</td>
        </tr>
        </table>
				<?php
				} elseif ($adim == 2) {
				?>
				<table border="0" cellpadding="1" cellspacing="2" width="100%" id="autonumber7" bgcolor="#ffffff"> 
			  <tr>
          <td width="100%" colspan="5" align="center">
					<form action="kurulum_install.php?adim=3" method="post" name="kur" id="kur">
					  <table width="100%" align="center">
						  
						  <tr bgcolor="#b6c5f2">
		            <td colspan="2" align="center" height="20" class="border_4"><b>ADIM 2</b></td>
		          </tr>
							
							<?php
							if (empty($vtsunucu) || empty($vtkullanici) || empty($vtisim) || empty($vtonek))
				      {
				        echo '<tr><td width="100%" align="center" colspan="2"><font color="#ff0000">HATA<br />* İşaretli Alanları Boş Bırakmayınız</font><br />';
								echo '<a href="kurulum_install.php">Geri Dön</a></td></tr>';
							} elseif (!@$mysql_baglanti = mysql_connect($vtsunucu,$vtkullanici,$vtsifre)) {
							  echo '<tr><td width="100%" align="center" colspan="2"><font color="#ff0000">HATA<br />MySQL Bağlantısı Yapılamadı</font><br />';
								echo '<a href="kurulum_install.php">Geri Dön</a></td></tr>';
							} elseif (!@mysql_select_db($vtisim,$mysql_baglanti)) {
							  echo '<tr><td width="100%" align="center" colspan="2"><font color="#ff0000">HATA<br />Veritabanı Bulunamadı</font><br />';
								echo '<a href="kurulum_install.php">Geri Dön</a></td></tr>';
				      } else {
							?>
							<tr>
                <td width="100%" align="center" height="25" colspan="2"><b>AŞAĞIDAKİ BİLGİLERİ EKSİKSİZ DOLDURUNUZ</b></td>
              </tr>
							<tr bgcolor="#D7D7FF">
                <td width="100%" align="center" height="25" colspan="2"><b>Site Bilgileriniz</b></td>
              </tr>
							<tr bgcolor="#efeffa">
							  <td width="40%" align="right" height="25">* Web Sitenizin Adı :<br />(Web Site Name) :</td>
								<td width="60%" align="left" height="25"><input type="text" name="sitead" id="sitead" class="input" size="40" /></td>
							</tr>
							<tr>
							  <td width="40%" align="right" height="25">* Web Sitenizin Adresi :<br />(Web Site Url) :</td>
								<td width="60%" align="left" height="25"><input type="text" name="siteadres" id="siteadres" class="input" size="40" value="http://" /></td>
							</tr>
							<tr bgcolor="#efeffa">
							  <td width="40%" align="right" height="25">* Web Site E-Posta :<br />(Web Site E-mail) :</td>
								<td width="60%" align="left" height="25"><input type="text" name="siteeposta" id="siteeposta" class="input" size="40" /></td>
							</tr>
							<tr>
                <td width="100%" align="center" height="25" colspan="2">&nbsp;</td>
              </tr>
							<tr bgcolor="#D7D7FF">
                <td width="100%" align="center" height="25" colspan="2"><b>Üyelik Bilgileriniz</b></td>
              </tr>
							<tr bgcolor="#efeffa">
							  <td width="40%" align="right" height="25">* Kullanıcı Adınız :<br />(Admin Name) :</td>
								<td width="60%" align="left" height="25"><input type="text" name="kullaniciadi" id="kullaniciadi" class="input" size="40" /></td>
							</tr>
							<tr>
							  <td width="40%" align="right">* Şifre :<br />(Admin Password) :</td>
								<td width="60%" align="left"><input type="text" name="sifre" id="sifre" class="input" size="40" /></td>
							</tr>
							<tr bgcolor="#efeffa">
							  <td width="40%" align="right">* Şifre Tekrar :<br />(Re-Enter Password) :</td>
								<td width="60%" align="left"><input type="text" name="sifretekrar" id="sifretekrar" class="input" size="40" /></td>
							</tr>
							<tr>
                <td width="100%" align="center" height="25" colspan="2">&nbsp;</td>
              </tr>
							
							<tr>
							  <td width="100%" colspan="2" align="center"><?php
								$vt->query("SELECT VERSION() AS version");
                $version = $vt->fetchObject()->version;
								echo 'MySQL Versionunuz : '.$version.'<br />';
					      if (floatval($version) < 5)
								{ 
								  echo '<font color="#ff0000">MySQL Versionunuz 5.0 ve Üstü Tavsiye Edilir</font><br />';
								}
								?><br />
								<input type="submit" id="ipkayit" name="ipkayit" value="ADIM 3 >>>" class="input" /></td>
							</tr>
							
							<tr>
							  <td width="100%" colspan="2" align="center">Bu İşlemden Sonra Kurulum Yapılacaktır... Kurulum Sonrası Bu Dosya Otomatik Olarak Silinecektir</td>
							</tr>
														<tr>
                <td width="100%" align="center" height="25" colspan="2">&nbsp;</td>
              </tr>
							<tr  bgcolor="#efeffa">
                <td width="100%" align="center" height="25" colspan="2"><b>MySQL Tabloları İçin Tablo Önüne Eklenecek Önek</b></td>
              </tr>
							<tr>
							  <td width="40%" align="right" valign="top" height="20">MySQL Tablo Öneki :</td>
								<td width="60%" align="left" valign="top" height="20"><div id="onek" style="font-weight:bold; background-color:#ffffff; width:100px"></div></td>
							</tr>
							<tr>
							  <td width="40%" align="right" valign="top" height="20">Veritabanınızda Oluşacak Tablo Örneği :</td>
								<td width="60%" align="left" valign="top" height="20"><b><label id="onekk"></label>uyeler</b></td>
							</tr>
							<tr>
                <td width="100%" align="center" height="25" colspan="2">Tablo Önekini Değiştirmek İsterseniz Kurulumdan Önce<br /> <font color="#ff0000">icerik/vt.inc.php</font> Dosyasındaki <font color="#0000FF">define("TABLO_ONEKI","<label id="tonek" style="font-weight:bold;color:#ff0000"></label>");</font> Bölümünü Değiştiriniz...<br />Değiştirilecek Bölüm Sadece Tırnaklar İçindeki <font color="#ff0000"><b><label id="onekkk"></label></b></font> Yazan Yerdir</td>
              </tr>
							<?php
							}
							?>
						</table>
					  </form>
					</td>
        </tr>
        </table>
				<?php
				} elseif ($adim == 3) {
$sql['album'] = "
CREATE TABLE IF NOT EXISTS `[onek]album` (
  `albumno` int(5) NOT NULL auto_increment,
  `uyeno` int(10) NOT NULL default '0',
  `resim` varchar(30) collate utf8_unicode_ci NOT NULL default 'a_',
  `albumadi` varchar(50) collate utf8_unicode_ci NOT NULL,
  `aciklama` varchar(150) collate utf8_unicode_ci NOT NULL,
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `duzentarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `onay` enum('E','H') collate utf8_unicode_ci NOT NULL default 'E',
  `izin` enum('E','H') collate utf8_unicode_ci NOT NULL default 'H',
  PRIMARY KEY  (`albumno`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";

$sql['anketcevap'] = "
CREATE TABLE IF NOT EXISTS `[onek]anketcevap` (
  `cevapno` int(10) NOT NULL auto_increment,
  `anketno` int(10) NOT NULL default '0',
  `uyeno` int(10) NOT NULL default '0',
  `anketcevap` text character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`cevapno`),
  KEY `anketno` (`anketno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";

$sql['anketsecenek'] = "
CREATE TABLE IF NOT EXISTS `[onek]anketsecenek` (
  `secenekno` int(10) NOT NULL auto_increment,
  `anketno` int(10) NOT NULL default '0',
  `secenek` char(250) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`secenekno`),
  KEY `anketno` (`anketno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";

$sql['anketsoru'] = "
CREATE TABLE IF NOT EXISTS `[onek]anketsoru` (
  `anketno` int(10) NOT NULL auto_increment,
  `anketsoru` char(250) character set utf8 collate utf8_unicode_ci NOT NULL,
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `goster` enum('E','H') NOT NULL default 'H',
  `acik` enum('E','H') NOT NULL default 'H',
  `secenekizin` mediumint(2) NOT NULL default '1',
  `bitistarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`anketno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  AUTO_INCREMENT=1";

$sql['baglantilar'] = "CREATE TABLE `[onek]baglantilar` (
  `baglantino` tinyint(3) NOT NULL auto_increment,
  `baglantiadres` text NOT NULL,
  `baglantiadi` text character set utf8 collate utf8_unicode_ci,
  `baglantihedef` varchar(6) NOT NULL default '_self',
  `baglantionay` enum('E','H') NOT NULL default 'E',
  PRIMARY KEY  (`baglantino`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2";


$sql['dosyalar'] = "
CREATE TABLE IF NOT EXISTS `[onek]dosyalar` (
  `dosyano` int(5) NOT NULL auto_increment,
  `dosyaadi` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL,
  `dosyayolu` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL,
  `dosyadeneme` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL,
  `dosyaaciklama` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `dosyakayittarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `dosyaduzentarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `dosyaindirsayi` int(10) NOT NULL default '0',
  `dosyaonay` enum('E','H') collate utf8_unicode_ci NOT NULL default 'E',
  PRIMARY KEY  (`dosyano`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";

$sql['hizlimesaj'] = "
CREATE TABLE IF NOT EXISTS `[onek]hizlimesaj` (
  `mesajno` int(10) NOT NULL auto_increment,
  `uyeno` int(10) unsigned NOT NULL default '0',
  `mesaj` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `onay` enum('E','H') character set utf8 collate utf8_unicode_ci NOT NULL default 'H',
  PRIMARY KEY  (`mesajno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  AUTO_INCREMENT=1";

$sql['ipengelle'] = "
CREATE TABLE IF NOT EXISTS `[onek]ipengelle` (
  `ipno` int(15) NOT NULL auto_increment,
  `ip` char(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `aciklama` char(250) character set utf8 collate utf8_unicode_ci NOT NULL,
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ipno`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";

$sql['ipkontrol'] = "
CREATE TABLE IF NOT EXISTS `[onek]ipkontrol` (
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` char(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `denemesayi` tinyint(2) NOT NULL default '0',
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

$sql['menuler'] = "CREATE TABLE IF NOT EXISTS `[onek]menuler` (
  `menuno` tinyint(3) NOT NULL auto_increment,
  `menugrup` mediumint(1) NOT NULL default '1',
  `menuanahtar` varchar(25) collate utf8_unicode_ci NOT NULL,
  `menuresim` varchar(50) collate utf8_unicode_ci default NULL,
  `menuadi` varchar(100) collate utf8_unicode_ci NOT NULL,
  `menudil` enum('E','H') collate utf8_unicode_ci NOT NULL default 'E',
  `menusayfaadi` varchar(250) collate utf8_unicode_ci NOT NULL,
  `menusayfadil` enum('E','H') collate utf8_unicode_ci NOT NULL default 'E',
  `menudescription` varchar(200) collate utf8_unicode_ci NOT NULL default 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5',
  `menukeywords` text collate utf8_unicode_ci,
  `menuadres` text collate utf8_unicode_ci NOT NULL,
  `menuait` mediumint(1) NOT NULL default '1',
  `menuhedef` varchar(10) collate utf8_unicode_ci NOT NULL default '_self',
  `menu1sira` tinyint(3) NOT NULL default '0',
  `menu2sira` tinyint(3) NOT NULL default '0',
  `menuizin` mediumint(1) NOT NULL default '1',
  `menuduzen` enum('E','H') collate utf8_unicode_ci NOT NULL default 'H',
  `menudurum` enum('A','P') collate utf8_unicode_ci NOT NULL default 'A',
  PRIMARY KEY  (`menuno`),
  UNIQUE KEY `menuanahtar` (`menuanahtar`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";


$sql['ozelmesaj'] = "
CREATE TABLE IF NOT EXISTS `[onek]ozelmesaj` (
  `mesajno` int(10) unsigned NOT NULL auto_increment,
  `kimden` int(10) NOT NULL default '0',
  `kime` int(10) NOT NULL default '0',
  `baslik` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `mesaj` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `okundu` enum('E','H') NOT NULL default 'H',
  `cevaplandi` mediumint(1) NOT NULL default '0',
  PRIMARY KEY  (`mesajno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";

$sql['resim'] = "CREATE TABLE IF NOT EXISTS `[onek]resim` (
  `resimno` int(10) unsigned NOT NULL auto_increment,
  `albumno` int(5) NOT NULL default '0',
  `uyeno` int(10) NOT NULL default '0',
  `resim` varchar(30) collate utf8_unicode_ci default NULL,
  `resimadi` varchar(50) collate utf8_unicode_ci NOT NULL,
  `aciklama` varchar(150) collate utf8_unicode_ci default NULL,
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `duzentarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `puan` bigint(10) NOT NULL default '0',
  `goruntuleme` bigint(10) NOT NULL default '0',
  `onay` enum('E','H') collate utf8_unicode_ci NOT NULL default 'E',
  PRIMARY KEY  (`resimno`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";

$sql['resimpuan'] = "CREATE TABLE IF NOT EXISTS `[onek]resimpuan` (
  `resimno` int(10) NOT NULL default '0',
  `uyeno` text collate utf8_unicode_ci NOT NULL,
  UNIQUE KEY `resimno` (`resimno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";


$sql['sayac'] = "
CREATE TABLE IF NOT EXISTS `[onek]sayac` (
  `tarih` date NOT NULL default '0000-00-00',
  `buguntekil` int(5) NOT NULL default '0',
  `toplamtekil` int(10) NOT NULL default '0',
  `buguncogul` int(6) unsigned NOT NULL default '0',
  `toplamcogul` int(8) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

$sql['sozluk'] = "
CREATE TABLE IF NOT EXISTS `[onek]sozluk` (
  `sozcukno` int(10) NOT NULL auto_increment,
  `turkce` varchar(100) character set utf8 collate utf8_turkish_ci NOT NULL,
  `ingilizce` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL,
  `uyeno` int(10) NOT NULL default '0',
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `onay` enum('E','H') character set utf8 collate utf8_unicode_ci NOT NULL default 'E',
  PRIMARY KEY  (`sozcukno`),
  UNIQUE KEY `sozcukno` (`sozcukno`),
  KEY `sozluk_index` (`ingilizce`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";


$sql['uyeler'] = "
CREATE TABLE IF NOT EXISTS `[onek]uyeler` (
  `uyeno` int(10) unsigned NOT NULL auto_increment,
  `resim` char(16) character set utf8 collate utf8_unicode_ci default NULL,
  `uyeadi` char(25) character set utf8 collate utf8_unicode_ci NOT NULL,
  `sifre` char(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `adi` char(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `soyadi` char(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `eposta` char(150) character set utf8 collate utf8_unicode_ci NOT NULL,
  `dogumtarihi` date NOT NULL default '0000-00-00',
  `seviye` mediumint(1) NOT NULL default '1',
  `onay` enum('E','H') NOT NULL default 'H',
  `yonay` mediumint(1) NOT NULL default '0',
  `kayittarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  `girisdenemesayi` TINYINT( 3 ) NOT NULL DEFAULT '0' ,
  `girisdenemetarih` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `girissayisi` int(5) NOT NULL default '0',
  `songiris` datetime NOT NULL default '0000-00-00 00:00:00',
  `songiristarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  `guncellemetarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  `onlinetarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` char(50) NOT NULL default '1',
  `onaykodu` char(50) NOT NULL,
  `bilgi` mediumint(1) NOT NULL default '0',
  PRIMARY KEY  (`uyeno`),
  UNIQUE KEY `uyeadi_2` (`uyeadi`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";

$sql['yazikategori'] = "
CREATE TABLE IF NOT EXISTS `[onek]yazikategori` (
  `kategorino` int(5) NOT NULL auto_increment,
  `altkategorino` int(10) NOT NULL default '0',
  `kategoriadi` char(100) character set utf8 collate utf8_unicode_ci NOT NULL,
	`kategoriaciklama` char(200) character set utf8 collate utf8_unicode_ci NULL,
	`kategorisira` int(4) NULL default'0',
  PRIMARY KEY  (`kategorino`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";

$sql['yazilar'] = "
CREATE TABLE IF NOT EXISTS `[onek]yazilar` (
  `yazino` int(10) unsigned NOT NULL auto_increment,
  `kategorino` int(5) NOT NULL default '0',
  `uyeno` int(10) unsigned NOT NULL default '0',
  `resim` char(20) character set utf8 collate utf8_unicode_ci default NULL,
  `baslik` char(100) character set utf8 collate utf8_unicode_ci NOT NULL,
  `yazi` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `eklemetarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  `duzenlemetarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  `onay` enum('E','H') NOT NULL default 'H',
  `okunma` int(10) NOT NULL default '0',
	`puan` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`yazino`),
  KEY `kategorino` (`kategorino`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";

$sql['yazipuan'] = "
CREATE TABLE IF NOT EXISTS `[onek]yazipuan` (
  `yazino` int(10) NOT NULL default '0',
  `uyeno` text collate utf8_unicode_ci NOT NULL,
  UNIQUE KEY `yazino` (`yazino`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

$sql['yonetim'] = "
CREATE TABLE IF NOT EXISTS `[onek]yonetim` (
  `ayarno` mediumint(1) NOT NULL default '1',
  `siteadi` char(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `siteadresi` char(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `siteeposta` char(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `sitedil` char(4) character set utf8 collate utf8_unicode_ci NOT NULL default 'tr',	
  `sitetema` char(200) character set utf8 collate utf8_unicode_ci NOT NULL default 'tema1',
  `girisdenemesayisi` mediumint(2) NOT NULL default '5',
  `girisdenemesuresi` mediumint(2) NOT NULL default '5',
  `kayitarasisure` mediumint(2) NOT NULL default '5',
  `uyelikonayi` mediumint(1) unsigned NOT NULL default '1',
  `uyesilmezamani` mediumint(2) NOT NULL default '24',
  `epostadegisti` mediumint(1) NOT NULL default '0',
  `yazikarakter` int(10) NOT NULL default '5000',
  `yazieklemeizin` smallint(1) NOT NULL default '1',
  `yazikayitarasisure` tinyint(3) NOT NULL default '1',
  `yazionay` mediumint(1) NOT NULL default '1',
  `yaziduzenlemeizin` mediumint(1) NOT NULL default '2',
  `yaziduzenlemesuresi` mediumint(1) NOT NULL default '5',
  `yaziokumaizin` mediumint(1) NOT NULL default '0',
  `yaziayrintiokumaizin` mediumint(1) NOT NULL default '0',
  `yazioylama` enum('E','H') collate utf8_unicode_ci NOT NULL default 'H',
	`yazikategorisira` mediumint(1) NOT NULL default '1',
  `yorumkarakter` int(4) NOT NULL default '250',
  `yorumeklemeizin` mediumint(1) NOT NULL default '1',
  `yorumonay` mediumint(1) NOT NULL default '1',
  `yorumarasisure` mediumint(2) NOT NULL default '1',
  `ozelmesajgondermeizin` mediumint(1) NOT NULL default '1',
  `ozelmesajarasisure` tinyint(2) NOT NULL default '1',
  `ozelmesajkarakter` int(5) NOT NULL default '250',
  `ozelmesajizin` tinyint(3) NOT NULL default '10',
	`ozelmesajisimizin` mediumint(1) NOT NULL default '1',
  `hizlimesajeklemeizin` mediumint(1) NOT NULL default '1',
  `hizlimesajonay` mediumint(1) NOT NULL default '2',
  `hizlimesajsure` tinyint(2) NOT NULL default '1',
  `hizlimesajkarakter` int(5) NOT NULL default '250',
  `galeriresimgormeizin` mediumint(1) NOT NULL default '0',
  `galeriresimeklemeizin` mediumint(1) NOT NULL default '1',	
  `galeriresimkayitsure` tinyint(2) NOT NULL default '1',
	`galeriresimduzenizin` mediumint(1) NOT NULL default '1',
	`galeriresimduzensure` tinyint(2) NOT NULL default '1',
  `galeriresimonay` mediumint(1) NOT NULL default '2',
  `galeriresimoylama` enum('E','H') NOT NULL default 'E',
  `galerialbumeklemeizin` mediumint(1) NOT NULL default '1',
  `galerialbumkayitsure` tinyint(2) NOT NULL default '1',
  `galerialbumeklemesayi` tinyint(3) NOT NULL default '1',
  `galerialbumduzenizin` mediumint(1) NOT NULL default '1',
  `galerialbumduzensure` tinyint(2) NOT NULL default '1',
  `galerialbumonay` mediumint(1) NOT NULL default '1',
	`sozcukeklemeizin` mediumint(1) NOT NULL default '2',
  `sozcukduzenlemeizin` mediumint(1) NOT NULL default '5',
  `sozcukonay` mediumint(1) NOT NULL default '5',
  `uyekayitkapat` enum('E','H') NOT NULL default 'H',
	`uyegormeizin` mediumint(1) NOT NULL default '3',
  `uye1` char(20) character set utf8 collate utf8_unicode_ci NOT NULL default 'Normal Uye',
  `uye2` char(20) character set utf8 collate utf8_unicode_ci NOT NULL default 'Gumus Uye',
  `uye3` char(20) character set utf8 collate utf8_unicode_ci NOT NULL default 'Bronz Uye',
  `uye4` char(20) character set utf8 collate utf8_unicode_ci NOT NULL default 'Altin Uye',
  `uye5` char(20) character set utf8 collate utf8_unicode_ci NOT NULL default 'Yonetici',
  `uye6` char(20) character set utf8 collate utf8_unicode_ci NOT NULL default 'Genel Yönetici',
  PRIMARY KEY  (`ayarno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";




$sql['yorumlar'] = "
CREATE TABLE IF NOT EXISTS `[onek]yorumlar` (
  `yorumno` int(10) NOT NULL auto_increment,
  `yazino` int(10) NOT NULL default '0',
  `resimno` int(10) NOT NULL default '0',
  `uyeno` int(10) NOT NULL default '0',
  `baslik` char(100) collate utf8_unicode_ci default NULL,
  `yorum` text collate utf8_unicode_ci NOT NULL,
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `onay` enum('E','H') collate utf8_unicode_ci NOT NULL default 'H',
  PRIMARY KEY  (`yorumno`),
  KEY `yazino` (`yazino`),
  KEY `resimno` (`resimno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
";
					
$vt = new Baglanti();
$sitead       = trim(htmlspecialchars($_POST['sitead']));
$siteadres    = trim(htmlspecialchars($_POST['siteadres']));
$siteeposta   = trim(htmlspecialchars($_POST['siteeposta']));
$kullaniciadi = trim(htmlspecialchars($_POST['kullaniciadi']));
$sifre        = trim(htmlspecialchars($_POST['sifre']));
$sifretekrar  = trim(htmlspecialchars($_POST['sifretekrar']));
					
try { 
  if (empty($sitead) || empty($siteadres) || empty($siteeposta) || empty($kullaniciadi) || empty($sifre) || empty($sifretekrar))
  {
    throw new Exception('* İşaretli Alanları Boş Bırakmayınız');
    exit;
  }
  if (preg_match('/[^a-zA-Z0-9_]/',$kullaniciadi))
  {
    throw new Exception('Kullanıcı Adı Geçersiz');
    exit;
  }
  if (preg_match('/[^a-zA-Z0-9_]/',$sifre) || preg_match('/[^a-zA-Z0-9_]/',$sifretekrar))
  {
    throw new Exception('Şifre Geçersiz');
    exit;
  }
  if ($sifre != $sifretekrar)
  {
    throw new Exception('Şifreleriniz Birbiriyle Aynı Değil');
    exit;
  }
  if (!preg_match("/^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$/", $siteeposta))
  {
    throw new Exception('E-Posta Geçersiz');
    exit;
  }
  function tablo_kontrol($tabloadi)
  {
    $vt = new Baglanti();
    $sonuc = false;
    $vt->query("SHOW TABLE STATUS");
    while($tablo = $vt->fetchArray())
    {
      $tablo_adi = $tablo['Name'];
      if ($tabloadi == $tablo_adi)
      {
        $sonuc = true;
      }
    }
    $vt->freeResult();
    return $sonuc;
  }
  echo '<tr><td width="100%" align="left" style="padding-left:20px">';

  foreach($sql as $tabloadi => $sorgu)
  {
    $sorgu = str_replace('[onek]',TABLO_ONEKI,$sorgu);
    if (!tablo_kontrol(TABLO_ONEKI.$tabloadi))
    {
      if ($vt->query($sorgu))
      {
        echo '<font color="#008000">'.TABLO_ONEKI.$tabloadi.' -> Tablosu Oluşturuldu</font><br />';
      }
    } else {
      echo '<font color="#ff0000">'.TABLO_ONEKI.$tabloadi.' -> Tablo Önceden Oluşturulmuş<br />';
    }
  }
  echo '<tr><td width="100%"  align="center">&nbsp;</td></tr>';
  echo '<tr><td align="center">';
	
  if ($vt->kayitSay("SELECT COUNT(tarih) FROM ".TABLO_ONEKI."sayac")==0)
  {
    $vt->query("INSERT INTO `".TABLO_ONEKI."sayac` VALUES (CURRENT_DATE(), 1, 1, 1, 1)");
  }
  define("UYE_IP", getenv("REMOTE_ADDR"));
  if ($vt->kayitSay("SELECT COUNT(ip) FROM ".TABLO_ONEKI."ipkontrol WHERE ip='".UYE_IP."'") == 0)
  {
    $vt->query("INSERT INTO `".TABLO_ONEKI."ipkontrol` (tarih,ip,denemesayi) VALUES (NOW(), '".UYE_IP."', 0)");
  }
  if ($vt->kayitSay("SELECT COUNT(uyeno) FROM ".TABLO_ONEKI."uyeler") == 0)
  {
    $yenisifre = sha1($sifre);
    $kod = sha1($yenisifre);
    $vt->query("INSERT INTO `".TABLO_ONEKI."uyeler` (resim,uyeadi,sifre,adi,soyadi,eposta,dogumtarihi,seviye,onay,yonay,kayittarihi,girisdenemesayi,girisdenemetarih,girissayisi,songiris,songiristarihi,guncellemetarihi,onlinetarih,ip,onaykodu,bilgi) VALUES ('','$kullaniciadi', '$yenisifre', 'PeHePe', 'PeHePe', '$siteeposta', '0000-00-00', 6, 'E', 5, NOW(),0,'0000-00-00 00:00:00', 0,NOW(), '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".UYE_IP."', '$kod', 1)");
  }
  if ($vt->kayitSay("SELECT COUNT(ayarno) FROM ".TABLO_ONEKI."yonetim") == 0)
  {
    $vt->query("INSERT INTO `".TABLO_ONEKI."yonetim` (siteadi,siteadresi,siteeposta,sitedil,girisdenemesayisi,girisdenemesuresi,kayitarasisure,uyelikonayi,uyesilmezamani,epostadegisti,yazikarakter,yazieklemeizin,yazikayitarasisure,yazionay,yaziduzenlemeizin,yaziduzenlemesuresi,yaziayrintiokumaizin,yazioylama,yorumkarakter,yorumeklemeizin,yorumonay,yorumarasisure,ozelmesajgondermeizin,hizlimesajeklemeizin,hizlimesajonay,hizlimesajsure,hizlimesajkarakter,galeriresimgormeizin,galeriresimeklemeizin,galeriresimkayitsure,galeriresimduzenizin,galeriresimduzensure,galeriresimonay,galeriresimoylama,galerialbumeklemeizin,galerialbumkayitsure,galerialbumeklemesayi,galerialbumduzenizin,galerialbumduzensure,galerialbumonay,uyekayitkapat,uyegormeizin,uye1,uye2,uye3,uye4,uye5,uye6) VALUES ('$sitead', '$siteadres', '$siteeposta', 'tr', 5, 5, 1, 3, 24, 0, 5000, 1, 1, 1, 2, 5, 0, 'H', 250, 1, 1, 1, 1, 1, 1, 1, 250, 0, 1, 1, 3, 1, 2, 'E', 1, 1, 1, 1, 1, 2, 'H', '3', 'Normal Uye', 'Gumus Uye', 'Bronz Uye', 'Altin Uye', 'Yonetici', 'Genel Yönetici')");
  }
	
	//Baglanti Adresi Ekleniyor
	$vt->query("INSERT INTO `".TABLO_ONEKI."baglantilar` (`baglantiadres`, `baglantiadi`, `baglantihedef`, `baglantionay`) VALUES 
('http://www.arslandesign.com', 'Arslan Web Tasarım', '_blank', 'E'),
('http://www.turk-php.com','Türk PHP','_blank','E'),
('http://www.ceviz.net','Ceviz.Net','_blank','E'),
('http://www.php.net','PHP Resmi Web Sitesi','_blank','E'),
('http://www.mysql.com','MySQL Resmi Web Sitesi','_blank','E');");

//Menu Verileri Ekleniyor
  $vt->query("INSERT INTO `u_menuler` (`menuno`, `menugrup`, `menuanahtar`, `menuresim`, `menuadi`, `menudil`, `menusayfaadi`, `menusayfadil`, `menudescription`, `menukeywords`, `menuadres`, `menuait`, `menuhedef`, `menu1sira`, `menu2sira`, `menuizin`, `menuduzen`, `menudurum`) VALUES
(1, 1, 'anasayfa', 'anasayfa.gif', 'AnaSayfa', 'E', '', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'index.php', 0, '_self', 1, 0, 0, 'H', 'A'),
(2, 1, 'yazi', 'yazi.gif', 'SonYazilar', 'E', 'Yazilar', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/yazi_yorum.php', 1, '_self', 2, 0, 0, 'H', 'A'),
(3, 1, 'yaziekle', 'yaziekle.gif', 'YaziEkle', 'E', 'YaziEkle', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/yazi_ekle.php', 1, '_self', 5, 0, 0, 'H', 'A'),
(4, 1, 'indir', 'indir.gif', 'Indir', 'E', 'Indir', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/indir.php', 1, '_self', 4, 0, 0, 'H', 'A'),
(5, 1, 'galeri', 'galeri.gif', 'ResimGalerisi', 'E', 'ResimGalerisi', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/galeri.php', 1, '_self', 3, 0, 0, 'H', 'A'),
(6, 0, 'albumekle', 'albumekle.gif', 'AlbumEkle', 'E', 'AlbumEkle', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/album_ekle.php', 1, '_self', 6, 5, 0, 'H', 'A'),
(7, 1, 'resimekle', 'resimekle.gif', 'ResimEkle', 'E', 'ResimEkle', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/resim_ekle.php', 1, '_self', 7, 0, 0, 'H', 'A'),
(8, 1, 'kayit', 'kayit.gif', 'KayitOl', 'E', 'KayitOl', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/kayit_form.php', 1, '_self', 8, 0, -1, 'H', 'A'),
(9, 1, 'cikis', 'cikis.gif', 'Cikis', 'E', '', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'cikis.php', 0, '_self', 9, 0, 1, 'H', 'A'),
(10, 2, 'ornek', NULL, 'Örnek Sayfa (Test Page)', 'H', 'Örnek Sayfa', 'H', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/ornek.php', 1, '_self', 0, 2, 0, 'E', 'A'),
(11, 2, 'uye', NULL, 'Uyeler', 'E', 'Uyeler', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/uye_profil.php', 1, '_self', 0, 3, 0, 'H', 'A'),
(12, 2, 'aciklama', '', 'Sistem Özellikleri ve Kurulum', 'H', 'Sistem Özellikleri ve Kurulum', 'H', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/aciklama.php', 1, '_self', 10, 7, 0, 'E', 'A'),
(13, 2, 'anket', '', 'Anket', 'E', 'Anket', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/anket_form.php', 1, '_self', 0, 6, 0, 'H', 'A'),
(14, 2, 'sozluk', '', 'Sozluk', 'E', 'Sozluk', 'E', 'PHP Üyelik Sistemi, www.arslandesign.com/projeler/uyelik5', NULL, 'sayfa/sozluk.php', 1, '_self', 0, 1, 0, 'H', 'A'),
(15, 2, 'yorum', '', 'Yorumlar', 'H', 'Yorumlar', 'H', 'PeHePe Üyelik Sistemi', 'Üyelik Sistemi, Uyelik Sistemi', 'sayfa/yorumlar.php', 1, '_self', 0, 4, 1, 'E', 'A');
");



$vt->query("INSERT INTO `".TABLO_ONEKI."yazilar` (`yazino`, `kategorino`, `uyeno`, `resim`, `baslik`, `yazi`, `eklemetarihi`, `duzenlemetarihi`, `onay`, `okunma`, `puan`) VALUES 
(1, 0, 1, NULL, 'PeHePe Üyelik Sistemi v5.4', 'Hoşgeldiniz\r\nBu mesaj otomatik olarak oluşturulmuştur.\r\nPeHePe Üyelik Sistemi 5.4 ile Bir Çok Yeni Özellik Sisteme Eklenmiştir.\r\nBu Sistemin İşinize Yaramasını Umuyorum.\r\nGördüğünüz Hataları ve Eksiklikleri http://www.arslandesign.com/projeler/uyelik5 Adresine Yazabilirsiniz.\r\nKolay Gelsin', NOW(), '0000-00-00 00:00:00', 'E', 0, 0)");

$vt->query("INSERT INTO `".TABLO_ONEKI."dosyalar` (`dosyano`, `dosyaadi`, `dosyayolu`, `dosyadeneme`, `dosyaaciklama`, `dosyakayittarih`, `dosyaduzentarih`, `dosyaindirsayi`, `dosyaonay`) VALUES 
(1, 'PeHePe Üyelik Sistemi v5.4 (Son Sürüm)', 'indir/pehepe_uyelik_v5.4.zip', 'http://www.arslandesign.com/projeler/uyelik5', '--------------------------------------------------------------\r\nSistemin Kurulumu Çok Basittir. kurulum_install.php yi Çalıştırıp Adımları Takip Ediniz \r\nBu Sistemde ajax (sajax 0.12) Sistemi Kullanılmıştır \r\nBu Sistem PHP 5 den Daha Alt Versiyonlarda Çalışmaz \r\n-------------------------------------------------------------\r\nPeHePe Üyelik Sistemi Diğer Versiyonlara Ek Olarak v.4 İle Gelen Özellikleri\r\n- Yönetim Panelinden İstediğiniz Kadar Menü ve Sayfa Ekleme Özelliği\r\n- Bağlantılar Ekleme Özelliği\r\n\r\nSınırsız Alt Kategori Oluşturma Özelliği Eklendi\r\nKategorileri Sıralama Özelliği Eklendi (16.05.08 İtibariyle)\r\nTema Özelliği Eklendi\r\nBugün Kayıt Olan Üyeler Özelliği Eklendi\r\nYazılara RSS Özelliği Eklendi\r\n\r\n\r\nOturumla ve Çerezle Sayfa Denetimi.. \r\nÇerezle Üyenin Tekrar Gelişinde Tanınması \r\nÜyelik Kaydı \r\n5 Seviyede Üye Kaydı \r\nBazı Bölümler İstenilen Üye Seviyesine Gösterilebilir \r\nÜye Profiline Resim Ekleme \r\nŞifre Unutmada Yeni Şifrenin E-Posta Hesabına Gönderilmesi \r\nYönetim Panelinden Tüm Site Ayarlarının Yapılması \r\nResim Galerisi Eklendi (Yeni) \r\nResimlere Oy Verme ve Yorum Yapma Eklendi (Yeni) \r\nKişisel veya Genel Resim Albümü Oluşturma Eklendi (Yeni) \r\nToplu E-Posta ve Özel Mesaj Gönderme Eklendi (Yeni) \r\nSite İçi Özel Mesajlaşma \r\nÖzel Mesaj Göndermede Üye Arayabilme \r\nGelen ve Giden Mesajları Sıralayabilme \r\nGünlük ve Toplam Tekil Sayaç \r\nOnline Kişi Sayısı (Dakikada Güncellenir. Gerçekçi Olarak Çevrimiçi (Online) Kişileri Gösterir) \r\nOnline Üyeler (Dakikada Güncellenir. Gerçekçi Olarak Çevrimiçi (Online) Üyeleri Gösterir) \r\nYazı Ekleme Bölümü \r\nYazılara HTML Kodu Ekleyebilme \r\nYazılara Yorum Ekleyebilme \r\nYazılara Kategori Ekleyebilme \r\nYazılara Oy Verme \r\nHızlı Mesajlaşma Sistemi \r\nİstediğiniz Seçeneğe İzin Verebileceğiniz Anket \r\nDoğum Günü Olan Üyeleri Gösterme \r\nDil Dosyası Diğer Dillere Çevrilebilir', '2008-06-14 12:22:16', '2008-07-26 18:50:27', 1757, 'E')");



  echo '</td></tr>';
  echo '<tr><td width="100%" align="center"><font color="#008000">Kurulum Başarıyla Tamamlandı</font><br /><br />';
  echo '<font color="#008000">Site Ayarlarını Yönetim Panelinden Değiştirebilirsiniz. icerik klasörünün chmod ayarını değiştirmeyi unutmayınız...</font><br /><br />';
  echo '<font color="#008000"><a href="kurulum_install.php?adim=4">SİTEYE GİT ve KURULUM DOSYASINI KALDIR</a></font><br /><br />';
  echo '<font color="#ff0000">Eğer kurulum_install.php Silinmezse Lütfen Kaldırınız</font></td></tr>';
}
catch (Exception $e)
{
  echo '<tr><td width="100%" align="center"><font color="#ff0000">'.$e->getMessage().'</font><br /><a href="javascript:history.go(-1)">Geri Dön</a></td></tr>';
}
echo '<tr><td width="100%"  align="center">&nbsp;</td></tr>';
} elseif ($adim == 4) {
  @ unlink('kurulum_install.php');
	sleep(1);
  header('Location: index.php');
}
?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
