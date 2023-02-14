<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");
include_once($CLASS . "control.php");


$oControl=new Control();

date_default_timezone_set("America/Bogota"); 

$msg=true; 

$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );
$item  = (isset($_REQUEST['item'] ) ? $_REQUEST['item'] : "" );

if(!isset($_SESSION)){ session_start(); }

$idCotizacion=$datos["idCotizacion"];

$oLista=new Lista("cotizacion_item");
$oLista->setFiltro("idCotizacion","=",$datos["idCotizacion"]);
$cotizacionAntiguaItems=$oLista->getLista();
unset($oLista);

foreach ($cotizacionAntiguaItems as $key => $value) {
    $oItem=new Data("cotizacion_item","idCotizacionItem",$value["idCotizacionItem"]);
    $oItem->eliminar();
    unset($oItem);
}


$aDatos["fechaRegistro"]=$datos["fechaCotizacion"];
$aDatos["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
// $aDatos["idEmpresa"]=$datos["idEmpresa"]; 
$aDatos["fechaVencimientoCotizacion"]=$datos["fechaVencimientoCotizacion"]; 

// $aDatos["idCliente"]=$datos["cliente"]; 


$aDatos["observaciones"]=$datos["observaciones"]; 
$aDatos["subtotal"]=str_replace("$", "", str_replace(".", "", $datos["subtotal"])); 
$aDatos["descuento"]=str_replace("$", "", str_replace(".", "", $datos["descuento"])); 
$aDatos["iva"]=str_replace("$", "", str_replace(".", "", $datos["iva"])); 
$aDatos["total"]=str_replace("$", "", str_replace(".", "", $datos["total"])); 
$aDatos["estado"]=1; 
$aDatos["numeroCotizacion"]=$datos["numeroCotizacion"]; 


$oItem=new Data("cotizacion","idCotizacion",$idCotizacion);
// $aCotizacionAntigua=$oItem->getDatos();

foreach($aDatos  as $keyC => $valueC){
    $oItem->$keyC=$valueC; 
}
$msg=$oItem->guardar(); 
unset($oItem);

if($msg){
   foreach ($item as $key => $value) {
        $aItem["idCotizacion"]=$idCotizacion; 
        $aItem["detalleProducto"]=$value["producto"]; 
        $aItem["idProductoServicio"]=$value["idProducto"]==""?0:$value["idProducto"]; 
        $aItem["descripcion"]=$value["descripcion"]; 
        $aItem["idUnidad"]=$value["idUnidad"]; 
        $aItem["cantidad"]=$value["cantidad"]; 
        $aItem["iva"]=$value["iva"]; 
        $aItem["valorUnitario"]=str_replace("$", "", str_replace(".", "", $value["valorUnitario"])); 
        $aItem["subtotal"]=str_replace("$", "", str_replace(".", "", $value["subtotal"])); 
        $aItem["total"]=str_replace("$", "", str_replace(".", "", $value["total"]));

        $oItem=new Data("cotizacion_item","idCotizacionItem"); 
        foreach($aItem  as $key => $value){
          $oItem->$key=$value; 
        }
        $msg=$oItem->guardar(); 
        unset($oItem);

    } 
}



echo json_encode(array("msg"=>$msg));

?>