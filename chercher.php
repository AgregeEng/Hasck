<?php

//echo "chercher debut";
include_once("accueil.php");
//echo "chercher suite";

$achercher=$_POST["achercher"];
$bogus=$_POST['bogus'];
//echo "chercher fin";

afficheAccueil($achercher,$bogus);
//echo "chercher apres fin";

?>