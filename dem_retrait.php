<!DOCTYPE html>
<html>
<head>
<title>Hasck : demande de retrait</title>
</head>
<body>

<?php
include("produits.inc");
include_once("accueil.php");
include_once("maj.php");

MettreAJour();

$montant=$_POST['montant_ret'];
$id_user=$_POST['id'];
$bogus=$_POST['bogus'];

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in dem_retrait.php");
$query="SELECT Disponible FROM utilisateurs
	WHERE IdUtilisateur=$id_user";
$result=mysqli_query($connection,$query) or die("Impossible to execute the query to retrieve the available cash");
$line=mysqli_fetch_assoc($result);
$available=$line['Disponible'];
if($montant>$available){
	echo "Vous avez demande a retirer $montant, mais vous ne pouvez retirer que $available.<br>";
	mysqli_close($connection);
	afficheAccueil("",$bogus);
}
else{
	$query_withdraw="UPDATE utilisateurs
	SET ARetirer=$montant,Disponible=Disponible-$montant,Total=Total-$montant
	WHERE IdUtilisateur=$id_user";
	$query_successful=mysqli_query($connection,$query_withdraw);
	if($query_successful!==false){
		echo "Demande de retrait enregistree";
	}
	else{
		echo "Erreur, demande de retrait NON enregistree";
	}
	mysqli_close($connection);
	afficheAccueil("",$bogus);
}

?>

</body>
</html>