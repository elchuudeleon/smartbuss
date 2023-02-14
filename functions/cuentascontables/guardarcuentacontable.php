<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");
date_default_timezone_set("America/Bogota"); 


$aDatos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );

$msg=true; 
if(!isset($_SESSION)){ session_start(); }

$grupo=explode("-",str_replace(" ","",$aDatos["grupo"])); 
$codigoCuentaContable=$grupo[0]; 

$cuenta=explode("-",str_replace(" ","",$aDatos["cuenta"])); 
$codigoCuentaContable=$codigoCuentaContable.$cuenta[0]; 

if($aDatos["idSubcuenta"]!=""){
    $subcuenta=explode("-",str_replace(" ","",$aDatos["codigoSubcuenta"])); 
    $nombresubcuenta=explode("-",$aDatos["codigoSubcuenta"]); 
    
    $nombre=ltrim($nombresubcuenta[1], ' '); 
    $codigoCuentaContable=$codigoCuentaContable.$subcuenta[0];     
}else{
    $nombre=$aDatos["subcuenta"]; 
    $codigoCuentaContable=$codigoCuentaContable.str_pad($aDatos["codigoSubcuenta"], 2, "0", STR_PAD_LEFT); 
}

if($aDatos["checkauxiliar"]==1){
    $nombre=$aDatos["auxiliar"]; 
    $codigoCuentaContable=$codigoCuentaContable.str_pad($aDatos["idAuxiliar"], 2, "0", STR_PAD_LEFT); 
}
if($aDatos["checksubauxiliar"]==1){
    $nombre=$aDatos["subauxiliar"]; 
    $codigoCuentaContable=$codigoCuentaContable.str_pad($aDatos["cuentaSubauxiliar"], 2, "0", STR_PAD_LEFT); 
}

$data["codigoCuenta"]=$codigoCuentaContable;
$data["nombre"]=$nombre;
$data["naturaleza"]=$aDatos["naturaleza"];
$data["tipoCuenta"]=$aDatos["tipoCuenta"];
$data["detalle"]=$aDatos["detalle"];
$data["tercero"]=$aDatos["tercero"];
$data["idEmpresa"]=$aDatos["idEmpresa"];
$data["centroCosto"]=$aDatos["checkcentrocosto"];
$data["porcentajeRetencion"]=$aDatos["porcentajeRetencion"];


$oItem=new Data("cuenta_contable","idCuentaContable");
foreach($data as $key => $value){
    $oItem->$key=$value; 
}
$msg=$oItem->guardar();
$idCuenta=$oItem->ultimoId();
unset($oItem);

if($aDatos["idCuenta"]==""){
    // $cuenta=explode("-",str_replace(" ","",$aDatos["codigoSubcuenta"])); 
    // $nombrecuenta=explode("-",$aDatos["cuenta"]); 
    
    // $aSubcuenta["codigo"]=$cuenta[0]; 
    // $aSubcuenta["denominacion"]=ltrim($nombrecuenta[1], ' '); 
    // $aSubcuenta["idGrupo"]=$aDatos["idGrupo"]; 
    // $oItem=new Data("cuenta","idCuenta");
    // foreach($aSubcuenta as $key => $value){
    //     $oItem->$key=$value; 
    // }
    // $msg=$oItem->guardar();
    // $aDatos["idCuenta"]=$oItem->ultimoId();
    // unset($oItem);
}

if($msg){
   if($aDatos["checksubcuenta"]==1){
    
        if($aDatos["idSubcuenta"]==""&&$aDatos["checkauxiliar"]==1){
            $data["codigoCuenta"]=substr($codigoCuentaContable,0,6);
            $data["nombre"]=$aDatos["subcuenta"];
            $data["naturaleza"]=$aDatos["naturaleza"];
            $data["tipoCuenta"]=$aDatos["tipoCuenta"];
            $data["detalle"]=$aDatos["detalle"];
            $data["tercero"]=$aDatos["tercero"];
            $data["idEmpresa"]=$aDatos["idEmpresa"];
            $data["centroCosto"]=$aDatos["checkcentrocosto"];
            $data["porcentajeRetencion"]=$aDatos["porcentajeRetencion"];
            
            
            $oItem=new Data("cuenta_contable","idCuentaContable");
            foreach($data as $key => $value){
                $oItem->$key=$value; 
            }
            $msg=$oItem->guardar();
            $idCuentas=$oItem->ultimoId();
            unset($oItem);
            
            // $aSubcuenta["codigo"]=$aDatos["codigoSubcuenta"]; 
            // $aSubcuenta["denominacion"]=$aDatos["subcuenta"]; 
            // $aSubcuenta["idCuenta"]=$idCuentas; 
            // $oItem=new Data("subcuenta","idSubcuenta");
            // foreach($aSubcuenta as $key => $value){
            //     $oItem->$key=$value; 
            // }
            // $msg=$oItem->guardar();
            // $idSubcuenta=$oItem->ultimoId();
            // unset($oItem);
        }
        if($msg){
           // $aSubcuenta["codigo"]=$aDatos["codigoSubcuenta"]; 
           //  $aSubcuenta["denominacion"]=$aDatos["subcuenta"]; 
           //  $aSubcuenta["idCuenta"]=$idCuentas; 
           //  $oItem=new Data("subcuenta","idSubcuenta");
           //  foreach($aSubcuenta as $key => $value){
           //      $oItem->$key=$value; 
           //  }
           //  $msg=$oItem->guardar();
           //  $idSubcuenta=$oItem->ultimoId();
           //  unset($oItem); 

                if($aDatos["idSubcuenta"]==""&&$aDatos["checkauxiliar"]==0){
                    $aSubcuenta["codigo"]=$aDatos["codigoSubcuenta"]; 
                    $aSubcuenta["denominacion"]=$aDatos["subcuenta"]; 
                    $aSubcuenta["idCuenta"]=$aDatos["idCuenta"]; 
                    $aSubcuenta["idEmpresa"]=$aDatos["idEmpresa"]; 
                    $oItem=new Data("subcuenta","idSubcuenta");
                    foreach($aSubcuenta as $key => $value){
                        $oItem->$key=$value; 
                    }
                    $msg=$oItem->guardar();
                    $aDatos["idSubcuenta"]=$oItem->ultimoId();
                    unset($oItem); 
                }else if($aDatos["idSubcuenta"]!=""){
                    $oItem=new Data("subcuenta","idSubcuenta", $aDatos["idSubcuenta"]); 
                    $aValidateSubcuenta=$oItem->getDatos(); 
                    unset($oItem); 

                    $aSubcuentaCodigo=explode("-",str_replace(" ","",$aDatos["codigoSubcuenta"])); 
                    $aSubcuenta["codigo"]=$aSubcuentaCodigo[0]; 
                    if($aDatos["subcuenta"]==""){
                        $aDatos["subcuenta"]=explode("- ",$aDatos["codigoSubcuenta"])[1]; 
                    }
                    $aSubcuenta["denominacion"]=$aDatos["subcuenta"]; 
                    $aSubcuenta["idCuenta"]=$aDatos["idCuenta"]; 
                    $aSubcuenta["idEmpresa"]=$aDatos["idEmpresa"]; 
                    if($aValidateSubcuenta["idEmpresa"]!=0){
                        $oItem=new Data("subcuenta","idSubcuenta");
                    }else{
                        $oItem=new Data("subcuenta","idSubcuenta",$aDatos["idSubcuenta"]);
                    }
                    foreach($aSubcuenta as $key => $value){
                        $oItem->$key=$value; 
                    }
                    $msg=$oItem->guardar();
                    $aDatos["idSubcuenta"]=$oItem->ultimoId();
                    unset($oItem);
                }

            
        }
        
        
    }
    
    if($msg){
        if($aDatos["checkauxiliar"]==1){
            if($aDatos["codigoAuxiliar"]==""&&$aDatos["checksubauxiliar"]==1){
                $data["codigoCuenta"]=substr($codigoCuentaContable,0,8);
                $data["nombre"]=$aDatos["auxiliar"];
                $data["naturaleza"]=$aDatos["naturaleza"];
                $data["tipoCuenta"]=$aDatos["tipoCuenta"];
                $data["detalle"]=$aDatos["detalle"];
                $data["tercero"]=$aDatos["tercero"];
                $data["idEmpresa"]=$aDatos["idEmpresa"];
                $data["centroCosto"]=$aDatos["checkcentrocosto"];
                $data["porcentajeRetencion"]=$aDatos["porcentajeRetencion"];
                
                
                $oItem=new Data("cuenta_contable","idCuentaContable");
                foreach($data as $key => $value){
                    $oItem->$key=$value; 
                }
                $msg=$oItem->guardar();
                $idCuentaa=$oItem->ultimoId();
                unset($oItem);
                
                if($msg){
                   $aAuxiliar["codigo"]=$aDatos["idAuxiliar"]; 
                    $aAuxiliar["denominacion"]=$aDatos["auxiliar"]; 
                    if($idSubcuenta!=""){
                        $aAuxiliar["idSubcuenta"]=$idSubcuenta;
                    }else{
                        $aAuxiliar["idSubcuenta"]=$aDatos["idSubcuenta"];
                    }
                    $aAuxiliar["idEmpresa"]=$aDatos["idEmpresa"];
                    $oItem=new Data("auxiliar","idAuxiliar");
                    foreach($aAuxiliar as $key => $value){
                        $oItem->$key=$value; 
                    }
                    $msg=$oItem->guardar();
                    $aDatos["codigoAuxiliar"]=$oItem->ultimoId();
                    unset($oItem);  

                    if($msg){
                       if($aDatos["codigoAuxiliar"]==""&&$aDatos["checksubauxiliar"]==0){
                        $aAuxiliar["codigo"]=$aDatos["idAuxiliar"]; 
                        $aAuxiliar["denominacion"]=$aDatos["auxiliar"]; 
                        $aAuxiliar["idSubcuenta"]=$aDatos["idSubcuenta"]; 
                        $oItem=new Data("auxiliar","idAuxiliar");
                        foreach($aAuxiliar as $key => $value){
                            $oItem->$key=$value; 
                        }
                        $msg=$oItem->guardar();
                        $aDatos["codigoAuxiliar"]=$oItem->ultimoId(); 
                        unset($oItem);  
                    } 
                    }
                }
                 
                
            }else if($aDatos["codigoAuxiliar"]==""&&$aDatos["checksubauxiliar"]==0){
                $aAuxiliar["codigo"]=$aDatos["idAuxiliar"]; 
                $aAuxiliar["denominacion"]=$aDatos["auxiliar"]; 
                if($idSubcuenta!=""){
                    $aAuxiliar["idSubcuenta"]=$idSubcuenta;
                }else{
                    $aAuxiliar["idSubcuenta"]=$aDatos["idSubcuenta"];
                }
                $aAuxiliar["idEmpresa"]=$aDatos["idEmpresa"];
                $oItem=new Data("auxiliar","idAuxiliar");
                foreach($aAuxiliar as $key => $value){
                    $oItem->$key=$value; 
                }
                $msg=$oItem->guardar();
                $aDatos["codigoAuxiliar"]=$oItem->ultimoId();
                unset($oItem);
            }
            
            
        }

        if($msg){
            if($aDatos["checksubauxiliar"]==1&&$aDatos["idSubauxiliar"]==""){
                $aSubauxiliar["codigo"]=$aDatos["cuentaSubauxiliar"]; 
                $aSubauxiliar["denominacion"]=$aDatos["subauxiliar"]; 
                $aSubauxiliar["idAuxiliar"]=$aDatos["codigoAuxiliar"]; 
                $aSubauxiliar["idEmpresa"]=$aDatos["idEmpresa"];
                $oItem=new Data("subauxiliar","idSubauxiliar");
                foreach($aSubauxiliar as $key => $value){
                    $oItem->$key=$value; 
                }
                $msg=$oItem->guardar();
                unset($oItem); 
            } 
        }
        
    }
    
}


if(!$msg){
    $oItem=new Data("cuenta_contable","idCuentaContable",$idCuenta);
    $oItem->eliminar(); 
    unset($oItem);

    if($idCuentas>0){
        $oItem=new Data("cuenta_contable","idCuentaContable",$idCuentas);
        $oItem->eliminar();
        unset($oItem);
    }
    
}


echo json_encode(array("msg"=>$msg));

?>