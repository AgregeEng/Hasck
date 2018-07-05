<?php
include("produits.inc");

$no_piece=$_POST['No_piece'];
$nom=$_POST['Nom'];
$prenom=$_POST['Prenom'];
$montant=$_POST['Montant'];

echo "no_piece : $no_piece<br>";
echo "nom : $nom<br>";
echo "prenom : $prenom<br>";

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in confirme_retrait.php");

$query_update="UPDATE utilisateurs
			SET ARetirer=0
			WHERE No_piece='$no_piece' AND Nom='$nom' AND Prenom='$prenom'";
$result=mysqli_query($connection,$query_update) or die("Impossible to take into account the withdrawal");
if($result === false){
	echo "Gros probleme dans confirme retrait";
}

mysqli_close($connection);

?>
