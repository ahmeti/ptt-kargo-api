# PTT Kargo Api (PHP)

## Composer ile Yükleme
```code
composer require "ahmeti/ptt-kargo-api:@dev"
```

## Ortam Değişkenleri
```code
PTT_ACT_ID=
PTT_ACT_PASS=
```

## 01. Gönderi Hareket İşlem Tarihi Sorgu
```php
<?php

# Ortam değişkenlerini tanımlı değil ise;
$pttApi = new \Ahmeti\PttKargoApi\PttKargoApi($pttMusteriId, $pttMusteriSifre);

# Ortam değişkenleri tanımlı ise;
$pttApi = new \Ahmeti\PttKargoApi\PttKargoApi();

$result = $pttApi->gonderiHareketIslemTarihiSorgu('2018-03-01');

if( isset($result['rcode']) ){

    foreach( $result['dongu'] as $item ){
        // Başarılı
        var_dump($item);
    }

}else{
    // Hata (SoapFault)
    var_dump($result);
}
