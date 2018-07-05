<!DOCTYPE html>
<html>
<head>
<title>Hasck : compte</title>
</head>
<body>

<?php

include("produits.inc");

$bogus=$_POST['bogus'];

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in ajoutpanier.php");

$nb_articles_ajoutes=0;
foreach($_POST as $param_name => $param_val){
		echo "param_name :".$param_name."<br>";
		echo "param_val :".$param_val."<br>";
		if(strpos($param_name,"ach")!==false){
			if($param_val != ""){
				$longueur=strlen($param_name);
				$no_prod_s=substr($param_name,3,$longueur-3);
				$no_prod=intval($no_prod_s);
				$quantite=intval($param_val);
				$query_cart="INSERT INTO paniers(Bogus,IdProdt,Quantite)
				VALUES('$bogus',$no_prod,$quantite)";
				$result=mysqli_query($connection,$query_cart) or die("Impossible to add item to cart");
				if($result!==false){
					$nb_articles_ajoutes++;
				}
			}
		}
}

mysqli_close($connection);

echo "Nombre d'articles ajoutes au panier : $nb_articles_ajoutes<br>";

/*
echo "<form action='acheter.php' method='POST'>";
echo "<input type='hidden' name='bogus' value='$bogus'>";
echo "<input type='submit' value='Acheter'>";
echo "</form>";
*/

echo "<form action='consulter_panier.php' method='POST'>";
echo "<input type='hidden' name='bogus' value='$bogus'>";
echo "<input type='submit' value='Voir tout le panier, Acheter'>";
echo "</form>";

echo "<form action='retour.php' method='POST'>";
echo "<input type='hidden' name='bogus' value='$bogus'>";
echo "<input type='submit' value='Retour'>";
echo "</form>";

?>

</body>
</html>