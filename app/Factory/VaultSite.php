<?php

namespace App\Factory;

use SoapClient;
use Exception;

class VaultSite
{
    protected $client;

    public function __construct()
    {
        try {
            $this->client = new SoapClient("https://e6d6f2f82928.ngrok-free.app/VaultSite/apiwebservice.asmx", [
                'trace' => 1,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
            ]);
        } catch (Exception $e) {
            throw new Exception("SOAP Client Error: " . $e->getMessage());
        }
    }

   public function addCard(array $cardProfile)
    {
        try {
            $params = [
                'CardProfile' => $cardProfile
            ];

            return $this->client->__soapCall('AddCard', [$params]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
