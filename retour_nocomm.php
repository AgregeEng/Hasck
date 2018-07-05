<?php

include("produits.inc");
include_once("annulcomm_bdd.php");
include_once("rembstripe.php");

$no_comm=$_POST['idcomm'];

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in retour_nocomm.php");

annule_comm($no_comm);

$query_return_pending="SELECT IdProdt2,Paye,Boutique_internet,Quantite,Prix,QteLivree_auclient FROM commandes,produits
						WHERE commandes.IdProdt2=produits.IdProduit AND IdCommande=$no_comm AND Remboursable=1";
$result=mysqli_query($connection,$query_return_pending) or die("Impossible to retrieve data about orders to cancel and refund");
if($line=mysqli_fetch_assoc($result)){
	$id_prod=$line['IdProdt2'];
	$paye=$line['Paye'];
	$boutique_ouinternet=$line['Boutique_internet'];
	$qte=$line['Quantite'];
	$prix=$line['Prix'];
	$livree_client=$line['QteLivree_auclient'];
	$montant=$qte*$prix;
	$remb_fait=1;//Remboursement à faire (0 : remboursement n'est pas à faire, 2 : remboursement est fait)
	if($paye==1 && $boutique_ouinternet==1){
		remboursement_stripe($no_comm);
                $remb_fait=2;
	}
	else{
		if($paye==1 && $boutique_ouinternet==0){
			echo "Commande à rembourser, montant : $montant";
			$remb_fait=2;		
		}
	}
	$update_ord="UPDATE commandes
			SET Remboursable=0,Honoree=1,Ret_pendanteffectue=2,Annule_parclient=1,Rembourse=$remb_fait
			WHERE IdCommande=$no_comm";
	mysqli_query($connection,$update_ord) or die("Impossible to set the new statuses for the order corresponding to the returned product");
	//Quantité livrée au client = quantité retournée par le client, donc qui s'ajoute au stock
	$update_stk="UPDATE produits
				SET Stock=Stock+$livree_client
				WHERE IdProduit=$id_prod";
	mysqli_query($connection,$update_stk) or die("Impossible to update the stock that has increased as a result of the return of products");
}
else{
	echo "Impossible de trouver les donnees utiles pour enregistrer le retour, ou commande non remboursable";
}

	
mysqli_close($connection);
	
?>