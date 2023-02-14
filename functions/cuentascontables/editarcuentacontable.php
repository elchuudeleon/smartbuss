<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");
date_default_timezone_set("America/Bogota"); 


$item  = (isset($_REQUEST['item'] ) ? $_REQUEST['item'] : "" );
$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );

// print_r($item);
$msg=true; 
if(!isset($_SESSION)){ session_start(); }
// print_r($item);

foreach ($item as $key => $value) {
    if ($value["cuenta"]!="") {
        $nombre=substr($value["cuenta"], 5);
    }
    if ($value["subcuenta"]!="") {
        $nombre=substr($value["subcuenta"], 5);
    }    


    if ($value["checksubcuenta"]!="") {
        
        $aCuenta["codigo"]=$value["subcuenta"];
        $aCuenta["denominacion"]=$value["descripcionSubcuenta"];
        $aCuenta["idCuenta"]=$value["idCuenta"];
        $nombre=$value["descripcionSubcuenta"];

        $oItem=new Data("subcuenta","idSubcuenta",$value["idSubcuenta"]); 
        foreach($aCuenta  as $keya => $valuea){
            $oItem->$keya=$valuea; 
        }
        $validateSubcuenta=$oItem->getDatos();

        if($validateSubcuenta["idEmpresa"]==0){
            $aCuenta["idEmpresa"]=$_SESSION["idEmpresa"];
            $oItem=new Data("subcuenta","idSubcuenta",$value["idSubcuenta"]); 
            foreach($aCuenta  as $keya => $valuea){
                $oItem->$keya=$valuea; 
            }
            $msg=$oItem->guardar(); 
        }else{
            if($validateSubcuenta["idEmpresa"]==$_SESSION["idEmpresa"]){
                $oItem=new Data("subcuenta","idSubcuenta",$value["idSubcuenta"]); 
            }else{
                $aCuenta["idEmpresa"]=$_SESSION["idEmpresa"];
                $oItem=new Data("subcuenta","idSubcuenta"); 
            }
            foreach($aCuenta  as $keya => $valuea){
                $oItem->$keya=$valuea; 
            }
            $msg=$oItem->guardar(); 
        }
        
        $idSubCuentaN=$oItem->ultimoId();
        unset($oItem);
        $value["idSubcuenta"]=$idSubCuentaN;
    }
    if($msg){
        if ($value["checkauxiliar"]!="") {
            
            $aItemAuxiliar["codigo"]=$value["idAuxiliar"];
            $aItemAuxiliar["denominacion"]=$value["auxiliar"];
            $aItemAuxiliar["idSubcuenta"]=$value["idSubcuenta"];
            $aItemAuxiliar["idEmpresa"]=$_SESSION["idEmpresa"];
            $nombre=$value["auxiliar"];
            
            $oItem=new Data("auxiliar","idAuxiliar",$value["idCAuxiliar"]); 
            foreach($aItemAuxiliar  as $keya => $valuea){
                $oItem->$keya=$valuea; 
            }

            $msg=$oItem->guardar(); 
            $idAuxiliar=$oItem->ultimoId();
            unset($oItem);

        }
        if($msg){
            if ($value["checkauxiliar"]=="") {
                if ($value["idAuxiliar"]!="") {
                    $idAuxiliar=$value["idAuxiliar"];
                }else{
                    $idAuxiliar='';
                }
            }
            if ($value["checksubauxiliar"]!="") {
                $aItemSubauxiliar["codigo"]=$value["idSubauxiliar"];
                $aItemSubauxiliar["denominacion"]=$value["subauxiliar"];
                $aItemSubauxiliar["idAuxiliar"]=$idAuxiliar;
                $aItemSubauxiliar["idEmpresa"]=$_SESSION["idEmpresa"];
                $nombre=$value["subauxiliar"];

                $oItem=new Data("subauxiliar","idSubauxiliar",$value["idCSubauxiliar"]); 

                foreach($aItemSubauxiliar  as $keys => $values){
                    $oItem->$keys=$values; 
                }
                $msg=$oItem->guardar(); 
                $idSubauxiliar=$oItem->ultimoId();
                unset($oItem);
            }

            if($msg){
                if ($value["idSubauxiliar"]!="") {
                    $idSubauxiliar=$value["idSubauxiliar"];
                }
                if ($value["idSubauxiliar"]=="") {
                    $idSubauxiliar='';
                }


                if ($value["checkcentrocosto"]==1) {
                    $centroCosto=1;
                }
                if ($value["checkcentrocosto"]=="") {
                    $centroCosto=0;
                }  

                $aItem["nombre"]=$nombre;
                $aItem["naturaleza"]=$value["naturaleza"];
                $aItem["codigoCuenta"]=substr($value["grupo"], 0, 2).substr($value["cuenta"], 0, 2).substr($value["subcuenta"], 0, 2).substr($value["idAuxiliar"], 0, 2).substr($value["idSubauxiliar"], 0, 2);
                $aItem["centroCosto"]=$centroCosto;
                $aItem["tipoCuenta"]=$value["tipoCuenta"];
                $aItem["idEmpresa"]=$_SESSION["idEmpresa"];
                $aItem["detalle"]=$value["detalle"];
                $aItem["tercero"]=$value["tercero"];
                $aItem["porcentajeRetencion"]=$value["porcentajeRetencion"];

                $aItemCuenta["centroCosto"]=$centroCosto;
                $aItemCuenta["naturaleza"]=$value["naturaleza"];
                $aItemCuenta["tipoCuenta"]=$value["tipoCuenta"];
                $aItemCuenta["detalle"]=$value["detalle"];
                $aItemCuenta["nombre"]=$nombre;
                $aItemCuenta["tercero"]=$value["tercero"];
                $aItemCuenta["porcentajeRetencion"]=$value["porcentajeRetencion"];

                $oItem=new Data("cuenta_contable","idCuentaContable",$datos["idCuentaContable"]);
                foreach($aItemCuenta  as $keycc => $valuecc){
                    $oItem->$keycc=$valuecc; 
                }

                $msg=$oItem->guardar(); 
                $idCuentaContable=$datos["idCuentaContable"];

                unset($oItem);
            }

        }
    }


}



echo json_encode(array("msg"=>$msg));

?>