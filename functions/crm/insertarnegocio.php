<?php

require_once("../../php/restrict.php");

include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");

date_default_timezone_set("America/Bogota");

$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );

 
$msg=true;
 
if(!isset($_SESSION)){ session_start(); }


$codigocliente=$datos["codigocliente"];

 

$aDatos["tipo"]="negocio";
$aDatos["fechaCreacion"]=$datos["fecha"]; 
$aDatos["horaCreacion"]=$datos["hora"]; 
$aDatos["vencimiento"]=$datos["vencimiento"];
$aDatos["encargado"]=$datos["empleadoEncargado"];
$aDatos["estado"]="negociacion";
$aDatos["motivo"]=trim($datos["negocio"]);

$aDatos["creador"]=$datos["creador"];
$aDatos["icono"]="5";
$aDatos["idCliente"]=$codigocliente;
$aDatos["valor"]=str_replace("$", "", str_replace(".", "", $datos["valor"]));


$oItem=new Data("actividades","idActividad"); 
foreach($aDatos  as $key => $value){
    $oItem->$key=$value; 
}
$msg=$oItem->guardar(); 
$codigoa=$oItem->ultimoId(); 
unset($oItem);

if($msg){
	if ($datos["empleadoEncargado"] != "" and $datos["vencimiento"] != "") {
		
		$tDatos["motivo"]="negociar con el cliente ".$datos["negocio"];

			$tDatos["encargado"]=$datos["empleadoEncargado"];
		
		$tDatos["vencimiento"]=$datos["vencimiento"];

		$tDatos["fechaCreacion"]=$datos["fecha"]; 
		$tDatos["horaCreacion"]=$datos["hora"]; 
		$tDatos["creador"]=$datos["creador"];
		$tDatos["tipo"]="tarea";
		$tDatos["icono"]="4";	
		$tDatos["estado"]="pendiente";
	    $tDatos["idCliente"]=$datos["codigocliente"];
		
		$oItem=new Data("actividades","idActividad"); 
	    foreach($tDatos  as $key => $value){
	        $oItem->$key=$value; 
	    }
	    $msg=$oItem->guardar(); 
	    $codigo=$oItem->ultimoId(); 
	    unset($oItem);

	}	

	if($msg){
		$cDatos["fechaUltimoContacto"]=$aDatos["fechaCreacion"];

		$oItem=new Data("t_clientes","codigoCliente",$codigocliente); 
		foreach($cDatos  as $key => $value){
		$oItem->$key=$value; 
		}
		$msg=$oItem->guardar(); 
		unset($oItem);
	}
		
}


echo json_encode(array("msg"=>$msg));
	
  // header('location: ../../negocios/',$codigocliente);
 ?>
 