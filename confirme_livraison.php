<?php
include("produits.inc");

$id_comm=$_POST['idcomm'];
$qte=$_POST['qteremise'];
echo "id commande :$id_comm<br>";
echo "qte :$qte<br>";

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in confirme_livraison.php");

$query_delivery="UPDATE commandes
				SET QteLivree_auclient=QteLivree_auclient+$qte
				WHERE IdCommande=$id_comm";
$result=mysqli_query($connection,$query_delivery) or die("Impossible to update the volume transferred to the client");

$query_honored="UPDATE commandes
               SET Honoree=1
               WHERE QteLivree_auclient>=Quantite";
mysqli_query($connection,$query_honored) or die("Impossible to set completed orders to honored status");

mysqli_close($connection);

?>