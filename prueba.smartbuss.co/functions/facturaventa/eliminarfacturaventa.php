<?php

header('Content-type: application/json');

require_once("../../php/restrict.php");



include_once($CLASS . "data.php");

include_once($CLASS . "lista.php");



date_default_timezone_set("America/Bogota"); 


$idFactura=(isset($_REQUEST['idFactura'] ) ? $_REQUEST['idFactura'] : "" );




$oItem=new Data("factura_venta","idFacturaVenta",$idFactura); 
$oItem->eliminar(); 
unset($oItem);


$oItem=new Data("factura_venta_item","idFacturaVenta",$idFactura); 
$oItem->eliminar();
unset($oItem);
    
    


$msg=true; 


echo json_encode(array("msg"=>$msg));

?>