<?php 
//Resmi Yeniden Boyutlandiran Fonksiyonumuz 
function boyutlandir($resim,$max_en,$max_boy)  
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
    $eski = imagecreatefromgif($resim); 
  } elseif ($uzanti == 'png') { 
    $eski = imagecreatefrompng($resim); 
  } else { 
    $eski = imagecreatefromjpeg($resim); 
  }  
  $yeni = imagecreatetruecolor($son_en,$son_boy);  

  // Eski Resmi Yeniden Renklendiriyoruz  
  $renk = imagecolorallocate($yeni,255,255,255); 
  imagefill($yeni,0,0,$renk); 
  imagecopyresampled($yeni,$eski,0,0,0,0,$son_en,$son_boy,$en,$boy);  
   
  // Yeni Resmi Tarayiciya Yansitiyoruz  
  if ($uzanti == 'gif') 
  { 
    header("Content-type: image/gif"); 
    imagegif($yeni,null,100); 
  } elseif ($uzanti == 'png') { 
    header("Content-type: image/png"); 
    imagepng($yeni,null,100); 
  } else { 
    header("Content-type: image/jpeg"); 
    imagejpeg($yeni,null,100);  
  }  
  $icerik = ob_get_contents();  

  // Temizlik 
  ob_end_clean();  
  imagedestroy($eski);  
  imagedestroy($yeni);  
  return $icerik;  
} 

// resim_goster.html den Gelen Resim Degerlerini Aliyoruz 
$resim     = trim(strip_tags(htmlspecialchars($_GET['resim']))); //Resim Yolu ve Adin Aliyoruz     
$max_en    = intval($_GET['en']); // Resim Geniligi 
$max_boy   = intval($_GET['boy']); // Resim Yuksekligi 

if (empty($max_en))  $max_en = 130; 
if (empty($max_boy)) $max_boy = 80; 
//Resmin Olup Olmadigini  Kontrol Ediyoruz 
if ($resim && file_exists($resim))  
{ 
  //Fonksiyonu Cagiriyoruz 
  echo boyutlandir($resim,$max_en,$max_boy); 
} 
?> 
