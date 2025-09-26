<?php

namespace App\Services;

use SoapClient;
use Exception;

class VaultSiteService
{
    protected $client;

    public function __construct()
    {
        try {
            $this->client = new SoapClient(env("VAULTSITE_API_URL") . "?WSDL", [
                'trace' => 1,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'keep_alive' => false,
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

    public function addArea(array $alGroup)
    {
        try {
            $params = [
                'ALGroup' => $alGroup
            ];

            return $this->client->__soapCall('AddGroupAccessLevel', [$params]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteArea(string $accessNo)
    {
        try {
            $params = [
                'AccessNo' => $accessNo
            ];

            return $this->client->__soapCall('DeleteGroupAccessLevel', [$params]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    

}
