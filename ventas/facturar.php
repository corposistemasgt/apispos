<?php
    require_once "../funciones.php";
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        if(isset($_POST ['code']) && isset($_POST ['cliente'])&& isset($_POST ['nit'])&& isset($_POST ['idvendedor'])&& isset($_POST ['monto'])
        && isset($_POST ['idtienda'])&& isset($_POST ['serie'])&& isset($_POST ['guid'])&& isset($_POST ['numero'])&& isset($_POST ['tipo'])
        && isset($_POST ['totaliva']) && isset($_POST ['items'])&& isset($_POST ['tarjeta']))
        { 
            $code=$_POST['code'];
            date_default_timezone_set("America/Guatemala");
            $fecha=date("Y").'-'.date("n").'-'.date("j")." ".date("H").':'.date("i").':'.date("s");
            $nit=$_POST['nit'];
            $cliente=$_POST['cliente'];
            $idvendedor=$_POST['idvendedor'];
            $monto=$_POST['monto'];
            $idtienda=$_POST['idtienda'];
            $serie=$_POST['serie'];
            $tarjeta=$_POST['tarjeta'];
            $certi=$_POST['guid'];
            $numero=$_POST['numero'];
            $tipo=$_POST['tipo'];
            $totaliva=$_POST['totaliva'];
            $items=$_POST['items'];
            $trans=numerotrans($code);  
            $numero_factura=numerofactura($code,$idtienda);
            $idcliente=cliente($code,$nit,$cliente);
            require_once '../conexiones/'.$code.'.php';
            $pdo = new Conexion();
            $sql="insert into facturas_ventas (numero_factura,fecha_factura,id_cliente,id_vendedor,condiciones,monto_factura,estado_factura,id_users_factura,
            dinero_resibido_fac,id_sucursal,id_comp_factura,num_trans_factura,factura_nombre_cliente,factura_nit_cliente,serie_factura,guid_Factura,
            numero_certificacion,tipodocumento,fechacertificacion,totaliva,fecha_emision,estado_documento,num_cheque,idcierre) values
            (:numero_factura,:fechafactura,:idcliente,:idvendedor,:condiciones,:monto,:estado,:iduser,:dinero,:idsucursal,:idcompra,:numerotrans,:cliente,:nit,:serie,
            :certificacion,:numero,:tipo,:fechacerti,:iva,:fecha,:estadodoc,:cheque,:idcierre)";
            $stmt=$pdo->prepare($sql);
            $stmt->bindValue(':numero_factura',$numero_factura);
            $stmt->bindValue(':fechafactura',$fecha);
            $stmt->bindValue(':idcliente',$idcliente);
            $stmt->bindValue(':idvendedor',$idvendedor);
            $stmt->bindValue(':condiciones',$tarjeta);
            $stmt->bindValue(':monto',$monto);
            $stmt->bindValue(':estado',"1"); 
            $stmt->bindValue(':iduser',$idvendedor); 
            $stmt->bindValue(':dinero',$monto); 
            $stmt->bindValue(':idsucursal',$idtienda); 
            $stmt->bindValue(':idcompra',"1"); 
            $stmt->bindValue(':numerotrans',$trans); 
            $stmt->bindValue(':cliente',$cliente); 
            $stmt->bindValue(':nit',$nit); 
            $stmt->bindValue(':serie',$serie); 
            $stmt->bindValue(':certificacion',$certi); 
            $stmt->bindValue(':numero',$numero); 
            $stmt->bindValue(':tipo',$tipo); 
            $stmt->bindValue(':fechacerti',$fecha); 
            $stmt->bindValue(':iva',$totaliva); 
            $stmt->bindValue(':fecha',$fecha); 
            $stmt->bindValue(':estadodoc',"activo"); 
            $stmt->bindValue(':cheque',"");
            $stmt->bindValue(':idcierre',"0"); 
            $stmt->execute();
            $idfactura=$pdo-> lastInsertId(); 
            $res=base64_decode($items);
            
            $array = json_decode($res, true);
            foreach ($array as $value) {
                $idproducto=$value['idproducto'];
                $cantidad=$value['cantidad'];
                $precio=$value['precio'];
                $sub=$cantidad*$precio;
                if(strcmp($idproducto,'0')==0)
                {
                    $nombre=$value['producto'];
                    $sql="insert into productoslibres (idfactura,producto,cantidad,precio) 
                    values(:idfactura,:producto,:cantidad,:precio)";
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindValue(':idfactura',$idfactura);
                    $stmt->bindValue(':producto',$nombre); 
                    $stmt->bindValue(':cantidad',$cantidad); 
                    $stmt->bindValue(':precio',$precio); 
                    $stmt->execute();
                }
                else
                {
                    $sql="insert into detalle_fact_ventas (id_factura,numero_factura,id_producto,cantidad,desc_venta,precio_venta,importe_venta) 
                    values(:idfactura,:numero,:idproducto,:cantidad,0,:venta,:sub)";
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindValue(':idfactura',$idfactura);
                    $stmt->bindValue(':numero',$numero_factura);
                    $stmt->bindValue(':idproducto',$idproducto); 
                    $stmt->bindValue(':cantidad',$cantidad); 
                    $stmt->bindValue(':venta',$precio); 
                    $stmt->bindValue(':sub',$sub); 
                    $stmt->execute();
                }
                
             }
             echo json_encode(array("resultado"=>"Venta Exitosa")); 
        }
    }
?>