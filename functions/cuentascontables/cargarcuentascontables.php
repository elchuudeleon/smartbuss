<?php

require_once("../../php/restrict.php");
include_once($CLASS . "data.php");
include_once($CLASS . "lista.php");
require_once("../../class/cuentascontables.php");


date_default_timezone_set("America/Bogota"); 

$idEmpresa  = (isset($_REQUEST['idEmpresa'] ) ? $_REQUEST['idEmpresa'] : "" );

$oCuentasContables=new CuentasContables(); 
$aCuentasContables=$oCuentasContables->getCuentasContables(array("idEmpresa"=>$idEmpresa));

foreach($aCuentasContables as $index=>$aCuentas){
	$aCuentasContables[$index]["codigoCuentaContable"]=str_pad($aCuentas["codigoCuentaContable"],10, "0"); 
}
echo json_encode($aCuentasContables); 

?>
