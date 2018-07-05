<?php

define ('SITE_ROOT', realpath(dirname(__FILE__)));
include("accueil.php");
 
if(!isset($_POST['bogus'])){
		$bogus=generateRandomString(10).microtime();
                $bogus=str_replace(' ','',$bogus);
}
else{
	$bogus=$_POST['bogus'];
}

afficheAccueil("",$bogus);

?>