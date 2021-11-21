<?php

namespace App\Helpers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class MediumApiHelper {


    public function makeRequest ($method, $requestUrl, $queryParams = [], 
        $formParams = [], $headers = [], $hasFile = false) {

        $client = new Client([
            'verify' => env('SSL_CERT'),
            'base_uri' => 'https://api.medium.com/v1/',
        ]);

        $bodyType = 'form_params';

        if ($hasFile) {
            $bodyType = 'multipart';
            $multipart = [];
            foreach ($formParams as $name => $contents) {
                $multipart[] = [
                    'name'      => $name,
                    'contents'  => $contents
                ];
            }
        }

        $response = $client->request($method, $requestUrl, [
            $bodyType => $hasFile ? $multipart : $formParams
        ]);

        if (200 == $response->getStatusCode()) {
            $response = $response->getBody()->getContents();
            return $response;
        } elseif (201 == $response->getStatusCode()) {
            return $response->getBody();
        }

    }

    public static function getUser ($data) {

        $response = self::makeRequest('GET', 'me?accessToken='.$data['access_token']);
        return $response;

    }

    public static function submitPost ($accessToken, $mediumUserId, $data=[]) {

        $response = self::makeRequest('POST', 'users/'.$mediumUserId.'/posts?accessToken='.$accessToken, [], $data, [], false);
        return $response;

    }

    public static function uploadImage ($data) {

        $response = self::makeRequest('POST', 'images?accessToken='.$data['access_token'], [], $data, [], true);
        return $response;

    }

    

}