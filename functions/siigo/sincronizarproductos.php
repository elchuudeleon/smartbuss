<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");

include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");


date_default_timezone_set("America/Bogota"); 

$item  = (isset($_REQUEST['itemProducto'] ) ? $_REQUEST['itemProducto'] : "" );

if(!isset($_SESSION)){ session_start(); }


foreach($item as $item){
    $aDatos["idUsuario"]=$_SESSION["idUsuario"]; 
    $aDatos["fechaRegistro"]=date("Y-m-d H:i:s"); 
    $aDatos["nombre"]=$item["nombre"]; 
    $aDatos["idEmpresa"]=$_SESSION["idEmpresa"]; 
    $aDatos["codigo"]=$item["codigo"]; 
    $aDatos["tipo"]=$item["tipo"]=="Servicios"?2:1; 


    $oItem=new Data("producto_servicio","idProductoServicio"); 
    foreach($aDatos  as $key => $value){
        $oItem->$key=$value; 

    }
    $msg=$oItem->guardar(); 
    $idProducto=$oItem->ultimoId(); 
    unset($oItem);

    if(!$msg){
        break; 
    }
}




// foreach ($item as $keyP => $valueP) {
//     $aItemP["idProducto"]=$idProducto;
//     $aItemP["idEmpresa"]=$datos["idEmpresa"]; 
//     $aItemP["idEmpresaCuenta"]=$valueP["idCuentaContable"];
//     if ($keyP==0) {
//         $aItemP["tipoDocumento"]=$datos["tipoDocumentoProductoCompra"];
//         $aItemP["tipoFactura"]="compra";

//     }
//     if ($keyP==1) {
//         $aItemP["tipoDocumento"]=$datos["tipoDocumentoProductoVenta"];
//         $aItemP["tipoFactura"]="venta";
//     }

//     if ($valueP["idCuentaContable"]!='') {
    
//     $oItem=new Data("producto_cuenta_contable","idProductoCuentaContable"); 
//             foreach($aItemP  as $keyPR => $valuePR){
//                 $oItem->$keyPR=$valuePR; 
//             }
//             $oItem->guardar(); 
//             unset($oItem);

//     }
// }


echo json_encode(array("msg"=>$msg));

?>