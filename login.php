<?php
require_once "funciones.php";
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        if(isset($_GET ['usuario']) && isset($_GET ['password'])&& isset($_GET ['nit']))
        { 
            $nit= $_GET['nit']; 
            $usuario=base64_decode($_GET['usuario']);  
            $password=base64_decode($_GET['password']);
            $data=base64_encode(json_encode(array("numero_nit"=>$nit, "codigo_usuario"=>$usuario, "password"=>$password)));
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
              CURLOPT_POSTFIELDS =>'<?xml version="1.0" encoding="utf-8"?>
            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Body>
                <RequestTransaction xmlns="http://www.fact.com.mx/schema/ws">
                  <Requestor>5e309112-f7ef-497b-81b2-bda09349d63d</Requestor>
                  <Transaction>SYSTEM_REQUEST</Transaction>
                  <Country>GT</Country>
                  <Entity>USERADMIN</Entity>
                  <User>5e309112-f7ef-497b-81b2-bda09349d63d</User>
                  <UserName>ADMIN</UserName>
                  <Data1>USUARIO_LOGIN</Data1>
                  <Data2>'.$data.'</Data2>
                  <Data3></Data3>
                </RequestTransaction>
              </soap:Body>
            </soap:Envelope>',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml'
              ),
            ));
            $response = curl_exec($curl);
            $requestor=verificar_login($response);
            if(strcmp($requestor,"")!==0)
            { 
              echo json_encode(array("resultado"=>"true","requestor"=>$requestor));            
            }   
            header ("HTTP/1.1 200 OK");            
            exit;
        }        
    }
?>