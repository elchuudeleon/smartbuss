<?php

require_once($CLASS."sql.php"); 



class Informes extends Sql{

	public function getBalanceComprobacionAcumuladoSA($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		// if($aDatos["desde"]!=""){ 

		// 	$condicion.=" AND ccm.fecha >='".$aDatos["desde"]."'";

		// }
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,8) ='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,10) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}

	public function getBalanceComprobacionSA($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha >='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}

		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,10) ='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,10) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}
	public function getBalanceComprobacionAnteriorSA($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha >='".$aDatos["desde"]."'";

		}
		if($aDatos["fecha"]!=""){ 

			$condicion.=" AND ccm.fecha <'".$aDatos["fecha"]."'";

		}

		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,10) ='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,10) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}
public function getBalanceComprobacionAcumuladoA($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}

		
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,6) ='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,8) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,8) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}

public function getBalanceComprobacionA($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}

		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha >='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,8) ='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,8) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,8) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}

public function getBalanceComprobacionAnteriorA($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha >='".$aDatos["desde"]."'";

		}
		if($aDatos["fecha"]!=""){ 

			$condicion.=" AND ccm.fecha <'".$aDatos["fecha"]."'";

		}

		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,8) ='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,8) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,8) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}


	public function getBalanceComprobacionAcumuladoSC($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		
		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,4) ='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,6) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,6) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}

	
	public function getBalanceComprobacionSC($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,6) ='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,6) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,6) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}

	public function getBalanceComprobacionAnteriorSC($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha >='".$aDatos["desde"]."'";

		}
		if($aDatos["fecha"]!=""){ 

			$condicion.=" AND ccm.fecha <'".$aDatos["fecha"]."'";

		}

		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,6) ='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,6) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,6) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}
	public function getBalanceComprobacionAcumuladoC($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
	

		$sql="SELECT  MIN(cc.nombre) as nombre,substring(cc.codigoCuenta,1,4) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,4) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 
	    return $aBalanceComprobacion; 

	}
	public function getBalanceComprobacionC($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,4) ='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,4) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,4) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}
	public function getBalanceComprobacionAnteriorC($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		
		if($aDatos["fecha"]!=""){ 

			$condicion.=" AND ccm.fecha <'".$aDatos["fecha"]."'";

		}

		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,4) ='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,4) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,4) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}
	
	public function getBalanceComprobacionG($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}

	
		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,2) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,ccm.fecha
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,2) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}

	public function getBalanceComprobacionAnteriorG($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha >='".$aDatos["desde"]."'";

		}
		if($aDatos["fecha"]!=""){ 

			$condicion.=" AND ccm.fecha <'".$aDatos["fecha"]."'";

		}

		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) ='".$aDatos["cuenta"]."'";

		}

		
		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,2) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,2) ORDER BY cc.codigoCuenta ASC";



	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}
	public function getBalanceComprobacionGrupoAcumulado($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		// if($aDatos["desde"]!=""){ 

		// 	$condicion.=" AND ccm.fecha >='".$aDatos["desde"]."'";

		// }
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}

		$sql="SELECT gc.denominacion,substring(cc.codigoCuenta,1,2) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		INNER JOIN grupo_contable gc on gc.codigo=substring(cc.codigoCuenta,1,2)
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,2) ORDER BY cc.codigoCuenta ASC";




	    $aBalanceComprobacionG=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacionG; 

	}
	public function getBalanceComprobacionGrupo($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha >='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2)='".$aDatos["cuenta"]."'";

		}

		$sql="SELECT gc.denominacion,substring(cc.codigoCuenta,1,2) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		INNER JOIN grupo_contable gc on gc.codigo=substring(cc.codigoCuenta,1,2)
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,2) ORDER BY cc.codigoCuenta ASC";




	    $aBalanceComprobacionG=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacionG; 

	}


	public function getBalanceComprobacionAcumuladoT($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}

		// if($aDatos["desde"]!=""){ 

		// 	$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		// }
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}


		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza,ccm.tipoTercero,ccm.idTercero
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." 
		GROUP BY cc.codigoCuenta,ccm.idTercero, ccm.tipoTercero
		ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}
	public function getBalanceComprobacionT($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}

		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,10) ='".$aDatos["cuenta"]."'";

		}
		if($aDatos["tercero"]!=""){ 

			$condicion.=" AND ccm.idTercero ='".$aDatos["tercero"]."'";

		}
		if($aDatos["tipoTercero"]!=""){ 

			$condicion.=" AND ccm.tipoTercero ='".$aDatos["tipoTercero"]."'";

		}

		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza,ccm.tipoTercero,ccm.idTercero
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." 
		GROUP BY cc.codigoCuenta,ccm.idTercero, ccm.tipoTercero
		ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}

	public function getBalanceComprobacionTAnterior($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}

		if($aDatos["fecha"]!=""){ 

			$condicion.=" AND ccm.fecha <'".$aDatos["fecha"]."'";

		}

		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,10) ='".$aDatos["cuenta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) >3";

		}
		if($aDatos["tercero"]!=""){ 

			$condicion.=" AND ccm.idTercero ='".$aDatos["tercero"]."'";

		}
		if($aDatos["tipoTercero"]!=""){ 

			$condicion.=" AND ccm.tipoTercero ='".$aDatos["tipoTercero"]."'";

		}


		$sql="SELECT  cc.nombre,substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza,ccm.tipoTercero,ccm.idTercero
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." 
		GROUP BY cc.codigoCuenta,ccm.idTercero, ccm.tipoTercero
		ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}

	public function getActivoCorriente($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) <=13";
			$condicion.=" AND substring(cc.codigoCuenta,1,2) >0 ";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}

	public function getActivoNoCorriente($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) =14";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}



	public function getActivoFijo($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) =15";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}



	public function getPasivoCorriente($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) >20 AND substring(cc.codigoCuenta,1,2) <27";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}


	public function getOtrosPasivos($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) =28 ";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}




	
	public function getActivo($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) <20 ";
			$condicion.=" AND substring(cc.codigoCuenta,1,2) >0 ";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}
	public function getPasivo($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) >20 AND substring(cc.codigoCuenta,1,2) <30 ";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}
	public function getPatrimonio($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["situacion"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) <=3";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) >30 AND substring(cc.codigoCuenta,1,2) <40 ";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}

	//-----------------------------------------------------------------------------------------------------------------


	public function getIngresosResultado($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) =4";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) >30 AND substring(cc.codigoCuenta,1,2) <40 ";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}




	public function getGastosResultado($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) =5";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) >30 AND substring(cc.codigoCuenta,1,2) <40 ";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}





	public function getCostosVentasResultado($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) =6";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) >30 AND substring(cc.codigoCuenta,1,2) <40 ";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}




	public function getCostosProduccionResultado($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["resultados"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,1) =7";

		}
		if($aDatos["corriente"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,2) >30 AND substring(cc.codigoCuenta,1,2) <40 ";

		}

		$sql="SELECT  cc.idEmpresa,sum(ccm.saldoDebito) as saldoDebito, sum(ccm.saldoCredito) as saldoCredito
		FROM cuenta_contable cc
		
		INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
		WHERE 0=0 ".$condicion." GROUP BY substring(cc.codigoCuenta,1,1) ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}


	public function getTerceroDetalladoCuentaAcumulado($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		// if($aDatos["desde"]!=""){ 

		// 	$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		// }
		if($aDatos["fecha"]!=""){ 

			$condicion.=" AND ccm.fecha <'".$aDatos["fecha"]."'";

		}
		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,10)='".$aDatos["cuenta"]."'";

		}
		if($aDatos["tercero"]!=""){ 

			$condicion.=" AND ccm.idTercero='".$aDatos["tercero"]."'";

		}
		if($aDatos["tipoTercero"]!=""){ 

			$condicion.=" AND ccm.tipoTercero ='".$aDatos["tipoTercero"]."'";

		}
		

		$sql="SELECT substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.nombre,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza,ccm.tipoTercero,ccm.idTercero,ccm.idComprobante
			FROM cuenta_contable cc
			
			INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
			WHERE 0=0  ".$condicion."
			GROUP BY substring(cc.codigoCuenta,1,10),ccm.idTercero, ccm.tipoTercero
			ORDER BY ccm.idTercero asc,cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}



	public function getTerceroDetalladoCuenta($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["cuentaPrimera"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,10) >='".$aDatos["cuentaPrimera"]."'";

		}
		if($aDatos["cuentaSegunda"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,10) <='".$aDatos["cuentaSegunda"]."'";

		}
		

		$sql="SELECT substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.nombre,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza,ccm.tipoTercero,ccm.idTercero,ccm.idComprobante
			FROM cuenta_contable cc
			
			INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
			WHERE 0=0  ".$condicion."
			GROUP BY substring(cc.codigoCuenta,1,10),ccm.idTercero, ccm.tipoTercero
			ORDER BY ccm.idTercero asc,cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}





	public function getTerceroDetalladoComprobante($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,10)='".$aDatos["cuenta"]."'";

		}
		if($aDatos["idTercero"]!=""){ 

			$condicion.="  AND ccm.idTercero='".$aDatos["idTercero"]."'";

		}
		if($aDatos["tipoTercero"]!=""){ 

			$condicion.="  AND ccm.tipoTercero='".$aDatos["tipoTercero"]."'";

		}

		

		$sql="SELECT substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.nombre,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza,ccm.tipoTercero,ccm.idTercero,ccm.idComprobante
			FROM cuenta_contable cc
			
			INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
			WHERE 0=0  ".$condicion."
			GROUP BY substring(cc.codigoCuenta,1,10),ccm.idTercero, ccm.tipoTercero,ccm.idComprobante
			ORDER BY ccm.idTercero asc,cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}



	public function getComprobanteItem($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion.=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion.=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["tercero"]!=""){ 

			$condicion.=" AND ci.tercero like'".$aDatos["tercero"]."%'";

		}
		if($aDatos["cuenta"]!=""){ 

			$condicion.="  and ci.cuenta like'".$aDatos["cuenta"]."%'";

		}
		if($aDatos["idComprobante"]!=""){ 

			$condicion.="  AND c.idComprobante ='".$aDatos["idComprobante"]."'";

		}
		if($aDatos["valor"]!=""){ 

			$condicion.="  AND ci.valor ='".$aDatos["valor"]."'";

		}
	
		

		$sql="SELECT * from comprobante c 
		INNER JOIN comprobante_items ci on c.idComprobante=ci.idComprobante
			WHERE 0=0  ".$condicion;
			 


	    $comprobante=$this->ejecutarSql($sql); 

	    return $comprobante; 

	}




	public function getCuentaContableGeneral($aDatos=array()){



		// $condicion=""; 
		$condicion="AND ccm.idComprobante !=0"; 
		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion.=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion.=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 

			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		}
		if($aDatos["hasta"]!=""){ 

			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";

		}
		if($aDatos["cuentaPrimera"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,10) >='".$aDatos["cuentaPrimera"]."'";

		}
		if($aDatos["cuentaSegunda"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,10) <='".$aDatos["cuentaSegunda"]."'";

		}

		$sql="SELECT substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.nombre,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza,ccm.tipoTercero,ccm.idTercero,ccm.idComprobante
			FROM cuenta_contable cc
			
			INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
			WHERE 0=0  ".$condicion."
			GROUP BY substring(cc.codigoCuenta,1,10)
			ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}


	public function getCuentaContableGeneralCuenta($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion.=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		if($_SESSION["idRol"]==3){
			$condicion.=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		if($aDatos["desde"]!=""){ 
			$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";
		}
		if($aDatos["hasta"]!=""){ 
			$condicion.=" AND ccm.fecha <='".$aDatos["hasta"]."'";
		}
		if($aDatos["cuenta"]!=""){ 
			$condicion.=" AND substring(cc.codigoCuenta,1,10)='".$aDatos["cuenta"]."'";
		}
		if($aDatos["idTercero"]!=""){ 
			$condicion.="  AND ccm.idTercero='".$aDatos["idTercero"]."'";
		}
		if($aDatos["tipoTercero"]!=""){ 
			$condicion.="  AND ccm.tipoTercero='".$aDatos["tipoTercero"]."'";
		}

		$sql="SELECT substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.nombre,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza,ccm.tipoTercero,ccm.idTercero,ccm.idComprobante,ccm.base,ccm.descripcion
			FROM cuenta_contable cc
			
			INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
			INNER JOIN comprobante c on c.idComprobante=ccm.idComprobante
			WHERE 0=0  ".$condicion."
			GROUP BY substring(cc.codigoCuenta,1,10),ccm.idComprobante,ccm.descripcion,ccm.idTercero,ccm.idComprobanteItem
			ORDER BY cc.codigoCuenta ASC,ccm.fecha ASC,c.comprobante ASC,c.numero ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}



	public function getCuentaContableGeneralAcumulado($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		if($_SESSION["idRol"]==1 || $_SESSION["idRol"]==2 || $_SESSION["idRol"]==5){
			$condicion.=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($_SESSION["idRol"]==3){
			$condicion.=" AND cc.idEmpresa=".$_SESSION["idEmpresa"]; 
		}
		// if($aDatos["desde"]!=""){ 

		// 	$condicion.=" AND ccm.fecha>='".$aDatos["desde"]."'";

		// }
		if($aDatos["fecha"]!=""){ 

			$condicion.=" AND ccm.fecha <'".$aDatos["fecha"]."'";

		}
		if($aDatos["cuenta"]!=""){ 

			$condicion.=" AND substring(cc.codigoCuenta,1,10)='".$aDatos["cuenta"]."'";

		}
		// if($aDatos["tercero"]!=""){ 

		// 	$condicion.=" AND ccm.idTercero='".$aDatos["tercero"]."'";

		// }
		// if($aDatos["tipoTercero"]!=""){ 

		// 	$condicion.=" AND ccm.tipoTercero ='".$aDatos["tipoTercero"]."'";

		// }
		

		$sql="SELECT substring(cc.codigoCuenta,1,10) as codigoCuenta,cc.nombre,cc.idEmpresa,sum(ccm.saldoDebito) as debito,sum(ccm.saldoCredito) as credito,MAX(ccm.fecha) as fecha,cc.naturaleza,ccm.tipoTercero,ccm.idTercero,ccm.idComprobante
			FROM cuenta_contable cc
			
			INNER JOIN comprobante_items ccm on ccm.idCuentaContable=cc.idCuentaContable
			WHERE 0=0  ".$condicion."
			GROUP BY substring(cc.codigoCuenta,1,10)
			ORDER BY cc.codigoCuenta ASC";


	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}










	public function getCentroCostoTC($aDatos=array()){


		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }
		

		if($aDatos["idEmpresa"]!=""){ 
			$condicion.=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($aDatos["desde"]!=""){ 
			$condicion.=" AND ci.fecha >='".$aDatos["desde"]."'";
		}
		if($aDatos["hasta"]!=""){ 
			$condicion.=" AND ci.fecha <='".$aDatos["hasta"]."'";
		}
		if($aDatos["cuentaPrimera"]!=""){ 
			$condicion.=" AND c.codigoCuenta >='".$aDatos["cuentaPrimera"]."'";
		}
		if($aDatos["cuentaSegunda"]!=""){ 
			$condicion.=" AND c.codigoCuenta <='".$aDatos["cuentaSegunda"]."'";
		}
		if($aDatos["terceroPrimero"]!=""){ 
			$condicion.=" AND t.nit >='".$aDatos["terceroPrimero"]."'";
		}
		if($aDatos["terceroSegundo"]!=""){ 
			$condicion.=" AND t.nit <='".$aDatos["terceroSegundo"]."'";
		}

		if($aDatos["centroCostoPrimero"]!=""){ 
			$condicion.=" AND cc.codigoCentroCosto >='".$aDatos["centroCostoPrimero"]."'";
		}
		if($aDatos["centroCostoSegundo"]!=""){ 
			$condicion.=" AND cc.codigoCentroCosto <='".$aDatos["centroCostoSegundo"]."'";
		}

		$sql="SELECT *,sum(saldoDebito) as debito,sum(saldoCredito) as credito FROM comprobante_items ci 
		INNER JOIN subcentro_costo scc ON ci.idSubcentroCosto=scc.idSubcentroCosto 
		INNER JOIN centro_costo cc on ci.idCentroCosto=cc.idCentroCosto 
		INNER JOIN cuenta_contable c on c.idCuentaContable=ci.idCuentaContable 
		INNER JOIN tercero t on t.idTercero=ci.idTercero
		WHERE 0=0 ".$condicion."
		GROUP BY ci.idCentroCosto,ci.idSubcentroCosto
		ORDER BY cc.codigoCentroCosto ASC, scc.codigoSubcentroCosto ASC";

	    $aCentroCosto=$this->ejecutarSql($sql); 
	    return $aCentroCosto; 

	}



	public function getCentroCostoCuenta($aDatos=array()){

		$condicion=""; 
		if(!isset($_SESSION)){ session_start(); }


		if($aDatos["idEmpresa"]!=""){ 
			$condicion.=" AND cc.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		
		if($aDatos["desde"]!=""){ 
			$condicion.=" AND ci.fecha >='".$aDatos["desde"]."'";
		}
		if($aDatos["hasta"]!=""){ 
			$condicion.=" AND ci.fecha <='".$aDatos["hasta"]."'";
		}
		if($aDatos["idCentroCosto"]!=""){ 
			$condicion.=" AND cc.idCentroCosto=".$aDatos["idCentroCosto"]; 
			// print_r($aDatos["idCentroCosto"]);
		}
		if($aDatos["idSubcentroCosto"]!=""){ 
			$condicion.=" AND scc.idSubcentroCosto=".$aDatos["idSubcentroCosto"]; 
			// print_r($aDatos["idSubcentroCosto"]);
		}
		if($aDatos["cuentaPrimera"]!=""){ 
			$condicion.=" AND c.codigoCuenta >='".$aDatos["cuentaPrimera"]."'";
		}
		if($aDatos["cuentaSegunda"]!=""){ 
			$condicion.=" AND c.codigoCuenta <='".$aDatos["cuentaSegunda"]."'";
		}
		if($aDatos["terceroPrimero"]!=""){ 
			$condicion.=" AND t.nit >='".$aDatos["terceroPrimero"]."'";
		}
		if($aDatos["terceroSegundo"]!=""){ 
			$condicion.=" AND t.nit <='".$aDatos["terceroSegundo"]."'";
		}
		if($aDatos["centroCostoPrimero"]!=""){ 
			$condicion.=" AND cc.codigoCentroCosto >='".$aDatos["centroCostoPrimero"]."'";
		}
		if($aDatos["centroCostoSegundo"]!=""){ 
			$condicion.=" AND cc.codigoCentroCosto <='".$aDatos["centroCostoSegundo"]."'";
		}

		$sql="SELECT *,sum(saldoDebito) as debito,sum(saldoCredito) as credito FROM comprobante_items ci 
		INNER JOIN centro_costo cc on ci.idCentroCosto=cc.idCentroCosto 
		INNER JOIN subcentro_costo scc ON ci.idSubcentroCosto=scc.idSubcentroCosto 
		INNER JOIN cuenta_contable c on c.idCuentaContable=ci.idCuentaContable 
		INNER JOIN tercero t on t.idTercero=ci.idTercero
		WHERE 0=0 ".$condicion."
		GROUP BY ci.idCuentaContable
		ORDER BY c.codigoCuenta ASC";

	    $aCentroCosto=$this->ejecutarSql($sql); 
	    return $aCentroCosto; 
	}

	public function getCentroCostoCuentaTercero($aDatos=array()){

		$condicion=""; 
		if(!isset($_SESSION)){ session_start(); }

		if($aDatos["idCuentaContable"]!=""){ 
			$condicion.=" AND cu.idCuentaContable= ".$aDatos["idCuentaContable"];
			// print_r('ingreso');
			// print_r($aDatos["idCuentaContable"]);
			// print_r('++');
		}
		if($aDatos["idEmpresa"]!=""){ 
			$condicion.=" AND c.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		if($aDatos["idCentroCosto"]!=""){ 
			$condicion.=" AND cc.idCentroCosto=".$aDatos["idCentroCosto"]; 
			// print_r($aDatos["idCentroCosto"]);
		}
		if($aDatos["idSubcentroCosto"]!=""){ 
			$condicion.=" AND scc.idSubcentroCosto=".$aDatos["idSubcentroCosto"]; 
			// print_r($aDatos["idSubcentroCosto"]);
		}
		if($aDatos["hasta"]!=""){ 
			$condicion.=" AND ci.fecha <='".$aDatos["hasta"]."'";
		}
		if($aDatos["desde"]!=""){ 
			$condicion.=" AND ci.fecha >='".$aDatos["desde"]."'";
		}
		if($aDatos["idComprobanteItem"]!=""){ 
			$condicion.=" AND ci.idComprobanteItem =".$aDatos["idComprobanteItem"];
		}
		if($aDatos["cuentaPrimera"]!=""){ 
			$condicion.=" AND cu.codigoCuenta >='".$aDatos["cuentaPrimera"]."'";
		}
		if($aDatos["cuentaSegunda"]!=""){ 
			$condicion.=" AND cu.codigoCuenta <='".$aDatos["cuentaSegunda"]."'";
		}
		if($aDatos["terceroPrimero"]!=""){ 
			$condicion.=" AND t.nit >='".$aDatos["terceroPrimero"]."'";
		}
		if($aDatos["terceroSegundo"]!=""){ 
			$condicion.=" AND t.nit <='".$aDatos["terceroSegundo"]."'";
		}
		if($aDatos["centroCostoPrimero"]!=""){ 
			$condicion.=" AND cc.codigoCentroCosto >='".$aDatos["centroCostoPrimero"]."'";
		}
		if($aDatos["centroCostoSegundo"]!=""){ 
			$condicion.=" AND cc.codigoCentroCosto <='".$aDatos["centroCostoSegundo"]."'";
		}

		$sql="SELECT *,sum(saldoDebito) as debito, sum(saldoCredito) as credito 
		FROM comprobante_items ci 
		INNER JOIN comprobante c on c.idComprobante= ci.idComprobante
		INNER JOIN subcentro_costo scc ON ci.idSubcentroCosto=scc.idSubcentroCosto 
		INNER JOIN centro_costo cc on ci.idCentroCosto=cc.idCentroCosto 
		INNER JOIN tercero t on t.idTercero=ci.idTercero
		INNER JOIN cuenta_contable cu on cu.idCuentaContable=ci.idCuentaContable 
		WHERE 0=0  ".$condicion."
		GROUP BY t.idTercero";

	    $aCentroCosto=$this->ejecutarSql($sql); 
	    // print_r($aCentroCosto);
	    // print_r('+++');
	    return $aCentroCosto; 
	}

	
	public function getComprobantesCierreMes($aDatos=array()){



		$condicion=""; 

		if(!isset($_SESSION)){ session_start(); }

		if($aDatos["idEmpresa"]!=""){ 
			$condicion.=" AND c.idEmpresa=".$aDatos["idEmpresa"]; 
		}
		

		$sql="SELECT SUM(ci.saldoDebito) as debito,SUM(ci.saldoCredito) as credito,YEAR(c.fecha) as anio,MONTH(c.fecha) as mes, c.idEmpresa
		FROM comprobante_items ci INNER JOIN comprobante c on c.idComprobante=ci.idComprobante 
			WHERE 0=0  ".$condicion."
			GROUP BY YEAR(c.fecha),MONTH(c.fecha)			
			ORDER BY c.fecha ASC";

	    $aBalanceComprobacion=$this->ejecutarSql($sql); 

	    return $aBalanceComprobacion; 

	}

}

?>