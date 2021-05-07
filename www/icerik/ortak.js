/* 
Ortak Olan JavaScript Kodlarini Barindirir
*/
<!-- RAKAM KONTROL  -->
function isNumberic(v) 
{
  var isNum = /^[0-9]*$/;
  if (!isNum.test(v.value)) 
  { 
    alert(rakam_kullaniniz);
    v.value = v.value.replace(/[^0-9]/g,"");
  }
}
<!-- RAKAM KONTROL SONU -->
<!-- SECIM KONTROL -->
var secim = "false";
function sec(alan,form) 
{
  dml=document.forms[form];
  len = dml.elements.length;
  
  if (secim == "false") 
  {
    for (i=0; i<len; i++) 
    {
      dml.elements[i].checked=true;
    }
    secim = "true";
  } else {
    for (i=0; i<len; i++) 
    {
      dml.elements[i].checked=false;
    }
    secim = "false";
  }
}
<!-- SECIM KONTROL SONU -->
<!-- ANKET SECIM KONTROL BASLANGICI -->
function anket_sec (secenekno)
{
  var kutu = eval(secenekno);
  kutu.checked = !kutu.checked;
}

function secim_kontrol(secimizin,secenekno) 
{
  var secilen = 0;
  var max = document.anketForm.secenek.length;
  for (var idx = 0; idx < max; idx++) 
  {
    if (eval("document.anketForm.secenek[" + idx + "].checked") == true) 
    {
      secilen += 1;
    }
  }
  if (secilen > secimizin)
  {
    alert(secenek_oy_izin);
    var secenek = document.anketForm.secenek[secenekno];
		if (secenek) secenek.checked=false;
    return false;
  } else { 
    return true;
  }
}
<!-- ANKET SECIM KONTROL SONU -->
<!-- RESIM DEGISTIRME -->
function resim_degistir(resimyolu,alan)
{
  document.getElementById(alan).src = ""+resimyolu;
}
<!-- RESIM DEGISTIRME SONU -->
<!-- KARAKTER SAYMA -->
function karakter_sayi_kontrol(alan,maxlimit) 
{
  var metin = new String(document.getElementById(alan).value);
  var limit = maxlimit-1;
	if (metin.length >= limit)
  {
	  alert(karakter_sayi_kontrol_mesaj);
		document.getElementById(alan).value = metin.substring(0, maxlimit);
		false;
  } else {
	  document.getElementById(alan+'_sayac').value = maxlimit - metin.length;
	}
}
<!-- KARAKTER SAYMA SONU -->

<!-- SAJAX BASLANGICI -->
var yuklemeresim  = new Image(16,16);
var bosresim      = new Image(1,1);
yuklemeresim.src  = 'resim/yukleniyor.gif';
bosresim.src      = 'resim/bosluk.gif';
var resim_id      = 'bekle';

function ajaxSonuc(deger)
{
  var resim = document.getElementById(resim_id);
  if (resim)
  resim.src = bosresim.src;
  
  var icerik_dizi   = document.getElementById(deger['icerik_div']);
  var ana_dizi      = document.getElementById(deger['ana_div']);
  var icerik        = deger['icerik'];
  if (icerik)
  {
    if (ana_dizi)
		{
		  ana_dizi.style.display = 'block';
      icerik_dizi.innerHTML  = icerik;
		}
  } else {
    if (icerik_dizi)
		{
      icerik_dizi.innerHTML  = '';
      ana_dizi.style.display = 'none';
		}
  }
}
function ajaxSorgu(alandeger,sayfaadi,fonksiyonadi,sonuc,resimid)
{
  if (resimid)
	resim_id = resimid;
	document.getElementById(resim_id).src = yuklemeresim.src;

  x_fonksiyonCagir(alandeger,sayfaadi,fonksiyonadi,eval(sonuc));
}
<!-- SAJAX SONU -->
<!-- GUNCEL VERI ALMA -->
function guncelVeriSonuc(veri) 
{	
  var resim = document.getElementById('hizliMesajDurum');
  if (resim)
  resim.src = bosresim.src;
	for (var key in veri)
	{
    var div = document.getElementById(key);
		if (div)
		{
		  div.style.display = 'block';
			div.innerHTML = veri[key];
		}
	}
}
var ilk_cagrim = false;
function guncelVeri() 
{
	
	if (ilk_cagrim)
	{
    var resim = document.getElementById('hizliMesajDurum');
    if (resim)
    resim.src = yuklemeresim.src;
    x_fonksiyonCagir('','guncelveri','guncelVeri','sayfa',guncelVeriSonuc);
	}
	setTimeout("guncelVeri()", yenileme_suresi);
	ilk_cagrim=true;
}
<!-- GUNCEL VERI ALMA SONU -->

function sayfaAc(sayfa,en,boy,scrollbars,toolbar)
{
  if (!scrollbars)
	scrollbars='no';
	if (!toolbar)
	toolbar='no';
  window.open(sayfa,"","width="+en+",height="+boy+",scrollbars="+scrollbars+",toolbar="+toolbar);
}