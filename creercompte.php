<!DOCTYPE html>
<html>
<head>
<title>Hasck : compte</title>
</head>
<body>


<?php

ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', true );

//echo "creer compte";
include("produits.inc");
//echo "creer compte bis";
//include("index.php");

//echo "creercompte2";
$no_piece=$_POST['num_id2'];
//echo "creercompte3";
$nom=$_POST['nom2'];
//echo "creercompte4";
$prenom=$_POST['prenom2'];
//echo "creercompte5";
$bogus=$_POST['bogus'];

//echo "$no_piece<br>";
//echo "$nom<br>";
//echo "$prenom<br>";

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in creercompte.php");

$query_exists="SELECT IdUtilisateur FROM utilisateurs
			WHERE No_piece='$no_piece' AND Nom='$nom' AND Prenom='$prenom'";
$result=mysqli_query($connection,$query_exists) or die("Impossible to execute the query to determine if the user already exists");
$nb_utilisateurs=mysqli_num_rows($result);

//print($nb_utilisateurs);

if($nb_utilisateurs==0){
	$query="INSERT INTO utilisateurs(No_piece,Nom,Prenom,Disponible,Total,ARetirer)
			VALUES('$no_piece','$nom','$prenom',100,100,0)";
	mysqli_query($connection,$query) or die("Impossible to execute the query to insert a new user");
}

mysqli_close($connection);

//afficheAccueil("",$bogus);

?>

</body>
</html>