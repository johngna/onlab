<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AuvoService
{
    public $baseUrl;
    public $apiKey;
    public $apiToken;
    public $accessToken;
    public $expiration;

    public function __construct()
    {
        $this->baseUrl = config('services.auvo.api_url');
        $this->apiKey = config('services.auvo.api_key');
        $this->apiToken = config('services.auvo.api_token');
        $this->accessToken = session('accessToken');
        $this->expiration = strtotime(session('expiration', '0'));


        if($this->accessToken == null || $this->expiration < time()){
            $this->login();
         }


    }

    private function makeRequest($method, $endpoint, $params = [])
    {

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->$method("{$this->baseUrl}/$endpoint", $params);
    }


    public function login(){



        $response = Http::get("$this->baseUrl/login/?apiKey=$this->apiKey&apiToken=$this->apiToken");



        if ($response->successful()) {

            $this->accessToken = $response->json()['result']['accessToken'];
            $this->expiration = $response->json()['result']['expiration'];

            session(['accessToken' => $response->json()['result']['accessToken']]);
            session(['expiration' => $response->json()['result']['expiration']]);

        }

    }



    public function getEquipmentData($serialNumber)
    {


        //json filter
        $filter = urlencode(json_encode([
            'identifier' => $serialNumber,
        ]));


        $cacheKey = "equipment_data_{$serialNumber}";
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $response = $this->makeRequest('get', 'equipments/', ['paramFilter' => $filter]);

        if ($response->successful()) {
            $data = $response->json();
            cache()->put($cacheKey, $data, now()->addMinutes(60)); // Cache for 60 minutes
            return $data;
        }

        return null;
    }


    public function getAllClients()
    {

        $response = $this->makeRequest('get', 'customers/', ['page' =>'1', 'pageSize' => '1000']);

        if ($response->successful()) {
            return $response->json()['result']['entityList'];
        }

        return null;
    }

    public function getClient($clientId)
    {

        $cacheKey = "client_{$clientId}";
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        // If not in cache, make the request
        $response = $this->makeRequest('get', 'customers/' . $clientId);

        if ($response->successful()) {
            $data = $response->json()['result'];
            // Store the data in cache for future requests
            cache()->put($cacheKey, $data, now()->addMinutes(60)); // Cache for 60 minutes
            return $data;
        }

        return null;
    }



    public function getTasks($clientId)
    {

        //json filter
        $filter = json_encode([
            'startDate' => '2000-01-01T00:00:01',
            'endDate' => '2024-11-20T23:59:59',
            'customerId' => $clientId,
        ]);


        $cacheKey = "tasks_{$clientId}";
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $response = $this->makeRequest('get', 'tasks/', ['paramFilter' => $filter]);

        if ($response->successful()) {
            $data = $response->json()['result']['entityList'];
            cache()->put($cacheKey, $data, now()->addMinutes(60)); // Cache for 60 minutes
            return $data;
        }

        return null;
    }

    public function getEquipments($clientId)
    {

        if($clientId == ''){
            $filter = '';

        }else{
            $filter = urlencode(json_encode([
                'associatedCustomerId' => $clientId,
            ]));
        }


        $cacheKey = "equipments_{$clientId}";
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $response = $this->makeRequest('get', 'equipments/', ['paramFilter' => $filter]);

        if ($response->successful()) {
            $data = $response->json()['result']['entityList'];
            cache()->put($cacheKey, $data, now()->addMinutes(60)); // Cache for 60 minutes
            return $data;
        }

        return null;
    }

    public function getEquipament($equipamentId)
    {


        $cacheKey = "equipment_{$equipamentId}";
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        // If not in cache, make the request
        $response = $this->makeRequest('get', 'equipments/' . $equipamentId);

        if ($response->successful()) {
            $data = $response->json()['result'];
            // Store the data in cache for future requests
            cache()->put($cacheKey, $data, now()->addMinutes(60)); // Cache for 60 minutes
            return $data;
        }

        return null;
    }



}
