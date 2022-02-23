<?php

namespace RoticSDK;

use RoticSDK\Model\Response;
use RoticSDK\Model\RoticSDKModel;

class RoticSDK
{
    public $token, $api;
    private $sdk;
    public static $unique_token;

    public function __construct()
    {
        self::$unique_token = self::$unique_token==null?rand(10000000,90000000):self::$unique_token;
        $this->sdk = new RoticSDKModel();
    }

    public function chat($data ,$unique_token=null): RoticSDKModel
    {

        try {
            if ($this->token!=null && $this->api !=null){
                $request = new Request();
                return $request->MakeRequest($this->token,$this->api,$data,$unique_token==null?self::$unique_token:$unique_token);
            }
            else{
                $this->sdk->response=[];
                $this->sdk->provider->source='Rotic PHP SDK';
                $this->sdk->provider->website='https://rotic.ir';
                $this->sdk->status=false;
                $this->sdk->error->code=207;
                $this->sdk->error->message="Token or Api token did not provided!";

                return $this->sdk;
            }
        }catch (\Exception $exception){
            $this->sdk->response=[];
            $this->sdk->provider->source='Rotic PHP SDK';
            $this->sdk->provider->website='https://rotic.ir';
            $this->sdk->status=false;
            $this->sdk->error->code=$exception->getCode();
            $this->sdk->error->message=$exception->getMessage();

            return $this->sdk;

        }
    }

}
