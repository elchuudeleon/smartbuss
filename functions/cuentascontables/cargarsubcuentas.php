<?php

require_once("../../php/restrict.php");


include_once($CLASS . "data.php");

include_once($CLASS . "lista.php");


date_default_timezone_set("America/Bogota"); 

$id  = (isset($_REQUEST['id'] ) ? $_REQUEST['id'] : "" );

if(!isset($_SESSION)){ session_start(); }

$oLista = new Lista('subcuenta');
$oLista->setFiltro("idCuenta","=",$id);
$oLista->setFiltro("idEmpresa","=",$_SESSION["idEmpresa"]);
$aSubcuenta=$oLista->getLista();
unset($oLista);

if(empty($aSubcuenta)){
	$oLista = new Lista('subcuenta');
	$oLista->setFiltro("idCuenta","=",$id);
	$oLista->setFiltro("idEmpresa","=",0);
	$oLista->setGrupo("codigo");
	$aSubcuenta1=$oLista->getLista();
	unset($oLista);

	$oLista = new Lista('subcuenta');
	$oLista->setFiltro("idCuenta","=",$id);
	$oLista->setFiltro("idEmpresa","!=",$_SESSION["idEmpresa"]);
	$oLista->setFiltro("idEmpresa","!=",0);
	$oLista->setGrupo("codigo");
	$aSubcuenta2=$oLista->getLista();
	unset($oLista);	
	$aSubcuenta=$aSubcuenta1;
	foreach($aSubcuenta2 as $iSubcuenta2){
		$agregar=true;
		foreach($aSubcuenta1 as $iSubcuenta1){
			if($iSubcuenta1["codigo"]==$iSubcuenta2["codigo"]){
				$agregar=false;
				break;
			}
		}
		if($agregar){
			$aSubcuenta[]=$iSubcuenta2;
		}
		
	}
	
	$longitud = count($aSubcuenta);
    for ($i = 0; $i < $longitud; $i++) {
        for ($j = 0; $j < $longitud - 1; $j++) {
            if ($aSubcuenta[$j]["codigo"] > $aSubcuenta[$j + 1]["codigo"]) {
                $temporal = $aSubcuenta[$j];
                $aSubcuenta[$j] = $aSubcuenta[$j + 1];
                $aSubcuenta[$j + 1] = $temporal;
            }
        }
    }
}

echo json_encode($aSubcuenta); 

?>
