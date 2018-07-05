<?php

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function afficheAccueil($achercher,$bog){
	include("produits.inc");
	
	//ob_start(PHP_OUTPPUT_HANDLER_CLEANABLE);
	
	echo "<!DOCTYPE html>";
	echo "<html>";
	echo "<head>";
	echo "<title>Hasck</title>";
	echo "<script>";
	echo "window.onload = valoriser_bogus;";
	echo "function valoriser_bogus(){";
	echo "	var inputs,index,name, value;";
	echo "	inputs = document.getElementsByTagName('input');";
	echo "	for(index=0 ; index<inputs.length ; index++){";
	echo "		name=inputs[index].name;";
	echo "		if(name.includes('bogus')){";
	echo "			inputs[index].value='$bog';";
	echo "		}";	
	echo "	}";
	echo "}";
	
	echo "function ajout_panier(){";
	echo "	var inputs,index,name, value,params='defaut=rien';";
	echo "	inputs = document.getElementsByTagName('input');";
	echo "	for(index=0 ; index<inputs.length ; index++){";
	echo "		name=inputs[index].name;";
	echo "		if(name.includes('ach')){";
	echo "			value=inputs[index].value;";	
	echo "			params=params.concat('&').concat(name).concat('=').concat(value);";
	echo "		}";
	echo "		if(name.includes('bogus')){";
	echo "			value=inputs[index].value;";	
	echo "			params=params.concat('&').concat(name).concat('=').concat(value);";					
	echo "		}";
	echo "	}";
	
	echo "	alert(params);";
	echo "	var http=new XMLHttpRequest();";
	echo "	var url='ajoutpanier.php';";
	echo "	http.open('POST',url,true);";
	echo "	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');";
	
	echo "	http.onreadystatechange = function() {";//Call a function when the state changes.
	echo "	    if(http.readyState == 4 && http.status == 200) {";
	echo "	        alert(http.responseText);";
	echo "	    }";
	echo "	};";
	echo "	http.send(params);";
	echo "}";
	
	echo "function valider(){";
	echo "	var inputs,index,name, value,params='defaut=rien';";
	echo "	inputs = document.getElementsByTagName('input');";
	echo "	for(index=0 ; index<inputs.length ; index++){";
	echo "		name=inputs[index].name;";
	echo "		if(name.includes('montant')){";
	echo "			value=inputs[index].value;";
	echo "			params=params.concat('&').concat(name).concat('=').concat(value);";
	echo "		}";
	echo "		if(name.includes('num_id') && !name.includes('num_id2')){";
	echo "			value=inputs[index].value;";
	echo "			params=params.concat('&').concat(name).concat('=').concat(value);";
	echo "		}";
	echo "		if(name.includes('nom') && !name.includes('nom2')){";
	echo "			value=inputs[index].value;";
	echo "			params=params.concat('&').concat(name).concat('=').concat(value);";
	echo "		}";
	echo "		if(name.includes('prenom') && !name.includes('prenom2')){";
	echo "			value=inputs[index].value;";
	echo "			params=params.concat('&').concat(name).concat('=').concat(value);";
	echo "		}";
	echo "		if(name.includes('bogus')){";
	echo "			value=inputs[index].value;";	
	echo "			params=params.concat('&').concat(name).concat('=').concat(value);";					
	echo "		}";
	echo "	}";
	echo "	alert(params);";
	echo "	var http=new XMLHttpRequest();";
	echo "	var url='mettrecote.php';";
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

	//echo "$_SERVER[DOCUMENT_ROOT]";
	echo "<form action='valider_prop.php'>";
	echo "<input type='hidden' name='bogus'>";
	echo "<input type='submit' value='Valider des propositions'></form>";
	
	echo "<form action='proposer.php' method='POST'>";
	echo "<input type='hidden' name='bogus'>";	
	echo "<input type='submit' value='Proposer un article'></form>";
	
	echo "<form action='creercompte.php' method='POST'>";
	echo "<input type='text' name='num_id2' placeholder='Numero de piece d identite'>";
	echo "<input type='text' name='prenom2' placeholder='Prenom'>";
	echo "<input type='text' name='nom2' placeholder='Nom'>";
	echo "<input type='hidden' name='bogus'>";
	echo "<input type='submit' value='Creer un compte'></form>";
	
	echo "<form action='chercher.php' method='POST'>";
	echo "<input type='text' name='achercher'>";
	echo "<input type='hidden' name='bogus'>";
	echo "<input type='submit' value='Rechercher'></form>";

	//echo "<form action='dem_retrait.php' method='POST'><input type='submit' value='Demander un retrait'></form>";
	echo "<button type='button' onclick='ajout_panier()'>Ajouter au panier</button>";
	
	echo "<form action='consulter_panier.php' method='POST'>";
	echo "<input type='hidden' name='bogus'>";
	echo "<input type='submit' value='Acheter le contenu du panier'>";
	echo "</form>";
	
	echo "<form action='affiche_commandes.php' method='POST'>";
	echo "<input type='hidden' name='bogus'>";
	echo "<input type='submit' value='Afficher les commandes'>";
	echo "</form>";
	
	
	echo "<form action='mesactions.php' method='POST'>";
	echo "<input type='text' name='num_id' placeholder='Numero de piece d identite'>";
	echo "<input type='text' name='prenom' placeholder='Prenom'>";
	echo "<input type='text' name='nom' placeholder='Nom'>";
	echo "<input type='hidden' name='bogus'>";
	echo "<input type='submit' value='Mon compte'></form>";
	
	echo "<form action='exec_maj.php' method='POST'>";
	echo "<input type='submit' value='Mettre a jour'>";
	echo "</form>";

	echo "<form action='entrer_livraisons.php' method='POST'>";
	echo "<input type='submit' value='Entrer livraisons'>";
	echo "</form>";

	
	$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in index.php");

        //echo "$connection<br>";
	
	if($achercher!=""){
		echo "on cherche<br>";
		$search_query="SELECT IdProduit,Nom,Image,Prix,Misdecote,Description,Objet,Stock FROM produits
		WHERE Nom LIKE '%$achercher%' OR Description LIKE '%$achercher%'
		ORDER BY Misdecote DESC LIMIT 50";
		$search_result=mysqli_query($connection,$search_query) or die("Impossible to execute the query to retrieve the products matching the search string");
		echo "<table><tr><td>Nom</td><td>Image</td><td>Prix</td><td>Mis de cote</td><td>Description</td>";
		echo "<td>Stock</td>";
		echo "<td>Acheter</td><td>Montant</td><td><button type='button' onClick='valider()'>Mettre de cote</button></td></tr>";
		while($search_line=mysqli_fetch_assoc($search_result)){
			$s_prodid=$search_line['IdProduit'];
			$s_nom=$search_line['Nom'];
			$s_image=$search_line['Image'];
			$s_prix=$search_line['Prix'];
			$s_misdecote=$search_line['Misdecote'];
			$s_description=$search_line['Description'];
			$s_objet=$search_line['Objet'];
			$s_stock="non applicable";
			if($s_objet == 1){
				$s_stock=$search_line['Stock'];
			}
			echo "<tr><td>$s_nom</td><td><input type='image' src='$s_image' width='100' height='100'></td>";
			echo "<td>$s_prix</td><td>$s_misdecote</td><td>$s_description</td><td>$s_stock</td>";
			//echo "<td><form action='acheter.php' method='POST'><input type='hidden' name='id_prod' value=$s_prodid><input type='submit' value='Acheter'></form></td><td><input type='number' name='"."montant"."$s_prodid"."'></td></tr>";
			echo "<td><input type='number' name='"."ach"."$s_prodid"."'></td><td><input type='number' name='"."montant"."$s_prodid"."'></td></tr>";		}
		echo "</table>";
	}
	
	$query="SELECT IdProduit,Nom,Image,Prix,Misdecote,Description,Objet,Stock FROM produits
	ORDER BY Misdecote DESC LIMIT 50";
	$result=mysqli_query($connection,$query) or die("Impossible to execute the query to retrieve the products");
	echo "<table><tr><td>Nom</td><td>Image</td><td>Prix</td><td>Mis de cote</td><td>Description</td>";
	echo "<td>Stock</td>";
	echo "<td>Acheter</td><td>Montant</td><td><button type='button' onClick='valider()'>Mettre de cote</td></tr>";
	while($line=mysqli_fetch_assoc($result)){
		$prodid=$line['IdProduit'];
		$nom=$line['Nom'];
		$image=$line['Image'];
		$prix=$line['Prix'];
		$misdecote=$line['Misdecote'];
		$description=$line['Description'];
		$objet=$line['Objet'];
		$stock="non applicable";
		if($objet == 1){
			$stock=$line['Stock'];
		}
		echo "<tr><td>$nom</td><td><img src='$image' width='100' height='100'></td>";
		echo "<td>$prix</td><td>$misdecote</td><td>$description</td><td>$stock</td>";
		//echo "<td><form action='acheter.php' method='POST'><input type='hidden' name='id_prod2' value=$prodid><input type='submit' value='Acheter'></form></td><td><input type='number' name='"."montant"."$prodid"."'></td></tr>";
		echo "<td><input type='number' name='"."ach"."$prodid"."'></td><td><input type='number' name='"."montant"."$prodid"."'></td></tr>";
	}
	echo "</table>";
	
	echo "</body>";
	echo "</html>";
	
	mysqli_close($connection);
}
?>