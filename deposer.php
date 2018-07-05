<?php

include("produits.inc");

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in deposer.php");

$no_pce=$_POST['Nopiece'];
$nom=$_POST['Nom'];
$prenom=$_POST['Prenom'];
$montant=$_POST['Montant'];
$p=$_POST['Passe'];

if($p==$passe){
	$query_accurate="SELECT IdUtilisateur FROM utilisateurs
	WHERE No_piece='$no_pce' AND Nom='$nom' AND Prenom='$prenom'";
	$result_accurate=mysqli_query($connection,$query_accurate) or die("Impossible to execute the query to retrieve the user in deposer.php");
	$nb_utilisateurs=mysqli_num_rows($result_accurate);
	if($nb_utilisateurs==1){
		$line=mysqli_fetch_assoc($result_accurate);
		$id_user=$line['IdUtilisateur'];
		
		$query_update="UPDATE utilisateurs
		SET Disponible=Disponible+$montant,Total=Total+$montant
		WHERE IdUtilisateur=$id_user";
		$result_update=mysqli_query($connection,$query_update) or die("Impossible to update the amount deposited by the user in deposer.php");
		echo "<!DOCTYPE html>";
		echo "<html>";
		echo "<head>";
		echo "<title>Hasck</title>";
		echo "</head>";
		echo "<body>";
		
		if($result_update!==false){
			echo "Oui<br>";
		}
		else{
			echo "Non<br>";
		}
		echo "</body>";
		echo "</html>";
		
	}
	else{
		if($nb_utilisateurs==0){
			
			$query_new_user="INSERT INTO utilisateurs(No_piece,Nom,Prenom,Disponible,Total)
			VALUES('$no_piece','$nom','$prenom',$montant,$montant)";
			$result_user=mysqli_query($connection,$query_new_user) or die("Impossible to execute the query to insert a new user in deposer.php");
			echo "<!DOCTYPE html>";
			echo "<html>";
			echo "<head>";
			echo "<title>Hasck</title>";
			echo "</head>";
			echo "<body>";
			
			if($result_user!==false){
				echo "Oui<br>";
			}
			else{
				echo "Non<br>";
			}
			echo "</body>";
			echo "</html>";
			
		}
		
	}
}
else{
	echo "<!DOCTYPE html>";
	echo "<html>";
	echo "<head>";
	echo "<title>Hasck</title>";
	echo "</head>";
	echo "<body>";
	echo "Non<br>";
	echo "</body>";
	echo "</html>";
			
}

mysqli_close($connection);

?>