<?php

header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");
date_default_timezone_set("America/Bogota"); 


$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );


if(!isset($_SESSION)){ session_start(); }


$msg=true; 


if ($datos["cuentaContableTotalPagarCompra"]!="" and $datos["idCuentaContableTotalPagarCompra"]!=""  ) {
   

$aItemAuxiliar["concepto"]='1';
$aItemAuxiliar["idEmpresa"]=$datos["idEmpresa"];
$aItemAuxiliar["idEmpresaCuenta"]=$datos["idCuentaContableTotalPagarCompra"];

$aItemAuxiliar["tipoDocumento"]=$datos["tipoDocumentoTotalPagarCompra"];
$aItemAuxiliar["tipoFactura"]=$datos["naturalezaNuevaTotalPagarCompra"];  

$oItem=new Data("compra_cuenta_contable","idCompraCuentaContable"); 

    foreach($aItemAuxiliar  as $keya => $valuea){

        $oItem->$keya=$valuea; 

    }
    $msg=$oItem->guardar(); 
    // $idAuxiliar=$oItem->ultimoId();
    unset($oItem);
}


echo json_encode(array("msg"=>$msg));


?>