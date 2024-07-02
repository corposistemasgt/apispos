<?php
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        if(isset($_GET ['idusuario']) && isset($_GET ['code']))
        {    
            require_once '../conexiones/'.$_GET['code'].'.php';
            $pdo = new Conexion();
            $sql = $pdo->prepare("update tarjetas set estado=1 where estado=0 and idusuario=:idusuario");
            $sql->bindValue(':idusuario',$_GET ['idusuario']);
            $sql ->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            echo  json_encode($sql->fetchAll());     
            header ("HTTP/1.1 200 OK");            
            exit;  
        }
    }
?>