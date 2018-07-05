<!DOCTYPE html>
<html>
<head>
<title>Hasck : Liste des retraits</title>
</head>
<body>

<?php
include("produits.inc");

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in mesactions.php");

$query_withdrawals="SELECT No_piece,Nom,Prenom,ARetirer FROM utilisateurs";
$result=mysqli_query($connection,$query_withdrawals) or die("Impossible to retrieve the list of due withdrawals");
while($line=mysqli_fetch_assoc($result)){
	$no_pce=$line['No_piece'];
	$nom=$line['Nom'];
	$prenom=$line['Prenom'];
	$montant=$line['ARetirer'];
	echo "$no_pce,$nom,$prenom,$montant;";
}

mysqli_close($connection);

?>

</body>
</html>