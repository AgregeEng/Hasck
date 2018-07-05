<?php

include("produits.inc");

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Hasck : retour</title>";
echo "</head>";
echo "<body>";

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in retours_pendants.php");
	
$pending_returns="SELECT IdCommande,IdProdt2,Nom,Quantite,QteLivree_auclient,EmailClient FROM commandes,produits
				WHERE commandes.IdProdt2=produits.IdProduit AND Ret_pendanteffectue=1";
$result=mysqli_query($pending_returns) or die("Impossible to retrieve the pending returns");
while($line=mysqli_fetch_assoc($result)){
	$id_comm=$line['IdCommande'];
        $id_prod=$line['IdProdt2'];
	$nom_prod=$line['Nom'];
	$qte=$line['Quantite'];
	$livree_client=$line['QteLivree_auclient'];
	$email=$line['EmailClient'];
	echo "$id_comm,$id_prod,$nom_prod,$livree_client,$email;";
}

echo "</body>";
echo "</html>";

mysqli_close($connection);

?>