<?php
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        if(isset($_GET ['code']))
        { 
            require_once '../conexiones/'.$_GET['code'].'.php';
            
            $pdo = new Conexion();
            $query="select id_producto as id,codigo_producto as codigo,nombre_producto as nombre,
            nombre_linea as categoria,cantidad_stock as stock,valor1_producto as precio1,
            valor2_producto as precio2,valor3_producto as precio3 from productos,lineas,stock 
            where (productos.id_linea_producto=lineas.id_linea and productos.id_producto=
            stock.id_producto_stock and id_sucursal_stock=".$_GET ['idsucursal'].") 
            order by nombre";
            $sql = $pdo->prepare($query);
            $sql ->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            echo  json_encode($sql->fetchAll());     
            header ("HTTP/1.1 200 OK");            
            exit;  
        }    
    }
?>