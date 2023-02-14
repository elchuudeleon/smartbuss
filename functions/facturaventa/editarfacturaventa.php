<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");


include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");


date_default_timezone_set("America/Bogota"); 


$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );
//$deducciones  = (isset($_REQUEST['baseImpuestos'] ) ? $_REQUEST['baseImpuestos'] : "" );
$deducciones  = (isset($_REQUEST['impuesto'] ) ? $_REQUEST['impuesto'] : "" );

$items  = (isset($_REQUEST['item'] ) ? $_REQUEST['item'] : "" );

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
        }else{
            $sFoto = "";
        }

} 


$id=$datos["idFactura"];

$aDatos["fechaFactura"]=$datos["fechaFactura"]; 
$aDatos["nroFactura"]=$datos["nroFactura"]; 
$aDatos["subtotal"]=str_replace("$", "", str_replace(".", "", $datos["subtotal"])); 
$aDatos["descuento"]=str_replace("$", "", str_replace(".", "", $datos["descuento"])); 
$aDatos["iva"]=str_replace("$", "", str_replace(".", "", $datos["iva"])); 
$aDatos["total"]=str_replace("$", "", str_replace(".", "", $datos["totalFactura"])); 
$aDatos["fechaVencimiento"]=$datos["fechaVencimientoFactura"]; 
$aDatos["idCliente"]=$datos["idCliente"]; 
$aDatos["formaPagoFactura"]=$datos["formaPagoFactura"];


$oLista=new Lista("banco_cuenta_contable");
$oLista->setFiltro("idBancoCuentaContable","=",$datos["formaPagoFactura"]);
$oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
$aFormaPago=$oLista->getLista();

if ($aFormaPago[0]["idCuentaBancaria"]!=0) {
    $aDatos["estado"]=3; 
    $aDatos["saldo"]=0;

    $totalFactura=str_replace("$", "", str_replace(".", "", $datos["totalPago"]));
    $idCuenta=$aFormaPago[0]["idCuentaBancaria"];

    $oItem=new Data("cuenta_bancaria","idCuentaBancaria",$aFormaPago[0]["idCuentaBancaria"]);
    $cuentaDatos=$oItem->getDatos(); 

    $saldoActual=$cuentaDatos["saldoActual"];

    $nuevoSaldo=$saldoActual + $totalFactura;
    $cuatropormil=$cuentaDatos['aplicaCuatroMil'];
    unset($oItem);

    $oItem=new Data("tercero","idTercero",$datos["idCliente"]); 
    $clienteDatos=$oItem->getDatos(); 
    unset($oItem);


    $bDatos["idCuentaBancaria"]=$idCuenta;
    $bDatos["idTipoMovimiento"]=3;
    $bDatos["fechaRegistro"]=date("Y-m-d H:i:s");
    $bDatos["valorIngreso"]=$totalFactura;
    $bDatos["valorEgreso"]=0;  
    $bDatos["saldoAnterior"]=$saldoActual;
    $bDatos["saldoActual"]=$nuevoSaldo;
    $bDatos["descripcionMovimiento"]='pago de factura '.$datos["nroFactura"].' del cliente '.$clienteDatos["razonSocial"]; 

    $oItem=new Data("cuenta_bancaria_movimientos","idCuentaBancariaMovimientos"); 
    foreach($bDatos  as $key => $value){
        $oItem->$key=$value; 
    }
    $oItem->guardar(); 
   
    unset($oItem);

    $oItem=new Data("cuenta_bancaria","idCuentaBancaria",$idCuenta); 
    $oItem->saldoActual=$nuevoSaldo; 
    $oItem->guardar(); 
    unset($oItem);

}
if ($aFormaPago[0]["idCuentaBancaria"]==0) {
    $aDatos["estado"]=1; 
    $aDatos["saldo"]=str_replace("$", "", str_replace(".", "", $datos["totalPago"]));
}

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


$oLista=new Lista("factura_venta_item");
$oLista->setFiltro("idFacturaVenta","=",$id);
$facturaItems=$oLista->getLista();
unset($oLista);
if (!empty($facturaItems)) {
    foreach ($facturaItems as $keyC => $valueC) {
    $oItem=new Data("factura_venta_item","idFacturaVentaItem",$valueC["idFacturaVentaItem"]);
    $oItem->eliminar();
    unset($oItem);
    }
}


foreach ($items as $keyI => $valueI) {
    
    $producto=explode('-', $valueI["idProducto"]);
    
    $aFacturaItems["idFacturaVenta"]=$id;
    $aFacturaItems["idProductoServicio"]=$producto[0];
    $aFacturaItems["detalleProducto"]=$producto[1].'-'.$producto[2];
    $aFacturaItems["descripcion"]=$valueI["descripcion"];
    $aFacturaItems["idUnidad"]=$valueI["idUnidad"];
    $aFacturaItems["cantidad"]=$valueI["cantidad"];
    $aFacturaItems["valorUnitario"]=str_replace("$", "", str_replace(".", "", $valueI["valorUnitario"]));
    $aFacturaItems["subtotal"]=str_replace("$", "", str_replace(".", "", $valueI["subtotal"]));
    $aFacturaItems["iva"]=$valueI["iva"];
    $aFacturaItems["total"]=str_replace("$", "", str_replace(".", "", $valueI["total"]));


    $oItem=new Data("factura_venta_item","idFacturaVentaItem"); 
    foreach($aFacturaItems  as $keyFI => $valueFI){
    $oItem->$keyFI=$valueFI; 
    }
    $oItem->guardar(); 
    unset($oItem);

}



$aDatosG["totalDeduccion"]=str_replace("$", "", str_replace(".", "", $datos["totalDeduccion"])); 
$aDatosG["valorAmortizacion"]=str_replace("$", "", str_replace(".", "", $datos["amortizacion"])); 
$aDatosG["totalPagar"]=str_replace("$", "", str_replace(".", "", $datos["totalPago"])); 

$oItem=new Data("factura_venta_gestion","idFacturaVenta",$id); 
foreach($aDatosG  as $keyG => $valueG){
    $oItem->$keyG=$valueG; 
}
$oItem->guardar(); 
unset($oItem);


$oLista=new Lista("factura_venta_deduccion");
$oLista->setFiltro("idFacturaVenta","=",$id);
$deduccionesItems=$oLista->getLista();
unset($oLista);

if (!empty($deduccionesItems)) {
    foreach ($deduccionesItems as $keyC => $valueC) {
    $oItem=new Data("factura_venta_deduccion","idFacturaVentaDeduccion",$valueC["idFacturaVentaDeduccion"]);
    $oItem->eliminar();
    unset($oItem);
    }
}

// foreach ($deducciones as $keyd => $valued) {
//     $aItem["idFacturaVenta"]=$id; 
//     $aItem["tipoDeduccion"]=$valued["tipoDeduccion"]; 
//     $aItem["idConcepto"]=$valued["conceptoSelect"]; 
//     $aItem["concepto"]=$valued["conceptoSelectTexto"]; 
//     $aItem["baseImpuestos"]=str_replace("$", "", str_replace(".", "", $valued["baseImpuestos"])); 
//     $aItem["valor"]=str_replace("$", "", str_replace(".", "", $valued["valorDeduccion"])); 
//     $aItem["estado"]=1; 
//     $oItem=new Data("factura_venta_deduccion","idFacturaVentaDeduccion"); 
//     foreach($aItem  as $keyF => $valueF){
//         $oItem->$keyF=$valueF; 
//     }
//     $oItem->guardar(); 
//     unset($oItem);

// }

foreach ($deducciones as $keydn => $valuedn) {
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


$oItem=new Data("factura_venta_comprobante","idFacturaVenta",$id); 
$aVentaComprobante=$oItem->getDatos();  


if(!empty($aVentaComprobante)){
    $comp=true;
    if(!isset($_SESSION)){ session_start(); }

    $oLista=new Lista("comprobante_items");
    $oLista->setFiltro("idComprobante","=",$aVentaComprobante["idComprobante"]);
    $itemsEliminar=$oLista->getLista();
    unset($oLista);

    foreach ($itemsEliminar as $keyEL => $valueEL) {
        $oItem=new Data("comprobante_items","idComprobanteItem",$valueEL["idComprobanteItem"]);
        $oItem->eliminar();
        unset($oItem);
    }

    $oLista=new Data("empresa","idEmpresa",$_SESSION["idEmpresa"]);
    $aEmpresaDatos=$oLista->getDatos();
    unset($oLista);

    $oLista=new Lista("impuesto_cuenta_contable");
    $oLista->setFiltro("idEmpresa","=",$_SESSION["idEmpresa"]);
    $oLista->setFiltro("tipoImpuesto","=",'3');
    $oLista->setFiltro("tipoFactura","=",'venta');
    $aCC=$oLista->getLista();

        if (!empty($aCC)) {


            // $oLista=new Lista("parametros_documentos");
            // $oLista->setFiltro("idParametrosDocumentos","=",$datos['tipoDocumento']);
            // $oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
            // $aNumero=$oLista->getLista();
            // unset($oLista);

            // $numeroComprobante=intval($datos["numeroComprobante"]);

            // $oItem=new Data("tipos_documento_contable","idTiposDocumento",$aNumero[0]["tipo"]);
            // $letraTipo=$oItem->getDatos();
            // unset($oItem);

            // $consecutivoComprobante=$letraTipo["letra"].'-'.$aNumero[0]["comprobante"].'-'.$numeroComprobante;




                // $aDatos["idTipo"]=$aNumero[0]["tipo"]; 
                // $aDatos["comprobante"]=$aNumero[0]["comprobante"]; 
                // $aDatos["fecha"]=$datos["fechaFactura"]; 
                // $aDatos["fechaRegistro"]=date('Y-m-d'); 
                // $aDatos["usuarioRegistra"]=$_SESSION["idUsuario"]; 
                // $aDatos["archivo"]=$sFoto; 
                // $aDatos["observaciones"]='Factura de venta No. '.$datos["nroFactura"]; 
                // $aDatos["idEmpresa"]=$datos["idEmpresa"]; 
                // $aDatos["numero"]=$numeroComprobante; 
                // $aDatos["estado"]=NULL; 
                
                // $oItem=new Data("comprobante","idComprobante"); 
                // foreach($aDatos  as $key => $value){
                //     $oItem->$key=$value; 
                // }
                // $oItem->guardar(); 
                // $idComprobante=$oItem->ultimoId(); 
                // unset($oItem);

                // $nCom["numeracionActual"]=$numeroComprobante+1;
                // $oItem=new Data("parametros_documentos","idParametrosDocumentos",$aNumero[0]["idParametrosDocumentos"]); 
                // foreach($nCom  as $keyC => $valueC){
                //     $oItem->$keyC=$valueC; 
                // }

                // $oItem->guardar(); 
                // unset($oItem);


                $oItem=new Data("tercero","idTercero",$datos["idCliente"]);
                $aCliente=$oItem->getDatos();
                unset($oItem);

                foreach ($items as $key => $value) {
                    $oLista=new Lista("producto_servicio");
                    $oLista->setFiltro("idProductoServicio","=",$value["idProducto"]);
                    $oLista->setFiltro("idEmpresa","=",$_SESSION["idEmpresa"]);
                    $aProductoCuenta=$oLista->getLista();

                    if (empty($aProductoCuenta)) {
                        $comp=false;
                    }else{
                        
                        // $oItem=new Data("grupo_inventario","idGrupoInventario",$aProductoCuenta[0]["idGrupo"]);
                        // $aGrupo=$oItem->getDatos();
                        // unset($oItem);
                        // $oLista=new Data("empresa","idEmpresa",$datos["idEmpresa"]);
                        // $aEmpresaDatos=$oLista->getDatos();
                        // unset($oLista);

                        // $oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
                        
                        if ($aEmpresaDatos['manejaContabilidad']==1 && $aEmpresaDatos['manejaInventario']==1) {
                            $oItem=new Data("grupo_inventario","idGrupoInventario",$aProductoCuenta[0]["idGrupo"]);
                            $aGrupoContable=$oItem->getDatos();
                            unset($oItem);

                            $aItem["idCuentaContable"]=$aGrupoContable["venta"];
                            
                            $aItemInv["idComprobante"]=$aVentaComprobante["idComprobante"]; 
                            $aItemInv["idCuentaContable"]=$aGrupoContable["inventario"];
                            $aItemInv["idCentroCosto"]=" ";
                            $aItemInv["idSubcentroCosto"]=" ";
                            $aItemInv["idTercero"]=$datos["idCliente"];
                            $aItemInv["descripcion"]=$value["descripcion"].': '.$value["cantidad"].' unidades';
                            $aItemInv["naturaleza"]='credito';
                            $aItemInv["tipoTercero"]='p';
                            $aItemInv["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
                            $aItemInv["fecha"]=$datos["fechaFactura"]; 
                            $aItemInv["saldoDebito"]=0;
                            $aItemInv["saldoCredito"]=$aProductoCuenta[0]["costoPromedio"];
                            $aItemInv["base"]=0;
                        
                            $oItem=new Data("comprobante_items","idComprobanteItem"); 
                            foreach($aItemInv  as $keyInv => $valueInv){
                                $oItem->$keyInv=$valueInv; 
                            }
                            $oItem->guardar(); 
                            unset($oItem);



                            $aItemInv["idComprobante"]=$aVentaComprobante["idComprobante"]; 
                            $aItemInv["idCuentaContable"]=$aGrupoContable["costo"];
                            $aItemInv["idCentroCosto"]=" ";
                            $aItemInv["idSubcentroCosto"]=" ";
                            $aItemInv["idTercero"]=$datos["idCliente"];
                            $aItemInv["descripcion"]=$value["descripcion"].': '.$value["cantidad"].' unidades';
                            $aItemInv["naturaleza"]='debito';
                            $aItemInv["tipoTercero"]='p';
                            $aItemInv["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
                            $aItemInv["fecha"]=$datos["fechaFactura"]; 
                            $aItemInv["saldoDebito"]=$aProductoCuenta[0]["costoPromedio"];
                            $aItemInv["saldoCredito"]=0;
                            $aItemInv["base"]=0;
                        
                            $oItem=new Data("comprobante_items","idComprobanteItem"); 
                            foreach($aItemInv  as $keyInv => $valueInv){
                                $oItem->$keyInv=$valueInv; 
                            }
                            $oItem->guardar(); 
                            unset($oItem);



                        }else{
                            $aItem["idCuentaContable"]=$aProductoCuenta[0]["venta"];

                        }
                        
                            $aItem["idComprobante"]=$aVentaComprobante["idComprobante"]; 
                            $aItem["idCentroCosto"]=" ";
                            $aItem["idSubcentroCosto"]=" ";
                            $aItem["idTercero"]=$datos["idCliente"];
                            $aItem["descripcion"]=$value["descripcion"];
                            $aItem["naturaleza"]='credito';
                            $aItem["tipoTercero"]='p';
                            $aItem["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
                            $aItem["fecha"]=$datos["fechaFactura"]; 
                            $aItem["saldoDebito"]=0;
                            $aItem["saldoCredito"]=str_replace(",", ".",str_replace("$", "", str_replace(".", "",$value["subtotal"])));
                            $aItem["base"]=0;
                        
                            $oItem=new Data("comprobante_items","idComprobanteItem"); 
                            foreach($aItem  as $keycc => $valuecc){
                                $oItem->$keycc=$valuecc; 
                            }
                            $oItem->guardar(); 
                            unset($oItem);

                    }

                }


                $oItem=new Data("cuenta_contable","idCuentaContable",$aCC[0]["idEmpresaCuenta"]);
                $aCuentaContable=$oItem->getDatos();
                unset($oItem);

                $iva=str_replace(",", ".",str_replace("$", "", str_replace(".", "",$datos["iva"])));

                if ($iva!="0" && $iva!="0,0") {

                    $aIVA["idComprobante"]=$aVentaComprobante["idComprobante"]; 
                    $aIVA["idCuentaContable"]=$aCC[0]["idEmpresaCuenta"];
                    $aIVA["idCentroCosto"]=" ";
                    $aIVA["idSubcentroCosto"]=" ";
                    $aIVA["idTercero"]=$datos["idCliente"];
                    $aIVA["descripcion"]=$value["descripcion"];
                    $aIVA["naturaleza"]='credito';
                    $aIVA["tipoTercero"]='p';
                    $aIVA["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
                    $aIVA["fecha"]=$datos["fechaFactura"]; 
                    $aIVA["saldoDebito"]=0;
                    $aIVA["saldoCredito"]=str_replace(",", ".",str_replace("$", "", str_replace(".", "",$datos["iva"])));
                    $aIVA["base"]=0;

                        $oItem=new Data("comprobante_items","idComprobanteItem"); 
                        foreach($aIVA  as $keyIVA => $valueIVA){
                            $oItem->$keyIVA=$valueIVA; 
                        }
                        $oItem->guardar(); 
                        unset($oItem);
                }

                $totalDeduccionesFactura=0;


                foreach ($deducciones as $keyI => $valueI) {
                    /*if (empty($_SESSION["idEmpresa"])) {
                        $idEmpresaB=$datos["idEmpresa"];
                    }
                    if (!empty($_SESSION["idEmpresa"])) {*/
                        $idEmpresaB=$_SESSION["idEmpresa"];
                    //}
                    $oLista=new Lista("impuesto_cuenta_contable");
                    $oLista->setFiltro("idImpuesto","=",$valueI["idConcepto"]);
                    $oLista->setFiltro("idEmpresa","=",$idEmpresaB);
                    $oLista->setFiltro("tipoFactura","=",'venta');
                    $aImpuestoCuenta=$oLista->getLista();  
                    unset($oLista);  
                    // print_r($aImpuestoCuenta);

                    // foreach ($aImpuestoCuenta as $keyAI => $valueAI) {

                    if (empty($aImpuestoCuenta)) {
                        $comp=false;
                    }

                    if ($comp==true) {
                        // code...
                    

                        $aCuent=$aImpuestoCuenta[0]["idEmpresaCuenta"];    
                        
                        $oLista=new Data("cuenta_contable","idCuentaContable",$aCuent);
                        $aCuentaImpuesto=$oLista->getDatos();  
                        unset($oLista);  
                        
                        // print_r($aCuentaImpuesto);

                            if ( strpos($aCuentaImpuesto["nombre"],'RETENCION EN LA FUENTE') !== false ) {
                                $aImpuesto["idComprobante"]=$aVentaComprobante["idComprobante"]; 
                                $aImpuesto["idCuentaContable"]=$aCuentaImpuesto["idCuentaContable"];
                                $aImpuesto["idCentroCosto"]=" ";
                                $aImpuesto["idSubcentroCosto"]=" ";
                                $aImpuesto["idTercero"]=$datos["idCliente"];
                                $aImpuesto["descripcion"]='Fact No. '.$datos["nroFactura"];
                                $aImpuesto["naturaleza"]='debito';
                                $aImpuesto["tipoTercero"]='p';
                                $aImpuesto["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
                                $aImpuesto["fecha"]=$datos["fechaFactura"]; 
                                $aImpuesto["saldoDebito"]=str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valueI["valor"])));
                                $aImpuesto["saldoCredito"]=0;
                                $aImpuesto["base"]=str_replace(".", ",",$valueI["baseImpuestos"]);

                                $totalDeduccionesFactura=$totalDeduccionesFactura+floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valueI["valor"]))));

                                // print_r('ingreso');

                                $oItem=new Data("comprobante_items","idComprobanteItem"); 
                                foreach($aImpuesto  as $keyImpuesto => $valueImpuesto){
                                    $oItem->$keyImpuesto=$valueImpuesto; 
                                }
                                $oItem->guardar(); 
                                unset($oItem);
                            }
                        }
                            
                        if ($comp==true) {    
                            if ( strpos($aCuentaImpuesto["nombre"],'RETENCION EN LA FUENTE') === false ) {
                                
                                if (count($aImpuestoCuenta)>1) {
                                    $oLista=new Data("cuenta_contable","idCuentaContable",$aImpuestoCuenta[0]["idEmpresaCuenta"]);
                                    $aCuentaImpuestoP=$oLista->getDatos();  
                                    unset($oLista); 
                                    $aImpuesto["idComprobante"]=$aVentaComprobante["idComprobante"]; 
                                    $aImpuesto["idCuentaContable"]=$aImpuestoCuenta[0]["idEmpresaCuenta"];
                                    $aImpuesto["idCentroCosto"]=" ";
                                    $aImpuesto["idSubcentroCosto"]=" ";
                                    $aImpuesto["idTercero"]=$datos["idCliente"];
                                    $aImpuesto["descripcion"]='Fact No. '.$datos["nroFactura"];
                                    $aImpuesto["naturaleza"]=$aCuentaImpuestoP["naturaleza"];
                                    $aImpuesto["tipoTercero"]='p';
                                    $aImpuesto["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
                                    $aImpuesto["fecha"]=$datos["fechaFactura"]; 
                                    if ($aCuentaImpuestoP["naturaleza"]=='debito') {
                                        $aImpuesto["saldoDebito"]=str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valueI["valor"])));
                                        $aImpuesto["saldoCredito"]=0;
                                    }
                                    if ($aCuentaImpuestoP["naturaleza"]=='credito') {
                                        $aImpuesto["saldoDebito"]=0;
                                        $aImpuesto["saldoCredito"]=str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valueI["valor"])));
                                    }
                                    $aImpuesto["base"]=str_replace(".", ",",$valueI["baseImpuestos"]);
                                    $oItem=new Data("comprobante_items","idComprobanteItem"); 
                                    foreach($aImpuesto  as $keyImpuesto => $valueImpuesto){
                                        $oItem->$keyImpuesto=$valueImpuesto; 
                                    }
                                    $oItem->guardar(); 
                                    unset($oItem);
                                    $oLista=new Data("cuenta_contable","idCuentaContable",$aImpuestoCuenta[1]["idEmpresaCuenta"]);
                                    $aCuentaImpuestoS=$oLista->getDatos();  
                                    unset($oLista);
                                    $aImpuesto["idComprobante"]=$aVentaComprobante["idComprobante"]; 
                                    $aImpuesto["idCuentaContable"]=$aImpuestoCuenta[1]["idEmpresaCuenta"];
                                    $aImpuesto["idCentroCosto"]=" ";
                                    $aImpuesto["idSubcentroCosto"]=" ";
                                    $aImpuesto["idTercero"]=$datos["idCliente"];
                                    $aImpuesto["descripcion"]='Fact No. '.$datos["nroFactura"];
                                    $aImpuesto["naturaleza"]=$aCuentaImpuestoS["naturaleza"];
                                    $aImpuesto["tipoTercero"]='p';
                                    $aImpuesto["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
                                    $aImpuesto["fecha"]=$datos["fechaFactura"]; 
                                    if ($aCuentaImpuestoS["naturaleza"]=='debito') {
                                        $aImpuesto["saldoDebito"]=str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valueI["valor"])));
                                        $aImpuesto["saldoCredito"]=0;
                                    }
                                    if ($aCuentaImpuestoS["naturaleza"]=='credito') {
                                        $aImpuesto["saldoDebito"]=0;
                                        $aImpuesto["saldoCredito"]=str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valueI["valor"])));
                                    }
                                    $aImpuesto["base"]=str_replace(".", ",",$valueI["baseImpuestos"]);
                                    $oItem=new Data("comprobante_items","idComprobanteItem"); 
                                    foreach($aImpuesto  as $keyImpuesto => $valueImpuesto){
                                        $oItem->$keyImpuesto=$valueImpuesto; 
                                    }
                                    $oItem->guardar(); 
                                    unset($oItem);
                                    }
                            }

                        }
                        if ($comp==true) {
                            if (count($aImpuestoCuenta)<2) {
                                $oLista=new Data("cuenta_contable","idCuentaContable",$aImpuestoCuenta[0]["idEmpresaCuenta"]);
                                    $aCuentaImpuestoP=$oLista->getDatos();  
                                    unset($oLista); 

                                    $aImpuesto["idComprobante"]=$aVentaComprobante["idComprobante"]; 
                                    $aImpuesto["idCuentaContable"]=$aImpuestoCuenta[0]["idEmpresaCuenta"];
                                    $aImpuesto["idCentroCosto"]=" ";
                                    $aImpuesto["idSubcentroCosto"]=" ";
                                    $aImpuesto["idTercero"]=$datos["idCliente"];
                                    $aImpuesto["descripcion"]='Fact No. '.$datos["nroFactura"];
                                    $aImpuesto["naturaleza"]='debito';
                                    $aImpuesto["tipoTercero"]='p';
                                    $aImpuesto["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
                                    $aImpuesto["fecha"]=$datos["fechaFactura"]; 
                                    // if ($aCuentaImpuestoP["naturaleza"]=='debito') {
                                        $aImpuesto["saldoDebito"]=str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valueI["valor"])));
                                        $aImpuesto["saldoCredito"]=0;
                                    // }
                                    // if ($aCuentaImpuestoP["naturaleza"]=='credito') {
                                    //     $aImpuesto["saldoDebito"]=0;
                                    //     $aImpuesto["saldoCredito"]=str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valueI["valor"])));
                                    // }
                                    
                                    $aImpuesto["base"]=str_replace(".", ",",$valueI["baseImpuestos"]);

                                    $oItem=new Data("comprobante_items","idComprobanteItem"); 
                                    foreach($aImpuesto  as $keyImpuesto => $valueImpuesto){
                                        $oItem->$keyImpuesto=$valueImpuesto; 
                                    }
                                    $oItem->guardar(); 
                                    unset($oItem);

                                    $totalDeduccionesFactura=$totalDeduccionesFactura+floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valueI["valor"]))));
                            }
                        }
                    }

                    // }
                    
                    $oLista=new Lista("banco_cuenta_contable");
                    $oLista->setFiltro("idBancoCuentaContable","=",$datos["formaPagoFactura"]);
                    $oLista->setFiltro("idEmpresa","=",$_SESSION["idEmpresa"]);
                    $aCompraCuenta=$oLista->getLista(); 
                    unset($oLista);   

                    if (empty($aCompraCuenta)) {
                        // $oLista=new Lista("cuenta_contable");
                        // $oLista->setFiltro("codigoCuenta","=",'13050505');
                        // $oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
                        // $aCuentaCliente=$oLista->getLista(); 
                        // $cuent=$aCuentaCliente[0]["idCuentaContable"];
                        $comp=false;
                    }else {
                        $cuent=$aCompraCuenta[0]["idEmpresaCuenta"];
                    }

                    if ($comp==true) {
                        $saldoTotalFactura=floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$datos["totalFactura"]))))-$totalDeduccionesFactura;
                        
                        $aCompra["idComprobante"]=$aVentaComprobante["idComprobante"]; 
                        $aCompra["idCuentaContable"]=$aCompraCuenta[0]["idEmpresaCuenta"];
                        $aCompra["idCentroCosto"]=" ";
                        $aCompra["idSubcentroCosto"]=" ";
                        $aCompra["idTercero"]=$datos["idCliente"];
                        $aCompra["descripcion"]='Fact No. '.$datos["nroFactura"];
                        $aCompra["naturaleza"]='debito';
                        $aCompra["tipoTercero"]='p';
                        $aCompra["idUsuarioRegistra"]=$_SESSION["idUsuario"]; 
                        $aCompra["fecha"]=$datos["fechaFactura"]; 
                        $aCompra["saldoDebito"]=str_replace(",", ".",$saldoTotalFactura);
                        $aCompra["saldoCredito"]=0;
                        $aCompra["base"]=0;

                        $oItem=new Data("comprobante_items","idComprobanteItem"); 
                        foreach($aCompra  as $keyImpuesto => $valueImpuesto){
                            $oItem->$keyImpuesto=$valueImpuesto; 
                        }
                        $oItem->guardar(); 
                        unset($oItem);

                        $estado=1;

                    }

        }

}
$msg=true; 


echo json_encode(array("msg"=>$msg));

?>