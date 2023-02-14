<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");

include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");


date_default_timezone_set("America/Bogota"); 

$aItem  = (isset($_REQUEST['itemComprobante'] ) ? $_REQUEST['itemComprobante'] : "" );
$items=json_decode($aItem); 
if(!isset($_SESSION)){ session_start(); }
$msg=true;

foreach($items as $item){
    $fraccion=explode("-",$item->codigo); 
    $oLista = new Lista('configuracion_tipo_documentos_sincronizacion');
    $oLista->setFiltro("idEmpresa","=",$_SESSION["idEmpresa"]); 
    $oLista->setFiltro("tipo","=",1); 
    $oLista->setFiltro("idTiposDocumento","=",$item->tipoDocumento); 
    $aDocumentoConfi=$oLista->getLista();
    unset($oLista);

    if(empty($aDocumentoConfi)){
        $oItem=new Data("configuracion_tipo_documentos_sincronizacion","idFacturaVenta"); 
        $oItem->idEmpresa=$_SESSION["idEmpresa"];
        $oItem->tipo=1;
        $oItem->idTiposDocumento=$item->tipoDocumento;
        $oItem->letra=$item->codigoOculto;
        $oItem->guardar(); 
        unset($oItem);
    }
    $aDatos["usuarioRegistra"]=$_SESSION["idUsuario"]; 
    $aDatos["fechaRegistro"]=date("Y-m-d H:i:s"); 
    $aDatos["fecha"]=$item->fechaComprobante; 
    $aDatos["comprobante"]=$fraccion[1]; 
    $aDatos["idTipo"]=$item->tipoDocumento; 
    $aDatos["idEmpresa"]=$_SESSION["idEmpresa"];
    $aDatos["numero"]=$fraccion[2]; 
    $aDatos["estado"]=1; 
    
    $oItem=new Data("comprobante","idComprobante"); 
    foreach($aDatos  as $key => $value){
        $oItem->$key=$value; 
    }
    $msg=$oItem->guardar(); 
    $idComprobante=$oItem->ultimoId(); 
    unset($oItem);

    if($msg){
        foreach($item->items as $itemsComprobante){
            $saldoDebito=0; 
            $saldoCredito=0; 
            $oItem=new Data("cuenta_contable","codigoCuenta",$itemsComprobante->cuentaContable);
            $oCuenta=$oItem->getDatos();
            unset($oItem);

            if($itemsComprobante->movimiento=="Debit"){
                $saldoDebito=$itemsComprobante->valor;
                $saldoCredito=0;
            }else{
                $saldoDebito=0;
                $saldoCredito=$itemsComprobante->valor;    
            }
            $aItemComprobante["idComprobante"]=$idComprobante; 
            $aItemComprobante["idCuentaContable"]=$oCuenta["idCuentaContable"]>0?$oCuenta["idCuentaContable"]:1; 
            $aItemComprobante["idTercero"]=$itemsComprobante->idTercero; 
            $aItemComprobante["tipoTercero"]=$itemsComprobante->tipoTercero; 
            $aItemComprobante["descripcion"]=$itemsComprobante->descripcion; 
            $aItemComprobante["naturaleza"]=$itemsComprobante->movimiento=="Debit"?"debito":"credito"; 
            $aItemComprobante["fecha"]=$item->fechaComprobante; 
            $aItemComprobante["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
            $aItemComprobante["saldoDebito"]=$saldoDebito; 
            $aItemComprobante["saldoCredito"]=$saldoCredito; 
            
            $oItem=new Data("comprobante_items","idComprobanteItem"); 
            foreach($aItemComprobante  as $key => $value){
                $oItem->$key=$value; 
            }
            $msg=$oItem->guardar(); 
            unset($oItem);
        }
     }else{
        break; 
     }
}


echo json_encode(array("msg"=>$msg));

?>