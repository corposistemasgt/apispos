<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    require_once "../funciones.php";
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        if(isset($_GET ['nit']))
        {    
            $nit=($_GET ['nit']);
            if(strlen($nit)>10)
            {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.corposistemasgt.com/webservicefront/factwsfront.asmx?WSDL=null',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                    xmlns:ws="http://www.fact.com.mx/schema/ws">
                    <SOAP-ENV:Header/>
                        <SOAP-ENV:Body>
                            <ws:RequestTransaction>
                                <ws:Requestor>94E0301E-AD36-4BAC-B327-B6B6B658469E</ws:Requestor>
                                <ws:Transaction>SYSTEM_REQUEST</ws:Transaction>
                                <ws:Country>GT</ws:Country>
                                <ws:Entity>800000001026</ws:Entity>
                                <ws:User>94E0301E-AD36-4BAC-B327-B6B6B658469E</ws:User>
                                <ws:UserName>ADMINISTRADOR</ws:UserName>
                                <ws:Data1>CONSULTA_CUI</ws:Data1>
                                <ws:Data2>'.$nit.'</ws:Data2>
                                <ws:Data3></ws:Data3>
                            </ws:RequestTransaction>
                        </SOAP-ENV:Body>
                    </SOAP-ENV:Envelope>',
                CURLOPT_HTTPHEADER => array('Content-Type: text/xml'),));
                $response = curl_exec($curl);
                curl_close($curl);
                obtener_cui($response);
            }
            else
            {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.corposistemasgt.com/getnit/ConsultaNIT.asmx?WSDL=null',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
	                <soap:Body>
		                <getNIT xmlns="http://tempuri.org/">
			                <vNIT>'.$nit.'</vNIT>
			                <Entity>800000001026</Entity>
			                <Requestor>94E0301E-AD36-4BAC-B327-B6B6B658469E</Requestor>
		                </getNIT>
	                </soap:Body>
                </soap:Envelope>',
                CURLOPT_HTTPHEADER => array('Content-Type: text/xml'),));
                $response = curl_exec($curl);
                curl_close($curl);
                obtener_nit($response);
            }
        }    
    }
?>