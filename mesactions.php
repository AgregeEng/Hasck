<!DOCTYPE html>
<html>
<head>
<title>Hasck : compte</title>
</head>
<body>

<?php
include("produits.inc");
include_once("accueil.php");
include_once("maj.php");

MettreAJour();

$num_id=$_POST['num_id'];
$prenom=$_POST['prenom'];
$nom=$_POST['nom'];
$bogus=$_POST['bogus'];

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in mesactions.php");

$query_accurate="SELECT IdUtilisateur FROM utilisateurs
			WHERE No_piece='$num_id' AND Prenom='$prenom' AND Nom='$nom'";
$result_accurate=mysqli_query($connection,$query_accurate) or die("Impossible to execute the query to retrieve the user");
$nb_utilisateurs=mysqli_num_rows($result_accurate);
if($nb_utilisateurs==1){
	$line=mysqli_fetch_assoc($result_accurate);
	$id_user=$line['IdUtilisateur'];
	
	$query_available="SELECT Disponible,Total FROM utilisateurs
					WHERE IdUtilisateur=$id_user";
	$result_available=mysqli_query($connection,$query_available) or die("Impossible to execute the query to retrieve the amounts of the user");
	$line_available=mysqli_fetch_assoc($result_available);
	$available=$line_available['Disponible'];
	$total=$line_available['Total'];
	
	echo "<form action='retour.php' method='POST'><input type='hidden' name='bogus' value='$bogus'><input type='submit' value='Retour'></form>";
	
	echo "Montant disponible : ".$available."<br>";
	echo "Montant investi : ".$total."<br>";
	
	echo "<form action='dem_retrait.php' method='POST'>";
	echo "<input type='number' name='montant_ret'>";
	echo "<input type='hidden' name='id' value=$id_user>";
	echo "<input type='hidden' name='bogus' value='$bogus'>";
	echo "<input type='submit' value='Demander retrait'>";
	echo "</form><br>";
	
	$query_current="SELECT DISTINCT Nom,Image,MontantCote,Duree,DateDebut,Gagne FROM
	(SELECT DATE_ADD(DateDebut,INTERVAL Duree DAY) AS DateFin
	FROM actions) AS DateCalc,
	actions,produits
	WHERE NOW()>DateDebut AND NOW()<=DateFin AND actions.IdProd=produits.IdProduit AND actions.IdUtil=$id_user
	ORDER BY DateDebut";
	$result_current=mysqli_query($connection,$query_current) or die("Impossible to execute the query to retrieve the current actions : mysqli_error($connection)");
	$nb_lignes_current=mysqli_num_rows($result_current);
	if($nb_lignes_current>0){
		echo "<table><tr><td>Nom du produit</td><td>Image</td><td>Montant de cote</td><td>Date debut</td><td>Duree</td><td>Date fin</td><td>Gagne</td><td>A retirer</td></tr>";
		while($line_current=mysqli_fetch_assoc($result_current)){
			echo "<tr>";
			$nom=$line_current['Nom'];
			$image=$line_current['Image'];
			$de_cote=$line_current['MontantCote'];
			$duree=$line_current['Duree'];
			$debut=$line_current['DateDebut'];
			$fin=$line_current['DateFin'];
			$gagne=$line_current['Gagne'];
			//$aretirer=$line_current['ARetirer'];
                        $aretirer=0;
			echo "<td>$nom</td><td><img src='$image'></td><td>$de_cote</td><td>$debut</td><td>$duree</td><td>$fin</td><td>$gagne</td><td>$aretirer</td></tr>";
		}
		echo "</table>";
	}
	
	$query_past="SELECT DISTINCT Nom,Image,MontantCote,Duree,DateDebut,Gagne FROM
	(SELECT DATE_ADD(DateDebut,INTERVAL Duree DAY) AS DateFin FROM actions) AS DateCalc,
	actions,produits
	WHERE NOW()>DateFin AND actions.IdProd=produits.IdProduit AND actions.IdUtil=$id_user
	ORDER BY DateDebut DESC";
	$result_past=mysqli_query($connection,$query_past) or die("Impossible to execute the query to retrieve the past actions");
	$nb_lignes_past=mysqli_num_rows($result_past);
	if($nb_lignes_past>0){
		echo "<table><tr><td>Nom du produit</td><td>Image</td><td>Montant de cote</td><td>Date debut</td><td>Duree</td><td>Date fin</td><td>Gagne</td><td>A retirer</td></tr>";
		while($line_past=mysqli_fetch_assoc($result_past)){
			echo "<tr>";
			$nom_p=$line_past['Nom'];
			$image_p=$line_past['Image'];
			$de_cote_p=$line_past['MontantCote'];
			$duree_p=$line_past['Duree'];
			$debut_p=$line_past['DateDebut'];
			$fin_p=$line_past['DateFin'];
			$gagne_p=$line_past['Gagne'];
			//$aretirer_p=$line_past['ARetirer'];
                        $aretirer=0;
			echo "<td>$nom_p</td><td><img src='$image_p'></td><td>$de_cote_p</td><td>$debut_p</td><td>$duree_p</td><td>$fin_p</td><td>$gagne_p</td><td>$aretirer_p</td></tr>";
		}
		echo "</table>";
	}
	mysqli_close($connection);
}
else{
	echo "Identification erronee.<br>";
	mysqli_close($connection);
	afficheAccueil("",$bogus);
}
//Actions : Id_action, Id_utilisateur, Id_produit, Montant de cote, durée, date début, argent gagne

?>

</body>
</html>