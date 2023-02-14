<?php 
header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");

date_default_timezone_set("America/Bogota"); 

$idEliminar = (isset($_REQUEST['id']) ? $_REQUEST["id"] : "" );

$oItem=new Data("inventario_inicial","idInventarioInicial",$idEliminar);
$aDatos=$oItem->getDatos(); 
$oItem->eliminar();
unset($oItem);


$oLista = new Lista('inventario_productos_movimientos');
$oLista->setFiltro("idEmpresa","=",$aDatos["idEmpresa"]);
$oLista->setFiltro("idProducto","=",$aDatos["idProducto"]);
$oLista->setFiltro("idBodega","=",$aDatos["idBodega"]);
$aMovimientos=$oLista->getLista();
$msg=true; 


foreach($aMovimientos as $iMovimiento){
    $oItem=new Data("inventario_productos_movimientos","idInventarioProductosMovimientos",$iMovimiento["idInventarioProductosMovimientos"]);
    $oItem->eliminar();
    unset($oItem);   
}

echo json_encode(array("msg"=>$msg));


?>