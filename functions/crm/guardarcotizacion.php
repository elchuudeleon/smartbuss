<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");
include_once($CLASS . "control.php");

$oControl=new Control();

$msg=true; 
date_default_timezone_set("America/Bogota"); 

$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );
$item  = (isset($_REQUEST['item'] ) ? $_REQUEST['item'] : "" );


if(!isset($_SESSION)){ session_start(); }

$oLista=new Lista("cotizacion");
$oLista->setFiltro("idEmpresa","=",$_SESSION["idEmpresa"]);
$aCotizacion=$oLista->getLista();
unset($oLista);

$ultNroCotizacion=explode('-', end($aCotizacion)["numeroCotizacion"]);
$nroCotizacion=intval($ultNroCotizacion[1])+1;

$aDatos["fechaRegistro"]=date("Y-m-d");
$aDatos["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
$aDatos["idEmpresa"]=$datos["idEmpresa"]; 
$aDatos["fechaVencimientoCotizacion"]=$datos["fechaVencimientoCotizacion"]; 
$aDatos["idCliente"]=$datos["cliente"]; 
$aDatos["observaciones"]=$datos["observaciones"]; 
$aDatos["subtotal"]=str_replace("$", "", str_replace(".", "", $datos["subtotal"])); 
$aDatos["descuento"]=str_replace("$", "", str_replace(".", "", $datos["descuento"])); 
$aDatos["iva"]=str_replace("$", "", str_replace(".", "", $datos["iva"])); 
$aDatos["total"]=str_replace("$", "", str_replace(".", "", $datos["total"])); 
$aDatos["estado"]=1; 
$aDatos["numeroCotizacion"]='COT'.date('Y').date('m').'-'.$nroCotizacion; 


$oItem=new Data("cotizacion","idCotizacion"); 
foreach($aDatos  as $key => $value){
    $oItem->$key=$value; 
}
$msg=$oItem->guardar(); 
$idCotizacion=$oItem->ultimoId(); 
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