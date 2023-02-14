<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");

date_default_timezone_set("America/Bogota"); 

$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );
$item  = (isset($_REQUEST['item'] ) ? $_REQUEST['item'] : "" );
if( isset($_FILES['file']) && $_FILES['file'] != 'undefined')

    {
        $sNombre = $_FILES['file']['name'];                
        $sExtension = substr(strrchr($sNombre, '.'), 1);
        $sTemporal = $_FILES['file']['tmp_name'];
        $nombreEncript = uniqid(); 
        $nombre_archivo = "{$nombreEncript}.{$sExtension}"; 
        $directorioTmp = 'COMPROBANTE/';

        $ubicacionTmp = "{$directorioTmp}{$nombre_archivo}";  
        if(move_uploaded_file($sTemporal, "../../".$directorioTmp.$nombre_archivo)){
            $sFoto = "COMPROBANTE/".$nombre_archivo;

        }else{
            $sFoto = "";
        }  
} 

if(!isset($_SESSION)){ session_start(); }




// $oLista=new Lista("parametros_documentos");
// $oLista->setFiltro("tipo","=",$datos["tipoDocumento"]);
// $oLista->setFiltro("idParametrosDocumentos","=",$datos["comprobante"]);
// $oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
// $aNumero=$oLista->getLista();
// unset($oLista);

// if ($aNumero[0]["numeracionActual"]==$aNumero[0]["numeracionInicial"]) {
     // $numeroComprobanteA=intval($aNumero[0]["numeracionActual"]);
    
    // $numeroComprobanteAn=substr($numeroComprobanteA, 4);
    
    // $fechaComp=explode('-', $datos["fecha"]);
    
    $numeroComprobante=$datos["numeroComprobante"];
    
// }
// if ($aNumero[0]["numeracionActual"]==$aNumero[0]["numeracionInicial"]) {
//     $numeroComprobante=intval($aNumero[0]["numeracionActual"])+1;    
// }



$aDatos["idTipo"]=$datos["tipoDocumento"]; 

$aDatos["comprobante"]=$datos["comprobante"]; 

$aDatos["fecha"]=$datos["fecha"]; 

$aDatos["fechaRegistro"]=date('Y-m-d'); 

$aDatos["usuarioRegistra"]=$_SESSION["idUsuario"]; 

$aDatos["archivo"]=$sFoto; 

$aDatos["observaciones"]=$datos["obsevaciones"]; 

$aDatos["idEmpresa"]=$datos["idEmpresa"]; 

$aDatos["numero"]=$numeroComprobante; 

$oItem=new Data("comprobante","idComprobante"); 

foreach($aDatos  as $key => $value){
    $oItem->$key=$value; 
}
$oItem->guardar(); 
$idComprobante=$oItem->ultimoId(); 
unset($oItem);

$nCom["numeracionActual"]=intval($numeroComprobante)+1;
$oItem=new Data("parametros_documentos","idParametrosDocumentos",$datos["comprobante"]); 

foreach($nCom  as $keyC => $valueC){
    $oItem->$keyC=$valueC; 
}
$oItem->guardar(); 
unset($oItem);


foreach ($item as $key => $value) {
    $operacionNaturaleza="";

    $aItem["idComprobante"]=$idComprobante; 

    $aItem["idCuentaContable"]=$value["idCuentaContable"]; 

    $aItem["idCentroCosto"]=$value["idCentroCosto"];


    $aItem["idTercero"]=$value["idTercero"]; 

    $aItem["descripcion"]=$value["descripcion"]; 

    $aItem["tipoTercero"]=$value["tipoTercero"]; 

    $aItem["idUsuarioRegistra"]=$_SESSION["idUsuario"];

    $aItem["fecha"]=$datos["fecha"];






     // $oItem=new Data("cuenta_contable","idCuentaContable",$value["idCuentaContable"]); 

     //    $aCuentaContable=$oItem->getDatos(); 
     //    unset($oItem);
    if ($value["debito"] !="") {
        $valor=$value["debito"];
    }
    if ($value["credito"] !="") {
        $valor=$value["credito"]; 
    }

    //--------------------------------------------------------------------------------
    $valorN=floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valor))));
    
        // if (substr($value["cuentaContable"], 0,4)=='1592' ) {
        //     if ($valorN>0) {
        //         $valorN=$valorN*(-1);
        //     }
        // }
        // if (substr($value["cuentaContable"], 0,4)=='3610' || substr($value["cuentaContable"], 0,4)=='3710') {
        //     if ($valorN<0) {
        //         $valorN=$valorN*(-1);
        //     }
        // }

   
    //---------------------------------------------------------------

    if ($value["debito"] !="") {
        $aItem["naturaleza"]='debito';  
        $aItem["saldoDebito"]=str_replace(",", ".",$valorN);
        $aItem["saldoCredito"]=0;
    }
    if ($value["credito"] !="") {
        $aItem["naturaleza"]='credito';
        $aItem["saldoCredito"]=str_replace(",", ".",$valorN);
        $aItem["saldoDebito"]=0;
    }

    if ($value["base"]!='') {
        $aItem["base"]=str_replace(",", ".",str_replace("$", "", str_replace(".", "",$value["base"])));
    }
    if ($value["base"]=='') {
        $aItem["base"]=0;
    }


    $operacionNaturaleza=$value["naturaleza"];
    

        $oItem=new Data("comprobante_items","idComprobanteItem"); 

        foreach($aItem  as $keycc => $valuecc){

            $oItem->$keycc=$valuecc; 

        }

        $oItem->guardar(); 

        unset($oItem);


    }


    $msg=true; 



    echo json_encode(array("msg"=>$msg));

?>