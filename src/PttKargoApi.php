<?php

namespace Ahmeti\PttKargoApi;

class PttKargoApi {

    private $actId, $actPass;

    public function __construct($actId = null, $actPass = null)
    {
        if($actId){
            $this->actId = $actId;
        }elseif( getenv('PTT_ACT_ID') ){
            $this->actId = getenv('PTT_ACT_ID');
        }

        if($actPass){
            $this->actPass = $actPass;
        }elseif( getenv('PTT_ACT_PASS') ){
            $this->actPass = getenv('PTT_ACT_PASS');
        }
    }

    private function validateDate($date, $format = 'Y-m-d'){
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    private function getClient($options = null)
    {
        $config = [];

        if( is_array($options) ){
            $config = array_merge($config, $options);
        }

        return new \SoapClient('https://pttws.ptt.gov.tr/GonderiHareketV2/services/Sorgu?wsdl', $config);
    }

    public function gonderiHareketIslemTarihiSorgu($date)
    {
        if ( ! $this->validateDate($date) ){
            return 'Geçersiz bir tarih girdiniz...';
        }

        $date = str_replace('-', '', $date);

        try {
            $soap = $this->getClient();

            $data = $soap->gonderiHareketIslemTarihiSorgu([
                'input' => [
                    'musteri_id' => $this->actId,
                    'sifre' => $this->actPass,
                    'son_islem_tarihi' => $date,
                ]
            ]);

            $collect = [
                'aciklama' => isset($data->return->aciklama) ? $data->return->aciklama : null,
                'barkod_devam' => isset($data->return->barkod_devam) ? $data->return->barkod_devam : null,
                // 'dongu' => [],
                'rcode' => isset($data->return->rcode) ? $data->return->rcode : null,
                'sqlcode' => isset($data->return->sqlcode) ? $data->return->sqlcode : null,
            ];


            $items = [];

            if( isset($data->return->dongu) ){
                if( is_array($data->return->dongu) ){
                    foreach ($data->return->dongu as $item){
                        array_push($items, (array)$item);
                    }
                }else{
                    array_push($items, (array)$data->return->dongu);
                }
            }

            $collect['dongu'] = $items;

            return $collect;

        }catch ( \SoapFault $fault){
            return $fault;
        }
    }
}