<?php 
header('Content-type: application/json');
require_once("../../php/restrict.php");

include_once($CLASS . "data.php");
include_once($CLASS . "control.php");

$oControl=new Control();


if(!isset($_SESSION)){ session_start(); }
$id  = (isset($_REQUEST['id'] ) ? $_REQUEST['id'] : "" );


$decrip["cadena"]=$id; 
$id=$oControl->desencriptar($decrip);


$oItem = new Data('usuario',"idUsuario", $_SESSION["idUsuario"]);
$aUser=$oItem->getDatos();
unset($oItem);

$_SESSION["idRolPrincipal"]=$aUser["idRol"]; 
$_SESSION["idEmpresa"]=$id; 


$oItem = new Data('empresa',"idEmpresa", $id);
$aEmpresa=$oItem->getDatos();
unset($oItem);

$token=""; 
if($aEmpresa["aplicaSiigo"]==1){
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://api.siigo.com/auth");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_POST, TRUE);

  curl_setopt($ch, CURLOPT_POSTFIELDS, "{
    \"username\": \"$aEmpresa[usuarioSiigo]\",
    \"access_key\": \"$aEmpresa[access_key]\"
  }");

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json"
  ));

  $response = curl_exec($ch);

  $response=json_decode($response);
  $token=$response->access_token; 
  $_SESSION["token"]=$token;
}


echo json_encode(array("msg"=>true,"token"=>$token));

?>