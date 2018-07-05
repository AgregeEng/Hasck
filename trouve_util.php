<!DOCTYPE html>
<html>
<head>
<title>Hasck</title>
</head>
<body>

<?php

include("produits.inc");
$no_piece=$_POST['No_piece'];
$nom=$_POST['Nom'];
$prenom=$_POST['Prenom'];

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in trouve_util.php");
$query="SELECT IdUtilisateur FROM utilisateurs
		WHERE No_piece='$no_piece' AND Nom='$nom' AND Prenom='$prenom'";
$result=mysqli_query($connection,$query) or die("Impossible to find the user");
$nb_util=mysqli_num_rows($result);
if($nb_util==1){
	$line=mysqli_fetch_assoc($result);
	$id=$line['IdUtilisateur'];
	echo "Identifiant :$id";
}
else{
	if($nb_util==0){
		echo "Aucun utilisateur trouve";
	}
	else{
		if($nb_util>1){
			echo "Nombre utilisateurs trouves :$nb_util";
		}
		else{
			echo "Cas bizarre";
		}
	}
}

mysqli_close($connection);

?>

</body>
</html>