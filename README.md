# PTT Kargo Api (PHP)
PTT Web Servisini kullanabilmek için PTT İl Müdürlüklerinden, Müşteri ID ve Müşteri şifre bilgilerinizi öğrenebilirsiniz.

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
