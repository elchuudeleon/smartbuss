<?php

header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");

date_default_timezone_set("America/Bogota"); 

$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );
$item  = (isset($_REQUEST['item'] ) ? $_REQUEST['item'] : "" );
$fraCruce  = (isset($_REQUEST['fraCruce'] ) ? $_REQUEST['fraCruce'] : "" );


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
    


    $oLista=new Lista("comprobante");
    $oLista->setFiltro("idTipo","=",$datos["tipoDocumento"]);
    $oLista->setFiltro("comprobante","=",$datos["comprobante"]);
    $oLista->setFiltro("numero","=",$datos["numeroComprobante"]);
    $oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
    $aNumeroComprobante=$oLista->getLista();
    unset($oLista);

    if (empty($aNumeroComprobante)) {
        

    

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

    // $aEncript['cadena']=$idComprobante;
    // $idComprobanteEncriptado=$oControl->encriptar($aEncript);


    $nCom["numeracionActual"]=intval($numeroComprobante)+1;
    $oItem=new Data("parametros_documentos","idParametrosDocumentos",$aNumero[0]["idParametrosDocumentos"]); 

    foreach($nCom  as $keyC => $valueC){
        $oItem->$keyC=$valueC; 
    }
    $oItem->guardar(); 
    unset($oItem);

    $valorPagoRetencion=0;
    $IVAcredito=0;
    $IVAdebito=0;
    $pagoSeguridadSocial=0;
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
            if (substr($value["cuentaContable"], 0,4)=='2365' &&  $datos["tipoDocumento"]==7 ) {
                $valorPagoRetencion+=$valorN;
            }


            if (substr($value["cuentaContable"], 0,4)=='2408' &&  $datos["tipoDocumento"]==7 ) {
                if ($aItem["naturaleza"]=='credito') {

                    $IVAcredito+=$valorN;

                }
                if ($aItem["naturaleza"]=='debito') {
                    
                    $IVAdebito+=$valorN;
                }
            
            }


            if ((substr($value["cuentaContable"], 0,4)=='2380' || substr($value["cuentaContable"], 0,4)=='2370') &&  $datos["tipoDocumento"]==7 ) {
            
                    $pagoSeguridadSocial+=$valorN;    
            
            }

            if (substr($value["cuentaContable"], 0,2)=='11' ) {
                    $idCuentaPago=$value["idCuentaContable"];

                    $oLista=new Lista('banco_cuenta_contable');
                    $oLista->setFiltro('idEmpresaCuenta',"=",$idCuentaPago);
                    $oLista->setFiltro("idEmpresa","=",$datos["idEmpresa"]);
                    $bancoCuenta=$oLista->getLista();
                    unset($oLista);
                    
                   if(count($bancoCuenta)>0){
                    $idCuentaBancaria=$bancoCuenta[0]["idCuentaBancaria"]; 
                    $oLista=new Data("cuenta_bancaria","idCuentaBancaria",$bancoCuenta[0]["idCuentaBancaria"]);
                    $cuentaBancaria=$oLista->getDatos();
                    unset($oLista);

                    // $saldoActual=$cuentaBancaria["saldoActual"];
                    // $cuatropormil=$cuentaBancaria['aplicaCuatroMil'];
                    // //$idCuenta=$cuentaBancaria['idCuentaBancaria'];

                    // if ($value["naturaleza"]=='debito') {
                    //     $saldoCuenta=$value["saldoDebito"];
                    //     $nuevoSaldo=$saldoActual - $saldoCuenta;

                    //     $bDatos["valorIngreso"]=0;
                    //     $bDatos["valorEgreso"]=$saldoCuenta;
                    // }else{
                    //     $saldoCuenta=$value["saldoCredito"];
                    //     $nuevoSaldo=$saldoActual + $saldoCuenta;

                    //     $bDatos["valorIngreso"]=$saldoCuenta;
                    //     $bDatos["valorEgreso"]=0;
                    // }            

                    // $oItem=new Data("cuenta_bancaria","idCuentaBancaria",$idCuentaBancaria);
                }
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


        // foreach ($cruce as $keyc => $valuec) {

        //     if ($datos["tipoDocumento"]==7) {

        //     $oItem=new Data("factura_compra","idFacturaCompra",$valuec["idFactura"]);
        //     $facturaCompra=$oItem->getDatos();
        //     unset($oItem);



        //     if ($item[$valuec["index"]]["debito"] !="") {

        //         $valor=$item[$valuec["index"]]["debito"];

        //     }
        //     if ($item[$valuec["index"]]["credito"] !="") {

        //         $valor=$item[$valuec["index"]]["credito"]; 
        //     }

           
        //     $saldoCruce=floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valor))));
        //     $diferencia=floatVal($facturaCompra["saldo"]) - $saldoCruce;
            
        //     if ($diferencia!=0) {

        //         $aDatos["estado"]=4;
        //         $aDatos["fechaPagoReal"]=$datos["fecha"]; 
        //         $aDatos["saldo"]=$diferencia;
        //         $comprobanteEgreso='G'.$datos["comprobante"].$numeroComprobante;
        //         $aDatos["comprobanteEgreso"]=$comprobanteEgreso;

        //         $oItem=new Data("factura_compra","idFacturaCompra",$valuec["idFactura"]);
        //         foreach ($aDatos as $keyF => $valueF) {
        //             $oItem->$keyF=$valueF; 
        //         }
        //         $oItem->guardar(); 
        //         unset($oItem);

                
        //         $abonos["valor"]=$saldoCruce;
        //         $abonos["fechaRegistro"]=date("Y-m-d H:i:s");
        //         $abonos["idUsuarioRegistra"]=$_SESSION["idUsuario"];
        //         $abonos["idFacturaCompra"]=$valuec["idFactura"];
        //         $abonos["comprobanteEgreso"]=$comprobanteEgreso;

        //         $oItem=new Data("factura_compra_abono","idFacturaCompraAbono"); 
        //         foreach ($abonos as $keyA => $valueA) {
        //             $oItem->$keyA=$valueA; 
        //         }
        //         $oItem->guardar(); 
        //         unset($oItem);
                
        //         $estado=4;
        //     }
        //     if ($diferencia==0) {
        //         $aDatos["estado"]=3;
        //         $aDatos["fechaPagoReal"]=$datos["fecha"]; 
        //         $aDatos["saldo"]=0;

        //         $comprobanteEgreso='G'.$datos["comprobante"].$numeroComprobante;

        //         $aDatos["comprobanteEgreso"]=$comprobanteEgreso;

        //         $oItem=new Data("factura_compra","idFacturaCompra",$valuec["idFactura"]);
        //         foreach ($aDatos as $keyF => $valueF) {
        //             $oItem->$keyF=$valueF; 
        //         }
        //         $oItem->guardar(); 
        //         unset($oItem);

        //         $estado=3;
        //     }

        //         $aFacturaComprobante["idFacturaCompra"]=$valuec["idFactura"];
        //         $aFacturaComprobante["idComprobante"]=$idComprobante;
        //         $aFacturaComprobante["estado"]=$estado;

        //         $oItem=new Data("factura_compra_comprobante","idFacturaCompraComprobante"); 
        //         foreach($aFacturaComprobante  as $keyFC => $valueFC){
        //             $oItem->$keyFC=$valueFC; 
        //         }
        //         $oItem->guardar(); 
        //         unset($oItem);

        // }

        //     if ($datos["tipoDocumento"]==15) {

            
        //         $oItem=new Data("factura_venta","idFacturaVenta",$valuec["idFactura"]);
        //         $facturaCompra=$oItem->getDatos();
        //         unset($oItem);



        //         if ($item[$valuec["index"]]["debito"] !="") {

        //             $valor=$item[$valuec["index"]]["debito"];

        //         }
        //         if ($item[$valuec["index"]]["credito"] !="") {

        //             $valor=$item[$valuec["index"]]["credito"]; 
        //         }

        //         // $diferencia=floatVal($facturaCompra["saldo"]) - floatval($valuec["saldo"]);
        //         $saldoCruce=floatval(str_replace(",", ".",str_replace("$", "", str_replace(".", "",$valor))));
        //         $diferencia=floatVal($facturaCompra["saldo"]) - $saldoCruce;
                

        //         if ($diferencia!=0) {

        //             $aDatos["estado"]=4;
        //             $aDatos["fechaPagoReal"]=$datos["fecha"]; 
        //             $aDatos["saldo"]=$diferencia;
        //             $comprobanteEgreso='G'.$datos["comprobante"].$numeroComprobante;

        //             // $aDatos["comprobanteIngreso"]=$diferencia;

        //             $oItem=new Data("factura_venta","idFacturaVenta",$valuec["idFactura"]);
        //             foreach ($aDatos as $keyF => $valueF) {
        //                 $oItem->$keyF=$valueF; 
        //             }
        //             $oItem->guardar(); 
        //             unset($oItem);

                    
        //             $abonos["valor"]=$saldoCruce;
        //             $abonos["fechaRegistro"]=date("Y-m-d H:i:s");
        //             $abonos["idUsuarioRegistra"]=$_SESSION["idUsuario"];
        //             $abonos["idFacturaCompra"]=$valuec["idFactura"];
        //             $abonos["comprobanteEgreso"]=$comprobanteEgreso;

        //             $oItem=new Data("factura_venta_abono","idFacturaVentaAbono"); 
        //             foreach ($abonos as $keyA => $valueA) {
        //                 $oItem->$keyA=$valueA; 
        //             }
        //             $oItem->guardar(); 
        //             unset($oItem);

        //             $estado=4;
                
        //         }
        //         if ($diferencia==0) {
        //             $aDatos["estado"]=3;
        //             $aDatos["fechaPagoReal"]=$datos["fecha"]; 
        //             $aDatos["saldo"]=0;

        //             $comprobanteEgreso='G'.$datos["comprobante"].$numeroComprobante;



        //             $oItem=new Data("factura_venta","idFacturaVenta",$valuec["idFactura"]);
        //             foreach ($aDatos as $keyF => $valueF) {
        //                 $oItem->$keyF=$valueF; 
        //             }
        //             $oItem->guardar(); 
        //             unset($oItem);
        //             $estado=3;
        //         }

        //         $aFacturaComprobante["idFacturaVenta"]=$valuec["idFactura"];
        //         $aFacturaComprobante["idComprobante"]=$idComprobante;
        //         $aFacturaComprobante["estado"]=$estado;

        //         $oItem=new Data("factura_venta_comprobante","idFacturaVentaComprobante"); 
        //         foreach($aFacturaComprobante  as $keyFC => $valueFC){
        //             $oItem->$keyFC=$valueFC; 
        //         }
        //         $oItem->guardar(); 
        //         unset($oItem);
        //     }
        // }

        $msg=true; 

    }else{

        $msg=false; 
    }

// foreach($fraCruce as $iCruce){
        
//         $aDatos["fechaPagoReal"]=$datos["fechaPago"]; 
//         $oItem=new Data("factura_venta","idFacturaVenta",$datos["idFacturaVenta"]); 
//         foreach ($aDatos as $key => $value) {
//             $oItem->$key=$value; 
//         }
//         $oItem->guardar(); 
//         unset($oItem);

//         $oItem=new Data("cuenta_bancaria","idCuentaBancaria",$cuentaBancaria["idCuentaBancaria"]); 
//         $aDatos=$oItem->getDatos(); 
//         $saldoActual=$aDatos["saldoActual"];
//         $nuevoSaldo=$saldoActual + $totalFactura;
//         $cuatropormil=$aDatos['aplicaCuatroMil'];
//         unset($oItem);



//         $bDatos["idCuentaBancaria"]=$datos["cuentaBancaria"];
//         $bDatos["idTipoMovimiento"]=3;
//         $bDatos["fechaRegistro"]=date("Y-m-d H:i:s");
//         $bDatos["valorIngreso"]=$totalFactura;
//         $bDatos["valorEgreso"]=0;  
//         $bDatos["saldoAnterior"]=$saldoActual;
//         $bDatos["saldoActual"]=$nuevoSaldo;
//         $bDatos["descripcionMovimiento"]='ingreso dinero de factura de venta '.$datos["numeroFactura"]; 

//         $oItem=new Data("cuenta_bancaria_movimientos","idCuentaBancariaMovimientos"); 
//         foreach($bDatos  as $key => $value){
//             $oItem->$key=$value; 
//         }
//         $oItem->guardar();
//         unset($oItem);


//         $oItem=new Data("cuenta_bancaria","idCuentaBancaria",$datos["cuentaBancaria"]); 
//         $oItem->saldoActual=$nuevoSaldo; 
//         $oItem->guardar(); 
//         unset($oItem);
    
// }
foreach($fraCruce as $iCruce){
    $oItem=new Data("cuenta_bancaria","idCuentaBancaria",$cuentaBancaria["idCuentaBancaria"]); 
    $aDatosCuenta=$oItem->getDatos(); 
    $saldoActual=$aDatosCuenta["saldoActual"];
    $cuatropormil=$aDatosCuenta['aplicaCuatroMil'];
    unset($oItem);

    if($datos["tipoDocumento"]==7){
         $tipoMovimiento=1; 
         $totalEgreso=$iCruce["valor"]; 
         $totalIngreso=0; 
         $oItem=new Data("factura_compra","idFacturaCompra",$iCruce["idFactura"]);
         $factura=$oItem->getDatos();
         unset($oItem);


         $nuevoSaldo=$saldoActual - $iCruce["valor"];
         if($factura["saldo"]==$iCruce["valor"]){
            $aActualizacion["estado"]=3; 
            $aActualizacion["saldo"]=0; 
         }else{
            $aActualizacion["estado"]=4; 
            $aActualizacion["saldo"]=$factura["saldo"]-$iCruce["valor"]; 
            $aActualizacion["fechaPagoReal"]=$datos["fecha"]; 
         }
        $oItem=new Data("factura_compra","idFacturaCompra",$iCruce["idFactura"]); 
        foreach($aActualizacion  as $keyFC => $valueFC){
            $oItem->$keyFC=$valueFC; 
        }
        $oItem->guardar(); 
        unset($oItem);

        $bDatos["descripcionMovimiento"]='salida dinero de factura de compra '.$factura["nroFactura"];
    }else{
        $tipoMovimiento=3; 
        $totalIngreso=$iCruce["valor"]; 
        $totalEgreso=0; 
        $oItem=new Data("factura_venta","idFacturaVenta",$iCruce["idFactura"]);
        $factura=$oItem->getDatos();
        unset($oItem);

        $nuevoSaldo=$saldoActual + $iCruce["valor"];
        if($factura["saldo"]==$iCruce["valor"]){
            $aActualizacion["estado"]=3; 
            $aActualizacion["saldo"]=0; 
         }else{
            $aActualizacion["estado"]=4; 
            $aActualizacion["saldo"]=$factura["saldo"]-$iCruce["valor"];
            $aActualizacion["fechaPagoReal"]=$datos["fecha"]; 
         }
        $oItem=new Data("factura_venta","idFacturaVenta",$iCruce["idFactura"]); 
        foreach($aActualizacion  as $keyFC => $valueFC){
            $oItem->$keyFC=$valueFC; 
        }
        $oItem->guardar(); 
        unset($oItem);
        $bDatos["descripcionMovimiento"]='ingreso dinero de factura de venta '.$factura["nroFactura"];
    }

    $bDatos["idCuentaBancaria"]=$cuentaBancaria["idCuentaBancaria"];
    $bDatos["idTipoMovimiento"]=$tipoMovimiento;
    $bDatos["fechaRegistro"]=date("Y-m-d H:i:s");
    $bDatos["valorIngreso"]=$totalIngreso;
    $bDatos["valorEgreso"]=$totalEgreso;  
    $bDatos["saldoAnterior"]=$saldoActual;
    $bDatos["saldoActual"]=$nuevoSaldo;
     

    $oItem=new Data("cuenta_bancaria_movimientos","idCuentaBancariaMovimientos"); 
    foreach($bDatos  as $key => $value){
        $oItem->$key=$value; 
    }
    $oItem->guardar();
    unset($oItem);


    $oItem=new Data("cuenta_bancaria","idCuentaBancaria",$cuentaBancaria["idCuentaBancaria"]); 
    $oItem->saldoActual=$nuevoSaldo; 
    $oItem->guardar(); 
    unset($oItem);

}

    // echo json_encode(array("msg"=>$msg,"idComprobante"=>$idComprobanteEncriptado));
    echo json_encode(array("msg"=>$msg));

?>