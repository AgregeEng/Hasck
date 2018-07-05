<?php

//MettreAJour();

function MettreAJour(){

        ini_set('display_errors',1);

	include("produits.inc");
	
	$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in mesactions.php");
	
	$update_or_not="SELECT Derniere_MAJ FROM miseajour
	WHERE NOW()>Derniere_MAJ";
	$result=mysqli_query($connection,$update_or_not) or die("Impossiible to determine if an update should take place");
	if($result!==false){
		echo "entre dans le branchement";
		$update=mysqli_num_rows($result);
		if($update==1){
			//MIse à jour du montant mis de côté, produit par produit
			$query="SELECT IP,SUM(MC) as ACote FROM
				(SELECT IdProd as IP,MontantCote as MC,DateDebut As DD,DATE_ADD(DateDebut,INTERVAL Duree DAY) as DateFin FROM actions) as DateCalc
				WHERE NOW()>DD AND NOW()<=DateFin
				GROUP BY IP";
			$new_amount=mysqli_query($connection,$query) or die("Impossiible to determine the new amount set aside");
			while($line=mysqli_fetch_assoc($new_amount)){
				$id_pr=$line['IP'];
				$aside=$line['ACote'];
				$update_prod="UPDATE produits
				SET Misdecote=$aside
				WHERE IdProduit=$id_pr";
				$set_new_amount=mysqli_query($connection,$update_prod) or die("Impossible to update the amount of money set aside for the product");
			}
			
			//Mise à jour des montants disponibles, utilisateur par utilisateur
			$new_available="SELECT IU,(TT-SUM(MC)) as Dispo,DateCalc.DateFin FROM
			(SELECT IdUtilisateur as IU,MontantCote as MC,Total as TT,DateDebut as DD,DATE_ADD(DateDebut,INTERVAL Duree DAY) as DateFin
             FROM actions,utilisateurs
            WHERE actions.IdUtil=utilisateurs.IdUtilisateur) As DateCalc 
			WHERE NOW()>DD AND NOW()<=DateFin
            GROUP BY IU";
			$result_available=mysqli_query($connection,$new_available) or die("Impossible to determine the new amount available for the users");
			while($line_available=mysqli_fetch_assoc($result_available)){
				$id_user=$line_available['IU'];
				$available=$line_available['Dispo'];
				$update_available="UPDATE utilisateurs
								SET Disponible=$available
								WHERE IdUtilisateur=$id_user";
				$available_done=mysqli_query($connection,$update_available) or die("Impossiible to update the new amount available for the user");
			}
		}
		
		//Mise à jour des commandes déinitivement acquises
		$orders="SELECT IdCommande FROM commandes
				WHERE Remboursable=1 AND Annule_parclient=0 AND Rembourse=0 AND NOW()>DATE_ADD(Date_comm,INTERVAL 15 DAY)";
		$result_orders=mysqli_query($connection,$orders) or die("Impossible to find the definitive orders");
		while($line_orders=mysqli_fetch_assoc($result_orders)){
			$id_comm=$line_orders['IdCommande'];
			$update_orders="UPDATE commandes
							SET Remboursable=0
							WHERE IdCommande=$id_comm";
			mysqli_query($connection,$update_orders) or die("Impossible to set definitive orders non refundable");
			$update_gains="UPDATE gains
							SET Acquis=1
							WHERE Acquis=0 AND IdCmd=$id_comm";
			mysqli_query($connection,$update_gains) or die("Impossible to set gains to acquired status");
			$compute_gains="SELECT IdUtilis,SUM(Montant) as Nouveaux_gains FROM gains
							WHERE Acquis=1 AND Comptabilise=0
							GROUP BY IdUtilis";
			$result_gains=mysqli_query($connection,$compute_gains) or die("Impossible to compute the gains");
			while($line_gains=mysqli_fetch_assoc($result_gains)){
				$id_user=$line_gains['IdUtilis'];
				$amount=$line_gains['Nouveaux_gains'];
				$set_gains="UPDATE utilisateurs
							SET Disponible=Disponible+$amount,Total=Total+$amount
							WHERE IdUtilisateur=$id_user";
				mysqli_query($connection,$set_gains) or die("Impossible to add gains to available amount");
			}
			
		}
		$accounted="UPDATE gains
					SET Comptabilise=1
					WHERE Acquis=1 AND Comptabilise=0";
		mysqli_query($connection,$accounted) or die("Impossible to set the definitive gains to accounted for status");
		
		//A Adapter
		/*
		$update_won2="UPDATE actions
						SET Gagne=Gagne+$amount_won
						WHERE IdProd=$id_prod AND IdUtil=$id_user AND NOW()>DateDebut AND NOW()<=DATE_ADD(DateDebut,INTERVAL Duree DAY)";
		mysqli_query($connection,$update_won2) or die("Impossible to update the amount won through the action");
		*/
		
		//Mise à jour de dernière mise à jour
		$update_date="UPDATE miseajour
		SET Derniere_MAJ=DATE(NOW())";
		$update_completed=mysqli_query($connection,$update_date) or die("Impossible to set the update to completed status");
		
	}
	echo "mise a jour effectuee";
	
	mysqli_close($connection);

}		

?>