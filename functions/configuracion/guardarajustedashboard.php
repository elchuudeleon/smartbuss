<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");

include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");

date_default_timezone_set("America/Bogota"); 

$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );

if(!isset($_SESSION)){ session_start(); }



$oLista = new Lista('dashboard');
$oLista->setFiltro("anio","=",date("Y"));
$oLista->setFiltro("mes","=",$datos["periodo"]);
$oLista->setFiltro("idEmpresa","=",$_SESSION["idEmpresa"]);
$aListas=$oLista->getLista();
unset($oLista);

foreach($aListas as $item){
$oItem=new Data("dashboard","idDashboard",$item["idDashboard"]); 
$oItem->eliminar(); 
unset($oItem);
}
$aData["idEmpresa"]=$_SESSION["idEmpresa"];
$aData["anio"]=date("Y");
$aData["mes"]=$datos["periodo"];
$aData["utilidad"]=$datos["utilidad"]==""?0:str_replace(",", ".",str_replace("$", "", str_replace(".", "", $datos["utilidad"])));
$aData["activo"]=$datos["activo"]==""?0:str_replace(",", ".",str_replace("$", "", str_replace(".", "", $datos["activo"])));
$aData["pasivo"]=$datos["pasivo"]==""?0:str_replace(",", ".",str_replace("$", "", str_replace(".", "", $datos["pasivo"])));
$aData["patrimonio"]=$datos["patrimonio"]==""?0:str_replace(",", ".",str_replace("$", "", str_replace(".", "", $datos["patrimonio"])));
$aData["bancos"]=$datos["banco"]==""?0:str_replace(",", ".",str_replace("$", "", str_replace(".", "", $datos["banco"])));
$aData["ica"]=$datos["ica"]==""?0:str_replace(",", ".",str_replace("$", "", str_replace(".", "", $datos["ica"])));
$aData["retencion"]=$datos["retencion"]==""?0:str_replace(",", ".",str_replace("$", "", str_replace(".", "", $datos["retencion"])));
$aData["iva"]=$datos["iva"]==""?0:str_replace(",", ".",str_replace("$", "", str_replace(".", "", $datos["iva"])));
$aData["seguridad"]=$datos["seguridad"]==""?0:str_replace(",", ".",str_replace("$", "", str_replace(".", "", $datos["seguridad"])));
$aData["proveedores"]=$datos["proveedor"]==""?0:str_replace(",", ".",str_replace("$", "", str_replace(".", "", $datos["proveedor"])));
$aData["cliente"]=$datos["cliente"]==""?0:str_replace(",", ".",str_replace("$", "", str_replace(".", "", $datos["cliente"])));

$oItem=new Data("dashboard","idDashboard"); 
foreach($aData  as $key => $value){
    $oItem->$key=$value; 
}
$oItem->guardar(); 
unset($oItem);


$oLista = new Lista('horas_extras');
$aLista=$oLista->getLista();
unset($oLista);

$msg=true; 

echo json_encode(array("msg"=>$msg,"lista"=>$aLista));
?>