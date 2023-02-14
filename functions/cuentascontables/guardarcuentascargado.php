<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");
date_default_timezone_set("America/Bogota"); 


$idEmpresa  = (isset($_REQUEST['idEmpresa'] ) ? $_REQUEST['idEmpresa'] : "" );
$aItem  = (isset($_REQUEST['item'] ) ? $_REQUEST['item'] : "" );

$aItem=json_decode($aItem);  
if(!isset($_SESSION)){ session_start(); }
$noRegister=array(); 
foreach ($aItem as $key => $value) {
    $codigo=str_replace("-","",$value->codigo); 
    $codigoFragmento=explode("-",$value->codigo); 

    $oLista = new Lista('cuenta_contable');
    $oLista->setFiltro("codigoCuenta","=",$codigo);
    $oLista->setFiltro("idEmpresa","=",$idEmpresa);
    $aExiste=$oLista->getLista();
    unset($oLista);

    if(empty($aExiste)){

        $tamanoCodigo=strlen($codigo)-2;

        $oLista = new Lista('grupo_contable');
        $oLista->setFiltro("codigo","=",$codigoFragmento[0]);
        //$oLista->setFiltro("idEmpresa","=",$aDatos["idEmpresa"]);
        $aGrupo=$oLista->getLista();
        unset($oLista);

        if($tamanoCodigo>2){
            $oLista = new Lista('cuenta');
            $oLista->setFiltro("codigo","=",$codigoFragmento[1]);
            $oLista->setFiltro("idGrupo","=",$aGrupo[0]["idGrupo"]);
            $aCuenta=$oLista->getLista();
            unset($oLista);

            $oLista = new Lista('subcuenta');
            $oLista->setFiltro("codigo","=",$codigoFragmento[2]);
            $oLista->setFiltro("idCuenta","=",$aCuenta[0]["idCuenta"]);
            $aSubcuenta=$oLista->getLista();
            unset($oLista);

            if($tamanoCodigo==8){
                $oLista = new Lista('auxiliar');
                $oLista->setFiltro("codigo","=",$codigoFragmento[3]);
                $oLista->setFiltro("idSubcuenta","=",$aSubcuenta[0]["idCuenta"]);
                $oLista->setFiltro("idEmpresa","=",$idEmpresa);
                $aAuxiliar=$oLista->getLista();
                unset($oLista);
            }
        }
        

        //if(!empty($aCuenta)){
            $data["codigoCuenta"]=$codigo;
            $data["nombre"]=$value->nombre;
            $data["naturaleza"]=$value->naturaleza;
            $data["tipoCuenta"]=$value->clase;
            $data["detalle"]=$value->detalle;
            $data["tercero"]=$value->tercero;
            $data["idEmpresa"]=$idEmpresa;
            $data["centroCosto"]=$value->costo=="SI"?1:0;
            $data["porcentajeRetencion"]=$value->retencion;

            $oItem=new Data("cuenta_contable","idCuentaContable");
            foreach($data as $keyD => $valueD){
                $oItem->$keyD=$valueD; 
            }
            $oItem->guardar();
            //$idCuenta=$oItem->ultimoId();
            unset($oItem);

            if($tamanoCodigo==2){
                $cuenta["codigo"]=$codigoFragmento[count($codigoFragmento)-1];
                $cuenta["denominacion"]=$value->nombre;
                $cuenta["idGrupo"]=$aGrupo[0]["idGrupo"];
                
                $oItem=new Data("cuenta","idCuenta");
                foreach($cuenta as $keys => $values){
                    $oItem->$keys=$values; 
                }
                $validar=$oItem->guardar();

                if($validar){

                }else{
                    $noRegister[]=$value->codigo;
                }
                unset($oItem);
            }else  if($tamanoCodigo==4){
                $subcuenta["codigo"]=$codigoFragmento[count($codigoFragmento)-1];
                $subcuenta["denominacion"]=$value->nombre;
                $subcuenta["idCuenta"]=$aCuenta[0]["idCuenta"];
                
                $oItem=new Data("subcuenta","idSubcuenta");
                foreach($subcuenta as $keys => $values){
                    $oItem->$keys=$values; 
                }
                $validar=$oItem->guardar();

                if($validar){

                }else{
                    $noRegister[]=$value->codigo;
                }
                unset($oItem);
            }else if($tamanoCodigo==6){
                $auxiliar["codigo"]=$codigoFragmento[count($codigoFragmento)-1];
                $auxiliar["denominacion"]=$value->nombre;
                $auxiliar["idSubcuenta"]=$aSubcuenta[0]["idSubcuenta"];
                $auxiliar["idEmpresa"]=$idEmpresa;
                
                $oItem=new Data("auxiliar","idAuxiliar");
                foreach($auxiliar as $keyA => $valueA){
                    $oItem->$keyA=$valueA; 
                }
                $validar=$oItem->guardar();

                if($validar){

                }else{
                    $noRegister[]=$value->codigo;
                }
                unset($oItem);
            }else if($tamanoCodigo==8){
                $subauxiliar["codigo"]=$codigoFragmento[count($codigoFragmento)-1];
                $subauxiliar["denominacion"]=$value->nombre;
                $subauxiliar["idAuxiliar"]=$aAuxiliar[0]["idAuxiliar"];
                $subauxiliar["idEmpresa"]=$idEmpresa;
                
                $oItem=new Data("subauxiliar","idSubauxiliar");
                foreach($subauxiliar as $keyS => $valueS){
                    $oItem->$keyS=$valueS; 
                }
                $validar=$oItem->guardar();

                if($validar){

                }else{
                    $noRegister[]=$value->codigo;
                }
                unset($oItem);
            }
        // }else{
        //     $cuentasNoExiste[]=$value->codigo;
        // }
        
    }else{
        $noRegister[]=$value->codigo; 
    }
    
}
$msg=true; 
echo json_encode(array("msg"=>$msg,"noRegister"=>$noRegister));

?>