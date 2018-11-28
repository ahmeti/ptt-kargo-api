# PTT Kargo Api (PHP)
PTT Web Servisini kullanabilmek için PTT İl Müdürlüklerinden, Müşteri ID ve Müşteri şifre bilgilerinizi öğrenebilirsiniz.

## Composer ile Yükleme
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