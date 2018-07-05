<?php

	 include_once("acheter.php");

     $mail=$_POST['Mail'];
     $bogus=$_POST['bogus'];
     $no_prod_s=$_POST['idprod'];
     $quantite=$_POST['quantite'];
 
     vide_panier($mail,"0",$no_prod_s,$quantite,"");
     
?>