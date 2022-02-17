<?php

header('Content-type: application/json');

require_once("../../php/restrict.php");

include_once($CLASS . "data.php");

include_once($CLASS . "lista.php");

date_default_timezone_set("America/Bogota"); 

$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );
$item  = (isset($_REQUEST['item'] ) ? $_REQUEST['item'] : "" );
$cruce  = (isset($_REQUEST['cruce'] ) ? $_REQUEST['cruce'] : "" );


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

$oLista=new Lista("parametros_documentos");
$oLista->setFiltro("tipo","=",$datos["tipoDocumento"]);
$oLista->setFiltro("comprobante","=",$datos["comprobante"]);
$oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
$aNumero=$oLista->getLista();
unset($oLista);

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
$oItem=new Data("parametros_documentos","idParametrosDocumentos",$aNumero[0]["idParametrosDocumentos"]); 

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
    $aItem["idSubcentroCosto"]=$value["idSubcentroCosto"];
    $aItem["idTercero"]=$value["idTercero"]; 
    $aItem["descripcion"]=$value["descripcion"]; 
    $aItem["tipoTercero"]=$value["tipoTercero"]; 
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
    if ($datos["tipoDocumento"]==7) {
        $oItem=new Data("factura_compra","idFacturaCompra",$datos["cruceFactura"]);
        $factura=$oItem->getDatos();
        unset($oItem);

        $aItem["base"]='Cruce factura: '.$factura["nroFactura"];
        $oLista=new Data("tercero","idTercero",$factura["idProveedor"]);
        $tercero=$oLista->getDatos();
        unset($oLista);

        $razonSocialTercero=$tercero["razonSocial"];
    }
    if ($datos["tipoDocumento"]==15) {
        $oItem=new Data("factura_venta","idFacturaVenta",$datos["cruceFactura"]);
        $factura=$oItem->getDatos();
        unset($oItem);

        $aItem["base"]='Cruce factura: '.$factura["nroFactura"];
        $oLista=new Data("tercero","idTercero",$factura["idCliente"]);
        $tercero=$oLista->getDatos();
        unset($oLista);
        $razonSocialTercero=$tercero["razonSocial"];
    }
    $operacionNaturaleza=$value["naturaleza"];
        $oItem=new Data("comprobante_items","idComprobanteItem"); 
        foreach($aItem  as $keycc => $valuecc){
            $oItem->$keycc=$valuecc; 
        }
        $oItem->guardar(); 
        unset($oItem);

        if (substr($value["cuentaContable"], 0,2)=='11' &&  $datos["tipoDocumento"]==7 ) {
            $idCuentaPago=$value["idCuentaContable"];

            $oLista=new Lista('banco_cuenta_contable');
            $oLista->setFiltro('idEmpresaCuenta',"=",$idCuentaPago);
            $oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
            $bancoCuenta=$oLista->getLista();
            unset($oLista);

            $oLista=new Data("cuenta_bancaria","idCuentaBancaria",$bancoCuenta[0]["idCuentaBancaria"]);
            $cuentaBancaria=$oLista->getDatos();
            unset($oLista);

            $totalFactura=$valorN;
            $saldoActual=$cuentaBancaria["saldoActual"];
            $nuevoSaldo=$saldoActual - $totalFactura;
            $cuatropormil=$cuentaBancaria['aplicaCuatroMil'];    
            $idCuenta=$cuentaBancaria['idCuentaBancaria'];        

            $bDatos["idCuentaBancaria"]=$idCuenta;
            $bDatos["idTipoMovimiento"]=1;
            $bDatos["fechaRegistro"]=date("Y-m-d H:i:s");
            $bDatos["valorIngreso"]=0;
            $bDatos["valorEgreso"]=$totalFactura;  
            $bDatos["saldoAnterior"]=$saldoActual;
            $bDatos["saldoActual"]=$nuevoSaldo;
            $bDatos["descripcionMovimiento"]='pago de factura '.$factura["nroFactura"].' del proveedor '.$razonSocialTercero; 

            $oItem=new Data("cuenta_bancaria_movimientos","idCuentaBancariaMovimientos"); 
                foreach($bDatos  as $key => $value){
                    $oItem->$key=$value; 
                }
                $oItem->guardar(); 
               
                unset($oItem);


            if ($cuatropormil==1) {

                $valorcuatromil = $totalFactura*4/1000;
                $nuevoSaldoActual = $nuevoSaldo - $valorcuatromil;
                $bDatos["idCuentaBancaria"]=$idCuenta;
                $bDatos["idTipoMovimiento"]=1;
                $bDatos["fechaRegistro"]=date("Y-m-d H:i:s");
                $bDatos["valorIngreso"]=0;
                $bDatos["valorEgreso"]=$valorcuatromil;  
                $bDatos["saldoAnterior"]=$nuevoSaldo;
                $bDatos["saldoActual"]=$nuevoSaldoActual;
                $bDatos["descripcionMovimiento"]='pago de 4xmil de factura '.$factura["nroFactura"].' del proveedor '.$razonSocialTercero; 

                $oItem=new Data("cuenta_bancaria_movimientos","idCuentaBancariaMovimientos"); 
                foreach($bDatos  as $key => $value){
                    $oItem->$key=$value; 
                }
                $oItem->guardar(); 
                unset($oItem);
                $nuevoSaldo = $nuevoSaldoActual;
            }


            $oItem=new Data("cuenta_bancaria","idCuentaBancaria",$bancoCuenta[0]["idCuentaBancaria"]);
            $oItem->saldoActual=$nuevoSaldo; 
            $oItem->guardar(); 
            unset($oItem);
        }

        if (substr($value["cuentaContable"], 0,2)=='11' &&  $datos["tipoDocumento"]==15 ) {   

        $idCuentaPago=$value["idCuentaContable"];

        $oLista=new Lista('banco_cuenta_contable');
        $oLista->setFiltro('idEmpresaCuenta',"=",$idCuentaPago);
        $oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
        $bancoCuenta=$oLista->getLista();
        unset($oLista);

        $oLista=new Data("cuenta_bancaria","idCuentaBancaria",$bancoCuenta[0]["idCuentaBancaria"]);
        $cuentaBancaria=$oLista->getDatos();
        unset($oLista);
        
        $totalFactura=$valorN;

        $saldoActual=$cuentaBancaria["saldoActual"];
        $nuevoSaldo=$saldoActual + $totalFactura;
        $cuatropormil=$cuentaBancaria['aplicaCuatroMil'];
        $idCuenta=$cuentaBancaria['idCuentaBancaria']; 


        $bDatos["idCuentaBancaria"]=$idCuenta;
        $bDatos["idTipoMovimiento"]=3;
        $bDatos["fechaRegistro"]=date("Y-m-d H:i:s");
        $bDatos["valorIngreso"]=$totalFactura;
        $bDatos["valorEgreso"]=0;  
        $bDatos["saldoAnterior"]=$saldoActual;
        $bDatos["saldoActual"]=$nuevoSaldo;
        $bDatos["descripcionMovimiento"]='pago de factura '.$factura["nroFactura"].' del cliente '.$razonSocialTercero; 

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

    }


    if ($datos["radioComprobanteRecurrente"]==1) {
        
        $recurrente["idComprobante"]=$idComprobante;
        $recurrente["idEmpresa"]=$datos["idEmpresa"];
        $recurrente["nombre"]=$datos["nombreComprobante"];

        $oItem=new Data("comprobante_recurrente","idComprobanteRecurrente"); 
        foreach($recurrente  as $keyr => $valuer){
            $oItem->$keyr=$valuer; 
        }
        $oItem->guardar(); 
        unset($oItem);
    }


    foreach ($cruce as $keyc => $valuec) {

        if ($datos["tipoDocumento"]==7) {

        $oItem=new Data("factura_compra","idFacturaCompra",$valuec["idFactura"]);
        $facturaCompra=$oItem->getDatos();
        unset($oItem);



        if ($item[$valuec["index"]]["debito"] !="") {

            $valor=$item[$valuec["index"]]["debito"];

        }
        if ($item[$valuec["index"]]["credito"] !="") {

            $valor=$item[$valuec["index"]]["credito"]; 
        }

       
        $saldoCruce=floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valor))));
        $diferencia=floatVal($facturaCompra["saldo"]) - $saldoCruce;
        
        if ($diferencia!=0) {

            $aDatos["estado"]=4;
            $aDatos["fechaPagoReal"]=$datos["fecha"]; 
            $aDatos["saldo"]=$diferencia;
            $comprobanteEgreso='G'.$datos["comprobante"].$numeroComprobante;
            $aDatos["comprobanteEgreso"]=$comprobanteEgreso;

            $oItem=new Data("factura_compra","idFacturaCompra",$valuec["idFactura"]);
            foreach ($aDatos as $keyF => $valueF) {
                $oItem->$keyF=$valueF; 
            }
            $oItem->guardar(); 
            unset($oItem);

            
            $abonos["valor"]=$saldoCruce;
            $abonos["fechaRegistro"]=date("Y-m-d H:i:s");
            $abonos["idUsuarioRegistra"]=$_SESSION["idUsuario"];
            $abonos["idFacturaCompra"]=$valuec["idFactura"];
            $abonos["comprobanteEgreso"]=$comprobanteEgreso;

            $oItem=new Data("factura_compra_abono","idFacturaCompraAbono"); 
            foreach ($abonos as $keyA => $valueA) {
                $oItem->$keyA=$valueA; 
            }
            $oItem->guardar(); 
            unset($oItem);
            
            $estado=4;
        }
        if ($diferencia==0) {
            $aDatos["estado"]=3;
            $aDatos["fechaPagoReal"]=$datos["fecha"]; 
            $aDatos["saldo"]=0;

            $comprobanteEgreso='G'.$datos["comprobante"].$numeroComprobante;

            $aDatos["comprobanteEgreso"]=$comprobanteEgreso;

            $oItem=new Data("factura_compra","idFacturaCompra",$valuec["idFactura"]);
            foreach ($aDatos as $keyF => $valueF) {
                $oItem->$keyF=$valueF; 
            }
            $oItem->guardar(); 
            unset($oItem);

            $estado=3;
        }

            $aFacturaComprobante["idFacturaCompra"]=$valuec["idFactura"];
            $aFacturaComprobante["idComprobante"]=$idComprobante;
            $aFacturaComprobante["estado"]=$estado;

            $oItem=new Data("factura_compra_comprobante","idFacturaCompraComprobante"); 
            foreach($aFacturaComprobante  as $keyFC => $valueFC){
                $oItem->$keyFC=$valueFC; 
            }
            $oItem->guardar(); 
            unset($oItem);

    }

        if ($datos["tipoDocumento"]==15) {

        
            $oItem=new Data("factura_venta","idFacturaVenta",$valuec["idFactura"]);
            $facturaCompra=$oItem->getDatos();
            unset($oItem);



            if ($item[$valuec["index"]]["debito"] !="") {

                $valor=$item[$valuec["index"]]["debito"];

            }
            if ($item[$valuec["index"]]["credito"] !="") {

                $valor=$item[$valuec["index"]]["credito"]; 
            }

            // $diferencia=floatVal($facturaCompra["saldo"]) - floatval($valuec["saldo"]);
            $saldoCruce=floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valor))));
            $diferencia=floatVal($facturaCompra["saldo"]) - $saldoCruce;
            

            if ($diferencia!=0) {

                $aDatos["estado"]=4;
                $aDatos["fechaPagoReal"]=$datos["fecha"]; 
                $aDatos["saldo"]=$diferencia;
                $comprobanteEgreso='G'.$datos["comprobante"].$numeroComprobante;

                // $aDatos["comprobanteIngreso"]=$diferencia;

                $oItem=new Data("factura_venta","idFacturaVenta",$valuec["idFactura"]);
                foreach ($aDatos as $keyF => $valueF) {
                    $oItem->$keyF=$valueF; 
                }
                $oItem->guardar(); 
                unset($oItem);

                
                $abonos["valor"]=$saldoCruce;
                $abonos["fechaRegistro"]=date("Y-m-d H:i:s");
                $abonos["idUsuarioRegistra"]=$_SESSION["idUsuario"];
                $abonos["idFacturaCompra"]=$valuec["idFactura"];
                $abonos["comprobanteEgreso"]=$comprobanteEgreso;

                $oItem=new Data("factura_venta_abono","idFacturaVentaAbono"); 
                foreach ($abonos as $keyA => $valueA) {
                    $oItem->$keyA=$valueA; 
                }
                $oItem->guardar(); 
                unset($oItem);

                $estado=4;
            
            }
            if ($diferencia==0) {
                $aDatos["estado"]=3;
                $aDatos["fechaPagoReal"]=$datos["fecha"]; 
                $aDatos["saldo"]=0;

                $comprobanteEgreso='G'.$datos["comprobante"].$numeroComprobante;



                $oItem=new Data("factura_venta","idFacturaVenta",$valuec["idFactura"]);
                foreach ($aDatos as $keyF => $valueF) {
                    $oItem->$keyF=$valueF; 
                }
                $oItem->guardar(); 
                unset($oItem);
                $estado=3;
            }

            $aFacturaComprobante["idFacturaVenta"]=$valuec["idFactura"];
            $aFacturaComprobante["idComprobante"]=$idComprobante;
            $aFacturaComprobante["estado"]=$estado;

            $oItem=new Data("factura_venta_comprobante","idFacturaVentaComprobante"); 
            foreach($aFacturaComprobante  as $keyFC => $valueFC){
                $oItem->$keyFC=$valueFC; 
            }
            $oItem->guardar(); 
            unset($oItem);
        }
    }

    $msg=true; 



    echo json_encode(array("msg"=>$msg));

?>