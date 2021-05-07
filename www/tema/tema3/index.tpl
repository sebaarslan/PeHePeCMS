<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<title>{TITLE}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="{KEYWORDS}" />
<meta name="description" content="{DESCRIPTION}" />
<link href="{SITE_TEMA}/stil.css" rel="stylesheet" />

<script language="JavaScript" type="text/javascript">
<!--
{SAJAX_SHOW_JAVASCRIPT}

var rakam_kullaniniz            = "{RAKAM_KULLANINIZ}";
var secenek_oy_izin             = "{SECENEK_OY_IZIN}";
var karakter_sayi_kontrol_mesaj = "{KARAKTER_SAYI_KONTROL}";
var yenileme_suresi             = "{YENILEME_SURESI}";
var site_adi                    = "{SITE_ADI}";
// -->
</script>
<script language="JavaScript" type="text/javascript" src="icerik/ortak.js"></script>
<script language="JavaScript" type="text/javascript" src="icerik/script.js"></script>

</head>
<body onload="guncelVeri();">
<table width="940" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3" height="100" align="center" valign="middle" bgcolor="#C4C4C4" style="border-left:1px solid #515128; border-bottom:5px solid #FF9900"><a href="index.php"><img src="resim/logo.png" alt="{SITE_ADI}" align="middle" border="0" /></a></td>
  </tr>  
  <tr>
    <td height="40" colspan="3" align="center" valign="middle">
      <table width='100%' height="40" border='0' align="center" cellpadding='0' cellspacing='0' style="border-left:1px solid #515128; border-right:1px solid #515128; background-color:#f1f1f1; padding-top:3px">
        <tr>
          <!-- BEGIN MENU1_BLOK -->							
          <td width="80" style="border-right:1px solid #FF6600; font-size:10px"><p align="center">
          <a href="{MENU1_ADRES}" target="{MENU1_HEDEF}"><img src="{SITE_TEMA}/resimli_menu/{MENU1_RESIM}"  width="29" height="29" border="0" /><br /><b>{MENU1_ADI}</b></a></p>
          </td>
          <!-- END MENU1_BLOK -->
          <td width="380" align="right" valign="bottom">
					<!-- BEGIN DIL_BLOK -->
    <a href="?dil={DIL_ANAHTAR}"><img width="40" height="25" src="resim/{DIL_RESIM}" id="{DIL_ANAHTAR}" alt="{DIL_ISIM}" title="{DIL_ISIM}" border="0" /></a>
          <!-- END DIL_BLOK -->&nbsp;</td>
				</tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="170" align="center" valign="top" class="enter">
      <table cellspacing="0" cellpadding="0" width="170" border="0" class="enter">
        <!-- BEGIN UYE_ISLEM_BLOK -->
        <tr>
          <td class="solmenubaslik" height="25"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{UYE_ISLEM_BASLIK}</td>
        </tr>
        <tr>
          <td class="sol_menu" align="middle">
            <table cellspacing="0" cellpadding="4" width="100%" border="0">
              <tr>
                <td valign="top" align="left">
                  <div id="giris_form">
                  {UYE_ISLEM}                                
                  </div>                              
                </td>
              </tr>
            </table>                        
          </td>
        </tr>
        <!-- END UYE_ISLEM_BLOK -->
											
        <!-- BEGIN SAYFALAR_BLOK -->
        <tr>
          <td class="solmenubaslik" height="25"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{SAYFALAR_BASLIK}</td>
        </tr>
        <tr>
          <td valign="top" align="middle">
            <table cellspacing="0" cellpadding="4" width="100%" border="0">
              <tr>
                <td valign="top" align="left" nowrap="nowrap" class="menu">
                  <div id="menu">
                  <!-- BEGIN MENU2_BLOK -->
                  <div id="menu"><a href="{MENU2_ADRES}" target="{MENU2_HEDEF}">{MENU2_ADI}</a></div>
                  <!-- END MENU2_BLOK -->
                  </div></td>
              </tr>
            </table>											  
          </td>
        </tr>
        <!-- END SAYFALAR_BLOK -->
											
        <!-- BEGIN HIZLI_MESAJ_BLOK -->
        <tr>
          <td valign="top" align="middle"></td>
        </tr>
        <!-- END HIZLI_MESAJ_BLOK -->
											
        <!-- BEGIN UYELER_DOGUM_GUNU_BLOK -->
        <tr>
          <td class="solmenubaslik" height="25"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{UYELER_DOGUM_GUNU_BASLIK}</td>
        </tr>
        <tr>
          <td valign="top" align="middle">
            <table cellspacing="0" cellpadding="4" width="100%" border="0">
              <tr>
                <td valign="top" align="left">
                {UYELER_DOGUM_GUNU}
                </td>
              </tr>
            </table>											  
          </td>
        </tr>
        <!-- END UYELER_DOGUM_GUNU_BLOK -->
											
        <!-- BEGIN ISTATISTIK_BLOK -->
        <tr>
          <td class="solmenubaslik" height="25">&nbsp;{ISTATISTIKLER}</td>
        </tr>
        <tr>
          <td align="left" style="padding-left:10px" background="resim/hm.jpg">
            <table cellspacing="0" cellpadding="2" width="100%" border="0">
              <tr>
                <td width="48%" nowrap="nowrap">&nbsp;{BUGUN_TEKIL}</td>
                <td width="2%" nowrap="nowrap">:</td>
                <td width="50%" nowrap="nowrap"><div id="bugunTekil">{BUGUN_TEKIL_YAZDIR}</div></td>
              </tr>
              <tr>
                <td width="48%" nowrap="nowrap">&nbsp;{TOPLAM_TEKIL}</td>
                <td width="2%" nowrap="nowrap">:</td>
                <td width="50%" nowrap="nowrap"><div id="toplamTekil">{TOPLAM_TEKIL_YAZDIR}</div></td>
              </tr>
              <tr>
                <td width="48%" nowrap="nowrap">&nbsp;{BUGUN_COGUL}</td>
                <td width="2%" nowrap="nowrap">:</td>
                <td width="50%" nowrap="nowrap"><div id="bugunCogul">{BUGUN_COGUL_YAZDIR}</div></td>
              </tr>
              <tr>
                <td width="48%" nowrap="nowrap">&nbsp;{TOPLAM_COGUL}</td>
                <td width="2%" nowrap="nowrap">:</td>
                <td width="50%" nowrap="nowrap"><div id="toplamCogul">{TOPLAM_COGUL_YAZDIR}</div></td>
              </tr>
              <tr>
                <td width="48%" nowrap="nowrap">&nbsp;{KAYITLI_UYE}</td>
                <td width="2%" nowrap="nowrap">:</td>
                <td width="50%" nowrap="nowrap"><div id="toplamUye">{KAYITLI_UYE_YAZDIR}</div></td>
              </tr>
              <tr>
                <td width="48%" nowrap="nowrap">&nbsp;{BUGUN_KAYIT}</td>
                <td width="2%" nowrap="nowrap">:</td>
                <td width="50%" nowrap="nowrap"><div id="bugunKayit">{BUGUN_KAYIT_YAZDIR}</div></td>
              </tr>
              <tr>
                <td width="48%" nowrap="nowrap">&nbsp;{ONLINE_MISAFIR}</td>
                <td width="2%" nowrap="nowrap">:</td>
                <td width="50%" nowrap="nowrap"><div id="onlineMisafir">{ONLINE_MISAFIR_YAZDIR}</div></td>
              </tr>
              <tr>
                <td width="48%" nowrap="nowrap">&nbsp;{ONLINE_UYE}</td>
                <td width="2%" nowrap="nowrap">:</td>
                <td width="50%" nowrap="nowrap"><div id="onlineUye">{ONLINE_UYE_YAZDIR}</div></td>
              </tr>
              <tr>
                <td colspan="3"><b>--------------------------<br />{AKTIF_UYELER}</b>
                <div id="onlineUyeler" style="width:100px; height:auto">{AKTIF_UYELER_YAZDIR}</div>                                 
                </td>
              </tr>
              <tr>
                <td colspan="3"><b>--------------------------<br />{BUGUN_KAYIT_OLAN_UYELER}</b>
                <div id="bugunKayitOlanUyeler" style="width:100px; height:auto">{BUGUN_KAYIT_OLAN_UYELER_YAZDIR}</div>                                 
								</td>
              </tr>
            </table>                        
          </td>
        </tr>
        <!-- END ISTATISTIK_BLOK -->
				
				<!-- BEGIN SON_EKLENEN_RESIMLER_ANA_BLOK -->
        <tr>
          <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
        </tr>
        <tr>
          <td class="solmenubaslik" height="20"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{SON_EKLENEN_RESIMLER_DIL}</td>
        </tr>
        <tr>
          <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
        </tr>
        <tr>
          <td valign="top" align="middle">
            <table cellspacing="0" width="100%" border="0">
              <!-- BEGIN SON_EKLENEN_RESIMLER_ALT_BLOK --> 
              <tr>
                <td align="center" class="resim_kenarlik1" onmouseover="this.className='resim_kenarlik2'" onmouseout="this.className='resim_kenarlik1'"><a href="?sayfa=galeri&resim={RESIM_NO}&islem=3&sk=1"><img src="resim.php?resim={RESIM}&en=100&boy=80" border="0" alt="{RESIM_ADI}" title="{RESIM_ADI}" align="absmiddle" /></a></td>
              </tr>
              <!-- END SON_EKLENEN_RESIMLER_ALT_BLOK -->
														
              <!-- BEGIN SON_EKLENEN_RESIMLER_KAYITYOK_BLOK -->
              <tr>
                <td align="center">{KAYIT_YOK}</td>
              </tr>
              <!-- END SON_EKLENEN_RESIMLER_KAYITYOK_BLOK -->
            </table>
          </td>
        </tr>
        <!-- END SON_EKLENEN_RESIMLER_ANA_BLOK -->
      </table>
    </td>
    <td width="598" align="center" valign="top" class="enter">
      <div align="center" style="text-align:center">
			<script type="text/javascript"><!--
      google_ad_client = "pub-3219641692306880";
      /* 468x60, olus,turulma 09.01.2009 */
      google_ad_slot = "0524535204";
      google_ad_width = 468;
      google_ad_height = 60;
      //-->
      </script>
      <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
      </div>
      <table width="598" cellpadding="0" cellspacing="0" class="enter">
        <tr>
          <td align="center" valign="top">
          <!-- ORTA ALAN BASLANGICI -->
          <div id="ortaBolum" name="ortaBolum">{ORTA_ALAN}</div>
          <!-- ORTA ALAN SONU -->
          </td>
        </tr>
      </table>
    </td>
    <td width="170" align="center" valign="top" class="enter">
      <table width="170" cellpadding="0" cellspacing="0" class="enter">
			  <!-- BEGIN YAZILAR_BLOK -->
        <tr>
          <td class="solmenubaslik" height="25"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{YAZILAR_BASLIK}</td>
        </tr>
        <tr>
          <td valign="top" align="left" nowrap="nowrap" style="padding:3px">
          <div class="menu">
          <!-- BEGIN YAZILAR_KONULAR_BLOK -->
          <div id="menu"><a href="{KONU_ADRES}">{KONU_ISIM}</a></div>
          <!-- END YAZILAR_KONULAR_BLOK -->
          </div>															
          </td>
        </tr>
				<!-- END YAZILAR_BLOK -->
			
        <!-- BEGIN COK_OKUNAN_YAZILAR_ANA_BLOK -->
        <tr>
          <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
        </tr>
        <tr>
          <td class="solmenubaslik" height="20"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{COK_OKUNAN_YAZILAR}</td>
        </tr>
        <tr>
          <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
        </tr>
        <tr>
          <td valign="top" align="middle">
            <table cellspacing="0" width="100%" border="0">
              <!-- BEGIN COK_OKUNAN_YAZILAR_ALT_BLOK --> 
              <tr class="menu">
                <td width="90%" align="left" nowrap="nowrap">
                <a href="{CO_YAZILAR_ADRES}">{CO_YAZILAR_ISIM} ({CO_YAZILAR_OKUNMA})</a>
                </td>
              </tr>
              <!-- END COK_OKUNAN_YAZILAR_ALT_BLOK -->
            </table>
          </td>
        </tr>
        <!-- END COK_OKUNAN_YAZILAR_ANA_BLOK -->
											
        <tr>
          <td class="solmenubaslik" height="25"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{HIZLI_MESAJ_BASLIK}</td>
        </tr>
        <tr>
          <td align="center" valign="top"><table cellspacing="0" cellpadding="4" width="100%" border="0">
        <tr>
          <td valign="top" align="left">
          <div id="hizliMesaj" style="width:auto; height:200px; background-image:url(resim/hm.jpg); border:1px solid #515128; padding:2px; color:#6A6A35">{HIZLI_MESAJ_YAZDIR}</div>
          <img height="1" alt="" src="resim/bosluk.gif" id="hizliMesajDurum" /></td>
        </tr>
        <form name="hmForm" id="hmForm" action="?sayfa=hmesaj" method="post" autocomplete="off">
        <tr>
          <td align="center"><div id="hzMesaj" style="display:block">{HIZLI_MESAJ_EKLEME_IZIN}</div></td>
        </tr>
        </form>
				
				<!-- BEGIN BAGLANTILAR_ANA_BLOK -->
				<tr>
          <td class="solmenubaslik" height="20"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{BAGLANTILAR_BASLIK}</td>
        </tr>
        <tr>
          <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
        </tr>
        <tr>
          <td valign="top" align="middle">
            <table cellspacing="0" width="100%" border="0">
              <!-- BEGIN BAGLANTILAR_ALT_BLOK --> 
              <tr class="menu">
                <td width="90%" align="left" nowrap="nowrap">
                <a href="{BAGLANTI_ADRES}" target="{BAGLANTI_HEDEF}" title="{BAGLANTI_ADI_UZUN}">{BAGLANTI_ADI_KISA}</a>
                </td>
              </tr>
              <!-- END BAGLANTILAR_ALT_BLOK -->
            </table>
          </td>
        </tr>
				<!-- END BAGLANTILAR_ANA_BLOK -->						 
        
      </table>
    </td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="altbolum">PHP Üyelik Sistemi v5&nbsp;&nbsp;-&nbsp;&nbsp;Programlama <a href="http://www.arslandesign.com/" target="_blank" title="Arslan Design"><strong>arslanDizayn</strong></a>
    <br />Her Hakkı  <a href="http://www.arslandesign.com/" target="_blank" title="Arslan Design"><strong>www.arslandizayn.com</strong></a>'a Aittir
    <br />Tema Dizayn : Soner Algan 
    <!-- BEGIN YONETIM_PANEL_BLOK -->
    {YONETIM_PANEL}
    <!-- END YONETIM_PANEL_BLOK --></td>
  </tr>
</table>
</body>
</html>
