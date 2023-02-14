<?php

require_once("../../php/restrict.php");


include_once($CLASS . "data.php");

include_once($CLASS . "lista.php");


date_default_timezone_set("America/Bogota"); 

$codigoAuxiliar  = (isset($_REQUEST['codigoAuxiliar'] ) ? $_REQUEST['codigoAuxiliar'] : "" );
$subcuenta  = (isset($_REQUEST['subcuenta'] ) ? $_REQUEST['subcuenta'] : "" );
if(!isset($_SESSION)){ session_start(); }

$oLista = new Lista('auxiliar');
$oLista->setFiltro("codigo","=",$codigoAuxiliar);
$oLista->setFiltro("idSubcuenta","=",$subcuenta);
$oLista->setFiltro("idEmpresa","=",$_SESSION["idEmpresa"]);
$aAuxiliar=$oLista->getLista();
unset($oLista);


echo json_encode($aAuxiliar[0]); 

?>
