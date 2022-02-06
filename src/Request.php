<?php


namespace RoticSDK;


use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use RoticSDK\Model\Options;
use RoticSDK\Model\Provider;
use RoticSDK\Model\Response;
use RoticSDK\Model\RoticSDKModel;

class Request
{
    private $sdk;

    public function __construct()
    {
        $this->sdk = new RoticSDKModel();
    }
    public function MakeRequest($token,$api,$data,$unique_token): RoticSDKModel
    {
        try {
            $AiUri = "https://api.rotic.ir/v2/services/" . $token . "/ai";

            $client = new Client();
            $response = json_decode($client->post($AiUri,[
                RequestOptions::FORM_PARAMS=>[
                    'data'=>$data,
                    'token'=>$token,
                    'api'=>$api,
                    'unique_token'=>$unique_token,
                ]
            ])->getBody()->getContents());

            foreach ($response->response as $key => $server_response){
                $response_object = new Response();
                $response_object->type=$server_response->type;
                $response_object->value=$server_response->value;
                $response_object->buttons=$server_response->buttons;
                $response_object->images=$server_response->images;
                $ores[$key]=$response_object;
            }
            $this->sdk->response=$ores;

            $provider = new Provider();
            $provider->source=$response->provider->source;
            $provider->website=$response->provider->website;
            $this->sdk->provider=$provider;

//            $this->sdk->options=$response->options;

            $this->sdk->status=$response->status;

            return $this->sdk;

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
