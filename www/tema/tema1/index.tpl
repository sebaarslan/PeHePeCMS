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

<table cellspacing="0" cellpadding="0" align="center" class="anatablo" bordercolor="#000000">
  <!-- LOGO ALANI -->
  <tr>
    <td>
      <table class="ust" cellspacing="0" cellpadding="0" width="100%" border="0">
        <tr>
          <td align="center"><a href="index.php"><img alt="{SITE_ADI}" title="{SITE_ADI}" src="resim/logo.png" width="500" height="100" border="0" />&nbsp;<img src="{SITE_TEMA}/resim/bayrak.gif" alt="TÜRKİYE" title="TÜRKİYE" border="0" align="absmiddle" /></a></td>
        </tr>
      </table>
    </td>
  </tr>
  <!-- LOGO ALANI SONU -->
  <tr>
    <td>
      <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tr>
          <td class="ortaalan">
            <table cellspacing="0" cellpadding="0" width="100%" border="0">
              <tr>
                <td class="ustmenu"><img height="4" alt="" src="resim/bosluk.gif" width="4" /></td>
                <td class="ustmenu" align="right">
								  <div id="ustmenu">
									<!-- BEGIN MENU1_BLOK -->
									<a href="{MENU1_ADRES}" target="{MENU1_HEDEF}"><b>{MENU1_ADI}</b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
									<!-- END MENU1_BLOK -->
									</div> 
									</td>
                </tr>
              </table>
              <table class="ana" cellspacing="0" cellpadding="0" width="100%" border="0">
                <tr>
                  <td class="solmenu" valign="top" width="180" style="border-right:dashed 2px #ff6600;">
                    <table cellspacing="0" cellpadding="0" width="180" border="0">
										  <!-- BEGIN UYE_ISLEM_BLOK -->
                      <tr>
                        <td class="solmenubaslik" height="20"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{UYE_ISLEM_BASLIK}</td>
                      </tr>
                      <tr>
                        <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
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
											<tr>
							        <td align="center">
							        <!-- BEGIN DIL_BLOK -->
                      <a href="?dil={DIL_ANAHTAR}"><img width="40" height="25" src="resim/{DIL_RESIM}" id="{DIL_ANAHTAR}" alt="{DIL_ISIM}" title="{DIL_ISIM}" border="0" /></a>
                      <!-- END DIL_BLOK -->
                      </td>
                      </tr>
											<!-- REKLAM BASLANGICI -->
                      <tr>
                        <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
                      </tr>
                      <tr>
                        <td class="solmenubaslik" height="20"><img height="4" alt="" src="resim/bosluk.gif" width="4" />REKLAM</td>
                      </tr>
                      <tr>
                        <td valign="top" align="center" class="ortaalan">
												<div id="menu">
                        <script type="text/javascript"><!--
                        google_ad_client = "pub-3219641692306880";
                        google_ad_width = 180;
                        google_ad_height = 90;
                        google_ad_format = "180x90_0ads_al_s";
                        //2007-04-24: uyelik
                        google_ad_channel = "7878114198";
                        google_color_border = "FFFFFF";
                        google_color_bg = "cccccc";
                        google_color_link = "000000";
                        google_color_text = "000000";
                        google_color_url = "FF6600";
                        //-->
                        </script>
                        <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script>
												</div>
												</td>
											</tr>
											<!-- REKLAM SONU -->
											
											<!-- BEGIN YAZILAR_BLOK -->
                      <tr>
                        <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
                      </tr>

                      <tr>
                        <td class="solmenubaslik" height="20"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{YAZILAR_BASLIK}</td>
                      </tr>
											
                      <tr>
                        <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
                      </tr>
											<tr>
                        <td valign="top" align="middle">
											    <table cellspacing="0" cellpadding="4" width="100%" border="0">
													  <tr>
                              <td valign="top" align="left" nowrap="nowrap">
															<div class="menu">
															<!-- BEGIN YAZILAR_KONULAR_BLOK -->
															<div id="menu"><a href="{KONU_ADRES}">{KONU_ISIM}</a></div>
															<!-- END YAZILAR_KONULAR_BLOK -->
															</div>
															</td>
														</tr>
                          </table>
												</td>
											</tr>
											<!-- END YAZILAR_BLOK -->
											
											<!-- BEGIN SAYFALAR_BLOK -->
                      <tr>
                        <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
                      </tr>

                      <tr>
                        <td class="solmenubaslik" height="20"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{SAYFALAR_BASLIK}</td>
                      </tr>
                      <tr>
                        <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
                      </tr>
											<tr>
                        <td valign="top" align="middle">
											    <table cellspacing="0" cellpadding="4" width="100%" border="0">
													  <tr>
                              <td valign="top" align="left" nowrap="nowrap">
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
                        <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
                      </tr>

                      <tr>
                        <td class="solmenubaslik" height="20"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{HIZLI_MESAJ_BASLIK}</td>
                      </tr>
                      <tr>
                        <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
                      </tr>
											<tr>
                        <td valign="top" align="middle">
											    <table cellspacing="0" cellpadding="4" width="100%" border="0">
													  <tr>
                              <td valign="top" align="left">
                              <div id="hizliMesaj" style="width:auto; height:200px">{HIZLI_MESAJ_YAZDIR}</div>
															<img height="1" alt="" src="resim/bosluk.gif" id="hizliMesajDurum" />
															</td>
														</tr>
														<form name="hmForm" id="hmForm" action="?sayfa=hmesaj" method="post" autocomplete="off">
                            <tr>
                              <td align="center">
															<div id="hzMesaj" style="display:block">
                              {HIZLI_MESAJ_EKLEME_IZIN}
															</div>
                              </td>
                            </tr>
                            </form>
                          </table>
												</td>
											</tr>
											<!-- END HIZLI_MESAJ_BLOK -->
											
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
														  <a href="{CO_YAZILAR_ADRES}">{CO_YAZILAR_ISIM}</a>
													    </td>
														  <td width="10%" align="center" nowrap="nowrap">{CO_YAZILAR_OKUNMA}</td>
												    </tr>
													  <!-- END COK_OKUNAN_YAZILAR_ALT_BLOK -->
													</table>
												</td>
											</tr>
											<!-- END COK_OKUNAN_YAZILAR_ANA_BLOK -->
											
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
											
											<!-- BEGIN UYELER_DOGUM_GUNU_BLOK -->
											<tr>
                        <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
                      </tr>

                      <tr>
                        <td class="solmenubaslik" height="20"><img height="4" alt="" src="resim/bosluk.gif" width="4" />{UYELER_DOGUM_GUNU_BASLIK}</td>
                      </tr>
                      <tr>
                        <td><img height="1" alt="" src="resim/bosluk.gif" width="1" /></td>
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
                          <td class="solmenubaslik" height="20">&nbsp;{ISTATISTIKLER}</td>
                      </tr>
                      <tr>
                        <td align="left" style="padding-left:10px">
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
                        </table>
                        <br />
                        <img height="1" alt="" src="resim/bosluk.gif" width="180" />
                      </td>

                      <td valign="top" width="18"><img height="18" alt="" src="resim/bosluk.gif" width="18" /></td>
                      <td valign="top" width="100%" align="center" style="padding-left:20px">
											<!-- GOOGLE REKLAM -->
                      <div align="center">
                      <script type="text/javascript"><!--
                      google_ad_client = "pub-3219641692306880";
                      google_ad_width = 468;
                      google_ad_height = 60;
                      google_ad_format = "468x60_as";
                      google_ad_type = "text_image";
                      //2007-04-24: uyelik
                      google_ad_channel = "7878114198";
                      google_color_border = "FFFFFF";
                      google_color_bg = "FFFFFF";
                      google_color_link = "3366FF";
                      google_color_text = "000000";
                      google_color_url = "FF6600";
                      //-->
                      </script>
                      <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
                      </div>
											<!-- GOOGLE REKLAM SONU -->
                      <!-- ORTA ALAN BASLANGICI -->
                      <div id="ortaBolum" name="ortaBolum">{ORTA_ALAN}</div>
											<!-- ORTA ALAN SONU -->
                      </td>
                      <td valign="top" width="19"><img height="19" alt="" src="resim/bosluk.gif" width="19" /></td>
                    </tr>
                 </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table cellspacing="0" cellpadding="0" width="100%" border="0">
            <tr>
              <td class="altbolum" align="center">PHP Üyelik Sistemi v5&nbsp;&nbsp;-&nbsp;&nbsp;Programlama <a href="http://www.arslandesign.com/" target="_blank" title="Arslan Design"><strong>arslanDizayn</strong></a>
							<br />Her Hakkı  <a href="http://www.arslandesign.com/" target="_blank" title="Arslan Design"><strong>www.arslandizayn.com</strong></a>'a Aittir <br />Tema Dizayn : Sebahattin Arslan
							<!-- BEGIN YONETIM_PANEL_BLOK -->
							{YONETIM_PANEL}
							<!-- END YONETIM_PANEL_BLOK --></td>
            </tr>		
          </table>
        </td>
      </tr>
		</table>
	</td>
</tr>
</table>
<br />
</body>
</html>