<?php
    function verificar_login($res)
    {
        $response = $res;
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        $xml = new SimpleXMLElement($response);
        $bodys = $xml->xpath('//soapBody')[0];
        $array = json_decode(json_encode((array)$bodys));             
        $n=$array->{'RequestTransactionResponse'}; 
        $n=json_encode($n);
        $r = json_decode($n);
        $r=$r->{'RequestTransactionResult'}; 
        $s=json_encode($r);
        $s = json_decode($s);
        $p=$s;
        $s=$s->{'Response'}; 
        $s=json_encode($s);
        $s = json_decode($s);
        $resultado=$s->{'Result'};
        if(strcmp($resultado,"true")==0)
        {
            $d=$p->{'ResponseData'}; 
            $d=json_encode($d);
            $d = json_decode($d);
            $res=$d->{'ResponseData1'};
            $res=base64_decode($res);
            $c=json_encode($res);
            $c = json_decode($c);
            $obj = json_decode($c);
            $requestor=$obj->{'guid_cuenta'};
            return $requestor;
        }
        else
        {
            echo json_encode(array("resultado"=>"Credenciales Incorrectas","requestor"=>""));
           
            return "";
        }
        
    }
    function obtener_nit($res)
    {
        $response = $res;
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
    
        $xml = new SimpleXMLElement($response);
        $bodys = $xml->xpath('//soapBody')[0];
        echo $bodys;
     
        $array = json_decode(json_encode((array)$bodys));             
        $n=$array->{'getNITResponse'}; 
        $n=json_encode($n);
        $r = json_decode($n);
        $r=$r->{'getNITResult'}; 
        $s=json_encode($r);
        $s = json_decode($s);
        $p=$s;
        $s=$s->{'Response'}; 
        $s=json_encode($s);
        $s = json_decode($s);
        $resultado=$s->{'Result'};
        if(strcmp($resultado,"true")==0)
        {
            $nombre=$s->{'nombre'};
            $nit=$s->{'NIT'};
            echo json_encode(array("resultado"=>"true","nombre"=>$nombre,"nit"=>$nit),JSON_UNESCAPED_UNICODE);
        }
        else
        {
            echo json_encode(array("resultado"=>"false","nombre"=>"","nit"=>""));
            return "";
        }
        
    }
    function obtener_cui($res)
    {
        $response = $res;
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
    
        $xml = new SimpleXMLElement($response);
        $bodys = $xml->xpath('//soapBody')[0];
        $array = json_decode(json_encode((array)$bodys));             
        $n=$array->{'RequestTransactionResponse'}; 
        $n=json_encode($n);
        $r = json_decode($n);
        $r=$r->{'RequestTransactionResult'}; 
        $s=json_encode($r);
        $s = json_decode($s);
        $p=$s;
        $s=$s->{'Response'}; 
        $s=json_encode($s);
        $s = json_decode($s);
        $resultado=$s->{'Result'};
        if(strcmp($resultado,"true")==0)
        {
            $d=$p->{'ResponseData'}; 
            $d=json_encode($d);
            $d = json_decode($d);
            $res=$d->{'ResponseData1'};
            $c=json_encode($res);
            $c = json_decode($c);
            $c='['.$c.']';
            $c = json_decode($c,true);
            $nombre='';
            $cui='';
            foreach ($c as $value) {
                $cui= $value['CUI']; 
                $nombre= $value['nombre'];   
               
            }
            echo json_encode(array("resultado"=>"true","nombre"=>$nombre,"nit"=>$cui),JSON_UNESCAPED_UNICODE); 
        }
        else
        {
            echo json_encode(array("resultado"=>"false","nombre"=>"","nit"=>""));        
        }
        
    }

    function obtener_frases($res)
    {
        $response = $res;
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
    
        $xml = new SimpleXMLElement($response);
        $bodys = $xml->xpath('//soapBody')[0];
        $array = json_decode(json_encode((array)$bodys));             
        $n=$array->{'RequestTransactionResponse'}; 
        $n=json_encode($n);
        $r = json_decode($n);
        $r=$r->{'RequestTransactionResult'}; 
        $s=json_encode($r);
        $s = json_decode($s);
        $p=$s;
        $s=$s->{'Response'}; 
        $s=json_encode($s);
        $s = json_decode($s);
        $resultado=$s->{'Result'};
        if(strcmp($resultado,"true")==0)
        {
            $d=$p->{'ResponseData'}; 
            $d=json_encode($d);
            $d = json_decode($d);
            $res=$d->{'ResponseData1'};
            $c=json_encode($res);
            $c = json_decode($c);
            $c='{"frases":'.$c.'}';
            return $c;
        }
        else
        {
            echo json_encode(array("resultado"=>"Datos Incorrectos"));
            return "";
        }
        
    }

    function obtener_establecimientos($res)
    {
        $response = $res;
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        $xml = new SimpleXMLElement($response);
        $bodys = $xml->xpath('//soapBody')[0];
        $array = json_decode(json_encode((array)$bodys));             
        $n=$array->{'RequestTransactionResponse'}; 
        $n=json_encode($n);
        $r = json_decode($n);
        $r=$r->{'RequestTransactionResult'}; 
        $s=json_encode($r);
        $s = json_decode($s);
        $p=$s;
        $s=$s->{'Response'}; 
        $s=json_encode($s);
        $s = json_decode($s);
        $resultado=$s->{'Result'};
        if(strcmp($resultado,"true")==0)
        {
            $d=$p->{'ResponseData'}; 
            $d=json_encode($d);
            $d = json_decode($d);
            $res=$d->{'ResponseData1'};
            $c=json_encode($res);
            $c = json_decode($c);
            $c='{"establecimientos":'.$c.'}';
            return $c;
        }
        else
        {
            echo json_encode(array("resultado"=>"Datos Incorrectos"));
            return "";
        }
        
    }

    function cliente($code,$nit,$nombre)
    {
        if(strcmp($nit,'CF')==0||strcmp($nit,'cf')==0||strcmp($nit,'cF')==0||strcmp($nit,'Cf')==0)
        {
            return 0;
        }
        else
        {
            $idcliente="";
            require_once('conexiones/'.$code.'.php');
            $pdo = new Conexion();
            $sql = $pdo->prepare("select id_cliente from clientes where fiscal_cliente=:nit");
            $sql->bindValue(':nit',$nit);
            $sql ->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            foreach ($sql as $registro) 
            {
                $idcliente=$registro['id_cliente'];
            }  
            if(strcmp($idcliente,"")==0)
            {
                date_default_timezone_set("America/Guatemala");
                $fecha=date("Y").'-'.date("n").'-'.date("j")." ".date("H").':'.date("i").':'.date("s");
                $sql="insert into clientes (nombre_cliente,fiscal_cliente,telefono_cliente,email_cliente,direccion_cliente,status_cliente,
                date_added,id_perfil) values (:nombre,:nit,'','','',1,:fecha,1)";
                $stmt=$pdo->prepare($sql);
                $stmt->bindValue(':nombre',$nombre);
                $stmt->bindValue(':nit',$nit);
                $stmt->bindValue(':fecha',$fecha); 
                $stmt->execute();
                $idcliente=$pdo-> lastInsertId();              
            }
            return $idcliente;
        }
       
    }
    function numerotrans($code)
    {
        require_once( 'conexiones/'.$code.'.php');
        $pdo = new Conexion();  
        $sql = $pdo->prepare("SELECT RIGHT(num_trans_factura,6) as trans FROM facturas_ventas
        ORDER BY trans DESC LIMIT 1");
        $sql ->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($sql as $registro) 
        {
            $trans=$registro['trans'];
        } 
        $int_value = (int) $trans;
        $trans=$int_value+1;
        $trans='T'.$trans;         
        return $trans;
    }
    function numerofactura($code,$idsucursal)
    {
        $factura;
        require_once( 'conexiones/'.$code.'.php');
        $pdo = new Conexion();  
        $sql = $pdo->prepare("SELECT RIGHT(numero_factura,6) as factura FROM facturas_ventas WHERE id_comp_factura=1 
        AND id_sucursal = '$idsucursal' ORDER BY factura DESC LIMIT 1");
        $sql ->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($sql as $registro) 
        {
            $factura=$registro['factura']+1;
        }        
        return $idsucursal.'A2018'.$factura;
       
    }
    function verificar($corpo)
    {
            
            require_once('conexiones/admin.php');
            $pdo = new Conexion();
            $sql = $pdo->prepare("select nit,establecimiento from tbautorizacion where code=:corpo");
            $sql->bindValue(':corpo',$corpo);
            $sql ->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $nit="";
            $esta="";
            foreach ($sql as $registro) 
            {
                $nit=$registro['nit'];
                $esta=$registro['establecimiento'];
            }  
            $array = [
                "nit" => $nit,
                "esta" => $esta,
            ];
            return $array;
    }
?>