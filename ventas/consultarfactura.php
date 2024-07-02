<?php
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        if(isset($_GET ['guid']))
        { 
            include 'conexiones/'.$_GET['code'].'.php';
            
            $pdo = new Conexion();
            $query="select nombre_linea,nombre_producto,cantidad,precio_venta from facturas_ventas,detalle_fact_ventas,productos,lineas
            where lineas.id_linea =productos.id_linea_producto and productos.id_producto=detalle_fact_ventas.id_producto and 
            facturas_ventas.id_factura=detalle_fact_ventas.id_factura  and guid_factura=:guid";
            $sql = $pdo->prepare($query);
            $sql->bindValue(':guid',$_GET ['guid']);
            $sql ->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            echo  json_encode($sql->fetchAll());     
            header ("HTTP/1.1 200 OK");            
            exit;  
        }    
    }
?>