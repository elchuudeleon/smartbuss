<?php

header('Content-type: application/json');

require_once("../../php/restrict.php");

include_once($CLASS . "data.php");

include_once($CLASS . "lista.php");

include_once($CLASS . "control.php");



$oControl=new Control();



date_default_timezone_set("America/Bogota"); 



$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );

// print_r($datos);
if(!isset($_SESSION)){ session_start(); }


if (!empty($_SESSION['idEmpresa'])) {
    $empresa=$_SESSION['idEmpresa'];
}



        $aItem["codigo"]=$datos["codigo"]; 

        $aItem["nombre"]=$datos["nombre"];

        $aItem["idGrupo"]=0;

        $aItem["idEmpresa"]=$empresa;

        $aItem["idUsuario"]=$_SESSION["idUsuario"];

        $aItem["fechaRegistro"]=date('Y-m-d H:i:s');

        $aItem["descripcion"]=$datos["nombre"];

        $aItem["tipo"]=1;  
        // if ($datos["inventario"]=="") {
        $aItem["inventario"]=0; 
        // }
        // if ($datos["inventario"]==1) {
        //     $aItem["inventario"]=1; 
        // }

        $aItem["costo"]=$datos["idCuentaCosto"];  
        $aItem["venta"]=$datos["idCuentaVenta"];  
        

        $oItem=new Data("producto_servicio","idProductoServicio"); 
        foreach($aItem  as $key => $value){
            $oItem->$key=$value; 
        }
        $oItem->guardar(); 
        $idProductoInventarioNuevo=$oItem->ultimoId();
        unset($oItem);

        


$msg=true; 

echo json_encode(array("msg"=>$msg));

?>