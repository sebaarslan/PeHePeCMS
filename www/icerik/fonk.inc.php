<?php
@ $mimikler = array();
@ $mimikler[1] = array('[:)]',$dil['Gulumseme']);
@ $mimikler[2] = array('[:D]',$dil['Kahkaha']);
@ $mimikler[3] = array('[;)]',$dil['GozKirpma']);
@ $mimikler[4] = array('[:S]',$dil['KafasiKarismis']);
@ $mimikler[5] = array('[:$]',$dil['Utanmis']);
@ $mimikler[6] = array('[8)]',$dil['Cekici']);
@ $mimikler[7] = array('[:(]',$dil['Uzgun']);
@ $mimikler[8] = array('[:@]',$dil['Kizgin']);
@ $mimikler[9] = array('[:P]',$dil['DilCikarmis']);
@ $mimikler[10] = array('[:O]',$dil['Saskin']);
@ $mimikler[11] = array("[:,(]",$dil['Aglayan']);

class Fonksiyon
{
  //Harf Rakam veya Altcizgi Kullanim Kontrolu
  public function kuladi_kontrol($girdi)
  {
    if (preg_match('#^[a-zA-Z0-9_-]+$#i',$girdi))
		return true;
		else
		return false;
  }
	
	//Sadece a-z, A-Z ve Sayilara Izin Verir
	public function parola_kontrol($girdi)
  {
    if (!preg_match('/[\"|\'|\s\<\>]/',$girdi))
		return true;
		else
		return false;
  }
	
	//E-Posta Kontrol 
  function eposta_kontrol($eposta)
  {
    if (!preg_match ("/^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$/", $eposta))
    {
      return false;
    } else {
      return true;
    }
  }
	
	//Web-Site Adresi Kontrolu
	function website_kontrol($adres)
	{
	  if (preg_match ('/^(http|https)\:\/\/[a-z0-9-]+(\.[a-z0-9-]+)+/i',$adres))
		{
		  return false;
		} else {
		  return true;
		}
	}
	
	//Sadece a-z, A-Z ve Türkçe Karakterlere Izin Verir
	public function turkceharf_kontrol($girdi)
  {
    if (preg_match('/[^a-zA-ZÇĞİÖŞÜçğıöşü ]/',$girdi))
    {
      return false;
    } else {
      return true;
    }
  }
	
	//Sadece a-z ve A-Z Harflere Izin Verir
	public function harf_kontrol($girdi)
  {
    if (preg_match('/[^a-zA-Z ]/',$girdi))
    {
      return false;
    } else {
      return true;
    }
  }
	public function resim_adi_kontrol($girdi)
	{
		if (preg_match('/[^a-zA-Z0-9_:\.\\ ]/',$girdi))
    {
      return false;
    } else {
      return true;
    }
	}
  public function resim_uzanti($resim)
  {
    $degerler = explode('.',$resim);
    $sonnokta = count($degerler)-1; 
    $uzanti   = $degerler[$sonnokta]; 
    return $uzanti;
  }
  
  //Normal Tarihin MySQL Tarih Biçimine Çevrilmesi
  //gg.aa.yyy Formatindaki Tarihi yyyy-aa-gg Sekline Cevirir...
  public function mysql_tarih_kontrol($tarih)
  {
    if (preg_match('/^([0-9]{1,2})+\.([0-9]{1,2})+\.+([0-9]{4})$/', $tarih))
    {
      @ $tarihparca = explode('.',$tarih);
      if (is_array($tarihparca))
      {
        $gun = $tarihparca[0];
        $ay = $tarihparca[1];
        $yil = $tarihparca[2];
        if (checkdate($ay,$gun,$yil))
        {
          return "$yil-$ay-$gun";
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
	
	//MySQL e Giden Tarih ve Zamanı MySQL Tarih Bicimine Cevirir
  public function mysql_tarih_saat($tarihsaat,$deger=false)
  {
    if ($deger)
    {
      @list($gun,$ay,$yil,$saat,$dakika,$saniye) = preg_split('/[.\ \:]/',$tarihsaat);
      return "$yil-$ay-$gun $saat:$dakika:$saniye";
    } else {
      @list($gun,$ay,$yil) = explode('.',$tarihsaat);
      return "$yil-$ay-$gun";
    }
  }
	
	//Tarih Kontrol
  // Tarihin gg.aa.yyyy  eklinde Olup Olmad n  Kontrol Eder
  function tarih_kontrol($tarih)
  {
	  if (preg_match('/^([0-9]{2})+\.([0-9]{2})+\.+([0-9]{4})$/', $tarih))
	  {
	    @ $tarihparca = explode('.',$tarih);
	    if (is_array($tarihparca))
	    {
	      $gun = $tarihparca[0];
	      $ay = $tarihparca[1];
	      $yil = $tarihparca[2];
		    if (checkdate($ay,$gun,$yil))
		    {
		      return true;
		    } else {
		      return false;
		    }
	    } else {
		    return false;
	    }
	  } else {
	    return false;
	  }
  }
  
  //Tarih Saat Kontrol
  // Zaman n gg.aa.yyyy ss:dd eklinde Olup Olmad  n Kontrol Eder
  function tarihsaat_kontrol($tarihsaat)
  {
	  if (preg_match('/^([0-9]{2})+\.([0-9]{2})+\.+([0-9]{4})+([\ \]{1})+([0-9]{2})+:([0-9]{2})+(:{0,2})+([0-9]{0,2})$/', $tarihsaat))
	  {
	    @ list ($gun,$ay,$yil,$saat,$dakika,$saniye) = split('[.\ \:]',$tarihsaat);
	    if (isset($gun,$ay,$yil,$saat,$dakika))
	    {
        if (!$saniye)
        {
          $saniye = 0;
        }
        if (checkdate($ay,$gun,$yil) && ($saat < 24 && $saat > -1) && ($dakika < 60 && $dakika > -1) && ($saniye < 60 && $saniye > -1))
        {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
	  } else {
	    return false;
	  }
  }
	
  //MySQL den Gelen Tarih ve Zamanı Duzgun Tarih Bicimine Cevirir
  public function duzgun_tarih_saat($tarihsaat,$deger=false)
  {
    if ($deger)
    {
      @list($yil,$ay,$gun,$saat,$dakika,$saniye) = split('[-\ \:]',$tarihsaat);
      return "$gun.$ay.$yil $saat:$dakika:$saniye";
    } else {
      @list($yil,$ay,$gun) = explode('-',$tarihsaat);
      return "$gun.$ay.$yil";
    }
  }
	
  //Verilen Yilin Ayinin Kac Gun Olduğunu Dondurur
	public function ay_gun_sayisi($yil,$ay)
	{
	  return date('t',strtotime($yil.'-'.$ay.'-01'));
	}

	public function ayadi($ay)
	{
    global $pdil;
    return $pdil['aylar'][$ay];
  }
	
	//Dil Dosyasi Icin Deger Ekleme
	public function yerine_koy($yazi,$deger)
	{
	  if (is_array($deger))
		{
	    $sayi   = count($deger);
		  $isaret = array();
	    for ($i=1; $i<=$sayi; ++$i)
		  {
		    array_push($isaret,"[".$i."]");
		  }
		} else {
		  $isaret = '[1]';
		}
		return str_replace($isaret,$deger,$yazi);
	}
	
	//Rastgele Kod Uretimi

	public function kod($uzunluk) 
  {
    $karakterler = "0123456789".
                 "abcdefghijklmnopqrstuvwxyz".
                 "ABCDEFGHIJKLMNOPQRSTUVWXYZ".
								 ")(.=";
    $kod = "";
    while(strlen($kod) < $uzunluk) 
    {
      $kod .= substr($karakterler, (rand() % strlen($karakterler)), 1);
    }
    return($kod);
  }

	function boyutlandir($resim='',$max_en,$max_boy)  
  {  
	ob_start();
  // Resmin Boyutunu Aliyoruz 
  $boyut = getimagesize($resim);  
  $en    = $boyut[0];  
  $boy   = $boyut[1];  

  // Boyut Oranlarini  Belirliyoruz 
  $x_oran = $max_en  / $en;  
  $y_oran = $max_boy / $boy;  

  // Resmin Yeni Boyunu Orantili  Sekilde Ayarliyoruz 
  if (($en <= $max_en) and ($boy <= $max_boy)) 
  {  
    $son_en  = $en;  
    $son_boy = $boy;  
  } else if (($x_oran * $boy) < $max_boy) {  
    $son_en  = $max_en;  
    $son_boy = ceil($x_oran * $boy);  
  } else {  
    $son_en  = ceil($y_oran * $en);  
    $son_boy = $max_boy;  
  }  
  
  // Resim Uzantsini Aliyoruz  
  $uzantilar =  pathinfo($resim); 
  $uzanti    = $uzantilar["extension"]; 

  if ($uzanti == 'gif') 
  { 
    $eski = imagecreatefromgif($resim) or die($dil['IslemBasarisiz']); 
  } elseif ($uzanti == 'png') { 
    $eski = imagecreatefrompng($resim) or die ($dil['IslemBasarisiz']); 
  } else { 
    $eski = imagecreatefromjpeg($resim) or die ($dil['IslemBasarisiz']); 
  }  
  $yeni = imagecreatetruecolor($son_en,$son_boy);  

  // Eski Resmi Yeniden Renklendiriyoruz  
  $renk = imagecolorallocate($yeni,255,255,255); 
  imagefill($yeni,0,0,$renk); 
  imagecopyresampled($yeni,$eski,0,0,0,0,$son_en,$son_boy,$en,$boy);  
  
  // Yeni Resmi Tarayiciya Yansitiyoruz
  if ($uzanti == 'gif') 
  { 
    //header("Content-type: image/gif"); 
    imagegif($yeni,null,100); 
  } elseif ($uzanti == 'png') { 
    //header("Content-type: image/png"); 
    imagepng($yeni,null,100); 
  } elseif ($uzanti == 'jpg' || $uzanti == 'jpeg') {
    //header("Content-type: image/jpeg"); 
    imagejpeg($yeni,null,100); 
  } else {
	  return false;
	}
  @$icerik = ob_get_contents();  
  ob_end_clean(); 
  @imagedestroy($eski);  
  @imagedestroy($yeni);  
  return $icerik;  
  } 
	
  //Sayfalama Fonksiyonu
	function sayfalama($limit, $satir_sayisi, $sayfano, $sayfaadi='') 
  { 
	  global $dil;
		$sayfa_sayisi = 0;
    $sayfalama = '';
    
		//Onceki
		if ($sayfano>1)
    $sayfalama .= '<a href="'.str_replace('[sn]',($sayfano-1),$sayfaadi).'">&laquo;&nbsp;'.$dil['Onceki'].'</a>&nbsp;&nbsp;&nbsp;';
    else
    $sayfalama .= '&laquo;&nbsp;'.$dil['Onceki'].'&nbsp;&nbsp;&nbsp;&nbsp;';
			
		if($satir_sayisi > $limit) 
    {                 
      $sayfa_sayisi = ceil($satir_sayisi / $limit);                          
      if($sayfano == $sayfa_sayisi) 
      {                         
        $to = $sayfa_sayisi;                 
      } elseif($sayfano == $sayfa_sayisi - 1) {                         
        $to = $sayfano + 1;                 
      } elseif($sayfano == $sayfa_sayisi - 2) {                         
        $to = $sayfano + 2;                 
      } else {                         
        $to = $sayfano + 3;                 
      }                
      if($sayfano < 4) 
      {                         
        $from = 1;                 
      } else {                         
        $from = $sayfano - 3;                 
      } 
      $i = 1;
			
      if (4 < $sayfano) 
      $sayfalama .= ' <b><a href="'.str_replace('[sn]',$i,$sayfaadi).'"><b>1</b></a>........</b> '; 
                
      for($i=$from; $i <= $to; $i++) 
      {  
			                        
        if($i == $sayfano) 
        {         
          $sayfalama .= ' <b>['.$i.']</b> ';                         
        } else {         
          $sayfalama .= ' <a href="'.str_replace('[sn]',$i,$sayfaadi).'">'.$i.'</a> ';                         
        }                 
      } 
             
      if ($to < $sayfa_sayisi) 
      { 
        $sayfalama .= ' <b>........<a href="'.str_replace('[sn]',$sayfa_sayisi,$sayfaadi).'"> '.$sayfa_sayisi.'</a></b> '; 
      }  
    } else {
		  $sayfalama .= "<b>1</b>";
		}  

		if ($sayfano<$sayfa_sayisi)
    $sayfalama .= '&nbsp;&nbsp;&nbsp;<a href="'.str_replace('[sn]',($sayfano+1),$sayfaadi).'">'.$dil['Sonraki'].'&nbsp;&raquo;</a>';
    else
    $sayfalama .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$dil['Sonraki'].'&nbsp;&raquo;';     


		unset($sayfano,$from,$sayfa_sayisi,$satir_sayisi,$to);        
    return $sayfalama; 
  } 
	
  function yorum_eposta_bilgi($yorumyazino,$uyeno)
  {
    global $vt;
	  global $dil;

    //EKLENEN YORUM İÇİN YAZI SAHİBİNE E-POSTA GÖNDERİLİYOR
    $vt->query("SELECT y.yazino,y.baslik,u.uyeno,u.uyeadi,u.eposta,u.bilgi
		FROM ".TABLO_ONEKI."yazilar AS y, ".TABLO_ONEKI."uyeler AS u 
		WHERE y.yazino=$yorumyazino AND y.uyeno=u.uyeno");
    if ($vt->numRows()>0)
		{
      $yazibilgi        = $vt->fetchObject();
      $yazi_no          = $yazibilgi->yazino;
      $yazi_baslik      = $yazibilgi->baslik;
      $yazi_eposta      = $yazibilgi->eposta;
		  $yazi_uyeno       = intval($yazibilgi->uyeno);
		  $uye_epostabilgi  = $yazibilgi->bilgi;
		

		  //Yorum Ekleyenin Ismi Aliniyor
		  $yazi_uyeadi      = $this->uye_adi($uyeno);

      $eposta_konu      = $dil['YeniYorumEklendiBilgisi'];
      $eposta_mesaj     = '
        <tr>
          <td align="left">'.$dil['DegerliUyemiz'].'</td>
        </tr>
        <tr>
          <td align="left">'.$this->yerine_koy('<b>'.$yazi_baslik.'</b>',$dil['YeniYorumEklendi']).'</td>
        </tr>
			  <tr>
          <td align="left">'.$dil['YorumEkleyen'].' : '.$yazi_uyeadi.'</td>
        </tr>
			  <tr>
          <td align="left">'.$dil['YorumlariGormekIcinTikla'].'</td>
        </tr>
			  <tr>
          <td align="left"><a href="'.SITE_ADRESI.'/?sayfa=yazi&yazino='.$yazi_no.'&islem=2" target="_blank">'.$dil['YORUMLARA_BAK'].'</a></td>
        </tr>';
		  //Yorumu Yazan Yazi Sahibi Degilse E-Posta Gonderiliyor
		  if ($yazi_uyeno != $uyeno && $uye_epostabilgi>0)
		  {
		    $this->eposta_gonder(array($yazi_eposta=>$yazi_baslik),$eposta_konu,$eposta_mesaj);
		  }
      //---------------------------------------------------------------------------
      //EKLENEN YORUM ICIN DIGER YORUM SAHIPLERINE E-POSTA GONDERILIYOR
      $vt->query("SELECT u.eposta,u.uyeno,u.bilgi FROM ".TABLO_ONEKI."yorumlar AS k, ".TABLO_ONEKI."uyeler AS u 
		  WHERE k.uyeno=u.uyeno AND k.yazino=$yorumyazino AND k.uyeno<>$uyeno AND k.onay='E' AND u.yonay=5 AND k.uyeno<>$yazi_uyeno AND u.bilgi>0
		  GROUP BY k.uyeno ORDER BY k.tarih DESC");
      $yorum_sayi     = $vt->numRows();

      if ($yorum_sayi > 0)
      {
        for ($y=0; $y<$yorum_sayi; $y++)
        {
          $yorum_bilgi  = $vt->fetchObject();
          $yorum_eposta = $yorum_bilgi->eposta;
				  //Diger Yorum Sahiplerine Yeni Yorum Eklendi Bilgisi Gonderiliyor
					$this->eposta_gonder(array($yorum_eposta=>''),$eposta_konu,$eposta_mesaj);
        }
		    unset($yorum_bilgi,$yorum_eposta,$yorum_sayi);
      }			
      $vt->freeResult();
	    unset($vt,$eposta_ustbilgi,$eposta_konu,$eposta_mesaj,$yazi_eposta,$yorum_sayi,$yazi_no,$yazi_baslik,$yazibilgi);
    } else {
		  return false;
		}
  }
  //===========================================================================
  //E-Posta Gonderme Fonksiyonu
	function eposta_gonder($eposta_dizi=array(),$konu='',$mesaj='',$cc=true,$type='html',$eposta_klasor='')
	{
		if ($type=='html')
		{
		$eposta_mesaj = "
    <html>
    <head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
    <title>".$konu."</title>
    </head>
    <body>
    <table align=\"left\">
      ".$mesaj."
      <tr>
        <td align=\"left\"><br /><br /><a href=\"".SITE_ADRESI."\" target=\"_blank\">".SITE_ADI."</a></td>
      </tr>
    </table>
    </body>
    </html>";
		} else {
		  $eposta_mesaj = $mesaj;
		}
		$eposta_sayi = count($eposta_dizi);

		$gonderme = false;
		
		if (SMTP_SUNUCU && SMTP_KULLANICI && SMTP_SIFRE)
		{
		  require_once($eposta_klasor.'kutuphane/XPM4-v.0.5/MAIL.php'); // path to 'MAIL.php' file from XPM4 package
      $m = new MAIL; // initialize MAIL class
      $m->From(SITE_EPOSTA); // set from address
			
      if ($eposta_sayi>0)
			{
			  foreach($eposta_dizi as $eposta_adres=>$eposta_kisi)
				{
				  //E-Posta Seri Olarak Gonderiliyor
				  $m->To[] = array(
          'address'  => $eposta_adres,
		      'name'     => $eposta_kisi, // required
          'charset'  => 'utf-8', // optional
          'encoding' => 'base64' // optional
				  );
				}
			} else {
			  //E-Posta Tek Kisiye Gonderiliyor
			  $m->AddTo(trim($eposta));
			}
		  if ($cc)
		  $m->AddCc(SITE_EPOSTA);
      $m->Subject($konu); // set subject
      $m->Html($eposta_mesaj,'utf-8');
      // connect to MTA server 'smtp.hostname.net' port '25' with authentication: 'username'/'password'
		  if (@$c = $m->Connect(SMTP_SUNUCU, 25, SMTP_KULLANICI, SMTP_SIFRE))
		  {
        if ($c)
        {
          if ($m->Send($c))
          {
				    $gonderme = true;
					  return true;
				  }
				} 
		  } 
			if ($gonderme===false)
		  @mail(SITE_EPOSTA,'SMTP Bağlantı Hatası','SMTP Bağlantısı Başarısız Olduğu İçin Elektronik Postalar mail Fonksiyonuyla Gönderiliyor<br />SMTP Bilgilerinizi Kontrol Ediniz');
			$m->Disconnect();
		}
    
    if ($gonderme===false)
		{
		  $gonderildi = 0;
		  if ($eposta_sayi>0)
			{
			  foreach($eposta_dizi as $eposta_adres=>$eposta_kisi)
				{
				  $eposta_ustbilgi  = 'MIME-Version: 1.0' . "\r\n";
          if ($type=='html')
		      $eposta_ustbilgi .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		      else
		      $eposta_ustbilgi .= 'Content-type: text/plain; charset=utf-8' . "\r\n";
		
          $eposta_ustbilgi .= "To: ".$eposta_kisi."<".trim($eposta_adres).">\r\n";
          $eposta_ustbilgi .= 'From: '.SITE_EPOSTA . "\r\n";
		      if ($cc)
          $eposta_ustbilgi .= 'Cc: '.SITE_EPOSTA . "\r\n";
				 
					if (@ mail(trim($eposta), $konu, $eposta_mesaj, $eposta_ustbilgi))
          {
            $gonderildi++;
          } 
				}
			}
			if ($gonderildi>0)
			return true;
		}
		
  }
	
  //============================================================================
  //bb Kodlarini html Kodlarine Ceviren Fonksiyon
	public function bb_html ($metin,$mdir='editor')
	{
	  global $mimikler;
		global $dil;
		$metin = ' '.$metin;
		global $vt;
		$bb = array(
		'/\](\s.)\[/is',
		'(\[ol\](.*?)\[/ol\])is',
		'(\[ul\](.*?)\[/ul\])is',
		'(\[li\](.*?)\[/li\])is',
		'(\[kod\](.*?)\[/kod\])is',
		'(\[code\](.*?)\[/code\])is',
		'(\[caption\](.+?)\[/caption\])is',
    '(\[table(.*?)\](.+?)\[/table\])is',
		'(\[tbody\](.+?)\[/tbody\])is',
		'(\[th\](.+?)\[/th\])is',
		'(\[tr\](.+?)\[/tr\])is',
		'(\[td\](.*?)\[/td\])is',
		'(\[left\](.*?)\[/left\])is',
		'(\[center\](.*?)\[/center\])is',
		'(\[right\](.*?)\[/right\])is',
		'(\[renk=(.*?)\](.*?)\[/renk\])is',
		'(\[a(.+?)\](.+?)\[/a\])is',
		'(\[img(.+?)\])is',
		'(\[b\](.+?)\[/b\])is',
		'(\[strong\>(.+?)\[/strong\])is',
		'(\[i\](.+?)\[/i\])is',
		'(\[strike\](.+?)\[/strike\])is',
		'(\[u\](.+?)\[/u\])is',
		'(\[s\](.+?)\[/s\])is',
		'(\[p(.*?)\](.+?)\[/p\])is',
		'(\[sub\](.+?)\[/sub\])is',
		'(\[sup\](.+?)\[/sup\])is',
		'(\[em\](.+?)\[/em\])is',
		'(\[pre\](.+?)\[/pre\])is',
		'(\[hr(.*?)\])is',
		'(\[br\])is',
		'(\[br /\])is'
	  );
    $html = array(
		"][",
		"<ol>$1</ol>",
		"<ul>$1</ul>",
		"<li>$1</li>",
		'<div id="code">'.highlight_string(html_entity_decode(trim("$1")), true).'</div>',
		'<div id="code">'.highlight_string(html_entity_decode(trim("$1")), true).'</div>',
		"<caption>$1</caption>",
		'<table border="1" style="width:500px;max-width:500px">$2</table>',
		"<tbody>$1</tbody>",
		"<th>$1</th>",
		'<tr>$1</tr>',
		"<td style=\"max-width:500px\">$1</td>",
		"<p align=\"left\">$1</p>",
		"<p align=\"center\">$1</p>",
		"<p align=\"right\">$1</p>",
		"<font color=\"".htmlspecialchars(strip_tags('$1'))."\">$2</font>",
		"<a$1 target=\"_blank\">$2</a>",
		"<img$1 />",
		"<b>$1</b>",
		"<b>$1</b>",
		"<i>$1</i>",		
		"<i>$1</i>",
		"<u>$1</u>",
		"<s>$1</s>",
		"<p$1>$2</p>",
		"<sub>$1</sub>",
		"<sup>$1</sup>",
		"<em>$1</em>",
		"<pre>$1</pre>",
		"<hr$1 />",
		"<br />",
		"<br />"
	  );

    $metin = preg_replace($bb,$html,$metin);
	  $metin = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $metin);

    $metin = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $metin);

    $metin = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $metin);
		
		$mimiksayi = count($mimikler);
		$mimik1 = array();
		$mimik2 = array();
		for ($i=1; $i<=$mimiksayi; $i++)
    {
			$mimik1[] = $mimikler[$i][0];
			$mimik2[] = '<img src="'.$mdir.'/mimik/mimik'.$i.'.gif" alt="'.$mimikler[$i][1].'" title="'.$mimikler[$i][1].'" />';
    }
		
		$metin = str_replace($mimik1,$mimik2,$metin);

    return substr($metin, 1);
  }
	
	public function post_duzen($giris)
  {	
		$degistir = array('`'=>"'");
		$giris = strtr($giris,$degistir);
		$giris = trim(strip_tags(htmlspecialchars($giris)));
		if (!get_magic_quotes_gpc())
		$giris = addslashes($giris);
    return $giris;
  }
	public function yazdir_duzen($giris)
	{
	  if (!get_magic_quotes_gpc())
		$giris = stripslashes($giris);
		return stripslashes($giris);
	}
	
	function html_duzen($form,$alan,$genislik='400px',$butondir='editor',$yardim=true)
  {
	  global $dil;
		global $mimikler;
    $butonlar[] = array($dil['Kalin'],'bold.gif','bold_on.gif','[b]','[/b]');
    $butonlar[] = array($dil['Egik'],'italics.gif','italics_on.gif','[i]','[/i]');
    $butonlar[] = array($dil['AltiCizgili'],'underline.gif','underline_on.gif','[u]','[/u]');
    $butonlar[] = array($dil['UstuCizgili'],'strikethrough.gif','strikethrough_on.gif','[s]','[/s]');
		$butonlar[] = array('ayirici','seperator.gif','seperator.gif');
		$butonlar[] = array($dil['SolaYasla'],'justify_left.gif','justify_left_on.gif','[left]','[/left]');
		$butonlar[] = array($dil['Ortala'],'justify_center.gif','justify_center_on.gif','[center]','[/center]');
		$butonlar[] = array($dil['SagaYasla'],'justify_right.gif','justify_right_on.gif','[right]','[/right]');
		$butonlar[] = array('ayirici','seperator.gif','seperator.gif');
		$butonlar[] = array($dil['AdresEkle'],'insert_hyperlink.gif','insert_hyperlink_on.gif','[a href=]','[/a]');
		$butonlar[] = array('ayirici','seperator.gif','seperator.gif');
		$butonlar[] = array($dil['AltSimge'],'subscript.gif','subscript_on.gif','[sub]','[/sub]');
		$butonlar[] = array($dil['UstSimge'],'superscript.gif','superscript_on.gif','[sup]','[/sup]');
		$butonlar[] = array('ayirici','seperator.gif','seperator.gif');
		$butonlar[] = array($dil['TabloEkle'],'insert_table.gif','insert_table_on.gif','[table]\n[tr]\n[td]','[/td]\n[/tr]\n[/table]');
		$butonlar[] = array('ayirici','seperator.gif','seperator.gif');
		$butonlar[] = array($dil['SiraliListe'],'list_ordered.gif','list_ordered_on.gif','[ol]\n[li]', '[/li]\n[/ol]');
		$butonlar[] = array($dil['SirasizListe'],'list_unordered.gif','list_unordered_on.gif','[ul]\n[li]','[/li]\n[/ul]');
		$butonlar[] = array($dil['KodEkle'],'code.gif','code_on.gif','[kod]', '[/kod]');


    $html = '';
    $html .= '<table cellpadding="0" cellspacing="0" border="0" class="toolbar3" style="width:'.$genislik.';">
    <tr>
    <td style="width: 6px;"><img src="'.$butondir.'/seperator2.gif" alt="" hspace="3"></td>';
    
		$butonsayi = count($butonlar);
    for ($i=0; $i<$butonsayi; $i++)
    {
      $buton_aciklama       = $butonlar[$i][0];
      $buton_resim1         = $butondir.'/'.$butonlar[$i][1];
      $buton_resim2         = $butondir.'/'.$butonlar[$i][2];
      @$buton_ilk           = $butonlar[$i][3];
      @$buton_son           = $butonlar[$i][4];
	    
      if ($buton_aciklama == "ayirici") {
        $html .= '<td style="width: 12px;" align="center"><img src="'.$buton_resim1.'" border=0 unselectable="on" width="2" height="18" hspace="2" unselectable="on"></td>';
      } else {
        $html .= '<td style="width: 22px;"><img src="'.$buton_resim1.'" border=0 unselectable="off" title="'.$buton_aciklama.'" id="'.$i.'" alt="'.$buton_aciklama.'" class="button" onclick="surroundText(\''.$buton_ilk.'\', \''.$buton_son.'\', document.'.$form.'.'.$alan.'); return false;" onmouseover="if(className==\'button\'){className=\'buttonOver\'}; this.src=\''.$buton_resim2.'\';" onmouseout="if(className==\'buttonOver\'){className=\'button\'}; this.src=\''.$buton_resim1.'\';" unselectable="off" width="20" height="20"></td>';
      }
    }
    $html .= '<td>&nbsp;</td></tr></table>'; 

		$html .= '<table cellpadding="0" cellspacing="0" border="0" class="toolbar2" style="width:'.$genislik.';">
    <tr>
    <td style="width: 6px;" valign="middle"><img src="'.$butondir.'/seperator2.gif" alt="" hspace="3"></td>
		
		<td style="width:auto;" nowrap="nowrap" valign="middle">';
		
    $html .= '
		<select onchange="surroundText(\'[renk=\'+this.options[this.selectedIndex].value+\']\', \'[/renk]\', document.'.$form.'.'.$alan.'); this.selectedIndex = 0;" style="margin-bottom: 1ex;">
							<option value="" selected="selected">'.$dil['YaziRengi'].'</option>
							<option value="#000000">'.$dil['Siyah'].'</option>
							<option value="#ff0000">'.$dil['Kirmizi'].'</option>
							<option value="#ffff00">'.$dil['Sari'].'</option>
							<option value="#ff00ff">'.$dil['Pembe'].'</option>
							<option value="#008000">'.$dil['Yesil'].'</option>
							<option value="#ffa500">'.$dil['Turuncu'].'</option>
							<option value="#800080">'.$dil['Mor'].'</option>
							<option value="#0000ff">'.$dil['Mavi'].'</option>
							<option value="#f5f5dc">'.$dil['Bej'].'</option>
							<option value="#a52a2a">'.$dil['Kahverengi'].'</option>
							<option value="#008080">'.$dil['MatYesil'].'</option>
							<option value="#000080">'.$dil['DenizMavisi'].'</option>
							<option value="#800000">'.$dil['KestaneRengi'].'</option>
							<option value="#00ff00">'.$dil['AcikYesil'].'</option>
						</select>&nbsp;';
				$mimiksayi = count($mimikler);
		for ($i=1; $i<=$mimiksayi; $i++)
		{
		  $html .= '<a href="javascript:void(0);" onclick="replaceText(\''.$mimikler[$i][0].'\', document.'.$form.'.'.$alan.'); return false;"><img src="'.$butondir.'/mimik/mimik'.$i.'.gif" alt="'.$mimikler[$i][1].'" title="'.$mimikler[$i][1].'" style="align:bottom" border="0" /></a>';
		}

    if ($yardim)
		{
		$html .= '&nbsp;&nbsp;&nbsp;<img src="'.$butondir.'/help.gif" border=0 unselectable="off" title="'.$dil['Yardim'].'" id="yardim" alt="'.$dil['Yardim'].'" class="button" onclick="sayfaAc(\'editoryardim.php\',450,400,\'yes\'); return false;" onmouseover="if(className==\'button\'){className=\'buttonOver\'}; this.src=\''.$butondir.'/help_on.gif\';" onmouseout="if(className==\'buttonOver\'){className=\'button\'}; this.src=\''.$butondir.'/help.gif\';" unselectable="off" width="20" height="20">';
		}
		$html .= '</td><td>&nbsp;</td></tr></table>';
		unset($butonsayi,$mimiksayi,$mimikler,$butonlar,$buton_aciklama,$buton_resim1,$buton_resim2,$buton_ilk,$buton_son);
		return $html; 
  }
	
	//Veritabaninda Giris Deneme Sayisini Artiran Fonksiyon
  function girisDeneme()
  {
    global $vt;
    $vt->query("UPDATE ".TABLO_ONEKI."ipkontrol SET tarih=NOW(),denemesayi=denemesayi+1 WHERE ip='".UYE_IP."'");
  
	  //Kullanici Adi  veya Sifre Yanlissa Deneme Saysisi  Oturumlara Yukleniyor
    $giris_deneme_csayi = isset($_COOKIE['girisdenemesayisi'])? $_COOKIE['girisdenemesayisi']:'';
    $giris_deneme_ctoplam = $giris_deneme_csayi+1;
    setcookie("girisdenemesayisi", $giris_deneme_ctoplam, time()+(GIRIS_DENEME_SURESI*60));

    @ $giris_deneme_osayi = $_SESSION['giris_deneme']['sayi'];
    $giris_deneme_otoplam = $giris_deneme_osayi+1;
    $_SESSION['giris_deneme']['sayi'] = $giris_deneme_otoplam;
  
    if (empty($_SESSION['giris_deneme']['sure']))
    {
      $_SESSION['giris_deneme']['sure'] = time();
    }
  }
	
	function uye_adi($uyeno)
	{
		$vt2 = new Baglanti();
		global $dil;
		if ($uyeno)
		{
		  $vt2->query("SELECT uyeadi FROM ".TABLO_ONEKI."uyeler WHERE uyeno=$uyeno");
		  if ($vt2->numRows() > 0)
		  {
		    return $vt2->fetchObject()->uyeadi;
		  } else {
		    return $dil['SilinmisUye'];
		  }
	  } else {
		  return $dil['SilinmisUye'];
		}
	}
	//Kategoride Kac Yazi Oldugunu Verir
	function yazi_sayi($kategorino)
	{
	  $ys = new Baglanti();
    $sayi = $ys->kayitSay("SELECT COUNT(yazino) FROM ".TABLO_ONEKI."yazilar WHERE kategorino=$kategorino AND onay='E'");
		return $sayi;
  }
  
	function hata_mesaj($mesaj,$hata=false,$adres=false,$klasor='resim/')
	{
    $hatamesaj = '<div align="center" class="hata_mesaj"><table width="100%" align="center"><tr><td align="center">';
    if (!$hata)
    {
      $hatamesaj .= '<img src="'.$klasor.'hata2.gif" /><br /><font color="#ff0000">'.nl2br($mesaj).'</font>';
    } else {
      $hatamesaj .= '<img src="'.$klasor.'tamam.gif" /><br /><font color="#008000">'.nl2br($mesaj).'</font>';
    }
		if ($adres) 
    $hatamesaj .= '</td></tr><tr><td align="center">'.$adres;
    $hatamesaj .= '</td></tr></table></div>';
		return $hatamesaj;
	}
	
	function hatamesaj($mesaj,$odak=false,$hata=true,$klasor='resim',$genislik='auto')
	{
    if ($hata)
		{
		  $hatastil = 'hata';
			$hataresim = 'hata.gif';
		} else {
		  $hatastil = 'normalmesaj';
			$hataresim = 'onay.gif';
		}
		if (empty($genislik))
		$genislik = 'auto';
	  return  '<div class="'.$hatastil.'" align="center" onclick="fare(\''.$odak.'\');" style="width:'.$genislik.'"><img src="'.$klasor.'/'.$hataresim.'" align="center" hspace="10" />'.$mesaj.'</div>';
	}

  //==============================================================
	// KATEGORI FONKSIYONLARI
	//==============================================================
  //Kategori ID sine Gore Kategori Bilgilerini Verir
  function kategoriAdi($kategoriid)
  {
    $vt = new Baglanti();
    $vt->query("SELECT kategoriadi FROM ".TABLO_ONEKI."yazikategori WHERE kategorino=$kategoriid"); 
    if ($vt->numRows()>0) 
    {
      $kategori_veri = $vt->fetchArray();;
		  unset($vt);
      return $kategori_veri['kategoriadi'];
	  } else {
	    return false;
	  }
  }

  function kategoriListe($kategoriid,$level,$sira=1) //completely expand category tree
  {
	  if ($sira==1)
		$kosul = "ORDER BY kategoriadi ASC";
		elseif ($sira==2)
		$kosul = "ORDER BY kategorino ASC";
		elseif ($sira==3)
		$kosul = "ORDER BY kategorisira ASC";
		
    $vt = new Baglanti();
	  $vt->query("SELECT kategorino,kategoriadi FROM ".TABLO_ONEKI."yazikategori WHERE kategorino<>0 AND altkategorino=$kategoriid ".$kosul);
	  $a = array();
	  while ($row = $vt->fetchRow())
	  {
		  $row[2] = $level;
		  $a[] = $row; //Ana Kategori Dizisi
		  //Alt Kategoriler
		  $b = $this->kategoriListe($row[0],$level+1);
      //Alt Kategoriler Ana Kategori Dizisine Ekleniyor
		  for ($j=0; $j<count($b); $j++)
		  {
			  $a[] = $b[$j];
		  }
	  }
	  return $a;
  } 
 
  function kategoriIdListe($kategoriid)
  { 
    $vt = new Baglanti();
		$vt->query("SELECT kategorino, FIND_IN_SET(".$kategoriid.",altkategorino) AS seviye FROM ".TABLO_ONEKI."yazikategori HAVING seviye=1");    
    $kategoriid_dizi = array();

    while($sonuc = $vt->fetchArray())
    {    
      $kategori_id       = $sonuc["kategorino"]; 
      $kategoriid_dizi[] = $kategori_id;
      $b = $this->kategoriIdListe($kategori_id); 
	    for ($j=0; $j<count($b); $j++)
      {
        $kategoriid_dizi[] = $b[$j];
      }
    } 
    return $kategoriid_dizi; 
  } 
	
	function kategoriSecListe($kategoriid)
  { 
    $vt = new Baglanti();
		$vt->query("SELECT kategorino,kategoriadi,FIND_IN_SET(".$kategoriid.",altkategorino) AS seviye FROM ".TABLO_ONEKI."yazikategori HAVING seviye=1");    
    $kategoriid_dizi = array();
    while($sonuc = $vt->fetchArray())
    {    
      $kategori_id       = $sonuc["kategorino"]; 
			$kategori_adi      = $sonuc['kategoriadi'];
      $kategoriid_dizi[$kategori_id] = $kategori_adi;
      $b = $this->kategoriSecListe($kategori_id); 
	    for ($j=0; $j<count($b); $j++)
      {
        $kategoriid_dizi[] = $b[$j];
      }
    } 
    return $kategoriid_dizi; 
  } 
  //===============================================================
  // KATEGORI FONKSIYONLARI SONU
  //===============================================================
  // SAYFA BILGI FONKSIYONU BASLANGICI
	//===============================================================
  function sayfa_bilgi($sayfaanahtar='',$sayfa,$yazino,$kategorino,$albumno,$resimno)
	{
	  global $dil;
		
		$sayfa_baslik      = SITE_ADI;
    if ($sayfa)
		$sayfa_baslik .= ' : '.$sayfa;
		else
		$sayfa_baslik .= '';
		
    if ($kategoriadi = $this->kategoriAdi($kategorino))
		$sayfa_baslik .= ' - '.$kategoriadi;
		
    if ($yazino>0)
    {
      $bvt = new Baglanti();
      $bvt->query("SELECT baslik FROM ".TABLO_ONEKI."yazilar WHERE yazino=".$yazino."");
      if ($bvt->numRows()>0) 
			$sayfa_baslik .= ' - '.$bvt->fetchObject()->baslik;
			unset($bvt);
    } 
		
		if ($albumno>0)
		{
		  $avt = new Baglanti();
			$avt->query("SELECT albumadi FROM ".TABLO_ONEKI."album WHERE albumno=$albumno");
			if ($avt->numRows()>0) 
			$sayfa_baslik .= ' : '.$avt->fetchObject()->albumadi;
			unset($avt);
		}
		
		if ($resimno>0)
		{
		  $rvt = new Baglanti();
			$rvt->query("SELECT resimadi FROM ".TABLO_ONEKI."resim WHERE resimno=$resimno");
			if ($rvt->numRows()>0) 
			$sayfa_baslik .= ' - '.$rvt->fetchObject()->resimadi;
			unset($rvt);
		}
		$sayfa_baslik = substr($sayfa_baslik,0,80);
		//Sayfa Baslik Sonu
		//Sayfa Description-Keywords Baslangici
		$menu_description='';
		$menu_keywords   = '';
		$menu_vt = new Baglanti();
		$menu_vt->query("SELECT menudescription,menukeywords FROM ".TABLO_ONEKI."menuler WHERE menuanahtar='$sayfaanahtar'");
    if($menu_vt->numRows()>0)
    {
		  $menu_veri = $menu_vt->fetchObject();
			$menu_description = $menu_veri->menudescription;
			$menu_keywords    = $menu_veri->menukeywords;
		}
    $menu_vt->freeResult();
		unset($menu_vt);
		if (!$menu_description)
		$menu_description = $dil['description'];
		if (!$menu_keywords)
		$menu_keywords    = $dil['keywords'];
		return array('baslik'=>$sayfa_baslik,'keywords'=>$menu_keywords,'description'=>$menu_description);
	}
  //===============================================================
  // SAYFA BILGI FONKSIYONU SONU
	//===============================================================
	function textWrap($text,$size=30)
      {
        $new_text = '';
        $text_1   = explode(']',$text);
        $sizeof = sizeof($text_1);
        for ($i=0; $i<$sizeof;++$i)
        {
          $text_2 = explode('[',$text_1[$i]);
          if (!empty($text_2[0])) 
          {
            $new_text .= preg_replace('#([^\n\r .]{'.$size.'})#i','\\1 ',$text_2[0]);
          }
          if (!empty($text_2[1]))
          {
            $new_text .= '[' . $text_2[1] .']';
          }
        }
        return $new_text;
      }
  function uye_resim($uyeno)
	{
	  $uye_vt = new Baglanti();
		$uye_vt->query("SELECT resim FROM ".TABLO_ONEKI."uyeler WHERE uyeno=$uyeno");
		if ($uye_vt->numRows()>0)
		{
		  $uye_resim_veri = $uye_vt->fetchObject();
      $resim = $uye_resim_veri->resim;
			if (!file_exists($resim) || empty($yazi_resim))
      $resim = 'bos.gif';
		} else {
		  $resim = 'bos.gif';
		}
		return $resim;
	}
	//Klasor Icin chmod Ayarlarini FTP ile Degistirir
function ftpChmod($chmod=0755,$yol='')
{
  $return = false;
	if ($yol)
	{
	if (function_exists('ftp_connect'))
  {
    $ftpconn_id = ftp_connect(FTP_SERVER);
    if ($ftpconn_id)
		{
      if (ftp_login($ftpconn_id, FTP_KULLANICI_ADI, FTP_KULLANICI_SIFRE))
      {
			  if (ftp_chdir($ftpconn_id,FTP_YOL))
				{
          if (@ftp_chmod($ftpconn_id, eval("return({$chmod});"), $yol))
					{
						$return = true;
					} else {
						$return = false;
					}
				} else {
					$return = false;
				}
			} else {
			  $return = false;
			}
		} else {
		  $return = false;
		}
		ftp_close($ftpconn_id);
	} else {
	  $return = false;
	}
	}
	return $return;
}
//============================
} // Sinif Sonu
//=============================
//KATEGORI SAYISI ALINIYOR
$kvt = new Baglanti();
define("KATEGORI_SAYI",intval($kvt->kayitSay("SELECT COUNT(kategorino) FROM ".TABLO_ONEKI."yazikategori")));//Yazi Kategori Sayisi : Eger Kategori Yoksa Son Yazilar Gosterilir
unset($kvt);
//KATEGORI SAYISI ALINDI
//=============================
?>