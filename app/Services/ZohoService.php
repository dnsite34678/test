<?php


namespace App\Services;


class ZohoService{

    private $client_id = '1000.LIV6CED8N1AXFJ6PSTAKF3S1CP2DHK';
    private $client_secret = '0997d1d502b3e80e1fc7a7ff8006dc25111765fbbf';

    public function refreshToken(){
        $post = [
            'code'          => '1000.8d44608583dca8f59b546228cada161f.c76e022ffde04e74cfea003eafbc415d',
            'redirect_uri'  => 'http://app.dev/deal/create',
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type'    =>'authorization_code'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        $response = curl_exec($ch);
    }

    public function accessToken(){
        $post = [
            'refresh_token' => '1000.13050cb1c9b1bcae93491e5b30a8810b.3b8b69c0d9bfe020255bab0e971ebcab',
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type'    =>'refresh_token'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        $response = curl_exec($ch);
        $response = json_decode($response);
        $response = $response->access_token;
        return $response;
    }
}
