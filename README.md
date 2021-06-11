# PTT Kargo Api (PHP)
PTT Web Servisini kullanabilmek için PTT İl Müdürlüklerinden, Müşteri ID ve Müşteri şifre bilgilerinizi öğrenebilirsiniz.

## PTT Web Servis Dokümanları
https://github.com/ahmeti/ptt-kargo-api/blob/master/01_PTT_Entegrasyon_Giris.pdf

https://github.com/ahmeti/ptt-kargo-api/blob/master/02_PTT_Veri_Yukleme_Web_Servisi.pdf

## PTT Ek Hizmet Kodları
```
AA - ADRESTEN ALMA
ST - ŞEHİR İÇİ TESLİM
DK - DEĞER KONULMUŞ
OS - ÖDEME ŞARTLI
AH - ALMA HABERLİ
AK - ALICININ KENDİNE TESLİM
TA - TELEFONLA BİLGİLENDİRME
KT - KONTROLLU TESLIM
OU - ÖZEL ULAK
UA - ÜCRETİ ALICIDAN TAHSİL
GD - GİDİŞ-DÖNÜŞ
SV - SERVİS
RP - RESMİ PUL
UO - ÜCRET ÖDEME MAKİNESİ
VI - KREDİ KARTI
PC - POSTA ÇEKİ HESABI
DN - BARKOD DÖNÜŞLÜ
PI - PTT ISYERINE TESLIM
AT - ADLI TIP
PR - POSTRESTANT
SB - SMS ILE BILGILENDIRME
```

## PTT Durum Kodları
| gonderi_durum_id | gonderi_durum_aciklama                                                | carpan | ust_durum_ad         | ust_durum_id |
|------------------|-----------------------------------------------------------------------|--------|----------------------|--------------|
| 831              | Tasfiye Edildi                                                        | 0      | TASFİYE              | 999          |
| 1                | Kabul Edildi                                                          | 0      | KABUL                | 1000         |
| 701              | Kayıt Edildi                                                          | 0      | KABUL                | 1000         |

Listenin tamamını görmek için [03_PTT_Durum_Kodlari.md](https://github.com/ahmeti/ptt-kargo-api/edit/master/03_PTT_Durum_Kodlari.md) 

Durum kodları listesi [Arif Bey'in](https://github.com/arifw3) desteği ile hazırlanmıştır.

## Composer ile Yükleme
https://packagist.org/packages/ahmeti/ptt-kargo-api
```code
composer require "ahmeti/ptt-kargo-api:@dev"
```

## Ortam Değişkenleri
```code
PTT_ACT_ID=
PTT_ACT_PASS=
```

## Ortam Değişkenleri Tanımlı İse
```php
<?php
# pttApi nesnemizi .env değişkenleri ile oluşturuyoruz.
$pttApi = new \Ahmeti\PttKargoApi\PttKargoApi();
```

## Ortam Değişkenleri Tanımlı Değil İse
```php
<?php
# pttApi nesnemizi parametre ile oluşturuyoruz.
$pttApi = new \Ahmeti\PttKargoApi\PttKargoApi($pttMusteriId, $pttMusteriSifre);
```

## 01. Barkod Sorgu
```php
<?php
$result = $pttApi->barkodSorgu('KP02168XXXXXX');

if( is_array($result) ){
    // Başarılı
    var_dump($result);
}else{
    // Hata (SoapFault)
    var_dump($result);
}
```

## 02. Gönderi Hareket İşlem Tarihi Sorgu
```php
<?php
$result = $pttApi->gonderiHareketIslemTarihiSorgu('2018-03-01');

if( is_array($result) ){

    foreach( $result['dongu'] as $item ){
        // Başarılı
        var_dump($item);
    }

}else{
    // Hata (SoapFault)
    var_dump($result);
}
```

## 03. Ptt Veri Yükle 2

API: https://pttws.ptt.gov.tr/PttVeriYukleme/services/Sorgu?wsdl

```php
<?php

use Ahmeti\PttKargoApi\PttVeriYukle2;

# $items = (array) Kargo Bilgileri

$ptt = (new PttVeriYukle2())->kullanici('PttWs')
    ->sifre(env('PTT_ACT_PASS'))
    ->musteriId(env('PTT_ACT_ID'))
    ->dosyaAdi(date('Ymd-His-').uniqid())
    ->gonderiTur('KARGO')
    ->gonderiTip('NORMAL');

foreach ($items as $item){
    $ptt->aAdres($item->aAdres)
        ->agirlik($item->agirlik)
        ->aliciAdi($item->aliciAdi)
        ->aliciIlAdi($item->aliciIlAdi)
        ->aliciIlceAdi($item->aliciIlceAdi)
        ->aliciSms($item->aliciSms)
        ->barkodNo($item->barkodNo)
        ->boy($item->boy)
        ->deger_ucreti($item->deger_ucreti)
        ->desi($item->desi)
        ->ekhizmet($item->ek_hizmet)
        ->en($item->en)
        ->musteriReferansNo($item->musteriReferansNo)
        ->odemesekli($item->odemesekli)
        ->odeme_sart_ucreti($item->odeme_sart_ucreti)
        ->rezerve1($item->rezerve1)
        ->yukseklik($item->yukseklik)
        ->ekle();
}

$result = $ptt->yukle();

if( is_array($result) && $result['hataKodu'] == 1 ){

    print_r($result);

    foreach ($result['dongu'] as $barcode){
        // $barcode
    }

    return true;

}else{

    print_r($result['aciklama']);

    return false;
}
```

## 04. Ptt Barkod Veri Sil
Oluşturduğumuz barkodu silmek için aşağıdaki metodu kullanabilirsiniz.

API: https://pttws.ptt.gov.tr/PttVeriYukleme/services/Sorgu?wsdl

```php
<?php

use Ahmeti\PttKargoApi\PttBarkodVeriSil;

$ptt = new PttBarkodVeriSil();

$result = $ptt->barcode('BARKOD NO')
    ->dosyaAdi('BORKODU İLK KAYDETTİĞİNİZ DOSYA ADI')
    ->musteriId('XXX_Musteri_Id')
    ->sifre('XXX_Sifre')
    ->sil();

if( is_array($result) && $result['hataKodu'] == 1 ){

    print_r($result);

    return true;

}else{

    print_r($result);

    return false;
}
```
