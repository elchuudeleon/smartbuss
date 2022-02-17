<?php

header('Content-type: application/json');

require_once("../../php/restrict.php");



include_once($CLASS . "data.php");

include_once($CLASS . "lista.php");



date_default_timezone_set("America/Bogota"); 



$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );
$deducciones  = (isset($_REQUEST['baseImpuestos'] ) ? $_REQUEST['baseImpuestos'] : "" );
$deduccionesNuevas  = (isset($_REQUEST['impuesto'] ) ? $_REQUEST['impuesto'] : "" );


if( isset($_FILES['file']) && $_FILES['file'] != 'undefined')

    {

               

        $sNombre = $_FILES['file']['name'];                

        $sExtension = substr(strrchr($sNombre, '.'), 1);

        $sTemporal = $_FILES['file']['tmp_name'];

        

        $nombreEncript = uniqid(); 

        

        $nombre_archivo = "{$nombreEncript}.{$sExtension}"; 


        $directorioTmp = 'FACTURAVENTA/';

        $ubicacionTmp = "{$directorioTmp}{$nombre_archivo}";  



        if(move_uploaded_file($sTemporal, "../../".$directorioTmp.$nombre_archivo))

        {	                                              

            $sFoto = "FACTURAVENTA/".$nombre_archivo;

        }

        else

        {

            $sFoto = "";

        }

    

} 


$id=$datos["idFactura"];

$aDatos["fechaFactura"]=$datos["fechaFactura"]; 

$aDatos["nroFactura"]=$datos["nroFactura"]; 
$aDatos["fechaVencimiento"]=$datos["fechaVencimientoFactura"]; 
$aDatos["idCliente"]=$datos["idCliente"]; 
if ($sFoto!="") {
	$aDatos["archivo"]=$sFoto; 
}

$aDatos["saldo"]=str_replace("$", "", str_replace(".", "", $datos["totalPago"])); 

$oItem=new Data("factura_venta","idFacturaVenta",$id); 

foreach($aDatos  as $key => $value){

$oItem->$key=$value; 

}

$oItem->guardar(); 

unset($oItem);




$aDatosG["totalDeduccion"]=str_replace("$", "", str_replace(".", "", $datos["totalDeduccion"])); 

$aDatosG["valorAmortizacion"]=str_replace("$", "", str_replace(".", "", $datos["amortizacion"])); 
$aDatosG["totalPagar"]=str_replace("$", "", str_replace(".", "", $datos["totalPago"])); 


$oItem=new Data("factura_venta_gestion","idFacturaVenta",$id); 

foreach($aDatosG  as $keyG => $valueG){

$oItem->$keyG=$valueG; 

}

$oItem->guardar(); 

unset($oItem);



foreach ($deducciones as $keyd => $valued) {

    $aItem["tipoDeduccion"]=$valued["tipoDeduccion"]; 

    $aItem["idConcepto"]=$valued["conceptoSelect"]; 

    $aItem["concepto"]=$valued["conceptoSelectTexto"]; 

    $aItem["baseImpuestos"]=str_replace("$", "", str_replace(".", "", $valued["baseImpuestos"])); 

    $aItem["valor"]=str_replace("$", "", str_replace(".", "", $valued["valorDeduccion"])); 

    
    $oItem=new Data("factura_venta_deduccion","idFacturaVentaDeduccion",$valued["idFacturaVentaDeduccion"]); 

    foreach($aItem  as $keyF => $valueF){

        $oItem->$keyF=$valueF; 

    }

    $oItem->guardar(); 

    unset($oItem);

}

foreach ($deduccionesNuevas as $keydn => $valuedn) {

    $aItemN["idFacturaVenta"]=$id; 

    $aItemN["tipoDeduccion"]=$valuedn["tipoDeduccion"]; 

    $aItemN["idConcepto"]=$valuedn["idConcepto"]; 

    $aItemN["concepto"]=$valuedn["concepto"]; 

    $aItemN["baseImpuestos"]=$valuedn["baseImpuestos"]; 

    $aItemN["valor"]=$valuedn["valor"]; 
    $aItemN["estado"]=1; 

    
    $oItem=new Data("factura_venta_deduccion","idFacturaVentaDeduccion"); 

    foreach($aItemN  as $keyFN => $valueFN){

        $oItem->$keyFN=$valueFN; 

    }

    $oItem->guardar(); 

    unset($oItem);

}

$msg=true; 


echo json_encode(array("msg"=>$msg));

?>