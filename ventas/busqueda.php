<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        if(isset($_GET ['code']) && isset($_GET ['cadena']))
        { 
            require_once '../conexiones/'.$_GET['code'].'.php';
            
            $pdo = new Conexion();
            $cade=base64_decode($_GET['cadena']);
            $aKeyword = explode(" ",$cade ); 
            $query="select id_producto as id,codigo_producto as codigo,nombre_producto as nombre,nombre_linea as 
            categoria,cantidad_stock as stock,valor1_producto as precio1,valor2_producto as precio2,valor3_producto as 
            precio3 from productos,lineas,stock where (productos.id_linea_producto=lineas.id_linea and 
            productos.id_producto=stock.id_producto_stock and id_sucursal_stock=".$_GET ['idsucursal'].")";
            for($i = 0; $i < count($aKeyword); $i++) {
                if(!empty($aKeyword[$i])) {
                    $query .= " and (codigo_producto like '%".$aKeyword[$i]."%' or 
                    nombre_producto like '%".$aKeyword[$i]."%' or nombre_linea like '%".$aKeyword[$i]."%')";
                }
            }
            $query.=" limit 100";
            $sql = $pdo->prepare($query);
            $sql ->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            echo  json_encode($sql->fetchAll());     
            header ("HTTP/1.1 200 OK");            
            exit;  
        }    
    }
?>