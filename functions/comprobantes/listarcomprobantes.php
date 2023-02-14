<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");
include_once($CLASS . "control.php");

$oControl=new Control();

require_once("../../class/comprobantes.php"); 


$fechaInicio  = (isset($_REQUEST['fechaInicio'] ) ? $_REQUEST['fechaInicio'] : "" );
$fechaFinal  = (isset($_REQUEST['fechaFin'] ) ? $_REQUEST['fechaFin'] : "" );

$oComprobantes=new Comprobantes(); 


$aFiltro["fechaInicio"]=$fechaInicio; 
$aFiltro["fechaFinal"]=$fechaFinal; 
$aComprobante=$oComprobantes->getComprobantesEmpresas($aFiltro); 

foreach($aComprobante as $index => $iComprobante){
	$aEncript['cadena']=$iComprobante['idComprobante'];
    $id=$oControl->encriptar($aEncript); 
	$aComprobante[$index]["idEncript"]=$id; 
	$aComprobante[$index]["saldoDebito"]="$".number_format($iComprobante["saldoDebito"],2,",","."); 
	$aComprobante[$index]["saldoCredito"]="$".number_format($iComprobante["saldoCredito"],2,",","."); 
}
echo json_encode(array("aComprobante"=>$aComprobante));

?>


