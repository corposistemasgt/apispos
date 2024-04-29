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
        $fecha=$_POST ['fecha'];
        $guid=$_POST ['guid'];
        $nit=$_POST ['nit'];
        date_default_timezone_set("America/Guatemala");
        $fecha=date('Y').'-'.date('m').'-'.date('d');
        $hora=date('H').':'.date('i').':'.date('s');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mynube.com/v1/fel/nullify-dte-json/test',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "estId": "'.$id.'",
        "nitCertificador": "108151654",
        "tipoDocumento": "FACT",
        "fechaEmisionDocumentoAnular": "'.$fecha.'",
        "fechaHoraAnulacion": "'.$fecha.'T'.$hora.'",
        "nitReceptor": "'.$nit.'",
        "motivoAnulacion": "Error en el detalle",
        "uuidDocumento": "'.$guid.'"
        }',CURLOPT_HTTPHEADER => array('Content-Type: application/json','X-Api-Key: '.$llave),));
        $response = curl_exec($curl);
        curl_close($curl);
        //echo $response; 
        echo '{
            "status": "ok",
            "body": {
                "numeroFactura": "A1B2C3D4-123456789",
                "serie": "A1B2C3D4",
                "folio": 123456789,
                "fechaEmision": "2021-01-02T12:00:00.000-06:00",
                "fechaCertificado": "2021-01-02T23:59:59.999-06:00",
                "uuidTransaccion": "275E2947-05C0-4CA8-BEC7-ADA67F1D7419",
                "uuidCertificado": "A1B2C3D4-3643-49B4-A294-16930801F9BE",
                "xmlCertificado": "<XML>",
                "message": "Documento anulado correctamente."
            }
        }';
    }
}

