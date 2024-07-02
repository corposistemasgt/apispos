<?php
require_once "funciones.php";
include 'admin.php';
if($_SERVER['REQUEST_METHOD']=='GET')
{
    $a=0;
    if(isset($_GET ['nit']))
    { 
        $nit=$_GET['nit']; 
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
                        <Requestor>7A1513A2-C414-4C46-AF7E-E9E966B1B2CC</Requestor>
                        <Transaction>SYSTEM_REQUEST</Transaction>
                        <Country>GT</Country>
                        <Entity>108151654</Entity>
                        <User>7A1513A2-C414-4C46-AF7E-E9E966B1B2CC</User>
                        <UserName>ADMINISTRADOR</UserName>
                        <Data1>MINIRTUFRFASES_QUERY_JSON</Data1>
                        <Data2>'.$nit.'</Data2>
                        <Data3></Data3>
                    </RequestTransaction>
                </soap:Body>
            </soap:Envelope>',
        CURLOPT_HTTPHEADER => array(  'Content-Type: text/xml' ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $frase=obtener_frases($response);
        $json_pss = json_decode($frase, true);
        $frases='{"frases":[';
        $a=0;   
        foreach($json_pss['frases'] as $pss_json)
        {
            $a++;
            $frases.='{"tipo_frase":"'.$pss_json['tipo_frase'].'","razon_social":"'.$pss_json['nombre'].'","escenario":"'.$pss_json['codigo_escenario'].'","frase":"'.$pss_json['Escenario'].'"},';
        }
        $frases=trim($frases, ',').'],';
        
        echo $frases;

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
              <UserName>ADMINISTRADOR</UserName>
              <Data1>ESTABLECIMIENTO_QUERY_JSON</Data1>
              <Data2>
              [
                {
                    "numero_nit": "'.$nit.'",
                    "id_establecimiento": "0"
                }
             ]
              </Data2>
              <Data3></Data3>
            </RequestTransaction>
          </soap:Body>
        </soap:Envelope>',
          CURLOPT_HTTPHEADER => array( 'Content-Type: text/xml'),
        ));   
        $response = curl_exec($curl);    
        curl_close($curl);
        $json_pss= obtener_establecimientos($response);
        $json_pss = json_decode($json_pss, true);
        $estas='"establecimientos":[';
        $a=0;  
        $e1="{";
        foreach($json_pss['establecimientos'] as $pss_json)
        {
            $e1.= '"id_establecimiento":"'.$pss_json['id_establecimiento'].'",';
            $e1.= '"Nombre":"'.$pss_json['Nombre'].'",';
            $e1.= '"Direccion":"'.$pss_json['Direccion'].'",';
            $e1.= '"Municipio":"'.$pss_json['Municipio'].'",';
            $e1.= '"Departamento":"'.$pss_json['Departamento'].'",';
            $e1.= '"Estatus":"'.$pss_json['Estatus'].'"';
            $e1.='},';
            $estas.=$e1;
            $e1="{";
        }
        $estas=trim($estas, ',').']}';
        echo $estas;
    }
}
?>