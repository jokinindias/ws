<!DOCTYPE html>
<html>
  <head>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Erregistratu</title>
  </head>
  <body>
		<form id="erregistroF" name="erregistroF" action="" method="post">
			<label>Korreoa</label><input id="korreoa" name="korreoa" type="text"><br/><br/>
			<label >Deitura</label> <input id ="deitura" name="deitura" type="text" ><br/><br/>
			<label>Nick </label><input id="nick" name="nick" type="text" ><br/><br/>
			<label>Pasahitza</label><input id="pasahitza" name="pasahitza" type="password"><br/><br/>
			<label>Pasahitza errepikatu</label><input id="pasahitza2" name="pasahitza2" type="password" ><br/><br/>
			<input id="botoiErregistroa" type="submit" name="botoiErregistroa"value="Erregistratu">
			<input id="botoiAtera" type="button" name="botoiAtera"value="Atzera"></br></br>
		</form>
</body>
<script>
    
    $(document).ready(function(){
		$("form").submit(function(){
			var emailRegex = /^[a-z]{3,}[0-9][0-9][0-9]+@ikasle\.ehu\.(?:eus|es)$/
			var deiRegex = /^[A-Z][a-z]{1,}[\s][A-Z][a-z]{1,}$/
			var nickRegex = /^[A-Za-z0-9]{1,}$/
			var korr = $("#korreoa").val().match(emailRegex) ==  $("#korreoa").val() 
			var dei = $("#deitura").val().match(deiRegex) == $("#deitura").val()
			var nick = $("#nick").val().match(nickRegex) == $("#nick").val()
			var pass = $.trim($("#pasahitza").val()).length > 0
			var pass2 = $.trim($("#pasahitza2").val()).length > 0
			var pasahitza = $.trim($("#pasahitza").val())
			var pasahitza2 = $.trim($("#pasahitza2").val())
			
			var boolean = true
			if(!korr){
               boolean =false
               $("#korreoa").css("background-color", "red")
            }
		    else $("#korreoa").css("background-color", "white")
			
			if(!dei){
               boolean =false
               $("#deitura").css("background-color", "red")
            }
		    else $("#deitura").css("background-color", "white")
			
			if(!nick){
               boolean =false
               $("#nick").css("background-color", "red")
            }
		    else $("#nick").css("background-color", "white")
			
			if(!pass){
               boolean =false
               $("#pasahitza").css("background-color", "red")
            }
		    else $("#pasahitza2").css("background-color", "white")
			
			if(!pass2){
               boolean =false
               $("#pasahitza2").css("background-color", "red")
            }
		    else $("#pasahitza2").css("background-color", "white")
			
			if(pasahitza != pasahitza2){
				boolean = false
				alert("Pasahitzak desberdinak dira")
			}
			 
			return boolean;
  		});
		
		$("#botoiAtera").click(function(){
			location.href="layout.php"
		});
        
    });
</script>
</html>

<?php
 
if(isset($_POST['korreoa'], $_POST['deitura'], $_POST['nick'], $_POST['pasahitza'], $_POST['pasahitza2'])){
	$zenb=0;
	include "configure.php";
	global $esteka;
	$sql = "SELECT * FROM erabiltzaileak";
	$ema = mysqli_query($esteka, $sql);
	if (!$ema){
		echo("Errorea datuak sartzerakoan: ". mysqli_error($esteka));
	} 
	$aurkitua = False;
	while($row = mysqli_fetch_assoc($ema)){
		if($row['Korreoa'] == $_POST['korreoa']){
			$aurkitua = True;	
		}
	}
	if(!$aurkitua){
		$sql = "INSERT INTO erabiltzaileak VALUES(DEFAULT, '$_POST[korreoa]' , '$_POST[deitura]' , '$_POST[nick]' , '$_POST[pasahitza]')";
		$ema = mysqli_query($esteka, $sql);
		if (!$ema){
			echo("Errorea datuak sartzerakoan: ". mysqli_error($esteka));
		}
		else{
			$sql = "SELECT ID FROM erabiltzaileak WHERE korreoa='$_POST[korreoa]'";
			$ema = mysqli_query($esteka, $sql);
			if (!$ema){
				echo("Errorea datuak sartzerakoan 1: ". mysqli_error($esteka));
			}
			else{
				$row = mysqli_fetch_assoc($ema);
				echo('<script>location.href="logIn.php"</script>');
				exit();
			}
		
		}
	}
	else{
		echo('<span style="color: red;">KORREOA JADA EXISTITZEN DA</span>');
	}
	mysqli_close($esteka);
	
	//nusoap.php klasea gehitzen dugu
	require_once('../lib/nusoap.php');
	require_once('../lib/class.wsdlcache.php');
	
	//soapclient motadun objektua sortzen dugu. http://www.mydomain.com/server.php
	//erabiliko den SOAP zerbitzua non dagoen zehazten url horrek
	$soapclient = new nusoap_client('http://localhost:1234/laborategiak/php/egiaztatuPasahitza.php?wsdl', false);

	//Web-Service-n inplementatu dugun funtzioari dei egiten diogu
	//$ematza = ;
	$result = $soapclient->call('bilatu', array( 'x'=>$_POST['pasahitza']));
	print_r($result);
	
	echo '<h2>Request</h2><pre>'.htmlspecialchars($soapclient->request, ENT_QUOTES).'</pre>';
	echo '<h2>Response</h2><pre>'.htmlspecialchars($soapclient->response,ENT_QUOTES).'</pre>';
}	
?>