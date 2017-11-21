<?php 
//nusoap.php klasea gehitzen dugu
require_once('../lib/nusoap.php');
require_once('../lib/class.wsdlcache.php');


//name of the service
$ns="http://localhost:1234/proba/php/egiaztatuPasahitza.php?wsdl";
$server = new soap_server;
$server->configureWSDL('egiaztatu',$ns);
$server->wsdl->schemaTargetNamespace=$ns;

//inplementatu nahi dugun funtzioa erregistratzen dugu
$server->register('bilatu',array('x'=>'xsd:int'),array('z'=>'xsd:int'),$ns);

//funtzioa inplementatzen dugu
function bilatu($x){
	$fitxategia = file('../txt/toppasswords.txt');
	
	$ema = False;
	foreach($fitxategia as $balioa){
		if ($balioa == $x){
			$ema = True;
		}
	}
	if($ema){
		return 'baliogabea';
	}
	else{
		return 'baliozkoa';
	}
}


//nusoap klaseko sevice metodoari dei egiten diogu
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>