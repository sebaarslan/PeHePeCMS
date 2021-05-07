## PeHePeCMS ##
PeHePe Üyelik ve İçerik Yönetimi

PHP kodlama dili ile yazılmış, çalışma düzeni belli ama kurulacağı ortama göre özelleştirilebilen, bir anlamda site yönetimi işine de yarayan bir Üyelik Betiğidir. 

Sistemin Kurulumu Çok Basittir. kurulum_install.php yi Çalıştırıp Adımları Takip Ediniz 

------------------------------------------
PeHePe Uyelik Sistemi v5.4
5 inci Versiyonda 4 üncü Düzenleme
------------------------------------------
## UYARILAR ##
------------------------------------------

1- Bu Üyelik Sistemi PHP 5 ve Üzeri Versiyonlarda Çalışır...

2- MySQL 5 ve Üzerini Kullanmanız Tavsiye Edilir

3- PHP Üyelik Sisteminde ajax (sajax sınıfı) Kullanılmıştır...

4- PeHePe Üyelik Sistemini Kullanmaya Başlamanızdan İtibaren Bu Sistemin Üreticileri Hiçbir Sorumluluk Kabul Etmez..
   Üyelik Sisteminin Kullanımından Doğacak Maddi ve Manevi Hiçbir Sorumluluğu Üstlenmez...

5- Sistemin Hataları veya Eksiklikleri Olabilir...
   Böyle Bir Hata Bulmanız Durumunda Buradan yazabilirsiniz...

6- En Doğrusu Kendi Sisteminizi Kullanmaktır (Bu Sistemi Yol Göstermek Amacıyla veya Fikir Edinmeniz Amacıyla Kullanabilirsiniz)....


---------------------
## KURULUM - SETUP##
---------------------

1- Öncelikle MySQL de Bir Veritabanı Oluşturunuz

2- icerik Klasörünün İznini (chmod) 777 Yapınız. Kurulum Bittikten Sonra Eski Haline Getirmeyi Unutmayınız...

3- kurulum_install.php Dosyasını çalıştırınız....kurulum_install.php deki Adımları Takip Edip Kurulum İşlemini Tamamlayınız...

4- Kurulum Tamamlanınca index.php yi Çalıştırınız...

5- Yeni Dillere Çeviri Yapacaksanız dil Klasöründeki tr.php Dosyasını Çoğaltınız ve icerik Klasöründeki dil.inc.php de Bu Yeni Dosyayı Tanımlayınız

6- uyeresim,yaziresim ve album Klasörlerinin Yazılabilir Olduğundan Emin Olunuz...

7- Eğer kurulum_install.php ile kurulumu başaramadysanz sql klasörünün içindeki PeHePe_Uyelik_v5.4.sql dosyasyla phpmyadminden kurulum yapabilirsiniz.

8- Eğer Resim Yüklemede FTP Bağlantısını Kullanmak İsterseniz icerik Klasöründeki ayar.inc.php de FTP Bilgilerini Doldurunuz.

9- Eğer SMTP Mail Kullanmak İsterseniz, icerik Klasöründeki ayar.inc.php de E-Posta Bilgilerinizi Doludurunuz...

------------------------
## ÜYELİK SEVİYELERİ ##
------------------------

İlk Kurulumda Oluşturacağınız Üye Bilgileri Site Sahibine Ait Olacaktır...

Site Sahibi Yöneticilerin Üstünde Bir Hakka Sahiptir

Üye Seviye Ayarlarını ve İsimlerini Yönetim Panelinden Değitirebilirsiniz..

Sistemde 5 Çeit Üye Seviyesi Vardır

1- Normal Üye

2- Normal Üyenin Üstü

3- Üst Seviye

4- Daha Üst Seviye

5- Yönetici

6- Site Sahibi

------------------------
## PeHePe Üyelik Sisteminin 5.4 Versiyonunun Özellikleri ##
------------------------
(Yeni) Yönetim Panelinden İstediğiniz Kadar Menü ve Sayfa Ekleme Özelliği 

(Yeni) Bağlantılar Ekleme Özelliği 

(Yeni) Türk Dil Kurumu Bilgisayar Terimler Karşılıklar Sözlüğü 

Çok İstek Üzerine Sınırsız Alt Kategori Oluşturma Özelliği Eklendi. 

Tema (Template) Olayı Eklendi. tema/ klasörüne kendi temanızı ekleyip kullanabilirsiniz 

Bu Sistemde ajax (sajax 0.12) Kütüphanesi Kullanılmıştır 

Oturumla ve Çerezle Sayfa Denetimi..

Çerezle Üyenin Tekrar Gelişinde Tanınması Üyelik Kaydı 5 Seviyede Üye Kaydı Bazı Bölümler İstenilen Üye Seviyesine Gösterilebilir 

Üye Profiline Resim Ekleme 

Şifre Unutmada Yeni Şifrenin E-Posta Hesabına Gönderilmesi 

Yönetim Panelinden Tüm Site Ayarlarının Yapılması 

Resim Galerisi Eklendi Resimlere Oy Verme ve Yorum Yapma Eklendi 

Kişisel veya Genel Resim Albümü Oluşturma Eklendi 

Toplu E-Posta ve Özel Mesaj Gönderme Eklendi 

Site İçi Özel Mesajlaşma Özel Mesaj Göndermede Üye Arayabilme Gelen ve Giden Mesajları Sıralayabilme 

Günlük ve Toplam Tekil Sayaç 

Online Kişi Sayısı (Dakikada Güncellenir. Gerçekçi Olarak Çevrimiçi (Online) Kişileri Gösterir) Online Üyeler (Dakikada Güncellenir. Gerçekçi Olarak Çevrimiçi (Online) Üyeleri Gösterir) 

Yazı Ekleme Bölümü 

Yazılara HTML Kodu Ekleyebilme 

Yazılara Yorum Ekleyebilme 

Yazılara Kategori Ekleyebilme 

Yazılara Oy Verme 

Hızlı Mesajlaşma Sistemi 

Doğum Günü Olan Üyeleri Gösterme 

Bugün Kayıt Olan Üyeleri Gösterme 

Dil Dosyası Diğer Dillere Çevrilebilir 

Üyelik Sisteminden Birkaç Güzel Özellik; 

İstediğiniz Seçeneğe İzin Verebileceğiniz Anket 
Oluşturduğunuz Anketleri kötü niyetli kullanıp, sitenizin kaynaklarını kemirmeye çalışanlar için, üye girişi yapmadan sadece anketi görme, giriş yaparak oy kullanma gibi hoş bir özellik mevcut. 

Aynı Kullanıcı Adıyla Aynı Zamanda Sadece Tek Giriş Yapılabilir... 

Siz Girdiğiniz Zaman Başka Bilgisayardan Aynı Kullanıcı Adıyla Giriş Yapılamaz. 

Ve daha birçok özellik Güle Güle Kullanın 
