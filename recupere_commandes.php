<!DOCTYPE html>
<html>
<head>
<title>Hasck : commandes</title>
</head>
<body>

<?php

include("produits.inc");

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in recupere_commandes.php");

$courriel=$_POST['Courriel'];

//On ne veut que les commandes qui n'ont pas encore été complètement honorées (où la quantité livrée au client est inférieure à la quantité commandée par le client)
$query="SELECT IdCommande,IdProduit,Nom,Quantite,QteLivree_parfourn FROM produits,commandes
		WHERE produits.IdProduit=commandes.IdProdt2 AND EmailClient='$courriel' AND QteLivree_auclient<Quantite";
$result=mysqli_query($connection,$query) or die("Impossible to retrieve the orders of the client");
echo "debut du message:";
while($line=mysqli_fetch_assoc($result)){
	$nom_prod=$line['Nom'];
	$qte_commandee=$line['Quantite'];
	$qte_dufourn=$line['QteLivree_parfourn'];
	$id_prod=$line['IdProduit'];
	$id_comm=$line['IdCommande'];
	$qte_livrable=min($qte_commandee,$qte_dufourn);
	echo "$id_comm,$id_prod,$nom_prod,$qte_livrable;";
}
echo ":fin du message";

mysqli_close($connection);

?>

</body>
</html>