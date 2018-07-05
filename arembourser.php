<?php

include("produits.inc");

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Hasck : remboursement</title>";
echo "</head>";
echo "<body>";

$connection=mysqli_connect($host,$user,$password,$database) or die("Impossible to connect to the database in arembourser.php");

$query="SELECT IdCommande,Prix,Quantite FROM produits,commandes
       WHERE produits.IdProduit=commandes.IdProdt2 AND Rembourse=1";//Rembourse = 0 : pas de remboursement à faire, =1 : remboursement à faire, =2 : remboursement fait

$result=mysqli_query($connection,$query) or die("Impossible to find the orders to refund");
while($line=mysqli_fetch_assoc($result)){
	$id_comm=$line['IdCommande'];
	$prix=$line['Prix'];
	$qte=$line['Quantite'];
	$montant=$prix*$qte;
	echo "$id_comm,$montant;";
}

echo "</body>";
echo "<html>";
       
mysqli_close($connection);

?>