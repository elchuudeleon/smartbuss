<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");

include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");


date_default_timezone_set("America/Bogota"); 

$aItem  = (isset($_REQUEST['itemFactura'] ) ? $_REQUEST['itemFactura'] : "" );
$items=json_decode($aItem); 
if(!isset($_SESSION)){ session_start(); }
$msg=true;
foreach($items as $item){
    
  if($item->tercero>0&&$item->estadoFinanciero=="Accepted"){
    $totalIva=0;
    foreach($item->productos as $productos){
        foreach($productos->impuestos as $impuestos){
            if($impuestos->tipoImpuesto=="IVA"){
                $totalIva+=$impuestos->valorImpuesto;
            }
        }
    }

    $oItem=new Data("usuario","numeroDocumento",$item->usuario);
    $oUser=$oItem->getDatos();
    unset($oItem);

    $aDatos["idUsuarioRegistra"]=$item->usuario==""?$_SESSION["idUsuario"]:$oUser["idUsuario"]; 
    $aDatos["fechaRegistro"]=$item->fechaRegistro; 
    $aDatos["idEmpresa"]=$_SESSION["idEmpresa"]; 
    $aDatos["fechaFactura"]=$item->fechaFactura; 
    $aDatos["idCliente"]=$item->tercero; 
    $aDatos["nroFactura"]=$item->nroFactura; 
    $aDatos["subtotal"]=$item->subtotal; 
    $aDatos["saldo"]=$item->total;
    $aDatos["descuento"]=0; 
    $aDatos["iva"]=$totalIva; 
    $aDatos["total"]=$item->total;
    $aDatos["estado"]=1; 
    $aDatos["fechaVencimiento"]=$item->fechaVencimiento==""?$item->fechaFactura:$item->fechaVencimiento;
    
    $oItem=new Data("factura_venta","idFacturaVenta"); 
    foreach($aDatos  as $key => $value){
        $oItem->$key=$value; 
    }
    $msg=$oItem->guardar(); 
    $idFactura=$oItem->ultimoId(); 
    unset($oItem);

    if($msg){
        foreach($item->productos as $productos){
            $totalIva=0; 
            foreach($productos->impuestos as $impuestos){
                if($impuestos->tipoImpuesto=="IVA"){
                    $totalIva+=$impuestos->valorImpuesto;
                }
            }
            $oLista=new Lista("producto_servicio"); 
            $oLista->setFiltro("codigo","=",$productos->codigo); 
            $oLista->setFiltro("idEmpresa","=",$_SESSION["idEmpresa"]); 
            $aProductos=$oLista->getLista(); 
            unset($oLista);

            $aProducto["idFacturaVenta"]=$idFactura; 
            $aProducto["idProductoServicio"]=$aProductos[0]["idProductoServicio"]; 
            $aProducto["detalleProducto"]=$aProductos[0]["codigo"]." - ".$aProductos[0]["nombre"]; 
            $aProducto["descripcion"]=$productos->descripcion; 
            $aProducto["idUnidad"]=1; 
            $aProducto["cantidad"]=$productos->cantidad; 
            $aProducto["valorUnitario"]=$productos->valorItem; 
            $aProducto["subtotal"]=$productos->valorItem*$productos->cantidad; 
            $aProducto["iva"]=$totalIva; 
            $aProducto["total"]=$productos->totalItem;
            
            $oItem=new Data("factura_venta_item","idFacturaVentaItem"); 
            foreach($aProducto  as $key => $value){
                $oItem->$key=$value; 
            }
            $msg=$oItem->guardar(); 
            unset($oItem);
        }
     }else{
        break; 
     }
  }
}


echo json_encode(array("msg"=>$msg));

?>