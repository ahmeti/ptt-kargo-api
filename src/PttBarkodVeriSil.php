<?php

namespace Ahmeti\PttKargoApi;

class PttBarkodVeriSil {

    private $_barcode, $_dosyaAdi, $_musteriId, $_sifre;

    public function __construct($musteriId = null, $sifre = null)
    {
        if($musteriId){
            $this->_musteriId = $musteriId;
        }elseif( getenv('PTT_ACT_ID') ){
            $this->_musteriId = getenv('PTT_ACT_ID');
        }

        if($sifre){
            $this->_sifre = $sifre;
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

        return new \SoapClient('https://pttws.ptt.gov.tr/PttVeriYukleme/services/Sorgu?wsdl', $config);
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

    public function barcode($barcode)
    {
        $this->_barcode = $barcode;
        return $this;
    }

    public function sil()
    {
        try {
            $soap = $this->getClient();

            $data = $soap->barkodVeriSil([
                'inpDelete' => [
                    'barcode' => $this->_barcode,
                    'dosyaAdi' => $this->_dosyaAdi,
                    'musteriId' => $this->_musteriId,
                    'sifre' => $this->_sifre,
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
