<?php
require_once "funciones.php";
include 'admin.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['nit'])) {
        $nit = $_GET['nit'];

        // === PRIMERA CONSULTA: Obtener frases ===
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.corposistemasgt.com/webservicefront/factwsfront.asmx?WSDL=null',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
                               xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
                               xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                        <RequestTransaction xmlns="http://www.fact.com.mx/schema/ws">
                            <Requestor>7A1513A2-C414-4C46-AF7E-E9E966B1B2CC</Requestor>
                            <Transaction>SYSTEM_REQUEST</Transaction>
                            <Country>GT</Country>
                            <Entity>108151654</Entity>
                            <User>7A1513A2-C414-4C46-AF7E-E9E966B1B2CC</User>
                            <UserName>ADMINISTRADOR</UserName>
                            <Data1>MINIRTUFRFASES_QUERY_JSON</Data1>
                            <Data2>' . $nit . '</Data2>
                            <Data3></Data3>
                        </RequestTransaction>
                    </soap:Body>
                </soap:Envelope>',
            CURLOPT_HTTPHEADER => array('Content-Type: text/xml')
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $frase = obtener_frases($response);
        $json_frases = json_decode($frase, true);

        $frases_array = array();
        foreach ($json_frases['frases'] as $item) {
            $frases_array[] = array(
                'tipo_frase' => $item['tipo_frase'],
                'razon_social' => $item['nombre'],
                'escenario' => $item['codigo_escenario'],
                'frase' => $item['Escenario']
            );
        }

        // === SEGUNDA CONSULTA: Obtener establecimientos ===
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.corposistemasgt.com/webservicefront/factwsfront.asmx?WSDL=null',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
                               xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
                               xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
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
                                    "numero_nit": "' . $nit . '",
                                    "id_establecimiento": "0"
                                }
                            ]
                            </Data2>
                            <Data3></Data3>
                        </RequestTransaction>
                    </soap:Body>
                </soap:Envelope>',
            CURLOPT_HTTPHEADER => array('Content-Type: text/xml')
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $establecimientos = obtener_establecimientos($response);
        $json_establecimientos = json_decode($establecimientos, true);

        $establecimientos_array = array();
        foreach ($json_establecimientos['establecimientos'] as $item) {
            $establecimientos_array[] = array(
                'id_establecimiento' => $item['id_establecimiento'],
                'Nombre' => $item['Nombre'],
                'Direccion' => $item['Direccion'],
                'Municipio' => $item['Municipio'],
                'Departamento' => $item['Departamento'],
                'Estatus' => $item['Estatus']
            );
        }

        // === ENSAMBLAR TODO EN UN SOLO JSON ===
        $resultado = array(
            array(
                'frases' => $frases_array,
                'establecimientos' => $establecimientos_array
            )
        );

        header('Content-Type: application/json');
        echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
    }
}
?>
