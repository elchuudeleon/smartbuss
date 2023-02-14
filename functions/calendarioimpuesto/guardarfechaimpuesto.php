<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");

include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");

date_default_timezone_set("America/Bogota"); 

$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );
$item  = (isset($_REQUEST['item'] ) ? $_REQUEST['item'] : "" );
$msg=true; 
$datos["fechaRegistro"]=date("Y-m-d H:i:s");
if($datos["tipoConfiguracion"]==2){
	$datos["fechaPago"]=0; 
}
$oItem=new Data("fecha_impuesto","idFechaImpuesto"); 
foreach($datos  as $key => $value){
    $oItem->$key=$value; 
}
$msg=$oItem->guardar(); 
$id=$oItem->ultimoId();
unset($oItem);
if($msg){
if($datos["tipoConfiguracion"]==2){
	foreach ($item as $key => $aIem) {
		$iItem["idFechaImpuesto"]=$id; 
		$iItem["digito"]=$key; 
		$iItem["diaPago"]=$aIem["digito"]; 
		$oItem=new Data("fecha_impuesto_digito","idFechaImpuestoDigito"); 
		foreach($iItem  as $key2 => $value){
		    $oItem->$key2=$value; 
		}
		$msg=$oItem->guardar(); 
		unset($oItem);
	}
}	
}




echo json_encode(array("msg"=>$msg));
?>