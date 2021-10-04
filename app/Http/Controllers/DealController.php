<?php

namespace App\Http\Controllers;

use App\Services\ZohoService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DealController extends Controller
{
    private $zohoService;

    public function __construct()
    {
        $this->zohoService = new ZohoService();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->insertDeal();
        $this->getTasks();
    }

    public function store(LanguageRequest $request)
    {

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function getTasks(){

        $token = $this->zohoService->accessToken();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.zohoapis.com/crm/v2/Tasks");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array(
            "Authorization" . ":" . "Zoho-oauthtoken " . $token
        ));
        $response = curl_exec($ch);
        $response = json_decode($response);
        $data = $response->data;
        $msg = 'Дані з розділу Задач з тестовими даними';
        $data2 = array_column($data,'Description', 'id');

        dd($msg, $data2);

    }

    public function insertDeal(){
        $token = $this->zohoService->accessToken();

        $postData = [
            'data' =>[
                [
                    "Description" => "Test Company Test Test",
                    "Deal_Name"   => "Test Name3 Test Test",
                    "Type"        => "Existing Business",
                    "Subject"     => "Register for upcoming CRM Webinars",
                ]
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.zohoapis.com/crm/v2/Deals");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array(
            "Authorization" . ":" . "Zoho-oauthtoken " . $token
        ));
        $response = curl_exec($ch);
        $response = json_decode($response,true);
        $idDeal = $response['data'][0]['details']['id'];

        return $idDeal;
    }

    public function insertTasks(){

        $token = $this->zohoService->accessToken();
        $idDeal = $this->insertDeal();

        $postData = [
            'data' => [
                [
                    "Subject"     => "Test",
                    "Due Date"    => "2029-11-20",
                    "Status"      => "Deferred",
                    "CONTACTID"   => "2000000017017",
                    "Description" => "Test",
                    "Status"      => "Not Started",
                    '$se_module'  => "Deals",
                    "Priority"    => "High",
                    "What_Id"     => [
                        'id' => $idDeal
                    ]
                ],
            ],
            'trigger' => [
                'approval',
                'workflow',
                'blueprint'
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.zohoapis.com/crm/v2/Tasks");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array(
            "Authorization" . ":" . "Zoho-oauthtoken " . $token
        ));
        $response = curl_exec($ch);
        $response = json_decode($response);

    }
}
