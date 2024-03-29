<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");


include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");


date_default_timezone_set("America/Bogota"); 


$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );
$id  = (isset($_REQUEST['id'] ) ? $_REQUEST['id'] : "" );


$msg=true; 

$oItem=new Data("tercero","nit",$datos["nitAnterior"]); 
$aTercero=$oItem->getDatos();
unset($oItem);

if (empty($aTercero)) {
	$oItem=new Data("tercero","razonSocial",$datos["razonSocialAnterior"]); 
	$aTerceroR=$oItem->getDatos();
	unset($oItem);
	if (!empty($aTerceroR)) {
		$aDatosC["tipoPersona"]=$datos["tipoPersona"]; 
		$aDatosC["nit"]=$datos["nit"]; 
		$aDatosC["digitoVerificador"]=$datos["digitoVerificador"]==""?0:$datos["digitoVerificador"]; 
		$aDatosC["razonSocial"]=$datos["razonSocial"]; 
		$aDatosC["email"]=$datos["email"]; 
		$aDatosC["telefono"]=$datos["telefono"]; 
		$aDatosC["idDepartamento"]=$datos["idDepartamento"]; 
		$aDatosC["idCiudad"]=$datos["idCiudad"]; 
		$aDatosC["direccion"]=$datos["direccion"]; 
		if ($datos["checkProveedor"]==1) {
			$aDatosC["tipoTercero"]=4; 
		}
		if ($datos["checkProveedor"]!=1) {
			$aDatosC["tipoTercero"]=1; 
		}
		$oItem=new Data("tercero","idTercero",$aTerceroR["idTercero"]); 
		foreach($aDatosC  as $keyC => $valueC){
		$oItem->$keyC=$valueC; 
		}
		$msg=$oItem->guardar(); 
		unset($oItem);
	}
}
if($msg){
	if (!empty($aTercero)) {
			$aDatosC["tipoPersona"]=$datos["tipoPersona"]; 
			$aDatosC["nit"]=$datos["nit"]; 
			$aDatosC["digitoVerificador"]=$datos["digitoVerificador"]==""?0:$datos["digitoVerificador"]; 
			$aDatosC["razonSocial"]=$datos["razonSocial"]; 
			$aDatosC["email"]=$datos["email"]; 
			$aDatosC["telefono"]=$datos["telefono"]; 
			$aDatosC["idDepartamento"]=$datos["idDepartamento"]; 
			$aDatosC["idCiudad"]=$datos["idCiudad"]; 
			$aDatosC["direccion"]=$datos["direccion"]; 
			if ($datos["checkProveedor"]==1) {
				$aDatosC["tipoTercero"]=4; 
			}
			if ($datos["checkProveedor"]=='') {
				$aDatosC["tipoTercero"]=1; 
			}

			$oItem=new Data("tercero","idTercero",$aTercero["idTercero"]); 
			foreach($aDatosC  as $keyC => $valueC){
			$oItem->$keyC=$valueC; 
			}
			$msg=$oItem->guardar(); 

			unset($oItem);
	}

	if($msg){
		$oItem=new Data("empresa","nit",$datos["nitAnterior"]); 
		$aEmpresa=$oItem->getDatos();
		unset($oItem);


		if (empty($aEmpresa)) {
			$oItem=new Data("empresa","razonSocial",$datos["razonSocialAnterior"]); 
			$aEmpresaR=$oItem->getDatos();
			unset($oItem);
			if (!empty($aEmpresaR)) {
				$aDatosE["tipoPersona"]=$datos["tipoPersona"]; 
				$aDatosE["nit"]=$datos["nit"]; 
				$aDatosE["digitoVerificador"]=$datos["digitoVerificador"]==""?0:$datos["digitoVerificador"]; 
				$aDatosE["razonSocial"]=$datos["razonSocial"]; 
				$aDatosE["email"]=$datos["email"]; 
				$aDatosE["telefono"]=$datos["telefono"]; 
				$aDatosE["idDepartamento"]=$datos["idDepartamento"]; 
				$aDatosE["idCiudad"]=$datos["idCiudad"]; 
				$aDatosE["direccion"]=$datos["direccion"]; 

				$oItem=new Data("empresa","idEmpresa",$aEmpresaR["idEmpresa"]); 
				foreach($aDatosE  as $keyE => $valueE){
				$oItem->$keyE=$valueE; 
				}
				$msg=$oItem->guardar(); 
				unset($oItem);
			}
		}

		if($msg){
			if (!empty($aEmpresa)) {
				$aDatosE["tipoPersona"]=$datos["tipoPersona"]; 
				$aDatosE["nit"]=$datos["nit"]; 
				$aDatosE["digitoVerificador"]=$datos["digitoVerificador"]==""?0:$datos["digitoVerificador"]; 
				$aDatosE["razonSocial"]=$datos["razonSocial"]; 
				$aDatosE["email"]=$datos["email"]; 
				$aDatosE["telefono"]=$datos["telefono"]; 
				$aDatosE["idDepartamento"]=$datos["idDepartamento"]; 
				$aDatosE["idCiudad"]=$datos["idCiudad"]; 
				$aDatosE["direccion"]=$datos["direccion"]; 

				$oItem=new Data("empresa","idEmpresa",$aEmpresa["idEmpresa"]);
				foreach($aDatosE  as $keyE => $valueE){
				$oItem->$keyE=$valueE; 
				}
				$msg=$oItem->guardar(); 
				unset($oItem);
		}
		}
		
	}
	
}


echo json_encode(array("msg"=>$msg));

?>