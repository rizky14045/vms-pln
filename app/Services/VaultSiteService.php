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
            // $this->client = new SoapClient(env("VAULTSITE_API_URL") . "?WSDL", [
            //     'trace' => 1,
            //     'exceptions' => true,
            //     'cache_wsdl' => WSDL_CACHE_NONE,
            //     'keep_alive' => false,
            // ]);

            $this->client = new SoapClient(env("VAULTSITE_API_URL") . "?WSDL", [
                'trace' => 1,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'location' => env("VAULTSITE_API_URL"), // <--- WAJIB
                'uri' => "WebAPI", // namespace dari WSDL
                'keep_alive' => false,
                'stream_context' => stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                    ],
                    'http' => [
                        'protocol_version' => 1.1,
                        'header' => "Connection: close"
                    ]
                ]),
            ]);


        } catch (Exception $e) {
            // throw new Exception("SOAP Client Error: " . $e->getMessage());
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

    public function updateCard(string $cardNo, array $cardProfile)
    {
        try {
            $params = [
                'CardNo'      => $cardNo,
                'CardProfile' => $cardProfile
            ];

            return $this->client->__soapCall('UpdateCard', [$params]);

        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function addToFR(array $requestBody)
    {
        try {
            return $this->client->__soapCall('FRDownloadCardUserFace', [
                $requestBody['FRDownloadCardUserFace']
            ]);
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

    public function updateCardAccessLevel(array $data)
    {
        try {
            $params = [
                'CardNo'       => $data['CardNo'],
                'AccessLevel'  => $data['AccessLevel'],
                'DownloadCard' => $data['DownloadCard'],
            ];

            return $this->client->__soapCall('UpdateCardAccessLevel', [$params]);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }
    public function deleteCard($cardNumber)
    {
        try {
            $params = [
                'CardNo'       => $cardNumber,
                'DeleteFromDevice'  => true,
            ];

            return $this->client->__soapCall('DeleteCard', [$params]);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function updateCardExpiryDate(string $cardNo, string $expiryDate)
    {
        try {
            $params = [
                'CardNo'       => $cardNo,
                'ExpiryDate'   => $expiryDate,
                'DownloadCard' => true,
            ];

            $result =  $this->client->__soapCall('UpdateCardExpiryDate', [$params]);

        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function checkFacePhoto(string $photoBase64)
    {
        try {
            $params = [
                'PhotoBase64' => $photoBase64
            ];

            $response = $this->client->__soapCall('FRCheckFacePhoto', [$params]);

            // Ambil hasil dari property "FRCheckFacePhotoResult"
            $result = $response->FRCheckFacePhotoResult ?? null;

            if (!$result) {
                return [
                    'error' => true,
                    'message' => 'Invalid response structure'
                ];
            }

            $errCode    = (string)($result->ErrCode ?? '');
            $errMessage = (string)($result->ErrMessage ?? '');
            $mediaId    = (string)($result->MediaID ?? '');

            if ($errCode === '-1') {
                return [
                    'error' => true,
                    'message' => $errMessage
                ];
            }

            return [
                'error' => false,
                'message' => 'Success',
                'media_id' => $mediaId
            ];

        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}
