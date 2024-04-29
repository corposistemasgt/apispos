<?php
require_once 'admin.php';
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        if(isset($_GET ['nitemisor']) && isset($_GET ['esta']) && isset($_GET ['terminal']))
        {
            $nit=$_GET ['nitemisor'];
            $esta=$_GET ['esta'];
            $terminal=$_GET ['terminal'];
            $pdo = new Conexion();
            $sql = $pdo->prepare("select idfactura,nombre,nit,direccion,certificar,cuotas from tbfactura where nitemisor=:nit and establecimiento=:esta 
            and terminal=:terminal and guid='' order by idfactura desc limit 1");
            $sql->bindValue(':nit',$nit);
            $sql->bindValue(':esta',$esta);
            $sql->bindValue(':terminal',$terminal);
            $sql ->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $idfactura='';
            $nombre='';
            $nit='';
            $direccion='';
            $certi='';
            $cuotas='';
            foreach ($sql as $registro) 
            {
                $idfactura=$registro['idfactura'];  
                $nombre=$registro['nombre']; 
                $nit=$registro['nit']; 
                $direccion=$registro['direccion'];  
                $certi=$registro['certificar'];         
                $cuotas=$registro['cuotas']; 
            }  
            $encabezado=base64_encode(json_encode(array("idfactura"=>$idfactura,"nombre"=>$nombre,"nit"=>$nit,"direccion"=>$direccion,
            "certificar"=>$certi,"cuotas"=>$cuotas)));
            $sql = $pdo->prepare("select producto,cantidad,precio,descuento from tbdetalle where idfactura=:idfactura");
            $sql->bindValue(':idfactura',$idfactura);
            $sql ->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $items= base64_encode(json_encode($sql->fetchAll()));  
            echo json_encode(array("encabezado"=>$encabezado,"items"=>$items));
        }
        else
        {
            echo "faltan datos";
        }
    }
    else
    {
        echo "no es get";
    }
?>