<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 require_once 'admin.php';
if($_SERVER['REQUEST_METHOD']=='GET')
{
    if(isset($_GET ['nit']) && isset($_GET ['esta'])&& isset($_GET ['usuario']))
    { 
        $nit=$_GET['nit']; 
        $esta=$_GET['esta']; 
        $usuario=$_GET['usuario'];
        $pdo = new Conexion();
        $sql = $pdo->prepare("select idautorizacion from tbautorizacion where nit=:nit and establecimiento=:esta");
        $sql->bindValue(':nit',$nit);
        $sql->bindValue(':esta',$esta);
        $sql ->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $api= '{"api":';
        $api.=json_encode($sql->fetchAll()); 
        $api.=',';
        echo $api;
        $web='"web":';
        $query="select codigo,iduser,idtienda from tbsistema where nit=:nit and 
        establecimiento=:esta and usuario=:usuario";
        $sql = $pdo->prepare($query);
        $sql->bindValue(':nit',$nit);
        $sql->bindValue(':esta',$esta);
        $sql->bindValue(':usuario',$usuario);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $web.= json_encode($sql->fetchAll()); 
        $web.=',';
        echo $web;
        $nube='"nube":';
        $query="SELECT llave,estid,pachamama FROM `tbmynube` WHERE nit=:nit";
        $sql = $pdo->prepare($query);
        $sql->bindValue(':nit',$nit);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $nube.= json_encode($sql->fetchAll()); 
        $nube.=',';
        echo $nube;
        $codi='"codi":';
        $query="SELECT codigo FROM tbusuariopacha WHERE usuario=:usuario";
        $sql = $pdo->prepare($query);
        $sql->bindValue(':usuario',$usuario);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $codi.= json_encode($sql->fetchAll()); 
        $codi.='}';
        echo $codi;
    }
}