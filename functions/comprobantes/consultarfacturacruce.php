<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");


include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");
require_once("../../class/comprobantes.php"); 

// include_once("../../class/comprobantes.php");


$idTipoDocumento  = (isset($_REQUEST['idTipoDocumento'] ) ? $_REQUEST['idTipoDocumento'] : "" );
$idEmpresa  = (isset($_REQUEST['idEmpresa'] ) ? $_REQUEST['idEmpresa'] : "" );
$idCuenta  = (isset($_REQUEST['idCuenta'] ) ? $_REQUEST['idCuenta'] : "" );
$idTercero  = (isset($_REQUEST['idTercero'] ) ? $_REQUEST['idTercero'] : "" );


if ($idTipoDocumento==7) {

	$oLista=new Comprobantes();
	$aArray["idEmpresa"]=$idEmpresa; 
	$aArray["idCuenta"]=$idCuenta; 
	$aArray["idTercero"]=$idTercero;
	$facturas=$oLista->getFacturaCompra($aArray);
	unset($oLista);
}

if ($idTipoDocumento==15) {
	$oLista=new Comprobantes();
	$aArray["idEmpresa"]=$idEmpresa; 
	$aArray["idCuenta"]=$idCuenta; 
	$aArray["idTercero"]=$idTercero; 
	
	$facturas=$oLista->getFacturaVenta($aArray);
	unset($oLista);
}


echo json_encode(array("facturas"=>$facturas));

?>
