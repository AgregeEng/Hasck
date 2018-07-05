<?php

commandes();
//commandes(1);

function commandes(){
	
	include("produits.inc");
	
	$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in entrer_livraisons.php");
	
	
	echo "<!DOCTYPE html>";
	echo "<html>";
	echo "<head>";
	echo "<title>Hasck : traitement des commandes</title>";
	echo "<script>";
	echo "function maj_stocks(){";
	echo "	var inputs,index,name, value,params='defaut=rien';";
	echo "	inputs = document.getElementsByTagName('input');";
	echo "	for(index=0 ; index<inputs.length ; index++){";
	echo "		name=inputs[index].name;";
	echo "		if(name.includes('qte')){";
	echo "			value=inputs[index].value;";
        echo "                  if(value != ''){";
	echo "			     params=params.concat('&').concat(name).concat('=').concat(value);";
        echo "                  }";
	echo "		}";
	echo "	}";
	
	echo "	alert(params);";
	echo "	var http=new XMLHttpRequest();";
	echo "	var url='majstocks.php';";
	echo "	http.open('POST',url,true);";
	echo "	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');";
	echo "  http.onreadystatechange = function() {"; //Call a function when the state changes.
	echo "	    if(http.readyState == 4 && http.status == 200) {";
	echo "	        alert(http.responseText);";
	echo "	    }";
	echo "	};";
	echo "	http.send(params);";
	echo "}";
	echo "</script>";
	
	echo "</head>";
	echo "<body>";
	

	$query_orders_made="SELECT IdCommande,IdProdt2,Nom,Image,Quantite FROM commandes,produits
	WHERE commandes.IdProdt2=produits.IdProduit AND Commande_passee=0 AND Objet=1 AND Honoree=0";
	$result=mysqli_query($connection,$query_orders_made) or die("Impossible to retrieve the orders made");

        echo "<table>";	
	echo "<tr><td>Reference</td><td>Nom</td><td>Image</td><td>Quantite a commander</td><td>Quantite commandee</td></tr>";
	
	while($line=mysqli_fetch_assoc($result)){
		$id_comm=$line['IdCommande'];
		$id_prod=$line['IdProdt2'];
		$nom=$line['Nom'];
		$image=$line['Image'];
		$qte_acommander=$line['Quantite'];
		echo "<tr><td>$id_prod</td><td>$nom</td><td><img src='$image'></td><td>$qte_acommander</td><td><input type='number' name='qte".$id_comm."'></td></tr>";
	}
	
	echo "</table>";
	
        echo "<table>";
        echo "<tr><td>Reference</td><td>Nom</td><td>Image</td><td>Quantite a recevoir</td><td>Quantite recue du fournisseur</td></tr>";

	$query_orders_received="SELECT IdCommande,IdProdt2,Nom,Image,Quantite FROM commandes,produits
	WHERE commandes.IdProdt2=produits.IdProduit AND Commande_passee=1 AND Objet=1 AND Honoree=0";
	$result2=mysqli_query($connection,$query_orders_received) or die("Impossible to retrieve the orders received");

	while($line2=mysqli_fetch_assoc($result2)){
		$id_comm=$line2['IdCommande'];
		$id_prod=$line2['IdProdt2'];
		$nom=$line2['Nom'];
		$image=$line2['Image'];
		$qte_acommander=$line2['Quantite'];
		echo "<tr><td>$id_prod</td><td>$nom</td><td><img src='$image'></td><td>$qte_acommander</td><td><input type='number' name='qte".$id_comm."'></td></tr>";
	}	
	echo "</table>";
	
        echo "<button type='button' onClick='maj_stocks()'>Mettre a jour les stocks</button>";
	
	mysqli_close($connection);

}
?>

</body>
</html>