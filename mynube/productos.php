<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if($_SERVER['REQUEST_METHOD']=='GET')
{
    if(isset($_GET ['estid']) && isset($_GET ['llave']))
    { 
        $id=$_GET ['estid'];
        $llave=$_GET ['llave'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mynube.com/v2/inv/get-products/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{ "estId":'.$id.'}',
        CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'X-Api-Key: '.$llave),));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }
}
