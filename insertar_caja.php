<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'admin.php';
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        if(isset($_GET ['pass']) && isset($_GET ['nit']) )
        {
            $pass=$_GET ['pass'];
            $nit=$_GET ['nit'];
            $pdo = new Conexion();
            date_default_timezone_set("America/Guatemala");
            $fecha=date("Y").date("n").date("j").' '.date("H").date("i").date("s");
            echo $fecha;
            echo $pass;
            echo $nit;
            $sql = $pdo->prepare("insert into tbcajas(password,fecha,nit) values(:pass,:fecha,:nit)");
            $sql->bindValue(':pass',$pass );
            $sql->bindValue(':nit',$nit );
            $sql->bindValue(':fecha',$fecha );
            $sql ->execute();
        }
        else
        {
            $campos="Falta el campo de password o el nit";
            echo json_encode(array("resultado"=>"false")); 
        }
    }
    else
    {
        echo json_encode(array("resultado"=>"false")); 
    }