<?php
require_once("../../php/restrict.php");

include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");
require_once("../../class/empresa.php"); 


$oEmpresa=new Empresa(); 
date_default_timezone_set("America/Bogota"); 

$anio  = (isset($_REQUEST['anio'] ) ? $_REQUEST['anio'] : "" );
$idEmpresa  = (isset($_REQUEST['idEmpresa'] ) ? $_REQUEST['idEmpresa'] : "" );


for ($i=1; $i <= 12 ; $i++) { 

$oLista = new Lista('balance_general');
$oLista->setFiltro("idEmpresa","=",$idEmpresa);
$oLista->setFiltro("periodoMes","=",$i);
$oLista->setFiltro("periodoAnio","=",$anio);
$aBalance=$oLista->getLista();
unset($oLista);		


	
$oLista = new Lista('balance_general_item');
$oLista->setFiltro("idBalanceGeneral","=",$aBalance[0]["idBalanceGeneral"]);
$aItems=$oLista->getLista();
unset($oLista);

	$pos=0; 


	foreach ($aItems as $key => $value) {

		
		if ($value["idBalanceGeneralItem"]!="") {
		$oLista = new Lista('balance_general_cuenta');
		$oLista->setFiltro("idBalanceGeneralItem","=",$value["idBalanceGeneralItem"]);
		$oLista->setFiltro("tipo","=",4);
		$oLista->setOrden("numeroCuenta","ASC");
		$aCuentas=$oLista->getLista();
		unset($oLista);
		}

		if ($aCuentas!="") {	

		foreach($aCuentas as  $iCuenta){
			if($iCuenta["numeroCuenta"]==36){
				//var_dump($i, $value["idBalanceGeneralItem"], $iCuenta); 
			}

			if ($iCuenta["numeroCuenta"]!="") {
				$iArray[$iCuenta["numeroCuenta"]]["numeroCuenta"]=$iCuenta["numeroCuenta"];
				$iArray[$iCuenta["numeroCuenta"]]["tipo"]=$iCuenta["tipo"];

			}
			if ($iCuenta["numeroCuenta"]!="") {
				$iArray[$iCuenta["numeroCuenta"]]["nombreCuenta"]=$iCuenta["nombreCuenta"];

			}
			if (empty($aBalance)) {
				$iArray[$iCuenta["numeroCuenta"]][$i]["valor"]="-";
		
			}
			if (!empty($aBalance)) {
				$iArray[$iCuenta["numeroCuenta"]][$i]["valor"]="$".number_format($iCuenta["valor"]/1000,0,",",".");
			}

			$pos++; 

		}
	}


	}

}

$aArray=array(); 
foreach($iArray as $array){
	$aArray[]=$array; 
}
$longitud = count($aArray);
for ($i = 0; $i < $longitud; $i++) {
    for ($j = 0; $j < $longitud - 1; $j++) {
        if ($aArray[$j]["numeroCuenta"] > $aArray[$j + 1]["numeroCuenta"]) {
            $temporal = $aArray[$j];
            $aArray[$j] = $aArray[$j + 1];
            $aArray[$j + 1] = $temporal;
        }
    }
}
echo json_encode($aArray);
?>