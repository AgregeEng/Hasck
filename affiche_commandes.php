<!DOCTYPE html>
<html>
<head>
<title>Hasck : affichage des commandes</title>
<script>
function valider(){
	var inputs,index,name, value,params='defaut=rien';
	inputs = document.getElementsByTagName('input');
	for(index=0 ; index<inputs.length ; index++){
		name=inputs[index].name;
		if(name.includes('cbx')){
                        if(inputs[index].checked == true){
			      params=params.concat('&').concat(name).concat('=').concat(name);
                        }
		}
		if(name.includes('bogus')){
			value=inputs[index].value;
			params=params.concat('&').concat(name).concat('=').concat(value);					
		}
	}
	
	alert(params);
	var http=new XMLHttpRequest();
	var url='annule_commandes.php';
	http.open('POST',url,true);
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	
	http.onreadystatechange = function() { //Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
	        alert(http.responseText);
	    }
	};
	http.send(params);
}
</script>
</head>
<body>

<?php

include("produits.inc");
$bogus=$_POST['bogus'];

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in affiche_commandes.php");

echo "<table>";
echo "<tr><td>Nom</td><td>Image</td><td>Description</td><td>Quantite</td><td>Supprimer</td></tr>";
$query_orders="SELECT IdCommande,IdProduit,Nom,Image,Description,Quantite FROM commandes,produits
			WHERE commandes.IdProdt2=produits.IdProduit AND Remboursable=1 AND Annule_parclient=0";
$result=mysqli_query($connection,$query_orders) or die("Impossible to retrieve the refundable non cancelled orders");
while($line=mysqli_fetch_assoc($result)){
	$nom=$line['Nom'];
	$image=$line['Image'];
	$descr=$line['Description'];
	$qte=$line['Quantite'];
        $id_comm=$line['IdCommande'];
	$id_prod=$line['IdProduit'];
	$nom_cb="cbx".$id_comm;
	echo "<tr><td>$nom</td><td><img src='$image'></td><td>$descr</td><td>$qte</td><td><input type='checkbox' name=$nom_cb></td></tr>";
}
echo "</table>";
echo "<input type='hidden' name='bogus' value=$bogus>";
echo "<button type='button' onclick='valider()'>Annuler</button>";

echo "<form action='index.php' method='POST'>";
echo "<input type='hidden' name='bogus' value=$bogus>";
echo "<input type='submit' value='Retour'>";
echo "</form>";

mysqli_close($connection);

?>