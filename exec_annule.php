<?php

include_once("annulcom_bdd.php");

$no_comm=$_POST['idcomm'];

annule_comm($no_comm);

?>