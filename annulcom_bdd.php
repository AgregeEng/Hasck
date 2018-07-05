<?php

function annule_comm($no_comm){
	        include("produits.inc");
                include_once("rembstripe.php");
	        $connection=mysqli_connect($host,$user,$password,$database) or die("Impossible to connect to the database in annulcom_bdd.php");

                        $query_refundable="SELECT IdCommande FROM commandes
                                          WHERE IdCommande=$no_comm AND Remboursable=1";
			$result=mysqli_query($connection,$query_refundable) or die("Impossible to find a refundable order matching the order number given");
                        $nb_comm_remboursables=mysqli_num_rows($result);
                if($nb_comm_remboursables>0){
	
			$query_data_ord="SELECT IdProdt2,Quantite,QteLivree_auclient,Honoree,Paye,Boutique_internet,QteLivree_parfourn,Objet FROM commandes,produits
						WHERE commandes.IdProdt2=produits.IdProduit AND IdCommande=$no_comm AND Remboursable=1";
			$r_data=mysqli_query($connection,$query_data_ord) or die("Impossible to retrieve data about an order due to be cancelled by the user");
                        $line=mysqli_fetch_assoc($r_data);
                        $id_prod=$line['IdProdt2'];
                        $qte=$line['Quantite'];
                        $livree_client=$line['QteLivree_auclient'];
                        $honoree=$line['Honoree'];
                        $paye=$lin['Paye'];
                        $boutique_ouinternet=$line['Boutique_internet'];
                        $livre_parfourn=$line['QteLivree_parfourn'];
                        $objet=$line['Objet'];

                        //Objet physique livré par le fournisseur, mais pas au client, commandé sur Internet
                        if($livree_client==0 && $livre_parfourn>0 && $boutique_ouinternet==1){
                                if($objet==1){
                                      $update_stk1="UPDATE produits
                                                   SET Stock=Stock+$livre_parfourn
                                                   WHERE IdProduit=$id_prod";
                                      mysqli_query($connection,$update_stk1) or die("Impossible to increase the stocks which were increased by delivery from the supplier,
                                             following a cancelled non honored order");
                                }
                                $update_ord1="UPDATE commandes
                                              SET Annule_parclient=1
                                              WHERE IdCommande=$no_comm";
                                mysqli_query($connection,$update_ord1) or die("Impossible to set order to cancelled status");
                                if($paye==1){
                                         remboursement_stripe($no_comm);
                                }
                        }
                        //Objet physique livré au client, commandé par Internet
                        if($objet==1 && $livree_client>0 && boutique_ouinternet==1){
                                $update_ord2="UPDATE commandes
                                              SET Ret_pendanteffectue=1,Annule_parclient=1
                                              WHERE IdCommande=$no_comm";
                                mysqli_query($connection,$update_ord2) or die("Impossible to set return to pending status, and to set order to cancelled status");
                                if($paye==1){
                                         remboursement_stripe($no_comm);
                                }
                        }
                        //Objet physique livré ni par le fournisseur ni au client, commandé par Internet
                        if($boutique_ouinternet==1 && $livree_client==0 && $livre_parfourn==0){
                                $update_ord3="UPDATE commandes
                                              SET Annule_parclient=1
                                              WHERE IdCommande=$no_comm";
                                mysqli_query($connection,$update_ord3) or die("Impossible to set order to cancelled status (no delivery)");
                                if($paye==1){
                                         remboursement_stripe($no_comm);
                                }
                                
                        }
                        //Objet physique livré par le fournisseur, non livré au client, commandé en boutique
                        if($livree_client==0 && $livre_parfourn>0 && $boutique_ouinternet==0){
                                if($objet==1){
                                     $update_stk2="UPDATE produits
                                                   SET Stock=Stock+$livre_parfourn
                                                   WHERE IdProduit=$id_prod";
                                     mysqli_query($connection,$update_stk2) or die("Impossible to increase the stocks which were increased by delivery from the supplier,
                                             following a cancelled non honored order (order made at the shop)");
                                }
                                $update_ord4="UPDATE commandes
                                              SET Annule_parclient=1
                                              WHERE IdCommande=$no_comm";
                                mysqli_query($connection,$update_ord4) or die("Impossible to set order to cancelled status (order made at the shop)");
                                if($paye==1){
                                         $update_ord4b="UPDATE commandes
                                              SET Rembourse=1
                                              WHERE IdCommande=$no_comm";
                                         mysqli_query($connection,$update_ord4b) or die("Impossible to set order to : to refund status (order made at the shop)");
                                }
                        }
                        //Objet physique livré au client, commandé par Internet
                        if($objet==1 && $livree_client>0 && boutique_ouinternet==0){
                                $update_ord5="UPDATE commandes
                                              SET Ret_pendanteffectue=1,Annule_parclient=1
                                              WHERE IdCommande=$no_comm";
                                mysqli_query($connection,$update_ord5) or die("Impossible to set return to pending status, and to set order to cancelled status (order made at the shop)");
                                if($paye==1){
                                         $update_ord5b="UPDATE commandes
                                              SET Rembourse=1
                                              WHERE IdCommande=$no_comm";
                                         mysqli_query($connection,$update_ord5b) or die("Impossible to set order to : to refund status (order made at the shop, delivered to the client)");
                                }

                        }
                        //Objet physique livré ni par le fournisseur ni au client, commandé en boutique
                        if($boutique_ouinternet==0 && $livree_client==0 && $livre_parfourn==0){
                                $update_ord6="UPDATE commandes
                                              SET Annule_parclient=1
                                              WHERE IdCommande=$no_comm";
                                mysqli_query($connection,$update_ord6) or die("Impossible to set order to cancelled status (no delivery, order made at the shop)");
                                if($paye==1){
                                         $update_ord6b="UPDATE commandes
                                              SET Rembourse=1
                                              WHERE IdCommande=$no_comm";
                                         mysqli_query($connection,$update_ord6b) or die("Impossible to set order to : to refund status (order made at the shop, not delivered to the client)");
                                }
                                
                        }
                        if($objet==0 && $boutique_ouinternet==1){
                                $update_ord7="UPDATE commandes
                                              SET Annule_parclient=1
                                              WHERE IdCommande=$no_comm";
                                mysqli_query($connection,$update_ord7) or die("Impossible to set order to cancelled status (non physical product, order made online)");
                                if($paye==1){
                                         remboursement_stripe($no_comm);
                                }                                
                        }
                        if($objet==0 && $boutique_ouinternet==0){
                                $update_ord8="UPDATE commandes
                                              SET Annule_parclient=1
                                              WHERE IdCommande=$no_comm";
                                mysqli_query($connection,$update_ord8) or die("Impossible to set order to cancelled status (non physical product, order made at the shop)");
                                if($paye==1){
                                         $update_ord8b="UPDATE commandes
                                              SET Rembourse=1
                                              WHERE IdCommande=$no_comm";
                                         mysqli_query($connection,$update_ord8b) or die("Impossible to set order to : to refund status (order made at the shop, non physical object)");
                                }                                
                        }
                        echo "<!DOCTYPE html>";
                        echo "<html>";
                        echo "<head>";
                        echo "<title>Hasck : annulation</title>";
                        echo "</head>";
                        echo "<body>";
                        echo "Annulation reussie";
                        echo "</body>";
                        echo "<html>";
                        


                        if($honoree==1 && $objet==1){
                                
                                /* lors du retour
			        $query_update_stk="UPDATE produits
                                                   SET Stock=Stock+$livree_client
                                                   WHERE IdProduit=$id_prod";
			        mysqli_query($connection,$query_data_ord) or die("Impossible to increase the stocks which were increased by the return of the client,
                                             following a cancelled honored order ");
                                */
                        }
                        $query_update_lvl="UPDATE produits
                                          SET Niveau_actuel=Niveau_actuel-$qte
                                          WHERE IdProduit=$id_prod";
                        mysqli_query($connection,$query_update_lvl) or die("Impossible to decrease the volume of sales resulting from the cancellation");

                        /*
			$query_delete_ord="DELETE FROM commandes
						WHERE IdCommande=$no_comm";
			mysqli_query($connection,$query_delete_ord) or die("Impossible to delete an order cancelled by the user");
                        */
			$query_delete_gain="DELETE FROM gains
								WHERE IdCmd=$no_comm";
			mysqli_query($connection,$query_delete_gain) or die("Impossible to delete gains associated to an order cancelled");
                }
                else{
                        echo "<!DOCTYPE html>";
                        echo "<html>";
                        echo "<head>";
                        echo "<title>Hasck : annulation</title>";
                        echo "</head>";
                        echo "<body>";
                        echo "Aucune commande remboursable trouvee";
                        echo "</body>";
                        echo "<html>";                
                }

		mysqli_close($connection);
}

?>
