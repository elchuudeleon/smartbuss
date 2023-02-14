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


$idComprobante=$datos["idComprobante"];
$idEmpresa=$datos["idEmpresa"];


$oLista=new Lista("parametros_documentos");
$oLista->setFiltro("tipo","=",$datos["tipoDocumento"]);
$oLista->setFiltro("comprobante","=",$datos["comprobante"]);
$oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
$aNumero=$oLista->getLista();
unset($oLista);


$oLista=new Lista("comprobante");
$oLista->setFiltro("numero","=",$datos["numeroComprobante"]);
$oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
$aComprobanteValidar=$oLista->getLista();
unset($oLista);


$aDatos["idTipo"]=$datos["tipoDocumento"]; 
$aDatos["comprobante"]=$aNumero[0]["comprobante"]; 
$aDatos["fecha"]=$datos["fecha"]; 
$aDatos["fechaRegistro"]=date('Y-m-d'); 
$aDatos["archivo"]=$sFoto; 
$aDatos["observaciones"]=$datos["obsevaciones"]; 
$aDatos["numero"]=$datos["numeroComprobante"]; 

$oItem=new Data("comprobante","idComprobante",$idComprobante); 

foreach($aDatos  as $key => $value){
    $oItem->$key=$value; 
}
$oItem->guardar();
unset($oItem);

$oLista=new Lista("comprobante_items");
$oLista->setFiltro("idComprobante","=",$idComprobante);
$itemsEliminar=$oLista->getLista();
unset($oLista);

foreach ($itemsEliminar as $keyEL => $valueEL) {

        $oLista=new Lista('cuenta_contable');
        $oLista->setFiltro('idCuentaContable',"=",$valueEL["idCuentaContable"]);
        $oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
        $cuentaContable=$oLista->getLista();
        unset($oLista);


        $oItem=new Data("comprobante_items","idComprobanteItem",$valueEL["idComprobanteItem"]);
        $oItem->eliminar();
        unset($oItem);
}


foreach ($item as $key => $value) {
    $operacionNaturaleza="";

    $aItem["idComprobante"]=$idComprobante; 
    $aItem["idCuentaContable"]=$value["idCuentaContable"]; 
    $aItem["idCentroCosto"]=$value["idCentroCosto"];
    $aItem["idSubcentroCosto"]=$value["idSubcentroCosto"];
    $aItem["idTercero"]=$value["idTercero"]; 
    $aItem["descripcion"]=$value["descripcion"]; 
    $aItem["tipoTercero"]='p'; 
    $aItem["idUsuarioRegistra"]=$_SESSION["idUsuario"];
    $aItem["fecha"]=$datos["fecha"];

    if ($value["debito"] !="") {
        $valor=$value["debito"];
    }
    if ($value["credito"] !="") {
        $valor=$value["credito"]; 
    }

    $valorN=floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valor))));


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

    if (is_nan($aItem["base"])) {
        $aItem["cruce"]=$aItem["base"];
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

$oItem=new Data("factura_venta_comprobante","idComprobante",$idComprobante); 
$aVentaComprobante=$oItem->getDatos();  

if(!empty($aVentaComprobante)){

    $oLista=new Lista('factura_venta_deduccion');
    $oLista->setFiltro('idFacturaVenta',"=",$aVentaComprobante["idFacturaVenta"]);
    $aFraDeducciones=$oLista->getLista();
    unset($oLista);

    $oItem=new Data("factura_venta","idFacturaVenta",$aVentaComprobante["idFacturaVenta"]);
    $aFrac=$oItem->getDatos(); 
    unset($oItem);

    if($aFrac["estado"]!=3){ 
    foreach($aFraDeducciones as $iFraDeducciones){
        $oItem=new Data("factura_venta_deduccion","idFacturaVentaDeduccion",$iFraDeducciones["idFacturaVentaDeduccion"]);
        $oItem->eliminar();
        unset($oItem);
    }

    foreach ($item as $key => $value) {
        
        $oLista=new Lista('impuesto_cuenta_contable');
        $oLista->setFiltro('idEmpresaCuenta',"=",$value["idCuentaContable"]);
        $aImpuesto=$oLista->getLista();
        unset($oLista);
        
        if(count($aImpuesto)>0){
           $oLista=new Lista('retencion');
           $oLista->setFiltro('idRetencion',"=",$aImpuesto[0]["idImpuesto"]);
           $aRetencion=$oLista->getLista();
           unset($oLista); 

           $aDeduccionIngresar["idFacturaVenta"]=$aVentaComprobante["idFacturaVenta"]; 
           
           if(count($aRetencion)>0){
            $aDeduccionIngresar["tipoDeduccion"]=$aRetencion[0]["tipo"];
            $aDeduccionIngresar["idConcepto"]=$aRetencion[0]["idRetencion"];
            $ciudad="";
            if($aRetencion[0]["idCiudad"]!=0){
                $oItem=new Data("ciudad","idCiudad",$aRetencion[0]["idCiudad"]);
                $aCiudad=$oItem->getDatos(); 
                unset($oItem); 

                $oItem=new Data("departamento","idDepartamento",$aRetencion[0]["idDepartamento"]);
                $aDepartamento=$oItem->getDatos(); 
                unset($oItem);

                $ciudad="(".$aCiudad["nombre"]." - ".substr($aDepartamento["nombre"], 0,3).")"; 
            }
            $aDeduccionIngresar["concepto"]=$aRetencion[0]["valor"]."% - ".$aRetencion[0]["descripcion"]." ".$ciudad;
            $valor=floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$value["debito"])))); 
            if($valor==0){
                $valor=floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$value["credito"])))); 
            }
            $aDeduccionIngresar["baseImpuestos"]=floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$value["base"]))));
            $aDeduccionIngresar["valor"]=$valor;
            $aDeduccionIngresar["restar"]=0;

            $oItem=new Data("factura_venta_deduccion","idFacturaVentaDeduccion"); 
            foreach($aDeduccionIngresar  as $llave => $valor){
                $oItem->$llave=$valor; 
            }
            $oItem->guardar(); 
            unset($oItem);
           }
           
        }
    }
  }
}

$msg=true; 
echo json_encode(array("msg"=>$msg));

?>


