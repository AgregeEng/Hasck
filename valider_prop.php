<!DOCTYPE html>
<html>
<head>
<title>Hasck : valider des propositions</title>
<script>
function valider(){
	var inputs,index,name, value,params='defaut=rien';
	inputs = document.getElementsByTagName('input');
	for(index=0 ; index<inputs.length ; index++){
			node=inputs[index];
			if(node.getAttribute('type')=='checkbox'){
				if(node.checked == true){
					name=node.name;					
					params=params.concat('&').concat(name).concat('=').concat(name);
				}
			}
			if(node.getAttribute('type')=='number'){
				name=node.name;
				value=node.value;
				params=params.concat('&').concat(name).concat('=').concat(value);				
			}
	}
	alert(params);
	var http=new XMLHttpRequest();
	var url='transferer_prop.php';
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
//Pour l'instant ca marche

include("produits.inc");
$connection=mysqli_connect($host,$user,$password,$database) or die("Impossible to connect to the database in valider_prop.php");

$query="SELECT IdPropos,Nom,Image,Prix,Description FROM propositions";
$result=mysqli_query($connection,$query) or die("Impossible to retrieve the propositions");
echo "<table><tr><td>Nom</td><td>Image</td><td>Prix</td><td>Description</td><td>Objet</td><td>Stock</td><td>Valider</td></tr>";
while($line=mysqli_fetch_assoc($result)){
	$id=$line['IdPropos'];
	$nom=$line['Nom'];
	$image=$line['Image'];
	$prix=$line['Prix'];
	$descr=$line['Description'];
	$code="cb".$id;
	$objet="obj".$id;
	$stk="stk".$id;
	echo "<tr><td>$nom</td><td><img src='$image'></td><td>$prix</td><td>$descr</td>";
	echo "<td><input type='checkbox' name='$objet'></td>";
	echo "<td><input type='number' name='$stk'></td>";	
	echo "<td><input type='checkbox' name='$code'></td></tr>";
}
echo "</table>";
echo "<button type='button' onclick='valider()'>Valider</button>";

mysqli_close($connection);

?>

</body>
</html>

