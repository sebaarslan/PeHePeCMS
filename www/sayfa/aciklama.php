<?php
if (!defined("UYE_SEVIYE"))
exit;
?>
<table width="100%">
<tr>
    <td align="right" valign="middle"><select name="tema" onchange="location=this.options[this.selectedIndex].value">
		<option value="?sayfa=<?php echo $sayfa; ?>&tema=tema1"<?php if ($site_tema=='tema1') echo ' selected="selected"'; ?>>Tema 1 - Sebahattin Arslan</option>
		<option value="?sayfa=<?php echo $sayfa; ?>&tema=tema2"<?php if ($site_tema=='tema2') echo ' selected="selected"'; ?>>Tema 2 - Sebahattin Arslan</option>
		<option value="?sayfa=<?php echo $sayfa; ?>&tema=tema3"<?php if ($site_tema=='tema3') echo ' selected="selected"'; ?>>Tema 3 - Soner Algan</option>
	  </select>
    <?php
		//Dil Değiştirme Resimleri
    foreach($dil_ayar AS $dilanahtar=>$dildeger)
    {
		  if ($dilanahtar != $site_dil)
			{
        echo ' <a href="?dil='.$dilanahtar.'"><img width="40" height="25" src="resim/'.$dildeger[2].'" id="'.$dilanahtar.'" alt="'.$dildeger[1].'" title="'.$dildeger[1].'" border="0" align="absmiddle" /></a>';
      }
			unset($dilanahtar,$dildeger);
    }
    ?>  
    </td>
  </tr>
<tr>
  <td width="100%" align="center"><h1>F - KLAVYE</h1><a href="http://tr.wikipedia.org/wiki/F_klavye" target="_blank"><img src="resim/f_klavye.gif" border="0"/></a><br />Tüm Programlamaları F-Klavye İle Yapıyorum. (Bu Sayede Bir Çok Projeyi Kısa Sürede Bitiriyorum)<br />Türkçe Konuşuyor ve Yazıyorsan F-Klavyene Sahip Çık<br /> </td>
</tr>
<tr>
  <td width="100%" align="center"><h1>PeHePe Üyelik Sistemi v.5.2</h1></td>
</tr>
<tr>
  <td align="center"><br /><a href="?sayfa=kayit"><b>Kayıt</b></a> Olup Üyelik Sistemi İle İlgili Eksiklikleri, İsteklerinizi veya Önerilerinizi Yazabilirsiniz</td>
</tr>

<tr>
  <td align="center" width="100%">
    <b>NOT :</b><br /><font color="#ff0000">PeHePe Üyelik Sisteminin 5. Versiyonunu Kullanabilmeniz İçin, Sisteminiz <b>PHP 5</b> ve Üstünü Desteklemeledir. <b>MySQL 5.0</b> ve Üstü Tavsiye Edilir...</font></td>
</tr>
<tr>
  <td width="100%" align="center"><h1>GENEL BİLGİLER</h1></td>
</tr>
<tr>
  <td align="center">
	  <table width="100%">
		  <tr>
			  <td align="left" width="30%"><b>Sürüm</b></td><td align="left" width="70%"><b>:</b> 5.4 (5. Versiyonda 4. Düzenleme)</td>
			</tr>
			<tr>
				<td align="left" width="30%"><b>Boyut</b></td><td align="left" width="70%"><b>:</b> 531 Kb</td>
			</tr>
			<tr>
				<td align="left" width="30%"><b>Kullanım İzni</b></td><td align="left" width="70%"><b>:</b> Ücretsiz (Freeware)</td>
			</tr>
			<tr>
				<td align="left" width="30%"><b>Dil</b></td><td align="left" width="70%"><b>:</b> Türkçe</td>
			</tr>
			<tr>
				<td align="left" width="30%"><b>Üretici Firma</b></td><td align="left" width="70%"><b>:</b> <a href="http://www.arslandizayn.com" target="_blank">Arslan Tasarım-Programlama / Sebahattin Arslan</a></td>
			</tr>
			<tr>
				<td align="left" width="30%"><b>Programlama Dili</b></td><td align="left" width="70%"><b>:</b> PHP</td>
			</tr>
			<tr>
				<td align="left" width="30%"><b>Veritabanı</b></td><td align="left" width="70%"><b>:</b> MySQL</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
  <td width="100%" align="center"><h1>TANITIM</h1></td>
</tr>
<tr>
  <td width="100%" align="left">PeHePe Üyelik Sistemi; PHP kodlama dili ile yazılmış, çalışma düzeni belli ama kurulacağı ortama göre özelleştirilebilen, bir anlamda site yönetimi işine de yarayan bir Üyelik Betiğidir.<br /><br />
<b>PeHePe Üyelik Sisteminin 5.4 Versiyonu İle Gelen Yenilikler :</b><br />
<ul>
<li><font color="#ff0000">Yeni</font> -Sitedeki Menüleri Yönetim Panelinden Yönetebileceksiniz. İstediğiniz Kadar Yeni Menü ve Sayfayı Yönetim Panelinden Ekleyebileceksiniz</li>
<li><font color="#ff0000">Yeni</font> -Sitenize Bağlantılar Ekleyebileceksiniz</li>
<li><font color="#ff0000">Yeni</font> -Sözlük Eklendi (Sözlüğün Tüm Hakları Türk Dil Kurumumuza (http://www.tdk.org.tr) Aittir.</li>
</ul>
<b>PeHePe Üyelik Sisteminin 5.3 ve 5.4 Versiyonunun Özellikleri :</b><br />
<ul>
<li><b>Bu Sistem PHP 5 den Daha Alt Versiyonlarda Çalışmaz</b></li>
<li>Sistemin Kurulumu Çok Basittir. kurulum_install.php yi Çalıştırıp Adımları Takip Ediniz</li>
<li>Yazılarda RSS Özelliği</li>
<li>Çok İstek Üzerine Sınırsız Alt Kategori Oluşturma Özelliği Eklendi.</li>
<li>Tema (Template) Olayı Eklendi. tema/ klasörüne kendi temanızı ekleyip kullanabilirsiniz</li>
<li>Bu Sistemde ajax (sajax 0.12) Kütüphanesi Kullanılmıştır</li>
<li>Oturumla ve Çerezle Sayfa Denetimi..</li>
<li>Çerezle Üyenin Tekrar Gelişinde Tanınması</li>
<li>Üyelik Kaydı</li>
<li>5 Seviyede Üye Kaydı</li> 
<li>Bazı Bölümler İstenilen Üye Seviyesine Gösterilebilir</li> 
<li>Üye Profiline Resim Ekleme</li>
<li>Şifre Unutmada Yeni Şifrenin E-Posta Hesabına Gönderilmesi</li>
<li>Yönetim Panelinden Tüm Site Ayarlarının Yapılması</li>
<li>Resim Galerisi Eklendi (Yeni)</li>
<li>Resimlere Oy Verme ve Yorum Yapma Eklendi (Yeni)</li>
<li>Kişisel veya Genel Resim Albümü Oluşturma Eklendi (Yeni)</li>
<li>Toplu E-Posta ve Özel Mesaj Gönderme Eklendi (Yeni)</li>
<li>Site İçi Özel Mesajlaşma</li>
<li>Özel Mesaj Göndermede Üye Arayabilme</li>
<li>Gelen ve Giden Mesajları Sıralayabilme</li>
<li>Günlük ve Toplam Tekil Sayaç</li>				
<li>Online Kişi Sayısı (Dakikada Güncellenir. Gerçekçi Olarak Çevrimiçi (Online) Kişileri Gösterir)</li>
<li>Online Üyeler (Dakikada Güncellenir. Gerçekçi Olarak Çevrimiçi (Online) Üyeleri Gösterir)</li>
<li>Yazı Ekleme Bölümü</li>
<li>Yazılara HTML Kodu Ekleyebilme</li>
<li>Yazılara Yorum Ekleyebilme</li>
<li>Yazılara Kategori Ekleyebilme</li>
<li>Yazılara Oy Verme</li>
<li>Hızlı Mesajlaşma Sistemi</li>
<li>İstediğiniz Seçeneğe İzin Verebileceğiniz Anket</li>
<li>Doğum Günü Olan Üyeleri Gösterme</li>
<li>Dil Dosyası Diğer Dillere Çevrilebilir</li>
</ul>
Üyelik Sisteminden Birkaç Güzel Özellik; Oluşturduğunuz Anketleri kötü niyetli kullanıp, sitenizin kaynaklarını kemirmeye çalışanlar için, üye girişi yapmadan sadece anketi görme, giriş yaparak oy kullanma gibi hoş bir özellik mevcut.<br />
Aynı Kullanıcı Adıyla Aynı Zamanda Sadece Tek Giriş Yapılabilir... Siz Girdiğiniz Zaman Başka Bilgisayardan Aynı Kullanıcı Adıyla Giriş Yapılamaz.
</td>
</tr>
<tr>
  <td width="100%" align="center"><h1>KURULUM</h1></td>
</tr>
<tr>
  <td width="100%" align="left">
  <ul>
  <li>Öncelikle MySQL'de bir veritabanı oluşturunuz.</li>
  <li>kurulum_install.php dosyasını çalıştırınız. Kurulum_install.php deki adımları takip edip kurulum işlemini tamamlayınız.<br />
  Kurulum tamamlanınca index.php yi çalıştırınız.</li>
	<li>kurulum_install.php dosyası ve sql klasörü otomatik olarak silinecektir</li>
  <li>Yeni dillere çeviri yapacaksanız dil klasöründeki tr.php dosyasını çoğaltınız (kopyalayarak) ve icerik klasöründeki dil.inc.php de bu yeni dosyayı tanımlayınız.</li>
  <li>uyeresim, yaziresim ve album klasörlerinin yazılabilir (CHMOD 777) olduğundan emin olunuz.</li>
	<li>Eğer kurulum_install.php İle kurulumu başaramadıysanız, sql klasöründeki pehepe_uyelik_v5.2.sql dosyası ile phpmyadminden tabloları oluşturabilirsiniz</li>
  </td>
</tr>
<tr>
  <td width="100%" align="center"><h1>ÜYELİK SEVİYELERİ</h1></td>
</tr>
<tr>
  <td width="100%" align="left">
  İlk kurulumda oluşturacağınız Üye Bilgileri, Site Sahibine Ait Olacaktır.<br />
  Site Sahibi Yöneticilerin üstünde bir hakka sahiptir.<br />
  Üye Seviye Ayarlarını ve İsimlerini yönetim panelinden değiştirebilirsiniz..<br />
  Sistemde 5 Çeşit Üye Seviyesi Vardır :<br />
  1- Normal Üye<br />
  2- Normal Üyenin Üstü<br />
  3- Üst Seviye<br />
  4- Daha Üst Seviye<br />
  5- Yönetici<br />
  6- Site Sahibi<br /><br />
  </td>
</tr>

<tr><td align="left"><b>İleride Eklenebilecek Önerileriniz</b><br />
1- Okunan Özel Mesajların Karşı Tarafca Silinememesi<br /><br />
Önerilerinizi Bana Yazarsanız Vakit Buldukça Eklemeye Çalışacağım
</td>
</tr>
<tr>
  <td width="100%" align="center"><h1>UYARILAR VE NOTLAR</h1></td>
</tr>
<tr>
  <td width="100%" align="left">
	<ul>
  <li>PeHePe Üyelik Sisteminin 5.4 Versiyonunu Kullanabilmeniz İçin, Sisteminiz PHP 5 ve Üstünü Desteklemelidir. (MySQL 5.0 ve Üstü Önerilir)</li>
  <li>Bu üyelik <u>sistemi PHP 5 ve üzeri</u> versiyonlarda çalışır.</li>
  <li>MySQL 5 ve üzerini kullanmanız tavsiye edilir.</li>
  <li>PHP Üyelik Sistemi v.5.4 de ajax (sajax 0.12 kütüphanesi) kullanılmıştır.</li>
  <li>Sayfalarda ve Veritabanında utf-8 unicode Karakter Seti Kullanılmıştır. index.php yi Düzenlemek İsterseniz Editörünüzün Karakter ve Fontunu utf-8 Olarak Ayarlamalısınız</li>
  <li>PeHePe Üyelik Sistemini kullanmaya başlamanızdan itibaren bu sistemin üreticileri (arslandizayn.com) hiçbir sorumluluk kabul etmez. Üyelik Sisteminin kullanımından doğacak maddi ve manevi hiçbir sorumluluğu üstlenmez. Sorumluluk son kullanıcıya aittir.</li>
  <li>Her sistemde, programda, yazılımda olabileceği gibi bu sistemde de gözden kaçan, tahmin edilemeyen hatalar ve/veya eksiklikler olabilir. Böyle bir hata veya eksiklik bulmanız durumunda <a mailto="bilgi@arslandizayn.com">bilgi@arslandizayn.com</a> adresine E-Posta göndererek bildirebilirsiniz.</li>
	</ul>
  <b>Güle Güle Kullanın</b>
  <br /> 
</td>
</tr>
<tr>
  <td height="40"> </td>
</tr>
<tr>
  <td align="center"><a href="http://www.pardus.org.tr" target="_blank"><img border="0" alt="Pardus... Özgürlük İçin..." title="Pardus... Özgürlük İçin..." src="http://www.pardus.org.tr/banner/btm01.png"></a></td>
</tr>
</table>