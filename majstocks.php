<?php

include("produits.inc");

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in majstocks.php");

foreach($_POST as $param_name => $param_val){
		if(strpos($param_name,"qte")!==false){
			$longueur=strlen($param_name);
			$id_comm=substr($param_name,3,$longueur-3);
			$livre=$param_val;
                    if($livre != ""){
	
                        echo "id_comm : $id_comm<br>";
                        echo "livre : $livre<br>";
			$query_order="SELECT Commande_passee FROM commandes
						WHERE IdCommande=$id_comm";
			$result_order=mysqli_query($connection,$query_order) or die("Impossible to retrieve the order");
			if($result_order!==false){
				$line_order=mysqli_fetch_assoc($result_order);
				$comm_passee=$line_order['Commande_passee'];
				if($comm_passee==0){
					//Pas encore commandé au fournisseur, donc $livre contient la quantité commandée au fournisseur
					$query_ordered_supplier="UPDATE commandes
										SET Commande_aufourn=Commande_aufourn+$livre,Commande_passee=1
										WHERE IdCommande=$id_comm";
					$result_order_supplier=mysqli_query($connection,$query_ordered_supplier) or die("Impossible to update the quantity ordered to the supplier : mysqli_error($connection)");
				}
				else{
					//Commande déjà effectuée, donc l'étape suivante est la réception de la commande au fournisseur
					$query_delivered_supplier="UPDATE commandes
												SET QteLivree_parfourn=QteLivree_parfourn+$livre
												WHERE IdCommande=$id_comm";
					$result_delivery_supplier=mysqli_query($connection,$query_delivered_supplier) or die("Impossible to update the quantity delivered by the supplier");
					
					//Si la quantité livrée par le fournisseur est supérieure à la quantité nécessaire pour le client, le surplus est versé au stock
					$query_surplus="SELECT IdProdt2,QteLivree_parfourn,Quantite FROM commandes
									WHERE QteLivree_parfourn>Quantite";
					$result_surplus=mysqli_query($connection,$query_surplus) or die("Impossible to determine the surplus quantity");
					while($line_surplus=mysqli_fetch_assoc($result_surplus)){
						$id_prod=$line_surplus['IdProdt2'];
						$qte_dufourn=$line_surplus['QteLivree_parfourn'];
						$qte_duclient=$line_surplus['Quantite'];
						$diff=$qte_dufourn-$qte_duclient;
						$query="UPDATE produits
								SET Stock=Stock+$diff
								WHERE IdProduit=$id_prod";
						$result=mysqli_query($connection,$query) or die("Impossible to update the stocks");
						if($result===false){
							echo "Probleme, les livraisons excedentaires n ont pas ete prises en compte dans les stocks";
						}
					}
					//On réduit le nombre de livraisons du fournisseur au nombre d exemplaires commandés par le client, car on a déjà mis le surplus dans le stock de produits
					$query_ord="UPDATE commandes
								SET QteLivree_parfourn=Quantite
								WHERE QteLivree_parfourn>Quantite";
					$result_ord=mysqli_query($connection,$query_ord) or die("Impossible to update the surplus delivered by suppliers in the orders");
					if($result_ord===false){
						echo "Probleme, les livraisons excedentaires n ont pas ete prises en compte dans les commandes";
					}

				}
				
			}
                    }
			
			
			
		}
}

mysqli_close($connection);
	
?>