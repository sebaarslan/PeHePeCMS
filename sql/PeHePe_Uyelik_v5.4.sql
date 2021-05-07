-- 
-- Tablo yapısı: `u_album`
-- 

CREATE TABLE `u_album` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tablo döküm verisi `u_album`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_anketcevap`
-- 

CREATE TABLE `u_anketcevap` (
  `cevapno` int(10) NOT NULL auto_increment,
  `anketno` int(10) NOT NULL default '0',
  `uyeno` int(10) NOT NULL default '0',
  `anketcevap` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`cevapno`),
  KEY `anketno` (`anketno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tablo döküm verisi `u_anketcevap`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_anketsecenek`
-- 

CREATE TABLE `u_anketsecenek` (
  `secenekno` int(10) NOT NULL auto_increment,
  `anketno` int(10) NOT NULL default '0',
  `secenek` char(250) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`secenekno`),
  KEY `anketno` (`anketno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tablo döküm verisi `u_anketsecenek`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_anketsoru`
-- 

CREATE TABLE `u_anketsoru` (
  `anketno` int(10) NOT NULL auto_increment,
  `anketsoru` char(250) collate utf8_unicode_ci NOT NULL,
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `goster` enum('E','H') collate utf8_unicode_ci NOT NULL default 'H',
  `acik` enum('E','H') collate utf8_unicode_ci NOT NULL default 'H',
  `secenekizin` mediumint(2) NOT NULL default '1',
  `bitistarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`anketno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tablo döküm verisi `u_anketsoru`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_baglantilar`
-- 

CREATE TABLE `u_baglantilar` (
  `baglantino` tinyint(3) NOT NULL auto_increment,
  `baglantiadres` text NOT NULL,
  `baglantiadi` text character set utf8 collate utf8_unicode_ci,
  `baglantihedef` varchar(6) NOT NULL default '_self',
  `baglantionay` enum('E','H') NOT NULL default 'E',
  PRIMARY KEY  (`baglantino`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Tablo döküm verisi `u_baglantilar`
-- 

INSERT INTO `u_baglantilar` (`baglantiadres`, `baglantiadi`, `baglantihedef`, `baglantionay`) VALUES 
('http://www.arslandesign.com', 'Arslan Web Tasarım', '_blank', 'E'),
('http://www.turk-php.com','Türk PHP','_blank','E'),
('http://www.ceviz.net','Ceviz.Net','_blank','E'),
('http://www.php.net','PHP Resmi Web Sitesi','_blank','E'),
('http://www.mysql.com','MySQL Resmi Web Sitesi','_blank','E');

-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_dosyalar`
-- 

CREATE TABLE `u_dosyalar` (
  `dosyano` int(5) NOT NULL auto_increment,
  `dosyaadi` varchar(100) collate utf8_unicode_ci NOT NULL,
  `dosyayolu` varchar(100) collate utf8_unicode_ci NOT NULL,
  `dosyadeneme` varchar(100) collate utf8_unicode_ci NOT NULL,
  `dosyaaciklama` text collate utf8_unicode_ci NOT NULL,
  `dosyakayittarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `dosyaduzentarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `dosyaindirsayi` int(10) NOT NULL default '0',
  `dosyaonay` enum('E','H') collate utf8_unicode_ci NOT NULL default 'E',
  PRIMARY KEY  (`dosyano`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- 
-- Tablo döküm verisi `u_dosyalar`
-- 

INSERT INTO `u_dosyalar` (`dosyano`, `dosyaadi`, `dosyayolu`, `dosyadeneme`, `dosyaaciklama`, `dosyakayittarih`, `dosyaduzentarih`, `dosyaindirsayi`, `dosyaonay`) VALUES 
(1, 'PeHePe Üyelik Sistemi v5.4 (Son Sürüm)', 'indir/pehepe_uyelik_v5.4.zip', 'http://www.arslandesign.com/projeler/uyelik5', '--------------------------------------------------------------\r\nSistemin Kurulumu Çok Basittir. kurulum_install.php yi Çalıştırıp Adımları Takip Ediniz \r\nBu Sistemde ajax (sajax 0.12) Sistemi Kullanılmıştır \r\nBu Sistem PHP 5 den Daha Alt Versiyonlarda Çalışmaz \r\n-------------------------------------------------------------\r\nPeHePe Üyelik Sistemi Diğer Versiyonlara Ek Olarak v.4 İle Gelen Özellikleri\r\n- Yönetim Panelinden İstediğiniz Kadar Menü ve Sayfa Ekleme Özelliği\r\n- Bağlantılar Ekleme Özelliği\r\n\r\nSınırsız Alt Kategori Oluşturma Özelliği Eklendi\r\nKategorileri Sıralama Özelliği Eklendi (16.05.08 İtibariyle)\r\nTema Özelliği Eklendi\r\nBugün Kayıt Olan Üyeler Özelliği Eklendi\r\nYazılara RSS Özelliği Eklendi\r\n\r\n\r\nOturumla ve Çerezle Sayfa Denetimi.. \r\nÇerezle Üyenin Tekrar Gelişinde Tanınması \r\nÜyelik Kaydı \r\n5 Seviyede Üye Kaydı \r\nBazı Bölümler İstenilen Üye Seviyesine Gösterilebilir \r\nÜye Profiline Resim Ekleme \r\nŞifre Unutmada Yeni Şifrenin E-Posta Hesabına Gönderilmesi \r\nYönetim Panelinden Tüm Site Ayarlarının Yapılması \r\nResim Galerisi Eklendi (Yeni) \r\nResimlere Oy Verme ve Yorum Yapma Eklendi (Yeni) \r\nKişisel veya Genel Resim Albümü Oluşturma Eklendi (Yeni) \r\nToplu E-Posta ve Özel Mesaj Gönderme Eklendi (Yeni) \r\nSite İçi Özel Mesajlaşma \r\nÖzel Mesaj Göndermede Üye Arayabilme \r\nGelen ve Giden Mesajları Sıralayabilme \r\nGünlük ve Toplam Tekil Sayaç \r\nOnline Kişi Sayısı (Dakikada Güncellenir. Gerçekçi Olarak Çevrimiçi (Online) Kişileri Gösterir) \r\nOnline Üyeler (Dakikada Güncellenir. Gerçekçi Olarak Çevrimiçi (Online) Üyeleri Gösterir) \r\nYazı Ekleme Bölümü \r\nYazılara HTML Kodu Ekleyebilme \r\nYazılara Yorum Ekleyebilme \r\nYazılara Kategori Ekleyebilme \r\nYazılara Oy Verme \r\nHızlı Mesajlaşma Sistemi \r\nİstediğiniz Seçeneğe İzin Verebileceğiniz Anket \r\nDoğum Günü Olan Üyeleri Gösterme \r\nDil Dosyası Diğer Dillere Çevrilebilir', '2008-06-14 12:22:16', '2008-07-26 18:50:27', 1757, 'E');
-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_hizlimesaj`
-- 

CREATE TABLE `u_hizlimesaj` (
  `mesajno` int(10) NOT NULL auto_increment,
  `uyeno` int(10) unsigned NOT NULL default '0',
  `mesaj` text collate utf8_unicode_ci NOT NULL,
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `onay` enum('E','H') collate utf8_unicode_ci NOT NULL default 'H',
  PRIMARY KEY  (`mesajno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tablo döküm verisi `u_hizlimesaj`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_ipengelle`
-- 

CREATE TABLE `u_ipengelle` (
  `ipno` int(15) NOT NULL auto_increment,
  `ip` char(50) collate utf8_unicode_ci NOT NULL,
  `aciklama` char(250) collate utf8_unicode_ci NOT NULL,
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ipno`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tablo döküm verisi `u_ipengelle`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_ipkontrol`
-- 

CREATE TABLE `u_ipkontrol` (
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` char(50) collate utf8_unicode_ci NOT NULL,
  `denemesayi` tinyint(2) NOT NULL default '0',
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Tablo döküm verisi `u_ipkontrol`
-- 

INSERT INTO `u_ipkontrol` (`tarih`, `ip`, `denemesayi`) VALUES 
('2008-06-14 10:41:50', '127.0.0.1', 0);

-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_menuler`
-- 

CREATE TABLE IF NOT EXISTS `u_menuler` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Tablo döküm verisi `u_menuler`
--

INSERT INTO `u_menuler` (`menuno`, `menugrup`, `menuanahtar`, `menuresim`, `menuadi`, `menudil`, `menusayfaadi`, `menusayfadil`, `menudescription`, `menukeywords`, `menuadres`, `menuait`, `menuhedef`, `menu1sira`, `menu2sira`, `menuizin`, `menuduzen`, `menudurum`) VALUES
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



-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_ozelmesaj`
-- 

CREATE TABLE `u_ozelmesaj` (
  `mesajno` int(10) unsigned NOT NULL auto_increment,
  `kimden` int(10) NOT NULL default '0',
  `kime` int(10) NOT NULL default '0',
  `baslik` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `mesaj` text collate utf8_unicode_ci NOT NULL,
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `okundu` enum('E','H') collate utf8_unicode_ci NOT NULL default 'H',
  `cevaplandi` mediumint(1) NOT NULL default '0',
  PRIMARY KEY  (`mesajno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tablo döküm verisi `u_ozelmesaj`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_resim`
-- 

CREATE TABLE `u_resim` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tablo döküm verisi `u_resim`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_resimpuan`
-- 

CREATE TABLE `u_resimpuan` (
  `resimno` int(10) NOT NULL default '0',
  `uyeno` text collate utf8_unicode_ci NOT NULL,
  UNIQUE KEY `resimno` (`resimno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Tablo döküm verisi `u_resimpuan`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_sayac`
-- 

CREATE TABLE `u_sayac` (
  `tarih` date NOT NULL default '0000-00-00',
  `buguntekil` int(5) NOT NULL default '0',
  `toplamtekil` int(10) NOT NULL default '0',
  `buguncogul` int(6) unsigned NOT NULL default '0',
  `toplamcogul` int(8) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Tablo döküm verisi `u_sayac`
-- 

INSERT INTO `u_sayac` (`tarih`, `buguntekil`, `toplamtekil`, `buguncogul`, `toplamcogul`) VALUES 
('2008-06-14', 1, 1, 1, 1);

-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_sozluk`
-- 

CREATE TABLE `u_sozluk` (
  `sozcukno` int(10) NOT NULL auto_increment,
  `turkce` varchar(100) character set utf8 collate utf8_turkish_ci NOT NULL,
  `ingilizce` varchar(100) collate utf8_unicode_ci NOT NULL,
  `uyeno` int(10) NOT NULL default '0',
  `tarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `onay` enum('E','H') collate utf8_unicode_ci NOT NULL default 'E',
  PRIMARY KEY  (`sozcukno`),
  UNIQUE KEY `sozcukno` (`sozcukno`),
  KEY `sozluk_index` (`ingilizce`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tablo döküm verisi `u_sozluk`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_uyeler`
-- 

CREATE TABLE `u_uyeler` (
  `uyeno` int(10) unsigned NOT NULL auto_increment,
  `resim` char(16) collate utf8_unicode_ci default NULL,
  `uyeadi` char(25) collate utf8_unicode_ci NOT NULL,
  `sifre` char(50) collate utf8_unicode_ci NOT NULL,
  `adi` char(50) collate utf8_unicode_ci NOT NULL,
  `soyadi` char(50) collate utf8_unicode_ci NOT NULL,
  `eposta` char(150) collate utf8_unicode_ci NOT NULL,
  `dogumtarihi` date NOT NULL default '0000-00-00',
  `seviye` mediumint(1) NOT NULL default '1',
  `onay` enum('E','H') collate utf8_unicode_ci NOT NULL default 'H',
  `yonay` mediumint(1) NOT NULL default '0',
  `kayittarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  `girisdenemesayi` tinyint(3) NOT NULL default '0',
  `girisdenemetarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `girissayisi` int(5) NOT NULL default '0',
  `songiris` datetime NOT NULL default '0000-00-00 00:00:00',
  `songiristarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  `guncellemetarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  `onlinetarih` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` char(50) collate utf8_unicode_ci NOT NULL default '1',
  `onaykodu` char(50) collate utf8_unicode_ci NOT NULL,
  `bilgi` mediumint(1) NOT NULL default '0',
  PRIMARY KEY  (`uyeno`),
  UNIQUE KEY `uyeadi_2` (`uyeadi`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Tablo döküm verisi `u_uyeler`
-- 

INSERT INTO `u_uyeler` (`uyeno`, `resim`, `uyeadi`, `sifre`, `adi`, `soyadi`, `eposta`, `dogumtarihi`, `seviye`, `onay`, `yonay`, `kayittarihi`, `girisdenemesayi`, `girisdenemetarih`, `girissayisi`, `songiris`, `songiristarihi`, `guncellemetarihi`, `onlinetarih`, `ip`, `onaykodu`, `bilgi`) VALUES 
(1, '', 'pehepe', 'c6f9614d07c51521cd6485a1190015303576c63a', 'PeHePe', 'PeHePe', 'info@arslandesign.com', '0000-00-00', 6, 'E', 5, '2008-06-14 10:41:46', 0, '0000-00-00 00:00:00', 0, '2008-06-14 10:41:46', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '127.0.0.1', '8b6799fc1c5d5116d56f6b90ec86c5a170589c04', 1);

-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_yazikategori`
-- 

CREATE TABLE `u_yazikategori` (
  `kategorino` int(5) NOT NULL auto_increment,
  `altkategorino` int(10) NOT NULL default '0',
  `kategoriadi` char(100) collate utf8_unicode_ci NOT NULL,
  `kategoriaciklama` char(200) collate utf8_unicode_ci default NULL,
  `kategorisira` int(4) default '0',
  PRIMARY KEY  (`kategorino`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tablo döküm verisi `u_yazikategori`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_yazilar`
-- 

CREATE TABLE `u_yazilar` (
  `yazino` int(10) unsigned NOT NULL auto_increment,
  `kategorino` int(5) NOT NULL default '0',
  `uyeno` int(10) unsigned NOT NULL default '0',
  `resim` char(20) collate utf8_unicode_ci default NULL,
  `baslik` char(100) collate utf8_unicode_ci NOT NULL,
  `yazi` text collate utf8_unicode_ci NOT NULL,
  `eklemetarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  `duzenlemetarihi` datetime NOT NULL default '0000-00-00 00:00:00',
  `onay` enum('E','H') collate utf8_unicode_ci NOT NULL default 'H',
  `okunma` int(10) NOT NULL default '0',
  `puan` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`yazino`),
  KEY `kategorino` (`kategorino`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Tablo döküm verisi `u_yazilar`
-- 

INSERT INTO `u_yazilar` (`yazino`, `kategorino`, `uyeno`, `resim`, `baslik`, `yazi`, `eklemetarihi`, `duzenlemetarihi`, `onay`, `okunma`, `puan`) VALUES 
(1, 0, 1, NULL, 'PeHePe Üyelik Sistemi v5.4', 'Hoşgeldiniz\r\nBu mesaj otomatik olarak oluşturulmuştur.\r\nPeHePe Üyelik Sistemi 5.4 ile Bir Çok Yeni Özellik Sisteme Eklenmiştir.\r\nBu Sistemin İşinize Yaramasını Umuyorum.\r\nGördüğünüz Hataları ve Eksiklikleri http://www.arslandesign.com/projeler/uyelik5 Adresine Yazabilirsiniz.\r\nKolay Gelsin', '2008-06-14 10:41:46', '0000-00-00 00:00:00', 'E', 0, 0);

-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_yazipuan`
-- 

CREATE TABLE `u_yazipuan` (
  `yazino` int(10) NOT NULL default '0',
  `uyeno` text collate utf8_unicode_ci NOT NULL,
  UNIQUE KEY `yazino` (`yazino`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Tablo döküm verisi `u_yazipuan`
-- 


-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_yonetim`
-- 

CREATE TABLE `u_yonetim` (
  `ayarno` mediumint(1) NOT NULL default '1',
  `siteadi` char(100) collate utf8_unicode_ci NOT NULL default '',
  `siteadresi` char(100) collate utf8_unicode_ci NOT NULL default '',
  `siteeposta` char(100) collate utf8_unicode_ci NOT NULL default '',
  `sitedil` char(4) collate utf8_unicode_ci NOT NULL default 'tr',
  `sitetema` char(200) collate utf8_unicode_ci NOT NULL default 'tema1',
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
  `galeriresimoylama` enum('E','H') collate utf8_unicode_ci NOT NULL default 'E',
  `galerialbumeklemeizin` mediumint(1) NOT NULL default '1',
  `galerialbumkayitsure` tinyint(2) NOT NULL default '1',
  `galerialbumeklemesayi` tinyint(3) NOT NULL default '1',
  `galerialbumduzenizin` mediumint(1) NOT NULL default '1',
  `galerialbumduzensure` tinyint(2) NOT NULL default '1',
  `galerialbumonay` mediumint(1) NOT NULL default '1',
  `sozcukeklemeizin` mediumint(1) NOT NULL default '2',
  `sozcukduzenlemeizin` mediumint(1) NOT NULL default '5',
  `sozcukonay` mediumint(1) NOT NULL default '5',
  `uyekayitkapat` enum('E','H') collate utf8_unicode_ci NOT NULL default 'H',
  `uyegormeizin` mediumint(1) NOT NULL default '3',
  `uye1` char(20) collate utf8_unicode_ci NOT NULL default 'Normal Uye',
  `uye2` char(20) collate utf8_unicode_ci NOT NULL default 'Gumus Uye',
  `uye3` char(20) collate utf8_unicode_ci NOT NULL default 'Bronz Uye',
  `uye4` char(20) collate utf8_unicode_ci NOT NULL default 'Altin Uye',
  `uye5` char(20) collate utf8_unicode_ci NOT NULL default 'Yonetici',
  `uye6` char(20) collate utf8_unicode_ci NOT NULL default 'Genel Yönetici',
  PRIMARY KEY  (`ayarno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Tablo döküm verisi `u_yonetim`
-- 

INSERT INTO `u_yonetim` (`ayarno`, `siteadi`, `siteadresi`, `siteeposta`, `sitedil`, `sitetema`, `girisdenemesayisi`, `girisdenemesuresi`, `kayitarasisure`, `uyelikonayi`, `uyesilmezamani`, `epostadegisti`, `yazikarakter`, `yazieklemeizin`, `yazikayitarasisure`, `yazionay`, `yaziduzenlemeizin`, `yaziduzenlemesuresi`, `yaziokumaizin`, `yaziayrintiokumaizin`, `yazioylama`, `yazikategorisira`, `yorumkarakter`, `yorumeklemeizin`, `yorumonay`, `yorumarasisure`, `ozelmesajgondermeizin`, `ozelmesajarasisure`, `ozelmesajkarakter`, `ozelmesajizin`, `ozelmesajisimizin`, `hizlimesajeklemeizin`, `hizlimesajonay`, `hizlimesajsure`, `hizlimesajkarakter`, `galeriresimgormeizin`, `galeriresimeklemeizin`, `galeriresimkayitsure`, `galeriresimduzenizin`, `galeriresimduzensure`, `galeriresimonay`, `galeriresimoylama`, `galerialbumeklemeizin`, `galerialbumkayitsure`, `galerialbumeklemesayi`, `galerialbumduzenizin`, `galerialbumduzensure`, `galerialbumonay`, `sozcukeklemeizin`, `sozcukduzenlemeizin`, `sozcukonay`, `uyekayitkapat`, `uyegormeizin`, `uye1`, `uye2`, `uye3`, `uye4`, `uye5`, `uye6`) VALUES 
(1, 'PeHePe Üyelik Sistemi', 'http://www.arslandesign.com', 'info@arslandesign.com', 'tr', 'tema1', 5, 5, 1, 3, 24, 0, 5000, 1, 1, 1, 2, 5, 0, 0, 'H', 1, 250, 1, 1, 1, 1, 1, 250, 10, 1, 1, 1, 1, 250, 0, 1, 1, 3, 1, 2, 'E', 1, 1, 1, 1, 1, 2, 2, 5, 5, 'H', 3, 'Normal Uye', 'Gumus Uye', 'Bronz Uye', 'Altin Uye', 'Yonetici', 'Genel Yönetici');

-- --------------------------------------------------------

-- 
-- Tablo yapısı: `u_yorumlar`
-- 

CREATE TABLE `u_yorumlar` (
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

-- 
-- Tablo döküm verisi `u_yorumlar`
-- 

