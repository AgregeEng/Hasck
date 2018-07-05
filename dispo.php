<?php

include("produits.inc");

$no_pce=$_POST['Nopiece'];
$nom=$_POST['Nom'];
$prenom=$_POST['Prenom'];

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Hasck</title>";
echo "</head>";
echo "<body>";

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in dispo.php");
	
$query_data="SELECT Disponible FROM utilisateurs
			WHERE No_piece='$no_pce' AND Nom='$nom' AND Prenom='$prenom'";
$result=mysqli_query($connection,$query_data) or die("Impossible to retrieve the amount available to the user");
$nb_reponses=mysqli_num_rows($result);
if($nb_reponses==1){
	$line=mysqli_fetch_assoc($result);
	$dispo=$line['Disponible'];
	echo "Disponible :$dispo";
}
else{
	if($nb_reponses>1){
		echo "Erreur, trop d utilisateurs ont ces coordonnees";
	}
	else{
		echo "Erreur, aucun utilisateur ne correspond";
	}
}

mysqli_close($connection);

echo "</body>";
echo "</html>";
?>