<?php
header('Content-type: application/json');
require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");

date_default_timezone_set("America/Bogota"); 

$datos  = (isset($_REQUEST['datos'] ) ? $_REQUEST['datos'] : "" );
$item  = (isset($_REQUEST['item'] ) ? $_REQUEST['item'] : "" );


$oItem=new Data("tercero","nit",$datos["nit"]); 
$aValidate=$oItem->getDatos(); 
unset($oItem); 

if(!isset($_SESSION)){ session_start(); }
if(empty($aValidate)){


    if ($datos["idDepartamentoExtranjero"]!="") {
        $aDepartamento["nombre"]=$datos["idDepartamentoExtranjero"];
        $aDepartamento["idPais"]=$datos["idPais"];
        $oItem=new Data("departamento","idDepartamento"); 
        foreach($aDepartamento  as $keyd => $valued){
            $oItem->$keyd=$valued; 
        }
        $msg=$oItem->guardar(); 
        $idDepartamentoExtranjero=$oItem->ultimoId(); 
        unset($oItem);
        
    }

    if($msg){
        if ($datos["idCiudadExtranjero"]!="") {
            $aCiudad["nombre"]=$datos["idCiudadExtranjero"];
            $aCiudad["idDepartamento"]=$idDepartamentoExtranjero;
            
            $oItem=new Data("ciudad","idCiudad"); 
            foreach($aCiudad  as $keyc => $valuec){
                $oItem->$keyc=$valuec; 
            }
            $msg=$oItem->guardar(); 
            $idCiudadExtranjero=$oItem->ultimoId(); 
            unset($oItem);
        }

        if($msg){
            $aDatos["tipoPersona"]=$datos["tipoPersona"]; 
            $aDatos["nit"]=$datos["nit"]; 
            $aDatos["digitoVerificador"]=$datos["digitoVerificador"]==""?0:$datos["digitoVerificador"]; 
            $aDatos["razonSocial"]=$datos["razonSocial"]; 
            if ($datos["email"]!="") {
                 $aDatos["email"]=$datos["email"]; 
            }
            if($datos["email"]=="") {
                $aDatos["email"]="";
            }

            $aDatos["telefono"]=$datos["telefono"]; 
            if ($datos["extranjero"]==1) {
                $aDatos["idDepartamento"]=$idDepartamentoExtranjero;
                $aDatos["idCiudad"]=$idCiudadExtranjero;
                if ($datos["idPais"]!="") {
                    $aDatos["idPais"]=$datos["idPais"];
                }
            }
            if ($datos["extranjero"]!=1) {
                $aDatos["idDepartamento"]=$datos["idDepartamento"]; 
                $aDatos["idCiudad"]=$datos["idCiudad"]; 
                $aDatos["idPais"]=42;
            }

            $aDatos["responsableIva"]=$datos["responsableIva"]; 
            $aDatos["direccion"]=$datos["direccion"];
            $aDatos["fechaRegistro"]=date("Y-m-d H:i:s");
            $aDatos["idUsuarioRegistra"]=$_SESSION["idUsuario"];
            $aDatos["periodoPago"]=30;

            if ($datos["checkCliente"]==1) {
                $aDatos["tipoTercero"]=4; 
            }
            if ($datos["checkCliente"]!=1) {
                $aDatos["tipoTercero"]=2; 
            }

            $oItem=new Data("tercero","idTercero"); 
            foreach($aDatos  as $key => $value){
                $oItem->$key=$value; 
            }
            $msg=$oItem->guardar(); 
            $idTercero=$oItem->ultimoId(); 
            unset($oItem);

            if($msg){
                foreach ($item as $key => $value) {
                    if($value["estado"]==1){
                    $oItem=new Data("tercero_empresa","idTerceroEmpresa"); 
                    $oItem->idTercero=$idTercero; 
                    $oItem->idEmpresa=$value["idEmpresa"]; 
                    $msg=$oItem->guardar(); 
                    unset($oItem); 
                    }

                }
            }
            
        }
        
    }
    

}else{

    $oItem=new Lista("tercero_empresa");
    $oItem->setFiltro("idTercero","=",$aValidate['idTercero']); 
    $oItem->setFiltro("idEmpresa","=",$_SESSION["idEmpresa"]); 
    $terceroE=$oItem->getLista();
    unset($oItem);

    if (empty($terceroE)) {
        
        $oItem=new Data("tercero_empresa","idTerceroEmpresa"); 
        $oItem->idTercero=$aValidate['idTercero']; 
        $oItem->idEmpresa=$_SESSION["idEmpresa"];
        $msg=$oItem->guardar(); 
        unset($oItem); 

    }

    if (!empty($terceroE)) {
        $msg=false; 
    }
    

}


echo json_encode(array("msg"=>$msg));

?>