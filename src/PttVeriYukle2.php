<?php

namespace Ahmeti\PttKargoApi;

class PttVeriYukle2 {

    private $_kullanici, $_sifre;

    private $_musteriId, $_dosyaAdi, $_gonderiTur, $_gonderiTip;

    private $_aAdres, $_agirlik, $_aliciAdi, $_aliciIlAdi, $_aliciIlceAdi,
            $_barkodNo, $_boy, $_deger_ucreti, $_desi, $_ekhizmet, $_en,
            $_musteriReferansNo, $_odemesekli, $_odeme_sart_ucreti,
            $_rezerve1, $_yukseklik;

    private $items = [];


    public function __construct($kullanici = null, $_sifre = null)
    {
        if($kullanici){
            $this->_kullanici = $kullanici;
        }elseif( getenv('PTT_ACT_ID') ){
            $this->_kullanici = getenv('PTT_ACT_ID');
        }

        if($_sifre){
            $this->_sifre = $_sifre;
        }elseif( getenv('PTT_ACT_PASS') ){
            $this->_sifre = getenv('PTT_ACT_PASS');
        }
    }

    private function getClient($options = null)
    {
        $config = [];

        if( is_array($options) ){
            $config = array_merge($config, $options);
        }

        return new \SoapClient('https://pttws.ptt.gov.tr/PttVeriYuklemeTest/services/Sorgu?wsdl', $config);
    }


    public function kullanici($kullanici)
    {
        $this->_kullanici = $kullanici;
        return $this;
    }

    public function sifre($sifre)
    {
        $this->_sifre = $sifre;
        return $this;
    }

    public function musteriId($musteriId)
    {
        $this->_musteriId = $musteriId;
        return $this;
    }

    public function dosyaAdi($dosyaAdi)
    {
        $this->_dosyaAdi = $dosyaAdi;
        return $this;
    }

    public function gonderiTur($gonderiTur)
    {
        $this->_gonderiTur = $gonderiTur;
        return $this;
    }

    public function gonderiTip($gonderiTip)
    {
        $this->_gonderiTip = $gonderiTip;
        return $this;
    }



    public function aAdres($aAdres)
    {
        $this->_aAdres = $aAdres;
        return $this;
    }

    public function agirlik($agirlik)
    {
        $this->_agirlik = $agirlik;
        return $this;
    }

    public function aliciAdi($aliciAdi)
    {
        $this->_aliciAdi = $aliciAdi;
        return $this;
    }

    public function aliciIlAdi($aliciIlAdi)
    {
        $this->_aliciIlAdi = $aliciIlAdi;
        return $this;
    }

    public function aliciIlceAdi($aliciIlceAdi)
    {
        $this->_aliciIlceAdi = $aliciIlceAdi;
        return $this;
    }

    public function barkodNo($barkodNo)
    {
        $this->_barkodNo = $barkodNo;
        return $this;
    }

    public function boy($boy)
    {
        $this->_boy = $boy;
        return $this;
    }

    public function deger_ucreti($deger_ucreti)
    {
        $this->_deger_ucreti = $deger_ucreti;
        return $this;
    }

    public function desi($desi)
    {
        $this->_desi = $desi;
        return $this;
    }

    public function ekhizmet($ekhizmet)
    {
        $this->_ekhizmet = $ekhizmet;
        return $this;
    }

    public function en($en)
    {
        $this->_en = $en;
        return $this;
    }

    public function musteriReferansNo($musteriReferansNo)
    {
        $this->_musteriReferansNo = $musteriReferansNo;
        return $this;
    }

    public function odemesekli($odemesekli)
    {
        $this->_odemesekli = $odemesekli;
        return $this;
    }

    public function odeme_sart_ucreti($odeme_sart_ucreti)
    {
        $this->_odeme_sart_ucreti = $odeme_sart_ucreti;
        return $this;
    }

    public function rezerve1($rezerve1)
    {
        $this->_rezerve1 = $rezerve1;
        return $this;
    }

    public function yukseklik($yukseklik)
    {
        $this->_yukseklik = $yukseklik;
        return $this;
    }

    public function ekle()
    {
        array_push($this->items, [
            'aAdres' => $this->_aAdres,
            'agirlik' => $this->_agirlik,
            'aliciAdi' => $this->_aliciAdi,
            'aliciIlAdi' => $this->_aliciIlAdi,
            'aliciIlceAdi' => $this->_aliciIlceAdi,
            'barkodNo' => $this->_barkodNo,
            'boy' => $this->_boy,
            'deger_ucreti' => $this->_deger_ucreti,
            'desi' => $this->_desi,
            'ekhizmet' => $this->_ekhizmet,
            'en' => $this->_en,
            'musteriReferansNo' => $this->_musteriReferansNo,
            'odemesekli' => $this->_odemesekli,
            'odeme_sart_ucreti' => $this->_odeme_sart_ucreti,
            'rezerve1' => $this->_rezerve1,
            'yukseklik' => $this->_yukseklik,
        ]);

        return $this;
    }

    public function yukle()
    {
        try {
            $soap = $this->getClient();

            $data = $soap->kabulEkle2([
                'input' => [
                    'dosyaAdi' => $this->_dosyaAdi,
                    'gonderiTip' => $this->_gonderiTip,
                    'gonderiTur' => $this->_gonderiTur,
                    'kullanici' => $this->_kullanici,
                    'musteriId' => $this->_musteriId,
                    'sifre' => $this->_sifre,
                    'dongu' => $this->items
                ]
            ]);

            if(isset($data->return)){
                return (array)$data->return;
            }else{
                return false;
            }

        }catch ( \SoapFault $fault){
            return $fault;
        }
    }

}