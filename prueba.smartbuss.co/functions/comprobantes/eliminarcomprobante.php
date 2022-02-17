<?php

header('Content-type: application/json');

require_once("../../php/restrict.php");



include_once($CLASS . "data.php");

include_once($CLASS . "lista.php");



date_default_timezone_set("America/Bogota"); 


$idEliminar=(isset($_REQUEST['idEliminar'] ) ? $_REQUEST['idEliminar'] : "" );




$oItem=new Data("comprobante","idComprobante",$idEliminar); 
$oItem->eliminar(); 
unset($oItem);


$oLista=new Lista("comprobante_items");
$oLista->setFiltro("idComprobante","=",$idEliminar);
$comprobanteItems=$oLista->getLista();
unset($oLista);
if (!empty($comprobanteItems)) {
	foreach ($comprobanteItems as $key => $value) {
	$oItem=new Data("comprobante_items","idComprobanteItem",$value["idComprobanteItem"]);
	$oItem->eliminar();
	unset($oItem);
	}
}


$oLista=new Lista("factura_venta_comprobante");
$oLista->setFiltro("idComprobante","=",$idEliminar);
$ventaComprobante=$oLista->getlista();
unset($oLista);
if (!empty($ventaComprobante)) {
	$idFactura=$ventaComprobante[0]['idFacturaVenta'];


	$oItem=new Data("factura_venta","idFacturaVenta",$idFactura); 
	$oItem->eliminar(); 
	unset($oItem);

	$oItem=new Lista("factura_venta_item");
	$oItem->setFiltro("idFacturaVenta","=",$idFactura); 
	$facturaVentaItem=$oItem->getLista();
	unset($oItem);

	foreach ($facturaVentaItem as $keym => $valuem) {
	$oItem=new Data("factura_venta_item","idFacturaVentaItem",$valuem["idFacturaVentaItem"]);
	$oItem->eliminar();
	unset($oItem);
	}
}

if (empty($ventaComprobante)) {
	$oLista=new Lista("factura_compra_comprobante");
	$oLista->setFiltro("idComprobante","=",$idEliminar);
	$compraComprobante=$oLista->getlista();
	unset($oLista);




	if (!empty($compraComprobante)) {
		$idFactura=$compraComprobante[0]['idFacturaCompra'];


		$oItem=new Data("factura_compra","idFacturaCompra",$idFactura); 
		$oItem->eliminar(); 
		unset($oItem);

		$oItem=new Lista("factura_compra_item");
		$oItem->setFiltro("idFacturaCompra","=",$idFactura); 
		$facturaCompraItem=$oItem->getLista();
		unset($oItem);

		foreach ($facturaCompraItem as $keym => $valuem) {
		$oItem=new Data("factura_compra_item","idFacturaCompraItem",$valuem["idFacturaCompraItem"]);
		$oItem->eliminar();
		unset($oItem);
		}
	}
}


$oItem=new Data("comprobante_recurrente","idComprobante",$idEliminar); 
$oItem->eliminar(); 
unset($oItem);
    


$msg=true; 


echo json_encode(array("msg"=>$msg));

?>