<!DOCTYPE html>
<html>
<head>
<title>Hasck : mise de cote</title>
</head>
<body>

<?php

//Faire pour tous les produits sélectionnés dans index
//Misdecote dans la table Produits pas mis à jour

//echo "mettrecote debut<br>";

include("produits.inc");
include_once("accueil.php");
//include("maj.php");

//MettreAJour();

$num_id=$_POST['num_id'];
$prenom=$_POST['prenom'];
$nom=$_POST['nom'];
$bogus=$_POST['bogus'];

//echo "num_id = ".$num_id."<br>";
//echo "prenom = ".$prenom."<br>";
//echo "nom = ".$nom."<br>";

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in mettrecote.php");

$query_accurate="SELECT IdUtilisateur FROM utilisateurs
			WHERE No_piece='$num_id' AND Prenom='$prenom' AND Nom='$nom'";
//echo "query=".$query_accurate."<br>";
$result_accurate=mysqli_query($connection,$query_accurate) or die("Impossible to execute the query to retrieve the user in mettrecote.php");
$nb_utilisateurs=mysqli_num_rows($result_accurate);
if($nb_utilisateurs==1){
	//echo "mettrecote milieu<br>";

	$line=mysqli_fetch_assoc($result_accurate);
	$id_user=$line['IdUtilisateur'];
	
	$query_available="SELECT Disponible,Total FROM utilisateurs
					WHERE IdUtilisateur=$id_user";
	$result_available=mysqli_query($connection,$query_available) or die("Impossible to execute the query to retrieve the amounts of the user");
	$line_available=mysqli_fetch_assoc($result_available);
	$available=$line_available['Disponible'];
	
	$nb_mises_de_cote=0;
	
	foreach($_POST as $param_name => $param_val){
		echo "param_name :".$param_name."<br>";
		echo "param_val :".$param_val."<br>";
		if(strpos($param_name,"montant")!==false){
			//echo "mettrecote apres milieu<br>";

			$longueur=strlen($param_name);
			$no_prod_s=substr($param_name,7,$longueur-7);
			$no_prod=intval($no_prod_s);
			$montant=intval($param_val);
			if($available>$montant && $montant>0){
				//echo "mettrecote apres milieu 2<br>";

				$query_already_aside="SELECT DISTINCT IdProd,MontantCote FROM
									(SELECT DATE_ADD(DateDebut,INTERVAL Duree DAY) as DateFin FROM actions) As DateCalc,
									actions,produits
									WHERE actions.IdProd=produits.IdProduit AND NOW()>DateDebut AND NOW()<=DateFin AND IdUtil=$id_user AND IdProd=$no_prod";
									//echo "query=".$query_already_aside."<br>";
				$already_aside=mysqli_query($connection,$query_already_aside) or die("Impossible to check if the user has already set aside money for this product");
				$nb_mises_de_cote=mysqli_num_rows($already_aside);
					//echo "mettrecote avant insert actions<br>";

					$set_aside_ok=0;
						$query_aside="INSERT INTO actions(IdUtil,IdProd,MontantCote,Duree,DateDebut,Gagne)
									VALUES($id_user,$no_prod,$montant,7,DATE(NOW()),0)";
						$aside=mysqli_query($connection,$query_aside) or die("Impossible to register the operation");
						if($aside!==false) $set_aside_ok=1;
						//Actions : Id_action, Id_utilisateur, Id_produit, Montant de cote, durée, date début, argent gagne
						
						//$line_already_aside=mysqli_fetch_assoc($result_already_aside);
						//echo "mettrecote avant update actions<br>";
                                                /*
						$query_aside0="UPDATE actions
										SET Montantcote=MontantCote+$montant
										WHERE IdUtil=$id_user AND IdProd=$no_prod";
						$aside0=mysqli_query($connection,$query_aside0) or die("Impossible to update the set aside amount");
						if($aside0!==false) $set_aside_ok=1;
                                                */

					if($set_aside_ok==1){
					        $available-=$montant;
					        //echo "mettrecote avant update disponible<br>";

					        $query_update_cash="UPDATE utilisateurs
										SET Disponible=Disponible-$montant
										WHERE IdUtilisateur=$id_user";
						$upd=mysqli_query($connection,$query_update_cash) or die("Impossible to update the remaining cash available to the user");
						if($upd===false) {
								echo "Gros probleme, impossible de reduire le cash disponible de l utilisateur no $id_user, pour un montant de $montant";
						}
					}
					//echo "mettrecote avant update produits<br>";

					$query_update_prod="UPDATE produits
										SET Misdecote=Misdecote+$montant
										WHERE IdProduit=$no_prod";
					$upd2=mysqli_query($connection,$query_update_prod) or die("Impossible to update the amount set aside for the product in products");
					if($upd2===false) {
							echo "Probleme, impossible de mettre a jour le montant mis de cote sur le produit";
					}
			}
		}
	}
	echo "Mises de cote effectuees :$nb_mises_de_cote";
	mysqli_close($connection);
}
if($bogus!="0"){
	mysqli_close($connection);
	afficheAccueil("",$bogus);
}
?>

</body>
</html>