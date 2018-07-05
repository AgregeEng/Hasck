<!DOCTYPE html>
<html>
<head>
<title>Hasck : retraits pendants</title>
</head>
<body>

<?php
include("produits.inc");

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in retraits_pendants.php");

$query_amount="SELECT SUM(ARetirer) as Montant FROM utilisateurs";
$result=mysqli_query($connection,$query_amount) or die("Impossible to retrieve the amounts due");
$line=mysqli_fetch_assoc($result);
$montant=$line['Montant'];
echo "A reverser :$montant fin du message";

mysqli_close($connection);

?>

</body>
</html>