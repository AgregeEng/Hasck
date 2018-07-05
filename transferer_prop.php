<?php

include("produits.inc");

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in transferer_prop.php");

//Recherche du numéro de proposition qui a été coché
foreach($_POST as $param_name => $param_val){
		if(strpos($param_val,"cb")!==false){
			$longueur=strlen($param_val);
			$no_propos_s=substr($param_val,2,$longueur-2);
                        echo "no_propos_s : $no_propos_s<br>";
			//Recherche des valeurs objet et stock associées à cette proposition
			$objet=0;
			$stock=0;
			foreach($_POST as $param_name2 => $param_val2){
				if(strpos($param_val2,"obj")!==false){
					$longueur2=strlen($param_val2);
					$no_propos_s2=substr($param_val2,3,$longueur2-3);
                                        echo "no_propos_s2 : $no_propos_s2<br>";
					if($no_propos_s==$no_propos_s2){
                                                $objet=1;
                                                echo "objet<br>";
                                        }
				}
				if(strpos($param_name2,"stk")!==false){
					if($param_val2 != ""){
						$longueur3=strlen($param_name2);
						$no_propos_s3=substr($param_name2,3,$longueur3-3);
                                                echo "no_propos_s3 : $no_propos_s3<br>";
						if($no_propos_s==$no_propos_s3){
							$stock=$param_val2;	
                                                        echo "stock : $stock<br>";
						}
					}
				}
			}
	
			$no_prop=intval($no_propos_s);
			//echo "no_prop :$no_prop";
			$query="SELECT Nom,Image,Prix,Description FROM propositions
			WHERE IdPropos=$no_prop";
			$result=mysqli_query($connection,$query) or die("Impossible to execute the query to retrieve the propositions to transfer");
			$line=mysqli_fetch_assoc($result);
			$nom=$line['Nom'];
			$image=$line['Image'];
			$prix=$line['Prix'];
			$descr=$line['Description'];
			$query_transfer="INSERT INTO produits(Nom,Image,Prix,Description,Distribue,Niveau_ref,Niveau_actuel,Misdecote,Objet,Stock)
			VALUES('$nom','$image',$prix,'$descr',0.1,0,0,0,$objet,$stock)";
			$completed=mysqli_query($connection,$query_transfer) or die("Impossible to execute the query to insert the new proposition");
			if($completed!==false){
				$query_delete="DELETE FROM propositions
				WHERE IdPropos=$no_prop";
				mysqli_query($connection,$query_delete) or die("Impossible to execute the query to delete the transferred proposition");
			}
		}
}
mysqli_close($connection);
?>