<?php
//VERITABANI BAGLANTI SINIFI
header("Content-Type: text/html; charset=UTF-8");
class Baglanti
{
	//=============================================================================
  //DUZENLENECEK BOLUM
	//Alttaki 4 Satiri Veritabani Bilgilerinize Gore Duzenleyiniz...
	//Sabit Degiskenler
  private $sunucu   = 'localhost'; // Sunucu - Server
  private $kuladi   = 'root'; // Veritabani Kullanici Adi - MySQL User
  private $sifre    = '1234'; // Veritabani Sifresi - MysQL Password
  private $vtadi    = 'uyelik5'; // Veritabani Adi - MySQL Name
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
        throw new Exception ('Hata: Veritabani Baglantisi Kurulamadi');
      }
			//Veritabani Secimi
			$this->db = @ mysqli_select_db($this->vt,$this->vtadi);
		  if (!$this->db )
			{
			  throw new Exception ('Hata: Veritabanı Secilemedi');
			}
			@ mysqli_query($this->vt,"SET NAMES 'utf8'");
			@ mysqli_query($this->vt,"SET CHARACTER SET 'utf8'");
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
      $tablo_adi = $tablo['Name'];
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
        throw new Exception ('Sorgu Hatasi : ('.mysqli_error($this->vt).')');
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
      throw new Exception ('Sorgu Hatasi : ('.mysqli_error($this->vt).')');
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
	
	//Verileri Dizi Değişkeni Olarak Listele 'Kolon İsimli Dizi'
	public function fetchAssoc()
	{
	  return ( @mysqli_fetch_assoc($this->result) );
	}
	
	//Verileri Dizi Degiskeni Olarak Listele 'Sayili Dizi'
	public function fetchArray()
	{
	  return ( @mysqli_fetch_array($this->result) );
	}
	
	//Verileri Dizi Degiskeni Olarak Listele 'Obje Olarak'
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
define("TABLO_ONEKI", 'u_'); //Kurulum Yapmadan Once Tablo Onekini Buradan Degistirebilirsiniz...
//========================================================================================================
?>