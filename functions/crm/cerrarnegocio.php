<?php

require_once("../../php/restrict.php");

include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");

date_default_timezone_set("America/Bogota");

$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );

$msg=true;

 
if(!isset($_SESSION)){ session_start(); }

$idActividad=$datos["idActividadCerrar"];
$creador = $_SESSION["idUsuario"];
$codigocliente=$datos["codigocliente"];

date_default_timezone_set("America/Bogota");


$aDatos["fechaCreacion"]=$datos["fecha"]; 
$aDatos["horaCreacion"]=$datos["hora"]; 
$aDatos["motivo"]=$datos["motivo"];
$aDatos["creador"]=$creador;
$aDatos["estado"]=$datos["cerrado"];

if ($datos["valorfinal"] != ""  ) {
	$aDatos["valor"]=str_replace("$", "", str_replace(".", "", $datos["valorfinal"]));
	
}
if ($datos["descripcionfinal"]!="") {
	$aDatos["descripcion"]=$datos["descripcionfinal"];
}


$oItem=new Data("actividades","idActividad",$idActividad); 
foreach($aDatos  as $key => $value){
$oItem->$key=$value; 
}
$msg=$oItem->guardar(); 
unset($oItem);

if($msg){
	$aDatosR["idNegocio"]=$idActividad; 
	$aDatosR["fechaRenovacion"]=$datos["renovacion"]; 
	$aDatosR["fechaAlarma"]=$datos["alarmaRenovacion"]; 
	 
	$oItem=new Data("negocio_renovacion","idNegocioRenovacion"); 
	foreach($aDatosR  as $keyR => $valueR){
	$oItem->$keyR=$valueR; 
	}
	$msg=$oItem->guardar(); 
	unset($oItem);
}

echo json_encode(array("msg"=>$msg));
 ?>
 