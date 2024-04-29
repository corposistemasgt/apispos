<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if($_SERVER['REQUEST_METHOD']=='POST')
{
    if(isset($_POST ['estid']) && isset($_POST ['llave']))
    {      
        $id=$_POST ['estid'];
        $llave=$_POST ['llave'];
        $nit=$_POST ['nit'];
        $nombre=$_POST ['nombre'];
        $total=$_POST ['total'];
        $detalles=$_POST ['detalles'];
        $curl = curl_init();
        date_default_timezone_set("America/Guatemala");
        $fecha=date('Y').'-'.date('m').'-'.date('d');
        $hora=date('H').':'.date('i').':'.date('s');
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mynube.com/v1/fel/certify-dte-json/test',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'
        {
            "estId":'.$id.',
            "tipoDocumento": "FACT",
            "nitCertificador": "108151654",
            "correosDestinatarios": "",
            "documento": 
            {
                "codigoCliente": "'.$nit.'",
                "datosCliente": 
                {
                    "nit": "'.$nit.'",
                    "nombre": "'.$nombre.'",
                    "direccion": "CIUDAD"
                },
                "fechaEmision": "'.$fecha.'T'.$hora.'-06:00",
                "valorTotal": '.$total.',
                "moneda": "GTQ",
                "aplicaIva": "Si",
                "tipoVenta": "Local",
                "tipoPago": "Contado",
                "idDocumentoErp": "Corpo'.$fecha.$hora.'",
                "detalle": ['.$detalles.']
            }
        }',
        CURLOPT_HTTPHEADER => array(
        'Content-Type: '.$id,
        'X-Api-Key: '.$llave),));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
        echo '{
            "estId":'.$id.',
            "tipoDocumento": "FACT",
            "nitCertificador": "108151654",
            "correosDestinatarios": "",
            "documento": 
            {
                "codigoCliente": "'.$nit.'",
                "datosCliente": 
                {
                    "nit": "'.$nit.'",
                    "nombre": "'.$nombre.'",
                    "direccion": "CIUDAD"
                },
                "fechaEmision": "'.$fecha.'T'.$hora.'-06:00",
                "valorTotal": '.$total.',
                "moneda": "GTQ",
                "aplicaIva": "Si",
                "tipoVenta": "Local",
                "tipoPago": "Contado",
                "idDocumentoErp": "Corpo'.$fecha.$hora.'",
                "detalle": ['.$detalles.']
            }
        }';
    }
}

