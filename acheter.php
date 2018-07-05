<?php

//Quand la vente a été effectuée !!!
//Pour la requête envoyée via l'application

function vide_panier($mail,$bogus,$no_prod_s,$quantite,$charge_id){
        ini_set('display_errors',1);

        echo "vide panier, debut<br>";
        echo "mail, $mail<br>";
        echo "no_prod_s, $no_prod_s<br>";
        echo "quantite, $quantite<br>";

	include("produits.inc");
	include_once("maj.php");
	include_once("accueil.php");
	
	/*
	$bogus=$_POST['bogus'];
	$nom=$_POST['nom'];
	$prenom=$_POST['prenom'];
	*/
	
	//MettreAJour();
	
	//if(isset($_POST['id_prod'])) $id_prod=$_POST['id_prod'];
	//else $id_prod=$_POST['id_prod2'];
	$boutique_ouinternet=0;//boutique
	$paye=1;
	if($bogus != "0"){
                //Achat par le biais du site Internet, puisque l'utilisateur a un bogus, qui est calculé en arrivant sur le site
                $boutique_ouinternet=1;//Par Internet
                echo "premier branchement, bogus different de 0 : $bogus<br>";

		$connection=mysqli_connect($host,$user,$password,$database)
		or die("Impossible to connect to the database in acheter.php");
		$query_cart="SELECT IdProdt,Quantite FROM paniers
		WHERE Bogus='$bogus'";
		$result_cart=mysqli_query($connection,$query_cart) or die("Impossible to retrieve the products in the cart");
		while($line_cart=mysqli_fetch_assoc($result_cart)){
			$id_prod=$line_cart['IdProdt'];
			$quantite=$line_cart['Quantite'];
			
			$query_remove="DELETE FROM paniers
			WHERE Bogus='$bogus' AND IdProdt=$id_prod";
			$result_remove=mysqli_query($connection,$query_remove) or die("Impossible to remove the purchased product from the cart");
			if($result_remove===false){
				echo "Impossible de retirer produit du panier";
			}
			mysqli_close($connection);	
			operationsAchat($id_prod,$mail,$quantite,$paye,$boutique_ouinternet,$charge_id);
		}
	}
	else{
		$id_prod=intval($no_prod_s);
		//mysqli_close($connection);	
		operationsAchat($id_prod,$mail,$quantite,$paye,$boutique_ouinternet,$charge_id);
	}
	if($bogus != "0"){
                echo "bogus different de 0<br>";
		afficheAccueil("",$bogus);
	}
}

function operationsAchat($id_prod,$mail,$quantite,$paye,$boutique_ouinternet,$charge_id){
	
	include("produits.inc");
        include_once("maj.php");
        echo "operationsAchat, debut<br>";

    if($quantite>0){
	$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in acheter, operationsAchat.php");
	
	$query_data="SELECT Prix,Distribue,Niveau_ref,Niveau_actuel,Objet,Stock FROM produits
				WHERE IdProduit=$id_prod";
	$result=mysqli_query($connection,$query_data) or die("Impossible to retrieve data about the product to purchase");
	$line=mysqli_fetch_assoc($result);
	$prix=$line['Prix'];
	$tx_distrib=$line['Distribue'];
	$niv_ref=$line['Niveau_ref'];
	$niv_actuel=$line['Niveau_actuel'];
	$objet=$line['Objet'];
	$stock=$line['Stock'];
	
	//Préfixées par h : valeurs de la partie de la commande qui a été honorée
	//Préfixées par n : valeurs de la partie de la commande qui n'a pas été honorée
	$non_honoree=0;
	if($objet == 1){
                if($stock>=$quantite){
		     $h_quantite=$quantite;
		     $h_qtelivree_auclient=$quantite;
		     $h_comm_passee=1;
		     $h_qtelivree_parfourn=$quantite;
		     $h_comm_aufourn=$quantite;
		     $h_honoree=1;
	        }
	        else{
		     $h_quantite=$stock;
		     $h_qtelivree_auclient=$stock;
		     $h_comm_passee=1;
		     $h_qtelivree_parfourn=$stock;
		     $h_comm_aufourn=$stock;
		     $h_honoree=1;
		
		     $non_honoree=1;
		     $n_quantite=$quantite-$stock;
		     $n_qtelivree_auclient=0;
		     $n_comm_passee=0;
		     $n_qtelivree_parfourn=0;
		     $n_comm_aufourn=0;
		     $n_honoree=0;
				
	        }
        }
        else{
                $h_quantite=$quantite;
		$h_qtelivree_auclient=$quantite;
		$h_comm_passee=1;
		$h_qtelivree_parfourn=$quantite;
		$h_comm_aufourn=$quantite;
		$h_honoree=1;                
        }

	$id_comm1=-1;
	$id_comm2=-1;

        echo "idprod : $id_prod<br>";
        echo "mail : $mail<br>";
        echo "h_quantite : $h_quantite<br>";
        echo "h_qtelivree_auclient : $h_qtelivree_auclient<br>";
        echo "h_comm_passee : $h_comm_passee<br>";
        echo "h_qtelivree_parfourn : $h_qtelivree_parfourn<br>";
        echo "h_comm_aufourn : $h_comm_aufourn<br>";
        echo "h_honoree : $h_honoree<br>";
        
	
	$query_order_h="INSERT INTO commandes(IdProdt2,NomClient,PrenomClient,EmailClient,Quantite,QteLivree_auclient,Commande_passee,QteLivree_parfourn,Commande_aufourn,Honoree,Remboursable,Date_comm,Paye,Boutique_internet,EncaissementID)
					VALUES($id_prod,'','','$mail',$h_quantite,$h_qtelivree_auclient,$h_comm_passee,$h_qtelivree_parfourn,$h_comm_aufourn,$h_honoree,1,DATE(NOW()),$paye,$boutique_ouinternet,'$charge_id')";
	$result_order_h=mysqli_query($connection,$query_order_h) or die("Impossible to insert a honored order : mysqli_error($connection)");
	if($result_order_h===false){
		echo "Probleme, la commande n a pas ete prise en compte (partie honoree)";
	}
	else{
		$id_comm1=mysqli_insert_id($connection);
	}

	if($non_honoree==1){
		$query_order_n="INSERT INTO commandes(IdProdt2,NomClient,PrenomClient,EmailClient,Quantite,QteLivree_auclient,Commande_passee,QteLivree_parfourn,Commande_aufourn,Honoree,Remboursable,Date_comm,Paye,Boutique_internet,EncaissementID)
					VALUES($id_prod,'','','$mail',$n_quantite,$n_qtelivree_auclient,$n_comm_passee,$n_qtelivree_parfourn,$n_comm_aufourn,$n_honoree,1,DATE(NOW()),$paye,$boutique_ouinternet,'$charge_id')";
		$result_order_n=mysqli_query($connection,$query_order_n) or die("Impossible to insert a not yet honored order");
	}
	if($result_order_n===false){
		echo "Probleme, la commande n a pas ete prise en compte (partie non honoree)";
	}
	else{
		$id_comm2=mysqli_insert_id($connection);
	}
	
	if($objet==1){
		if($stock>0){
			//Si c'est un objet physique, et qu'il y a du stock, il faut réduire la quantité en stock de 1
			$query_upd_stk="UPDATE produits
							SET Stock=Stock-$h_quantite
							WHERE IdProduit=$id_prod";
			$result_upd_stk=mysqli_query($connection,$query_upd_stk) or die("Impossible to update the volume of stocks");
			if($result_upd_stk===false){
				echo "Probleme, la quantite en stock n a pas diminue";
			}
		}
	}
	
	$query_update_level="UPDATE produits
						SET Niveau_actuel=Niveau_actuel+$quantite
						WHERE IdProduit=$id_prod";
	//echo "query :".$query_update_level."<br>";
	$result_upd_lvl=mysqli_query($connection,$query_update_level) or die("Impossible to update the volume of sales");
	if($result_upd_lvl===false){
		echo "Probleme, le nombre de ventes n a pas augmente de un";
	}
	$niv_actuel+=$quantite;
	if($niv_actuel>$niv_ref){
		$total_set_aside="SELECT Misdecote FROM produits
						WHERE IdProduit=$id_prod";
		$result_aside=mysqli_query($connection,$total_set_aside) or die("Impossible to retrieve the amount set aside for the product");
		$line_aside=mysqli_fetch_assoc($result_aside);
		$set_aside=$line_aside['Misdecote'];
		
		if($set_aside>0){
			//Recherche des utilisateurs qui ont de côté de l'argent sur ce produit
			$winning_users="SELECT DISTINCT IdUtil,MontantCote FROM
			(SELECT DATE_ADD(DateDebut,INTERVAL Duree DAY) as DateFin FROM actions) As DateCalc, 
			actions
			WHERE NOW()>DateDebut AND NOW()<=DateFin AND IdProd=$id_prod";
			echo "query :".$winning_users."<br>";
			
			$result_winners=mysqli_query($connection,$winning_users) or die("Impossible to retrieve the winning users");
			while($line_winners=mysqli_fetch_assoc($result_winners)){
				$id_user=$line_winners['IdUtil'];
				$aside_user=$line_winners['MontantCote'];
				$amount_forone=$prix*$tx_distrib*$aside_user/$set_aside;
				$coef1=min($niv_actuel-$niv_ref,$h_quantite);
				if($coef1<0) $coef1=0;
				$coef2=min($niv_actuel-$niv_ref-$coef1,$n_quantite);
				if($coef2<0) $coef2=0;
				$amount1=$amount_forone*$coef1;				
				$amount2=$amount_forone*$coef2;				

				if($amount1>0){
					$new_gain="INSERT INTO gains(IdUtilis,IdCmd,Montant,Acquis)
							VALUES($id_user,$id_comm1,$amount1,0)";
					mysqli_query($connection,$new_gain) or die("Impossible to set pending amount gained 1");
				}

				if($amount2>0){
					$new_gain2="INSERT INTO gains(IdUtilis,IdCmd,Montant,Acquis)
							VALUES($id_user,$id_comm2,$amount2,0)";
					mysqli_query($connection,$new_gain2) or die("Impossible to set pending amount gained 2");
				}
					
				/*				
				$update_won="UPDATE utilisateurs
							SET Disponible=Disponible+$amount_won,Total=Total+$amount_won
							WHERE IdUtilisateur=$id_user";
				mysqli_query($connection,$update_won) or die("Impossible to update the amount won by the user");
				$update_won2="UPDATE actions
							SET Gagne=Gagne+$amount_won
							WHERE IdProd=$id_prod AND IdUtil=$id_user AND NOW()>DateDebut AND NOW()<=DATE_ADD(DateDebut,INTERVAL Duree DAY)";
				mysqli_query($connection,$update_won2) or die("Impossible to update the amount won through the action");
				*/			
			}
		}
		
	}
	mysqli_close($connection);
        MettreAJour();
    }
	
}
?>