<?php
require_once 'admin.php';
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        if( isset($_POST ['guid']) && isset($_POST ['numero']) && isset($_POST ['serie'])&& isset($_POST ['idfactura']))
        {
            date_default_timezone_set("America/Guatemala");
            $fecha=date("Y").'-'.date("n").'-'.date("j")." ".date("H").':'.date("i").':'.date("s");
            $guid=$_POST ['guid'];
            $numero=$_POST ['numero'];
            $serie=$_POST ['serie'];
            $idfactura=$_POST ['idfactura'];
            $codigo=$_POST ['codigo'];
            $vaucher=$_POST ['vaucher'];
            $pdo = new Conexion();
            $sql = $pdo->prepare("update tbfactura set fecha=:fecha,guid=:guid,numero=:numero,serie=:serie,vaucher=:vaucher,codigo=:codigo 
             where idfactura=:idfactura");
            $sql->bindValue(':fecha',$fecha);
            $sql->bindValue(':guid',$guid);
            $sql->bindValue(':serie',$serie);
            $sql->bindValue(':numero',$numero);
            $sql->bindValue(':idfactura',$idfactura);
            $sql->bindValue(':codigo',$codigo);
            $sql->bindValue(':vaucher',$vaucher);
            $sql->execute(); 
            echo json_encode(array("resultado"=>"true"));
        }
    }
?>