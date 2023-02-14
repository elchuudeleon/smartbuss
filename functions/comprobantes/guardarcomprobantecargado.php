<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");

date_default_timezone_set("America/Bogota"); 

$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );
$item  = (isset($_REQUEST['item'] ) ? $_REQUEST['item'] : "" );
$comprobante  = (isset($_REQUEST['comprobante'] ) ? $_REQUEST['comprobante'] : "" );

if(!isset($_SESSION)){ session_start(); }

$existeC=0;
$msg=true; 
$fallos=[];
$fallosExiste=[];
foreach ($item as $key => $value) {
    
    $oItem=new Lista("tipos_documento_contable");
    $oItem->setFiltro("letra","like",$value['tipo']);
    $aTipo=$oItem->getLista();
    unset($oItem);

    $aDatos["idTipo"]=$aTipo[0]["idTiposDocumento"];


    $oLista=new Lista("comprobante");
    $oLista->setFiltro("numero","=",$value["numero"]);
    $oLista->setFiltro("idTipo","=",$aTipo[0]["idTiposDocumento"]);
    $oLista->setFiltro("comprobante","=",$value["comprobante"]);
    $oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
    $comprobanteExiste=$oLista->getLista();
    unset($oLista);
    $fecha=str_replace("/", "-", $value["fecha"]);
    if(empty($comprobanteExiste)){

        $aComprobante["fecha"]=$fecha;
        $aComprobante["numero"]=$value["numero"];
        $aComprobante["idTipo"]=$aTipo[0]["idTiposDocumento"];
        $aComprobante["comprobante"]=$value["comprobante"];
        $aComprobante["fechaRegistro"]=date('Y-m-d H:i:s'); 
        $aComprobante["usuarioRegistra"]=$_SESSION["idUsuario"]; 
        $aComprobante["archivo"]=""; 
        $aComprobante["observaciones"]=" "; 
        $aComprobante["idEmpresa"]=$datos["idEmpresa"];
        $oItem=new Data("comprobante","idComprobante"); 
        foreach($aComprobante  as $keyC => $iComprobante){
            $oItem->$keyC=$iComprobante; 
        }
        $msg=$oItem->guardar();
        $idComprobante=$oItem->ultimoId(); 
        unset($oItem);
    }else{
        $idComprobante=$comprobanteExiste[0]["idComprobante"];
        
        $oItem=new Data("comprobante","idComprobante",$idComprobante); 
        $oItem->fecha=$fecha;
        $msg=$oItem->guardar();
        
        $oLista=new Lista("comprobante_items");
        $oLista->setFiltro("idComprobante","=",$idComprobante);
        $aItemsExistente=$oLista->getLista();
        unset($oLista);

        foreach ($aItemsExistente as $keyEL => $valueEL) {
            $oItem=new Data("comprobante_items","idComprobanteItem",$valueEL["idComprobanteItem"]); 
            $oItem->eliminar(); 
            unset($oItem);
        }
    }
    
    foreach ($value["item"] as $keyi => $valueItem) {
         $itemComprobante["idTercero"]=""; 
         $oItem=new Data("tercero","nit",$valueItem["tercero"]); 
         $aValidate=$oItem->getDatos(); 
         unset($oItem); 

         if(!empty($aValidate)){
             $oItem=new Lista("tercero_empresa");
             $oItem->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
             $oItem->setFiltro("idTercero","=",$aValidate["idTercero"]);
             $aTerceroE=$oItem->getLista();
             unset($oItem); 

             if (empty($aTerceroE)) {
                 $oItem=new Data("tercero_empresa","idTerceroEmpresa");
                 $oItem->idTercero=$aValidate["idTercero"];        
                 $oItem->idEmpresa=$datos["idEmpresa"]; 
                 $msg=$oItem->guardar(); 
                 unset($oItem);

                 if(!$msg){
                    break; 
                 }
             }
             $itemComprobante["idTercero"]=$aValidate["idTercero"];
         }

        $debito=floatval(str_replace("$", "", str_replace(",", "",$valueItem["debito"]))); 
        $credito=floatval(str_replace("$", "", str_replace(",", "",$valueItem["credito"]))); 

        if ($debito > 0) {  
            $itemComprobante["naturaleza"]='debito';  
            // $aItem["saldoDebito"]=str_replace(",", ".",$valorN);
            // $aItem["saldoCredito"]=0;
        }
        if ($credito >0) {
            $itemComprobante["naturaleza"]='credito';
            // $aItem["saldoCredito"]=str_replace(",", ".",$valorN);
            // $aItem["saldoDebito"]=0;
        }
        if ($debito == 0 && $credito == 0) {
            $itemComprobante["naturaleza"]='credito';
            // $aItem["saldoCredito"]=0;
            // $aItem["saldoDebito"]=0;
        }
        
        $itemComprobante["idComprobante"]=$idComprobante; 
        $itemComprobante["idCuentaContable"]=$valueItem["idCuentaContable"]; 
        $itemComprobante["idCentroCosto"]=$valueItem["centroCosto"]; 
        $itemComprobante["idSubcentroCosto"]=$valueItem["subcentroCosto"]; 
        $itemComprobante["descripcion"]=$valueItem["descripcion"]; 
        $itemComprobante["tipoTercero"]='p'; 
        $itemComprobante["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
        $itemComprobante["fecha"]=$fecha; 
        $itemComprobante["saldoDebito"]=$debito; 
        $itemComprobante["saldoCredito"]=$credito; 
        $itemComprobante["base"]=$valueItem["base"]; 
        
        if($msg){
            $oItem=new Data("comprobante_items","idComprobanteItem"); 
            foreach($itemComprobante  as $keycc => $valuecc){
                $oItem->$keycc=$valuecc; 
            }
            $msg=$oItem->guardar(); 
            unset($oItem);    
        }else{
            break;
        }
        
    }
    
}


echo json_encode(array("msg"=>$msg));

?>