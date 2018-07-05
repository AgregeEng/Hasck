<?php

$bogus=$_POST['bogus'];
//echo "cp";
consulterPanier($bogus);

function consulterPanier($bogus){
	include("produits.inc");
	//include("index.php");

	$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in consulter_panier.php");
	
	$query_cart="SELECT IdProduit,Nom,Image,Prix,Description,Quantite FROM produits,paniers
	WHERE produits.IdProduit=paniers.IdProdt AND Bogus='$bogus'";
	$cart=mysqli_query($connection,$query_cart) or die("Impossible to retrieve the contents of the cart");
	
	echo "<!DOCTYPE html>";
	echo "<html>";
	echo "<head>";
	echo "<title>Hasck : panier</title>";
	echo "<script>";
	echo "function valider(){"; //Supprimer du panier
	echo "	var inputs,index,name, value,params='defaut=rien';";
	echo "	inputs = document.getElementsByTagName('input');";
	echo "	for(index=0 ; index<inputs.length ; index++){";
	echo "		name=inputs[index].name;";
	echo "		if(name.includes('chbx')){";
        echo "                  if(inputs[index].checked == true){";
	echo "			      params=params.concat('&').concat(name).concat('=').concat(name);";
        echo "                  }";
	echo "		}";
	echo "		if(name.includes('bogus')){";
	echo "			value=inputs[index].value;";	
	echo "			params=params.concat('&').concat(name).concat('=').concat(value);";					
	echo "		}";
	echo "	}";
	
	echo "	alert(params);";
	echo "	var http=new XMLHttpRequest();";
	echo "	var url='enleve_panier.php';";
	echo "	http.open('POST',url,true);";
	echo "	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');";
	
	echo "	http.onreadystatechange = function() {";//Call a function when the state changes.
	echo "	    if(http.readyState == 4 && http.status == 200) {";
	echo "	        alert(http.responseText);";
	echo "	    }";
	echo "	};";
	echo "	http.send(params);";
	echo "}";
	echo "</script>";
	echo "</head>";
	echo "<body>";
	
	echo "<table>";
	echo "<tr><td>Nom</td><td>Image</td><td>Prix</td><td>Description</td><td>Quantite</td><td>Supprimer</td></tr>";
	
	$total=0;
	
	while($line=mysqli_fetch_assoc($cart)){
		$nom=$line['Nom'];
		$image=$line['Image'];
		$prix=$line['Prix'];
		$descr=$line['Description'];
		$id_prod=$line['IdProduit'];
		$quantite=$line['Quantite'];
		$nom_cb="chbx".$id_prod;
		echo "<tr><td>$nom</td><td><img src='$image'></td><td>$prix</td><td>$descr</td><td>$quantite</td><td><input type='checkbox' name=$nom_cb></td></tr>";
		$total+=$prix*$quantite;
	}
	
	echo "</table>";
	echo "Montant total : $total<br>";
        $total_str=$total*100;	

	echo "<form action='payer.php' method='POST'>";
	echo "<input type='hidden' name='montant' value=$total_str>";
	echo "<input type='hidden' name='bogus' value=$bogus>";
	echo "  <script";
	echo "    src='https://checkout.stripe.com/checkout.js' class='stripe-button'";
	echo "    data-key='pk_test_PRbU67QlEXzCY2Mh5PmwaGJH'";
	echo "    data-amount=$total_str";
	echo "    data-name='Stripe.com'";
	echo "    data-description='Example charge'";
	echo "    data-image='https://stripe.com/img/documentation/checkout/marketplace.png'";
	echo "    data-locale='auto'";
	echo "    data-zip-code='true'>";
	echo "  </script>";
	echo "</form>";
	
	/*
	echo "<form action='acheter.php' method='POST'>";
	echo "<input type='hidden' name='bogus' value='$bogus'>";
	echo "<input type='submit' value='Acheter'>";
	echo "</form>";
	*/
	
	echo "<input type='hidden' name='bogus' value=$bogus>";
	echo "<button type='button' onclick='valider()'>Supprimer du panier</button>";
	
	echo "<form action='retour.php' method='POST'>";
	echo "<input type='hidden' name='bogus' value='$bogus'>";
	echo "<input type='submit' value='Retour'>";
	echo "</form>";
	
	echo "</body>";
	echo "</html>";
	
	mysqli_close($connection);

}	
?>