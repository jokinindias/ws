<?php 
$esteka = mysqli_connect("localhost", "root", "", "quiz");
if (mysqli_connect_errno()) {
echo ("Konexio hutxegitea MySQLra: " . mysqli_connect_error());
exit();
}
$sql = "INSERT INTO questions VALUES(DEFAULT, '$_POST[korreoa]' , '$_POST[galdera]' , '$_POST[zuzena]' , '$_POST[oker1]' , '$_POST[oker2]' , '$_POST[oker3]' , '$_POST[zailtasuna]' , '$_POST[arloa]' )";
$ema = mysqli_query($esteka, $sql); 
if (!$ema)
{
die('Errorea query-a gauzatzerakoan: '. mysqli_error($esteka));
}
mysqli_close($esteka);
?>