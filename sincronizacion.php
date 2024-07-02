<?php
 $URL = 'https://poscorpo-cec9f-default-rtdb.firebaseio.com/';
 require "vendor/autoload.php";
 use Firebase\FirebaseLib;
 $firebase = new FirebaseLib($URL);
/*$codigo= "0001";
$descripcion= "Producto de Prueba";
$categoria= "Cafe";
$precio= "1"; */  


$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.mynube.com/v2/inv/get-products',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "estId": 2324
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'X-Api-Key: A2soPuzYrP3OrGe6pNqfv2LCyGjxwVJ78UASylEw'
  ),
));
$response = curl_exec($curl);
curl_close($curl);
$json_pss = json_decode($response, true);
$updates = [];
$indice=0;
if(strcmp($json_pss['status'],"ok")===0)
{
    foreach($json_pss['body']['productos'] as $pss_json)
    {
        if(strcmp($pss_json ['status'],"Activo")===0)
        {
            $indice++;
            $codigo= $pss_json ['codigo'];
            $descripcion= $pss_json ['descripcion'];
            $categoria= $pss_json ['categoria'];
            $precio= $pss_json ['precio'];
            $updates['/'.$indice.'/codigo'] = $codigo;
            $updates['/'.$indice.'/descripcion'] = $descripcion;
            $updates['/'.$indice.'/categoria'] = $categoria;
            $updates['/'.$indice.'/precio'] = doubleval($precio);
        }
       
    }
    $firebase->update('/pachamama/', $updates);
    echo json_encode(array("resultado"=>"true","detalle"=>"Sincronizacion Exitosa "));
}
else
{
      echo json_encode(array("resultado"=>"false",
      "detalle"=>"Error en la consulta a mynube: ".$json_pss['body']['message'][0]['description']));
}