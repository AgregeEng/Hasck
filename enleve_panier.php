<?php

include("produits.inc");
//echo "enlpan";
include_once("consulter_panier.php");
//echo "enlpan2";

$bogus=$_POST['bogus'];

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in enleve_panier.php");

foreach($_POST as $param_name => $param_val){
		echo "param_name :".$param_name."<br>";
		echo "param_val :".$param_val."<br>";
		if(strpos($param_name,"chbx")!==false){
			$longueur=strlen($param_name);
			$no_prod_s=substr($param_name,4,$longueur-4);
			$no_prod=intval($no_prod_s);
			$remove_fromcart="DELETE FROM paniers
						WHERE IdProdt=$no_prod AND Bogus='$bogus'";
			mysqli_query($connection,$remove_fromcart) or die("Impossible to remove a product from the cart");
		}
}
mysqli_close($connection);

consulterPanier($bogus);

?>