<?php
    require_once "funciones.php";
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        if(isset($_POST ['terminal']) && isset($_POST ['corpoconnect'])&& isset($_POST ['datos_cliente']) && isset($_POST ['detalle_factura'])
        && isset($_POST ['cuotas'])&& isset($_POST ['certificar']))
        { 
            $terminal=$_POST ['terminal'];
            $corpo=$_POST ['corpoconnect'];
            $cliente=$_POST ['datos_cliente'];
            $items=$_POST ['detalle_factura'];
            $certi=$_POST ['certificar'];
            $cuotas=$_POST ['cuotas'];
            $campos='';
            if(strcmp($terminal,'')==0)
            {
                $campos.=" terminal,";
            }
            if(strcmp($corpo,'')==0)
            {
                $campos.=" corpoconnect,";
            }
            if(strcmp($cliente,'')==0)
            {
                $campos.=" datos_cliente,";
            }
            if(strcmp($items,'')==0)
            {
                $campos.=" detalle_factura,";
            }
            if(strcmp($certi,'')==0)
            {
                $campos.=" certificar,";
            }
            if(strcmp($cuotas,'')==0)
            {
                $campos.=" cuotas,";
            }
            if(strcmp($campos,'')!==0)
            {
                $campos="Los siguientes campos no pueden ir vacios: ".trim($campos, ',');
                echo json_encode(array("resultado"=>"false","detalles"=>$campos)); 
            }
            else
            {   $array=verificar($corpo);
                $nitemisor='';
                $esta='';
                $nitemisor=$array["nit"];
                $esta=$array["esta"];
                if(strcmp($nitemisor,'')==0 ||strcmp($esta,'')==0)
                {
                    echo json_encode(array("resultado"=>"false","detalles"=>"Establecimiento no Autorizado"));   
                }
                else
                {
                    $cliente=base64_decode($cliente);
                    $array = json_decode($cliente, true);
                    $nombre='';
                    $nit='';
                    $direccion='';
                    $errorc='';
                    if(is_array($array))
                    {
                        foreach ($array as $value) 
                        {
                            $nombre=$value['nombre'];
                            $nit=$value['nit'];
                            $direccion=$value['direccion'];
                        }
                        if(strcmp($nombre,'')==0 )
                        {
                            $errorc.=" Falta el nombre del cliente,";
                        }
                        if(strcmp($nombre,'')==0 )
                        {
                            $errorc.=" Falta el nit del cliente,";
                        }
                        if(strcmp($direccion,'')==0 )
                        {
                            $errorc.=" Falta la direccion del cliente,";
                        }
                    }
                    else
                    {
                        $errorc.=" Cadena Json de Datos de Cliente Invalida,";
                    }
                    if(strcmp($errorc,'')!==0)
                    {
                        $erroc=trim($errorc, ',');
                        echo json_encode(array("resultado"=>"false","detalles"=>$erroc)); 
                    }
                    else
                    {
                        $items=base64_decode($items);
                        $lista= json_decode($items, true);
                        $errori='';
                        if(is_array($lista))
                        {
                             $a=1;
                            foreach ($lista as $value) 
                            {
                                if(strcmp($value['producto'],'')==0)
                                {
                                    $errori.=" El nombre del producto ".$a." no puede ir vacio,";
                                }
                                if(strcmp($value['cantidad'],'')==0)
                                {
                                    $errori.=" La cantidad del producto ".$a." no puede ir vacio,";
                                }
                                if(strcmp($value['cantidad'],'0')==0)
                                {
                                    $errori.=" La cantidad del producto ".$a." no puede ser 0,";
                                }
                                if(strcmp($value['precio'],'')==0)
                                {
                                    $errori.=" El precio del producto ".$a." no puede ir vacio,";
                                }
                                if(strcmp($value['precio'],'0')==0)
                                {
                                    $errori.=" El precio del producto ".$a." no puede ser 0,";
                                }
                                if(strcmp($value['descuento'],'')==0)
                                {
                                    $errori.=" El descuento del producto ".$a." no puede ir vacio,";
                                }
                                $a++;
                            }
                            if(strcmp($errori,'')!==0)
                            {
                                $erroi=trim($errori, ',');
                                echo json_encode(array("resultado"=>"false","detalles"=>$erroi)); 
                            }
                            else
                            {
                                date_default_timezone_set("America/Guatemala");
                                $fecha=date("Y").date("n").date("j").date("H").date("i").date("s");
                                $cod=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10); 
                                $token=base64_encode($nit.$terminal.$fecha.$cod);
                                
                                require_once('conexiones/admin.php');
                                $pdo = new Conexion();
                                $sql = $pdo->prepare("insert into tbfactura (nombre,nit,guid,serie,numero,direccion,token,nitemisor,
                                establecimiento,terminal,certificar,cuotas) values(:nombre,:nit,'','','',:dir,:token,:nite,:esta,:te,:certificar,
                                :cuotas)");
                                $sql->bindValue(':nombre',$nombre);
                                $sql->bindValue(':nit',$nit);
                                $sql->bindValue(':dir',$direccion);
                                $sql->bindValue(':token',$token);
                                $sql->bindValue(':nite',$nitemisor);
                                $sql->bindValue(':esta',$esta);
                                $sql->bindValue(':te',$terminal);
                                $sql->bindValue(':certificar',$certi);
                                $sql->bindValue(':cuotas',$cuotas);
                                $sql ->execute();
                                $idfactura=$pdo-> lastInsertId();
                                foreach ($lista as $value) 
                                {
                                    $sql = $pdo->prepare("insert into tbdetalle (producto,cantidad,precio,descuento,idfactura) 
                                        values(:producto,:cantidad,:precio,:descuento,:idfactura)");
                                    $sql->bindValue(':producto',$value['producto']);
                                    $sql->bindValue(':cantidad',$value['cantidad']);
                                    $sql->bindValue(':precio',$value['precio']);
                                    $sql->bindValue(':descuento',$value['descuento']);
                                    $sql->bindValue(':idfactura',$idfactura);
                                    $sql ->execute();
                                }
                                echo json_encode(array("resultado"=>"true","detalles"=>"Insercion Existosa","token"=>$token));
                            }
                           
                        }
                        else
                        {
                            echo json_encode(array("resultado"=>"false","detalles"=>"Cadena Json de Detalles de Factura Invalida","token"=>"")); 
                        }
                    }                          
                }          
            }      
        }
        else
        {
            $campos="Faltan los siguientes campos: ";
            if(isset($_POST ['terminal'])==false)
            {
                $campos.=" terminal,";
            }
            if(isset($_POST ['corpoconnect'])==false)
            {
                $campos.=" corpoconnect,";
            }
            if(isset($_POST ['datos_cliente'])==false)
            {
                $campos.=" datos_cliente,";
            }
            if(isset($_POST ['detalle_factura'])==false)
            {
                $campos.=" detalle_factura,";
            }
            if(isset($_POST ['cuotas'])==false)
            {
                $campos.=" cuotas,";
            }
            if(isset($_POST ['certificar'])==false)
            {
                $campos.=" certificar,";
            }
            $campos=trim($campos, ',');
            echo json_encode(array("resultado"=>"false","detalles"=>$campos,"token"=>""));    
        }
    }
    else
    {
        echo json_encode(array("resultado"=>"false","detalles"=>"Metodo Incorrecto usa un POST","token"=>"")); 
    }
?>