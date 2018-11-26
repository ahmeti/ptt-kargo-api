# PTT Kargo Api (PHP)

## Composer ile Yükleme
```code
composer require "ahmeti/ptt-kargo-api:@dev"
```

## 01. Gönderi Hareket İşlem Tarihi Sorgu
```php
<?php

$pttApi = new \Ahmeti\PttKargoApi();

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
