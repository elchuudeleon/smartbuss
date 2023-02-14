<?php

require_once("../../php/restrict.php");


include_once($CLASS . "data.php");

include_once($CLASS . "lista.php");


date_default_timezone_set("America/Bogota"); 

$codigoSubauxiliar  = (isset($_REQUEST['codigoSubauxiliar'] ) ? $_REQUEST['codigoSubauxiliar'] : "" );
$auxiliar  = (isset($_REQUEST['auxiliar'] ) ? $_REQUEST['auxiliar'] : "" );
if(!isset($_SESSION)){ session_start(); }

$oLista = new Lista('subauxiliar');
$oLista->setFiltro("codigo","=",$codigoSubauxiliar);
$oLista->setFiltro("idAuxiliar","=",$auxiliar);
$oLista->setFiltro("idEmpresa","=",$_SESSION["idEmpresa"]);
$aSubauxiliar=$oLista->getLista();
unset($oLista);


echo json_encode($aSubauxiliar[0]); 

?>
