<?php
//Bu Örnek Sayfayı Kullanarak Siz de Kendi Sayfalarınızı Oluşturabilirsiniz
if (!defined("UYE_SEVIYE") || !defined("UYE_NO"))
exit;

//===============================================
try { // try Başlangıcı
//===============================================
//KODLARINIZ BURADA BAŞLIYOR
//===============================================
?>
<p>&nbsp;</p>
Bu Örnek Sayfayı Kullanarak Siz de Kendi Sayfalarınızı Oluşturabilirsiniz<br />
Bir Hata Mesajı Yazmak İçin Şu Kodu Kullanmalısınız<br />

throw new Exception('Hata');<br />

Bu Satıra Gelince Fırlatma Yapılır ve En Sondaki catch Bölümü Çalıştırılır<br />
catch Bölümünde throw new Exceptionun sonuna eklediğiniz koda göre gideceği adresleri belirleyebilirsiniz<br />
Örnek : <br />
throw new Exception('Hata Var',2);<br />
Gördüğünüz gibi burada Kod 2 olarak verilmiş.<br />
Aşağıda catch bölümünde kod 2 ise Tamam butonuna basılınca 
yine örnek sayfaya gidiyor, 2 de mesaj yeşil olarak gösteriliyor. Yani 2 de onay mesaj gösteriliyor.<br />
Onay Mesajı için; $hata = true; olarak belirtilmiş.
Hata Mesajı için; $hata = false; olarak belirtilmiş.

<br />
<?php
//Bütün Üyeler Görebilir
echo '<br /><b>Bu Bölümü Bütün Üyeler Görebilir</b><br />';
//---------------------------------------------------------
//Giriş Yapmış Üyeler Görmesini İstiyorsanız
if (UYE_SEVIYE == 0)
{
  throw new Exception($dil['IslemIcinGirisGerekli']);
	exit;
} else {
  echo '<br /><b>Bu Bölümü Giriş Yapmış Tüm Üyeler Görebilir</b><br />';
}
//----------------------------------------------------------
if (UYE_SEVIYE>2)
{
  echo '<br /><b>Bu Bölümü 3. Seviye Üyeler ve Üstündeki Üyeler Görebilir</b><br />';
}
//Üst Seviye Değilse Hata Mesajı Şu Şekilde Olmalıdır
//throw new Exception($fonk->yerine_koy($dil['UyeSeviyeYetersiz'],3));
//Buradaki 3, 3. seviye ve üzeri üyeleri gösterir
 

//===============================================
//KODLARINIZ BURADA SONA ERİYOR
//===============================================
} // try Sonu
//===============================================
catch (Exception $e)
{
  $hatakod = $e->getCode();
	//Hatalarda Verdiğiniz Kodlara Göre Bu Bölümü Kendinize Göre Değiştirebilirsiniz
  if ($hatakod == 1)
  {
    $adres = '<a href="index.php?sayfa=ornek">'.$dil['Tamam'].'</a>';
    $hata  = false;
  } elseif ($hatakod == 2) {
    $adres = '<a href="index.php?sayfa=ornek">'.$dil['Tamam'].'</a>';
    $hata = true;
  } else {
    $adres = '<a href="index.php">'.$dil['Tamam'].'</a>';
    $hata = false;
  }
  ?>
  <table align="center" cellpadding="0" cellspacing="0" width="85%">
    <tr>
      <td align="center">
        <?php echo $fonk->hata_mesaj($e->getMessage(),$hata,$adres); ?>
      </td>
    </tr>
  </table>
<?php
}
?>
