<?php

//include("produits.inc");
include_once("accueil.php");
include_once("annulcom_bdd.php");
$bogus=$_POST['bogus'];

/*
$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in annule_commandes.php");
*/

foreach($_POST as $param_name => $param_val){
		echo "param_name :".$param_name."<br>";
		echo "param_val :".$param_val."<br>";
		if(strpos($param_name,"cbx")!==false){
			$longueur=strlen($param_name);
			$no_comm_s=substr($param_name,3,$longueur-3);
			$no_comm=intval($no_comm_s);
                        annule_comm($no_comm);
                }
}


//mysqli_close($connection);

afficheAccueil("",$bogus);
?>