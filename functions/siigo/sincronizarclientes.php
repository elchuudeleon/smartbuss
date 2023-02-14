<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");

include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");


date_default_timezone_set("America/Bogota"); 

$aItem  = (isset($_REQUEST['itemCliente'] ) ? $_REQUEST['itemCliente'] : "" );
$items=json_decode($aItem); 
if(!isset($_SESSION)){ session_start(); }
$msg=true;
foreach($items as $item){
$valida=preg_match("/([A-Z])/", $item->identification);

 if($valida<1){
    
    $aDatos["tipoPersona"]=$item->tipoPersona=="Person"?1:2; 
    $aDatos["nit"]=$item->identification; 
    $aDatos["digitoVerificador"]=$item->digito==""?0:$item->digito; 
    $aDatos["razonSocial"]=$item->nombre; 
    $aDatos["email"]=$item->email; 
    $aDatos["telefono"]=$item->telefono; 
    $aDatos["direccion"]=$item->direccion; 
    $aDatos["periodoPago"]=$item->diasPago==""?1:$item->diasPago; 
    $aDatos["fechaRegistro"]=date("Y-m-d H:i:s"); 
    $aDatos["idUsuarioRegistra"]=$_SESSION["idUsuario"];
    $aDatos["idCiudad"]=904; 
    $aDatos["idDepartamento"]=27; 
    $aDatos["responsableIva"]=$item->iva; 
    $aDatos["tipoTercero"]=1; 
    
    $oItem=new Data("tercero","idTercero"); 
    foreach($aDatos  as $key => $value){
        $oItem->$key=$value; 
    }

    $msg=$oItem->guardar(); 
    $idTercero=$oItem->ultimoId(); 
    unset($oItem);

    if($msg){
        $oItem=new Data("tercero_empresa","idTerceroEmpresa");
        $oItem->idTercero=$idTercero; 
        $oItem->idEmpresa=$_SESSION["idEmpresa"]; 
        $msg=$oItem->guardar(); 
     }else{
        break; 
     }
 }
}


echo json_encode(array("msg"=>$msg));

?>