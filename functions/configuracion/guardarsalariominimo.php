<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");

$msg=true; 

include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");
include_once($CLASS . "control.php");


date_default_timezone_set("America/Bogota"); 
$oControl=new Control();
$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );


$oLista = new Lista('salario_minimo');
$oLista->setFiltro("anio","=",$datos["anio"]);
$aListas=$oLista->getLista();
unset($oLista);

$datos["salarioMensual"]=str_replace(",",".",str_replace("$", "", str_replace(".", "", $datos["salarioMensual"])));
$datos["salarioDiario"]=str_replace(",",".",str_replace("$", "", str_replace(".", "", $datos["salarioDiario"])));
$datos["salarioHora"]=str_replace(",",".",str_replace("$", "", str_replace(".", "", $datos["salarioHora"])));


if(count($aListas)>0){
$oItem=new Data("salario_minimo","idSalarioMinimo",$aListas[0]["idSalarioMinimo"]); 
}else{
$oItem=new Data("salario_minimo","idSalarioMinimo"); 
}
foreach($datos  as $key => $value){
    $oItem->$key=$value; 
}
$msg=$oItem->guardar(); 
unset($oItem);



echo json_encode(array("msg"=>$msg));

?>